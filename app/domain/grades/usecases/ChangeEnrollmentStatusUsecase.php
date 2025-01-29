<?php

/**
 * @property StudentEnrollment $enrollment
 * @property integer $frequency
 */

class ChangeEnrollmentStatusUseCase
{
    private $enrollment;
    private $frequency;

    public function __construct($enrollmentId)
    {
        $this->enrollment = $this->getStudentEnrollment($enrollmentId);
    }

    public function exec()
    {
        $isAllGradesFilled = true;
        $disciplines = $this->getDisciplines(
            $enrollment->classroomFk->edcenso_stage_vs_modality_fk,
            $enrollment->classroom_fk->school_year
        );

    }

    public function getStudentEnrollment($enrollmentId)
    {
        return StudentEnrollment::model()->findByPk($enrollmentId);
    }

    public function getDisciplines($esvsm, $schoolYear){
        $baseDisciplines = array();
        $diversifiedDisciplines = array();

        $curricularMatrixes = CurricularMatrix::model()
        ->with("disciplineFk")
        ->findAllByAttributes(
            [
                "stage_fk" => $esvsm,
                "school_year" => $schoolYear
            ]
        );

        return $curricularMatrixes;
    }
}

?>
