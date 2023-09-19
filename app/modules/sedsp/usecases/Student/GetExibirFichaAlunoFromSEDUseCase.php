<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    /**
     * Summary of exec
     * @param InAluno $inAluno
     * @return StudentIdentification
     */
    public function exec(InAluno $inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);

        $name = $response->getOutDadosPessoais()->getOutNomeAluno();
        $filiation_1 = $response->getOutDadosPessoais()->getOutNomeMae();

        $studentExists = $this->checkIfStudentExists($name, $filiation_1);
        
		if($studentExists){
            return false;
        }
        
        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);
            $documents =  (object) $mapper->StudentDocumentsAndAddress->getAttributes();

            $studentIdentification = $this->createAndSaveStudentIdentification($mapper->StudentIdentification);
            $studentDocumentsAndAddress = $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress, $studentIdentification, $documents->gov_id);

            return $studentIdentification;
            
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }

        throw new SedspException("Não foi possivel cadastrar aluno", 1);
        
    }


    public function createAndSaveStudentDocumentsAndAddress($attributes, $studentIdentification, $gov_id)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->gov_id = $gov_id;
        $studentDocumentsAndAddress->id = $studentIdentification->id;

        $sucess = $studentDocumentsAndAddress->validate() && $studentDocumentsAndAddress->save();
        
        if($sucess){
            return $studentDocumentsAndAddress;
        }

        throw new SedspException(CJSON::encode($studentDocumentsAndAddress->getErrors()), 1);
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

        throw new SedspException(json_encode($studentIdentification->getErrors(), JSON_UNESCAPED_UNICODE), 1);
    }

    /**
     * Summary of checkIfStudentIsEnrolled
     * @param string $cpf
     * @param string $name
     * @return array | false
     */
    public function checkIfStudentExists($name, $filiation_1)
    {
        $studentIdentification = StudentIdentification::model()->find('name = :name and filiation_1 = :filiation_1', [':name' => $name, 'filiation_1' => $filiation_1]);   
        $parseResult = [];
        if($studentIdentification){
            $parseResult["StudentIdentification"] = $studentIdentification;
            $parseResult["StudentDocumentsAndAddress"] = $studentIdentification->documentsFk;
            return $parseResult;
        }

        return false;
    }
}