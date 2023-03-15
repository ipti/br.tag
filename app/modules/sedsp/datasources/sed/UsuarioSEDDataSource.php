<?php
require 'vendor/autoload.php'; 

class UsuarioSEDDataSource
{
    public function login($username, $password)
    {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '/ncaapi/api/Usuario/ValidarUsuario', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Basic U01FNzAxOnp5ZDc4MG1oejFzNQ=='
            ],
        ]);

        return $response->getBody()->getContents();
    }
}

?>