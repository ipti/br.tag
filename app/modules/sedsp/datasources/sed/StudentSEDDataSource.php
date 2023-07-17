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
     * @param array $listStudentsData
     * @return mixed
     */
    function getListStudents(array $listStudentsData) {
        try {
            $listStudentsRequestBody = [
                'inFiltrosNomes' => [
                    'inNomeAluno' => $listStudentsData['inNomeAluno'] ?? null,
                    'inNomeSocial' => $listStudentsData['inNomeSocial'] ?? null,
                    'inNomeMae' => $listStudentsData['inNomeMae'] ?? null,
                    'inNomePai' => $listStudentsData['inNomePai'] ?? null,
                    'inDataNascimento' => $listStudentsData['inDataNascimento'] ?? null
                ],
                'inDocumentos' => [
                    'inNumRG' => $listStudentsData['inNumRG'] ?? null,
                    'inDigitoRG' => $listStudentsData['inDigitoRG'] ?? null,
                    'inUFRG' => $listStudentsData['inUFRG'] ?? null,
                    'inCPF' => $listStudentsData['inCPF'] ?? null,
                    'inNumNIS' => $listStudentsData['inNumNIS'] ?? null,
                    'inNumINEP' => $listStudentsData['inNumINEP'] ?? null
                ],
            ];

            $listStudentsResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ListarAlunos', [
                'body' => json_encode($listStudentsRequestBody)
            ]);
            
            return $listStudentsResponse->getBody()->getContents();
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } 
    }

    /**
     * Summary of getViewStudentSheet
     *
     * @param array $studentSheetData
     * @return string
     */
    function getViewStudentSheet(array $studentSheetData)
    {
        try {
            $studentSheetRequestBody = [
                'inAluno' => [
                    'inNumRA' => $studentSheetData['inNumRA'],
                    'inDigitoRA' => $studentSheetData['inDigitoRA'] ?? null,
                    'InSiglaUFRA' => $studentSheetData['inSiglaUFRA']
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
