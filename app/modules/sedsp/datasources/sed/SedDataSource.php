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
        $url = getenv("SEDSP_URL");
        $this->client = new Client([
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '. "YYlW35bvTjLdVc+j6ozpvBFHy/t8PLTGb4i6oeMwqgOx0vBR6QAXFgs0YGjZbXkk8AQd98TR9kOkJKhCwTlu5sghbj7BBh4u4Kzdu2F/u2I0crOI0bz7tAnl4Sr/PlbZqLY1TUG2ByoVAQM2SZ5SI9kgYwArZnpqycO6JHQAggmMCFUPYYxcOv5UqEB9jb7ZcgaxXu1w5iwZdF76CAXm8m768mHvoOrRTBztJZnF9/BuRztkHVc3SbQY0nS5EhYYTX0X+nTVGaLO8CPVhhFs7krzwDseWjPZKz6cVozBQiqqzUNmbYtKCpghAzrz4vqk+p3ArmqqNlDDX4oTlC4poX8kaZIyrrW2iKxh15g5Z5cVZFOqHcSrHDqx69d2cEKZm4Mdb3sxpcnYQaKi+q69Vw=="
            ], 
            'timeout'  => 10.0,
        ]);
    }

}