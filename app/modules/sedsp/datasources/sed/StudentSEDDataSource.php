<?php
require 'vendor/autoload.php'; 

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

        $content = $response = $this->client->request('GET', '/ncaapi/api/Aluno/ConsultaRA', [
            'body' => json_encode([
                "inNomeAluno" => $name,
                "inNomeMae" => $mothersName,
                "inDataNascimento" => $birthday
            ])
        ]);
        
        $content = $response->getBody()->getContents();
        $aluno_sed = new DadosAluno($content);

        return $aluno_sed;
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

?>