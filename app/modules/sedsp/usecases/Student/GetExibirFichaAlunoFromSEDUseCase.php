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

        $mapper = (object)StudentMapper::parseToTAGExibirFichaAluno($response);
        $studentIdentification = $mapper->StudentIdentification;

        try {
            $studentIdentification = $this->createAndSaveStudentIdentification($studentIdentification);
            $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress, $studentIdentification);

            return $studentIdentification;

        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
        }

        throw new SedspException("Não foi possivel cadastrar aluno", 1);

    }


    public function createAndSaveStudentDocumentsAndAddress($studentDocumentsAndAddress, $studentIdentification)
    {
        $documentos = StudentDocumentsAndAddress::model()->find('id = :studentFk', [':studentFk' => $studentIdentification->id]);

        if($documentos === null) {
            if ($studentDocumentsAndAddress->validate() && $studentDocumentsAndAddress->save()) {
                return $studentDocumentsAndAddress;
            } else {
                $log = new LogError();
                $log->salvarDadosEmArquivo($studentDocumentsAndAddress->getErrors());
            }
        } else {
            if ($documentos->validate() && $documentos->save()) {
                return $studentDocumentsAndAddress;
            } else {
                $log = new LogError();
                $log->salvarDadosEmArquivo($documentos->getErrors());
            }
        }
    }

    public function createAndSaveStudentIdentification($studentIdentification)
    {
        if ($studentIdentification->validate() && $studentIdentification->save()) {
            return $studentIdentification;
        } else {
            $log = new LogError();
            $log->salvarDadosEmArquivo($studentIdentification->getErrors());
        }
    }
}
