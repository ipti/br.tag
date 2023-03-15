<?php
require 'vendor/autoload.php'; 



class StudentSEDDataSource
{

    private $client;

    public function getStudentRA($name, $birthday, $mothersName)
    {  

        $promise = $this->client->requestAsync('GET', '/ncaapi/api/Aluno/ConsultaRA', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. Yii::app()->user->getState("SED_TOKEN")
            ],
            'body' => json_encode([
                "inNomeAluno" => $name,
                "inNomeMae" => $mothersName,
                "inDataNascimento" => $birthday
            ])
        ]);
        
        return $promise;
    }   

    public function addStudent($student_sed){
        $promise = $this->client->request('POST', '/ncaapi/api/Aluno/FichaAluno', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. Yii::app()->user->getState("SED_TOKEN")
            ],
            'body' => json_encode($student_sed)
        ]);
        
        return $promise;
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
     */
    public function __construct() {
        $this->client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
    }
}

?>