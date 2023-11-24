<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    /**
     * Summary of exec
     * @param InAluno $inAluno
     * @return StudentIdentification|bool
     */
    public function exec(InAluno $inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);

        $name = $response->getOutDadosPessoais()->getOutNomeAluno();
        $filiation_1 = $response->getOutDadosPessoais()->getOutNomeMae();

        $studentModel = StudentIdentification::model()->find('name = :name and filiation_1 = :filiation_1', [':name' => $name, 'filiation_1' => $filiation_1]);

		if($studentModel != null){
            $studentModel->sedsp_sync = 1;
            $studentModel->save();
            return false;
        }
        
        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);
            $documents =  (object) $mapper->StudentDocumentsAndAddress->getAttributes();

            $studentIdentification = $this->createAndSaveStudentIdentification($mapper->StudentIdentification);
            $studentDocumentsAndAddress = $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress, $studentIdentification, $documents->gov_id);

            return $studentIdentification;
            
        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
        }

        throw new SedspException("Não foi possivel cadastrar aluno", 1);
        
    }


    public function createAndSaveStudentDocumentsAndAddress($attributes, $studentIdentification, $gov_id)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->edcenso_city_fk = $attributes->edcenso_city_fk;
        $studentDocumentsAndAddress->gov_id = $gov_id;
        $studentDocumentsAndAddress->id = $studentIdentification->id;

        $sucess = $studentDocumentsAndAddress->validate() && $studentDocumentsAndAddress->save();
        
        if($sucess){
            return true;
        }
    }

    public function createAndSaveStudentIdentification($attributes)
    {
        
        $studentIdentification = new StudentIdentification();
        $studentIdentification->attributes = $attributes->getAttributes();
        $studentIdentification->gov_id = $attributes->gov_id;
        $studentIdentification->sedsp_sync = 1;

        $sucess = $studentIdentification->validate() && $studentIdentification->save();
        
        if($sucess){
            return true;
        }
    }
}