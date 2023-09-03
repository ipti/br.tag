<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    public function exec(InAluno $inAluno)
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
            $documents =  (object) $mapper->StudentDocumentsAndAddress->getAttributes();

            $studentIdentification = $this->createAndSaveStudentIdentification($mapper->StudentIdentification);
            $studentDocumentsAndAddress = $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress, $documents->gov_id);

            return $studentIdentification;
            
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }
    }


    public function createAndSaveStudentDocumentsAndAddress($attributes, $gov_id)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->gov_id = $gov_id;

        try {
            if ($studentDocumentsAndAddress->validate() && $studentDocumentsAndAddress->save()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function createAndSaveStudentIdentification($attributes)
    {
        $studentIdentification = new StudentIdentification();
        $studentIdentification->attributes = $attributes->getAttributes();
        $studentIdentification->gov_id = $attributes->gov_id;

        $sucess = $studentIdentification->validate() && $studentIdentification->save();
        
        if($sucess){

            return $studentIdentification;
        }

        throw new SedspException(CJSON::encode($studentIdentification->getErrors()), 1);
    }

    public function checkIfStudentIsEnrolled($cpf, $name)
    {
        $studentCpf = StudentDocumentsAndAddress::model()->find('cpf = :cpf', [':cpf' => $cpf])->cpf;
        $studentName = StudentIdentification::model()->find('name = :name', [':name' => $name])->name;
        
        return ($studentCpf !== null or $studentName !== null) ? true : false;
    }
}