<?php
require 'app/vendor/autoload.php';

Yii::import('application.modules.sedsp.*');

/**
 * Summary of StudentSEDDataSource
 */
class StudentSEDDataSource extends SedDataSource
{

    /**
     * Summary of getStudentRA
     * @param string $name
     * @param string $birthday
     * @param string $mothersName
     * @return DadosAluno|OutErro
     */
    public function getStudentRA($name, $birthday, $mothersName,$force)
    {
        if($force){
            $name = '';
        }
        $body = array("inNomeAluno" => $name,
        "inNomeMae" => $mothersName,
        "inDataNascimento" => $birthday);
        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ConsultaRA', [
                'body' => json_encode($body)
            ]);
            return new DadosAluno($name, $response->getBody()->getContents());
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
    }

    public function addStudent($student_sed){
        $promise = $this->client->request('POST', '/ncaapi/api/Aluno/FichaAluno', [
            'body' => json_encode($student_sed)
        ]);
        return $promise;
    }

    public function getStudentWithRA($RA)
    {
        $body['inAluno'] = array("inNumRA" => $RA,
        "inSiglaUFRA" => "SP");
        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ExibirFichaAluno', [
                'body' => json_encode($body)
            ]);
            return $response;
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
    }

    public function getAllStudentsRA($students){
        
        $promises = [];
        foreach (array_slice($students, 0, 5) as $key => $student) {
            $promises[] = $this->getStudentRA($student->name, $student->birthday, $student->filiation_1);
        }
        
        $data = GuzzleHttp\Promise\Utils::all($promises)->then(function (array $responses){
            $data = [];
            foreach ($responses as $response) {
                 $data[] = json_decode($response->getBody()->getContents(), true);
            }
            return $data;
        })->wait(true);

        return $data;
    }



    /**
     * Summary of getListStudents
     * @param ?string $inNomeAluno
     * @param ?string $inNomeSocial
     * @param ?string $inNomeMae
     * @param ?string $inNomePai
     * @param ?string $inDataNascimento
     * @return mixed
     */
    function getListStudents($inNomeAluno, $inNomeSocial, $inNomeMae, $inNomePai, $inDataNascimento) {
        
        $body['inFiltrosNomes'] = [
            "inNomeAluno" => $inNomeAluno,
            "inNomeSocial" => $inNomeSocial,
            "inNomeMae" => $inNomeMae,
            "inNomePai" => $inNomePai,
            "inDataNascimento" => $inDataNascimento
        ];

        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ListarAlunos', [
                'body' => json_encode($body)
            ]);
            return $response;
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } 
    }

    /**
     * Summary of getViewStudentSheet
     * @param ?array $viewStudentSheet
     * @return mixed
     */
    function getViewStudentSheet($viewStudentSheet)
    {
        $body['inAluno'] = [
            'inNumRA' => $viewStudentSheet['$inNumRA'],
            'inDigitoRA' => $viewStudentSheet['$inDigitoRA'],
            'InSiglaUFRA' => $viewStudentSheet['$inSiglaUFRA']
        ];

        $body['inDocumentos'] = [
             "inNumRG" => $viewStudentSheet['inNumRG'],
             "inDigitoRG" => $viewStudentSheet['inDigitoRG'],
             "inUFRG" => $viewStudentSheet['inUFRG'],
             "inCPF" => $viewStudentSheet['inCPF'],
             "inNumNIS" => $viewStudentSheet['inNumNIS'],
             "inNumINEP" => $viewStudentSheet['inNumINEP'],
             "inNumCertidaoNova" => $viewStudentSheet['inNumCertidaoNova'],
        ];

        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ExibirFichaAluno', [
                'body' => json_encode($body)
            ]);
            return ($response->getBody()->getContents());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
    }
}