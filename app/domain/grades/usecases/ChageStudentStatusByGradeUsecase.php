<?php

/**
 * @property integer $studentEnrollmentId
 * @property integer $disciplineId
 * @property integer $numUnities
 */
class ChageStudentStatusByGradeUsecase
{
    private const SITUATION_APPROVED = "APROVADO";
    private const SITUATION_DISPPROVED = "REPROVADO";
    private const SITUATION_RECOVERY = "RECUPERAÇÃO";


    public function __construct($studentEnrollmentId, $disciplineId, $numUnities)
    {
        $this->studentEnrollmentId = $studentEnrollmentId;
        $this->disciplineId = $disciplineId;
        $this->numUnities = $numUnities;
    }

    public function exec()
    {
        $enrollment = StudentEnrollment::model()->with("classroomFk")->find($this->studentEnrollmentId);

        if (
            $enrollment->status === null ||
            $enrollment->status === StudentEnrollment::STATUS_ACTIVE ||
            $enrollment->status === StudentEnrollment::STATUS_APPROVED ||
            $enrollment->status === StudentEnrollment::STATUS_DISAPPROVED
        ) {
            $gradeResult = $this->getGradesResultForStudent($this->studentEnrollmentId, $this->disciplineId);

            /** @var GradeRules  $gradeRule */
            $gradeRule = GradeRules::model()->find(
                "edcenso_stage_vs_modality_fk = :stage",
                [
                    ":stage" => $gradeResult->enrollmentFk->classroomFk->edcenso_stage_vs_modality_fk
                ]
            );

            $hasAllValues = true;
            for ($i=1; $i <= $this->numUnities; $i++) {
                $hasAllValues = $hasAllValues && (isset($gradeResult["grade_". $i]) && $gradeResult["grade_". $i] != "");
            }

            if ($gradeResult->final_media === null || $gradeResult->final_media === "") {
                throw new Exception("Aluno não tem média final", 1);
            }

            if(!$hasAllValues){
                $gradeResult->situation = null;
                $gradeResult->save();
                return;
            }

            $gradeResult->situation = self::SITUATION_DISPPROVED;
            if ($gradeResult->final_media >= $gradeRule->approvation_media) {
                $gradeResult->situation = self::SITUATION_APPROVED;
            } else {
                if ($gradeRule->has_final_recovery && isset($gradeResult->rec_final)) {
                    if ($gradeResult->rec_final >= $gradeRule->final_recover_media) {
                        $gradeResult->situation = self::SITUATION_APPROVED;
                    }
                } elseif ($gradeRule->has_final_recovery){
                    $gradeResult->situation = self::SITUATION_RECOVERY;
                }
            }

            $gradeResult->save();
        }
    }

    /**
     * @param integer $studentEnrollmentId
     * @param integer $disciplineId
     *
     * @return GradeResults
     */
    private function getGradesResultForStudent($studentEnrollmentId, $disciplineId)
    {
        $gradeResult = GradeResults::model()->find(
            "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
            [
                "enrollment_fk" => $studentEnrollmentId,
                "discipline_fk" => $disciplineId
            ]
        );

        $isNewGradeResult = $gradeResult == null;

        if ($isNewGradeResult) {
            $gradeResult = new GradeResults();
            $gradeResult->enrollment_fk = $studentEnrollmentId;
            $gradeResult->discipline_fk = $disciplineId;
            $gradeResult->save();
        }

        return $gradeResult;
    }
}
