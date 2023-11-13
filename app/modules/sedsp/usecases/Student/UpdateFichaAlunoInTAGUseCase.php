<?php

class UpdateFichaAlunoInTAGUseCase
{
    public function exec($inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $outExibirFichaAluno = $studentDatasource->exibirFichaAluno($inAluno);

        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($outExibirFichaAluno);

            $studentIdentification = $this->createOrUpdateStudentIdentification($mapper->StudentIdentification);
            $this->createOrUpdateStudentDocumentsAndAddress(
                $mapper->StudentDocumentsAndAddress,
                $studentIdentification,
                $mapper->StudentDocumentsAndAddress->gov_id
            );

            $this->createOrUpdateStudentEnrollment($mapper->StudentEnrollment);

            return $studentIdentification;
        } catch (Exception $e) {
            $this->handleException($mapper->StudentIdentification, $e);
        }
    }

    private function createOrUpdateStudentIdentification($studentIdentification)
    {
        if ($studentIdentification === null) {
            $studentIdentification = $this->createAndSaveStudentIdentification($studentIdentification);
        } else {
            $aluno = $this->findStudentByIdentification($studentIdentification->gov_id);
            $aluno->attributes = $studentIdentification->attributes;
            $aluno->sedsp_sync = 1;
            $aluno->save();
        }

        return $studentIdentification;
    }

    private function createOrUpdateStudentDocumentsAndAddress($studentDocumentsAndAddress,$studentIdentification,$govId)
    {
        if ($studentDocumentsAndAddress === null) {
            $studentDocumentsAndAddress = $this->createAndSaveStudentDocumentsAndAddress(
                $studentDocumentsAndAddress, $studentIdentification, $govId
            );
        } else {
            $studentDocumentsAndAddress->save();
        }

        return $studentDocumentsAndAddress;
    }

    public function createOrUpdateStudentEnrollment($studentEnrollments)
    {

        foreach($studentEnrollments as $studentEnrollment) {
            $enrollment = StudentEnrollment::model()->find(array(
                'condition' => 'school_inep_id_fk=:school_inep_id_fk AND student_fk=:student_fk AND classroom_fk=:classroom_fk',
                'params' => array(
                    ':school_inep_id_fk' => $studentEnrollment->school_inep_id_fk,
                    ':student_fk' => $studentEnrollment->student_fk,
                    ':classroom_fk' => $studentEnrollment->classroom_fk,
                ),
            ));
            
            if ($enrollment === null) {
                $newEnrollment = new StudentEnrollment();
                $newEnrollment->attributes = $studentEnrollment->attributes;
                $newEnrollment->save();
            } else {
                $enrollment->attributes = $studentEnrollment->attributes;
                $enrollment->save();
            }
        }

        return $studentEnrollment;
    }

    private function findStudentByIdentification($govId)
    {
        return StudentIdentification::model()->findByAttributes(array('gov_id' => $govId));
    }

    private function handleException($studentIdentification, $e)
    {
        $aluno = $this->findStudentByIdentification($studentIdentification->gov_id);
        $aluno->attributes = $studentIdentification->attributes;
        $aluno->sedsp_sync = 0;
        $aluno->save();
        $log = new LogError();
        $log->salvarDadosEmArquivo($e->getMessage());
    }


    public function createAndSaveStudentDocumentsAndAddress($attributes, $studentIdentification, $govId)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->edcenso_city_fk = $attributes->edcenso_city_fk;
        $studentDocumentsAndAddress->gov_id = $govId;
        $studentDocumentsAndAddress->id = $studentIdentification->id;

        if ($studentDocumentsAndAddress->save()) {
            return true;
        }
    }

    public function createAndSaveStudentIdentification($attributes)
    {
        $studentIdentification = new StudentIdentification();
        $studentIdentification->attributes = $attributes->getAttributes();
        $studentIdentification->gov_id = $attributes->gov_id;

        if ($studentIdentification->save()) {
            return $studentIdentification;
        }
    }
}
