<?php

/**
 * @property StudentEnrollment $enrollment
 */

class ChangeEnrollmentStatusUsecase
{
    private $enrollment;

    public function __construct($enrollmentId)
    {
        $this->enrollment = $this->getStudentEnrollment($enrollmentId);
    }

    public function exec()
    {
        $isApprovedInAllGrades = true;
        // STATUS VÁLIDOS PARA APROVAÇÃO OU REPROVAÇÃO:
        // 1 - MATRICULADO, 13 - REINTEGRADO
        $validStatus = [1, 6, 8, 13];
        $isRegistered = in_array($this->enrollment->status, $validStatus);

        $disciplines = $this->getDisciplines($this->enrollment->id);

        foreach ($disciplines as $discipline) {

            if ($discipline->situation == 'REPROVADO') {
                $isApprovedInAllGrades = false;
            }
        }

        if ($isRegistered) {
            $this->enrollment->status = $isApprovedInAllGrades?6:8;
        }

        if ($this->enrollment->save()) {
            TLog::info(
                'Status da matrícula',
                ['enrollmentSituation' => $this->gradeResult->status]
            );
        }
    }

    public function getStudentEnrollment($enrollmentId)
    {
        return StudentEnrollment::model()->findByPk($enrollmentId);
    }

    public function getDisciplines($enrollmentFk)
    {
        // Como cada disciplina do enrollment possui um gradeResult único
        // retornar uma disciplina significa retornar um gradeResult
        $allGradesResults = GradeResults::model()->findAllByAttributes(
            [
                'enrollment_fk' => $enrollmentFk
            ]
        );
        $result = [];
        foreach ($allGradesResults as $gradesResult) {
            $disipline =  EdcensoDiscipline::model()->findByPk($gradesResult->discipline_fk);
            if($disipline->requires_exam == 1) {
                array_push($result, $gradesResult);
            }
        }
        return $result;
    }
}
