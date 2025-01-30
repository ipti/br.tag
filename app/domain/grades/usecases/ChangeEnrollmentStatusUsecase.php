<?php

/**
 * @property StudentEnrollment $enrollment
 */

class ChangeEnrollmentStatusUseCase
{
    private $enrollment;

    public function __construct($enrollmentId)
    {
        $this->enrollment = $this->getStudentEnrollment($enrollmentId);
    }
    public function exec()
    {
        $isAllGradesFinalMediaFilled = true;
        $isApprovedInAllGrades = true;

        $disciplines = $this->getDisciplines($this->enrollment->id);

        foreach($disciplines as $discipline){
            if($discipline->final_media != null){
                $isAllGradesFinalMediaFilled = false;
            }

            if($discipline->situation == "REPROVADO")
            $isApprovedInAllGrades = false;
        }

        if($isAllGradesFinalMediaFilled){
            $this->enrollment->status = 1;
        }

        if($isApprovedInAllGrades){
            $this->enrollment->status = 6;
        }else{
            $this->enrollment->status = 8;
        }

        if($this->enrollment->save()){
            TLog::info(
                "Status da matrícula",
                [
                    "enrollmentSituation" => $this->gradeResult->status
                ]
                );
            return;
        }
    }

    public function getStudentEnrollment($enrollmentId)
    {
        return StudentEnrollment::model()->findByPk($enrollmentId);
    }

    public function getDisciplines($enrollmentFk){
        // Como cada disciplina do enrollment possui um gradeResult único
        // retornar uma disciplina significa retornar um gradeResult
        $gradesResults = GradeResults::model()->findAllByAttributes(
            [
                "enrollment_fk" => $enrollmentFk
            ]
        );
        return $gradesResults;
    }
}
?>
