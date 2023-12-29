<?php

/**
 * @property integer $studentEnrollmentId
 * @property integer $disciplineId
 */
class ChageStudentStatusByGradeUsecase
{
    private const SITUATION_APPROVED = "Aprovado";
    private const SITUATION_DISPPROVED = "Reprovado";
    private const SITUATION_RECOVERY = "Recuperação";


    public function __construct($studentEnrollmentId, $disciplineId)
    {
        $this->studentEnrollmentId = $studentEnrollmentId;
        $this->disciplineId = $disciplineId;
    }

    public function exec()
    {
        $enrollment = StudentEnrollment::model()->find($this->studentEnrollmentId);

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

            if ($gradeResult->final_media === null || $gradeResult->final_media === "") {
                throw new Exception("Aluno não tem média final", 1);
            }


            if ($gradeResult->final_media >= $gradeRule->approvation_media) {
                $gradeResult->situation = self::SITUATION_APPROVED;
            } else {
                if ($gradeRule->has_final_recovery) {
                    if ($gradeResult->rec_final >= $gradeRule->final_recover_media) {
                        $gradeResult->situation = self::SITUATION_APPROVED;
                    } else {
                        $gradeResult->situation = self::SITUATION_RECOVERY;
                    }
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
