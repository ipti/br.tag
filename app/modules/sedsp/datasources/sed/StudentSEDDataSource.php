<?php
require 'app/vendor/autoload.php';

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

    /**
     * Summary of getStudentWithRA
     * @param mixed $RA
     * @return mixed
     */
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

    /**
     * Summary of getAllStudentsRA
     * @param mixed $students
     * @return mixed
     */
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
     *
     * @param array $studentSheetData
     * @return mixed
     */
    function getViewStudentSheet(array $studentSheetData)
    {
        try {
            $studentSheetRequestBody = [
                'inAluno' => [
                    'inNumRA' => $studentSheetData['inNumRA'] ?? null,
                    'inDigitoRA' => $studentSheetData['inDigitoRA'] ?? null,
                    'InSiglaUFRA' => $studentSheetData['inSiglaUFRA'] ?? null
                ],
                'inDocumentos' => [
                    'inNumRG' => $studentSheetData['inNumRG'] ?? null,
                    'inDigitoRG' => $studentSheetData['inDigitoRG'] ?? null,
                    'inUFRG' => $studentSheetData['inUFRG'] ?? null,
                    'inCPF' => $studentSheetData['inCPF'] ?? null,
                    'inNumNIS' => $studentSheetData['inNumNIS'] ?? null,
                    'inNumINEP' => $studentSheetData['inNumINEP'] ?? null,
                    'inNumCertidaoNova' => $studentSheetData['inNumCertidaoNova'] ?? null,
                ]
            ];
    
            $studentSheetResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ExibirFichaAluno', [
                'body' => json_encode($studentSheetRequestBody)
            ]);
    
            return $studentSheetResponse->getBody()->getContents();
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
    }   
}
