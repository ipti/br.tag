<?php
require_once 'vendor/autoload.php'; 

class StudentClassroomSEDDataSource extends SedDataSource
{
   
    public function getRelationClassrooms($school_id, $year)
    {
        $promise = $this->client->request('GET', '/ncaapi/api/RelacaoAlunosClasse/RelacaoClasses', [
            'body' => json_encode([
                "inAnoLetivo" => $year,
                "inCodEscola" => $school_id
            ])
        ]);

        return $promise->getBody()->getContents();
    }
}