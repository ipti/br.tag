<?php

class GetExibirFichaAlunoFromSEDUseCase
{
    function exec(InAluno $inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);

        $cpf = $response->getOutDocumentos()->getOutCpf();
        $name = $response->getOutDadosPessoais()->getOutNomeAluno();
        $studentCpf = StudentDocumentsAndAddress::model()->find('cpf = :cpf', [':cpf' => $cpf])->cpf;
        $studentName = StudentIdentification::model()->find('name = :name', [':name' => $name])->name;
        
        //Verifica se o cpf ou nome do aluno já está no cadastrado
		if($studentCpf !== null or $studentName !== null)
            return false;
        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);
            $studentIdentification = new StudentIdentification();
            $studentIdentification->attributes = $mapper->StudentIdentification->getAttributes();
            $studentIdentification->gov_id = $mapper->StudentIdentification->gov_id;

            $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
            $studentDocumentsAndAddress->attributes = $mapper->StudentDocumentsAndAddress->getAttributes();

            $this->validateStudentData($studentIdentification, $studentDocumentsAndAddress);

            if ($studentIdentification->save() && $studentDocumentsAndAddress->save()) {
                return true;
            } else {
                throw new SedspException('Não foi possível salvar os dados no banco de dados.');
            }
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
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