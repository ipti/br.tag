<?php

class UpdateFichaAlunoInTAGUseCase
{
    public function exec($inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);

        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);

            $studentIdentification = $mapper->StudentIdentification;
            $studentDocumentsAndAddress = $mapper->StudentDocumentsAndAddress;

            if ($studentIdentification === null) {
                $studentIdentification = $this->createAndSaveStudentIdentification($mapper->StudentIdentification);
            } else {
                $aluno = StudentIdentification::model()->findByAttributes(array('gov_id' => $mapper->StudentIdentification->gov_id));
                $aluno->attributes = $mapper->StudentIdentification->attributes;
                $aluno->sedsp_sync = 1;
                $aluno->save();
            }

            if ($studentDocumentsAndAddress === null) {
                $studentDocumentsAndAddress = $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress, $studentIdentification, $mapper->StudentDocumentsAndAddress->gov_id);
            } else {
                $studentDocumentsAndAddress->attributes = $mapper->StudentDocumentsAndAddress->attributes;
                $studentDocumentsAndAddress->save();
            }

            return $studentIdentification;
        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
        }
    }

    public function createAndSaveStudentDocumentsAndAddress($attributes, $studentIdentification, $gov_id)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->edcenso_city_fk = $attributes->edcenso_city_fk;
        $studentDocumentsAndAddress->gov_id = $gov_id;
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
