<?php
require_once 'app/vendor/autoload.php';

class SchoolSEDDataSource extends SedDataSource
{
    public function getSchool($school_name, $school_mun)
    {
        $body = array("inNomeEscola" => $school_name,"inMunicipio" => $school_mun);
        try {
            $response = $this->client->request('GET', '/ncaapi/api/DadosBasicos/Escolas', [
                'body' => json_encode($body)
            ]);
            return $response;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
    }
}
