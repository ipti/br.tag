<?php
require 'vendor/autoload.php'; 

class UsuarioSEDDataSource extends SedDataSource
{
    public function login($username, $password)
    {
        $response = $this-> client->request('GET', '/ncaapi/api/Usuario/ValidarUsuario', [
            'headers' => [
                'Authorization' => 'Basic U01FNzAxOnp5ZDc4MG1oejFzNQ=='
            ],
        ]);

        return $response->getBody()->getContents();
    }
}

?>