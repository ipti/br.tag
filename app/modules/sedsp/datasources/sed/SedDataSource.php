<?php
require_once 'app/vendor/autoload.php';

use GuzzleHttp\Client;

abstract class SedDataSource {

    protected $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. Yii::app()->request->cookies['SED_TOKEN']->value
            ], 
            'timeout'  => 10.0,
        ]);
    }

}