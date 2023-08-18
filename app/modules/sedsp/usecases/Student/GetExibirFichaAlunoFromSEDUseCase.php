<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    function exec(InAluno $inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);
        $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);

        $inep_id = $mapper->StudentIdentification->inep_id;
        $student = StudentIdentification::model()->find('inep_id = :inep_id',array(':inep_id' => $inep_id));

        if($student === null){
            $studentIdentification = new StudentIdentification();
            $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
    
            $studentIdentification->attributes = $mapper->StudentIdentification->getAttributes();
            $studentIdentification->gov_id = $mapper->StudentIdentification->gov_id;
    
            $studentDocumentsAndAddress->attributes = $mapper->StudentDocumentsAndAddress->getAttributes();
    
            $this->validateStudentData($studentIdentification, $studentDocumentsAndAddress);
    
            $transaction = Yii::app()->db->beginTransaction();
            if ($studentIdentification->save() && $studentDocumentsAndAddress->save()) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollback();
                throw new SedspException('Não foi possível salvar os dados no banco de dados.');
            }
        } else {
            CVarDumper::dump('O aluno já está registrado.', 10, true);
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
