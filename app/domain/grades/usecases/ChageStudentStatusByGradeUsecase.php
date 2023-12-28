<?php

/**
 * @property StudentEnrollment $studentEnrollment
 */
class ChageStudentStatusByGradeUsecase
{
    private const SITUATION_APPROVED = "Aprovado";
    private const SITUATION_DISPPROVED = "Reprovado";
    private const SITUATION_RECOVERY = "Recuperação";


    public function __construct($studentEnrollment)
    {
        $this->studentEnrollment = $studentEnrollment;
    }

    public function exec()
    {

        if (
            $this->studentEnrollment->status == StudentEnrollment::STATUS_ACTIVE ||
            $this->studentEnrollment->status == StudentEnrollment::STATUS_APPROVED ||
            $this->studentEnrollment->status == StudentEnrollment::STATUS_DISAPPROVED
        ) {

            $this->changeEnrollmentStatus($this->studentEnrollment);
        }
    }

    /**
     * @param int $studentEnrollmentId
     * @param int $studentEnrollmentId
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
        }

        return $gradeResult;
    }

    private function changeEnrollmentStatus($studentEnrollment)
    {
        $allGradesFilled = true;
        $situation = self::SITUATION_APPROVED;
        $gradeResult = $this->getGradesResultForStudent();
        foreach ($studentEnrollment->gradeResults as $gradeResult) {
            if ($gradeResult->situation == null) {
                $allGradesFilled = false;
            } elseif ($gradeResult->situation == self::SITUATION_DISPPROVED) {
                $situation = self::SITUATION_DISPPROVED;
                break;
            } elseif ($gradeResult->situation == self::SITUATION_RECOVERY) {
                $situation = "Matriculado";
                break;
            }
        }

        if ($allGradesFilled && $situation == self::SITUATION_APPROVED) {
            $studentEnrollment->status = StudentEnrollment::getStatusId(StudentEnrollment::STATUS_APPROVED);
        } elseif ($situation == self::SITUATION_DISPPROVED) {
            $studentEnrollment->status = StudentEnrollment::getStatusId(StudentEnrollment::STATUS_DISAPPROVED);
        } else {
            $studentEnrollment->status = StudentEnrollment::getStatusId(StudentEnrollment::STATUS_ACTIVE);
        }
        $studentEnrollment->save();
    }

}
