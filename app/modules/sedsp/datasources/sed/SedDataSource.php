<?php
require 'app/vendor/autoload.php';

use GuzzleHttp\Client;

abstract class SedDataSource {

    /**
     * Summary of client
     * @var Client $client 
     */
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