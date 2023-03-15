<?php
require 'vendor/autoload.php'; 

class StudentClassroomSEDDataSource
{
    public function getRelationClassrooms($school_id, $year)
    {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $promise = $client->request('GET', '/ncaapi/api/RelacaoAlunosClasse/RelacaoClasses', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. Yii::app()->user->getState("SED_TOKEN")
            ],
            'body' => json_encode([
                "inAnoLetivo" => $year,
                "inCodEscola" => $school_id
            ])
        ]);

        return $promise->getBody()->getContents();
    }
}

?>