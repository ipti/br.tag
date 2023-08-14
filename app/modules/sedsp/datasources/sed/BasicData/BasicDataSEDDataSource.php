<?php
use GuzzleHttp\Exception\ClientException;

class BasicDataSEDDataSource extends SedDataSource
{

    /**
     * ===========================
     * GET REQUEST METHODS
     * ===========================
     */


    /**
     * Summary of getTipoEnsino
     * @return OutTiposEnsino|OutErro
     * @throws Exception
     */
    function getTipoEnsino() 
    {
        try {
            $url = '/ncaapi/api/DadosBasicos/TipoEnsino';
            $response = $this->getApiResponse('GET', $url, null);
            return OutTiposEnsino::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    /**
     * @param mixed $httpMethod
     * @param mixed $url
     * @param mixed $data
     * @return mixed
     */
    function getApiResponse($HTTPMethod, $url, $data) {
        $response = $this->client->request($HTTPMethod, $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
    
        return json_decode($response->getBody()->getContents(), true);
    }
}
