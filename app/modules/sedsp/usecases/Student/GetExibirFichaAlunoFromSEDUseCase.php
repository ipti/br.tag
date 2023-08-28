<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    function exec(InAluno $inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);
       
        $cpf = $response->getOutDocumentos()->getOutCpf();
        $name = $response->getOutDadosPessoais()->getOutNomeAluno();

        $studentExists = $this->checkIfStudentIsEnrolled($cpf, $name);
        
		if($studentExists)
            return false;

        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);
            
            $this->createAndSaveStudentIdentification($mapper->StudentIdentification);
            $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress->getAttributes());
            
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }
    }


    function createAndSaveStudentDocumentsAndAddress($attributes)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes;

        return ($studentDocumentsAndAddress->validate() && $studentDocumentsAndAddress->save()) ? true : false;
    }

    function createAndSaveStudentIdentification($attributes)
    {
        $studentIdentification = new StudentIdentification();
        $studentIdentification->attributes = $attributes->getAttributes();
        $studentIdentification->gov_id = $attributes->gov_id;

        return ($studentIdentification->validate() && $studentIdentification->save()) ? true : false;
    }

    function checkIfStudentIsEnrolled($cpf, $name)
    {
        $studentCpf = StudentDocumentsAndAddress::model()->find('cpf = :cpf', [':cpf' => $cpf])->cpf;
        $studentName = StudentIdentification::model()->find('name = :name', [':name' => $name])->name;
        
        return ($studentCpf !== null or $studentName !== null) ? true : false;
    }
}