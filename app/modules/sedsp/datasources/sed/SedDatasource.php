<?php 
require 'vendor/autoload.php'; 

use GuzzleHttp\Client;

abstract class SedDataSource {

    protected $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. Yii::app()->user->getState("SED_TOKEN")
            ], 
            'timeout'  => 2.0,
        ]);
    }

}


?>