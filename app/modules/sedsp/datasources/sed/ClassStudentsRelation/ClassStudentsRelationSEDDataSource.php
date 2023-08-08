<?php

use GuzzleHttp\Exception\ClientException;

require 'app/vendor/autoload.php';


class ClassStudentsRelationSEDDataSource extends SedDataSource
{
    /**
     * Summary of getRelacaoClasses
     * @param InRelacaoClasses $inRelacaoClasses
     * @return OutRelacaoClasses|OutErro
     */
    function getRelacaoClasses(InRelacaoClasses $inRelacaoClasses){
        try {
            $url = '/ncaapi/api/RelacaoAlunosClasse/RelacaoClasses';
            $response = $this->getApiResponse('GET', $url, $inRelacaoClasses);
            return OutRelacaoClasses::fromJson($response);

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
    function getApiResponse($HTTPMetho, $url, $data) {
        $response = $this->client->request($HTTPMetho, $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
    
        return json_decode($response->getBody()->getContents(), true);
    }
}
