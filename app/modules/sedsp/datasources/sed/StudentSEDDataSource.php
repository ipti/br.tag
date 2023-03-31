<?php
require 'app/vendor/autoload.php';

Yii::import('application.modules.sedsp.models.*');

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
     * @return DadosAluno
     */
    public function getStudentRA($name, $birthday, $mothersName)
    {
        $body['inFiltrosNomes'] = array("inNomeAluno" => $name,
        "inNomeMae" => $mothersName,
        "outDataNascimento" => $birthday);
        //var_dump(json_encode($body));exit;
        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ListarAlunos', [
                'body' => json_encode($body)
            ]);
            return new DadosAluno($response->getBody()->getContents());
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
}