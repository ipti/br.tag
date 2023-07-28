<?php
require 'app/vendor/autoload.php';

Yii::import('application.modules.sedsp.*');

/**
 * Summary of StudentSEDDataSource
 */
class TipoEnsinoSEDDataSource extends SedDataSource
{

    /**
     * Summary of getTipos
     * @return OutListaTiposEnsino | OutErro
     */
    public function getTipos()
    {
        
        try {
            $response = $this->client->request('GET', '/ncaapi/api/DadosBasicos/TipoEnsino');
            $json_data = json_decode($response->getBody()->getContents(), true);

            return OutListaTiposEnsino::fromJson($json_data);
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
    }
}