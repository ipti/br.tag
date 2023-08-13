<?php
use GuzzleHttp\Exception\ClientException;
require_once 'app/vendor/autoload.php';

class SchoolSEDDataSource extends SedDataSource
{
    /**
     * Summary of getSchool
     * @param InEscola $inEscola
     * @return OutEscola|OutErro
     */
    public function getSchool(InEscola $inEscola)
    {
        try {
            $url = '/ncaapi/api/DadosBasicos/Escolas';
            $response = $this->getApiResponse('GET', $url, $inEscola);
            return OutEscola::fromJson($response);

        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
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
