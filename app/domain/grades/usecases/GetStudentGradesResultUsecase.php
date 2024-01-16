<?php
/**
 * @property int $studentEnrollmentId
 * @property int $disciplineId
 */
class GetStudentGradesResultUsecase
{

    public function __construct(int $studentEnrollmentId, int $disciplineId)
    {
        $this->studentEnrollmentId = $studentEnrollmentId;
        $this->disciplineId = $disciplineId;
    }
    /**
     *
     * @return GradeResults
     */
    public function exec()
    {
        $gradeResult = GradeResults::model()->find(
            "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
            [
                "enrollment_fk" => $this->studentEnrollmentId,
                "discipline_fk" => $this->disciplineId
            ]
        );

        $isNewGradeResult = $gradeResult == null;

        if ($isNewGradeResult) {
            $gradeResult = new GradeResults();
            $gradeResult->enrollment_fk = $this->studentEnrollmentId;
            $gradeResult->discipline_fk = $this->disciplineId;
            $gradeResult->save();
        }

        return $gradeResult;
    }
}
