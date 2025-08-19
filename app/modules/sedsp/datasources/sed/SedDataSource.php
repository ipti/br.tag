<?php

use GuzzleHttp\Client;

abstract class SedDataSource {

    /**
     * Summary of client
     * @var Client $client
     */
    protected $client;

    public function __construct() {
        $url = getenv("SEDSP_URL");
        $this->client = new Client([
            'base_uri' =>  $url ?? "https://homologacaointegracaosed.educacao.sp.gov.br",
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. Yii::app()->request->cookies['SED_TOKEN']->value
            ],
            'timeout'  => 10.0,
        ]);
    }
}
