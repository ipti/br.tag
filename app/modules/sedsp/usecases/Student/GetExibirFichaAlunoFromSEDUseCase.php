<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    function exec(InAluno $inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);
        $student = StudentIdentification::model()->find('inep_id = :inep_id', array(':inep_id' => $response->getOutDadosPessoais()->getOutNumRa()));
        
        if ($student !== null) {
            CVarDumper::dump('O aluno já está registrado.', 10, true);
            return;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);
            $studentIdentification = new StudentIdentification();
            $studentIdentification->attributes = $mapper->StudentIdentification->getAttributes();
            $studentIdentification->gov_id = $mapper->StudentIdentification->gov_id;

            $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
            $studentDocumentsAndAddress->attributes = $mapper->StudentDocumentsAndAddress->getAttributes();

            $this->validateStudentData($studentIdentification, $studentDocumentsAndAddress);

            if ($studentIdentification->save() && $studentDocumentsAndAddress->save()) {
                CVarDumper::dump('O aluno registrado com sucesso.', 10, true);
            } else {
                throw new SedspException('Não foi possível salvar os dados no banco de dados.');
            }
            $transaction->commit();
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
            $transaction->rollback();
        }
    }

    private function validateStudentData(StudentIdentification $studentIdentification, StudentDocumentsAndAddress $studentDocumentsAndAddress)
    {
        if (!$studentIdentification->validate()) {
            throw new SedspException('Os dados de identificação do aluno não são válidos.');
        }

        if (!$studentDocumentsAndAddress->validate()) {
            throw new SedspException('Os dados dos documentos e endereço do aluno não são válidos.');
        }
    }
}