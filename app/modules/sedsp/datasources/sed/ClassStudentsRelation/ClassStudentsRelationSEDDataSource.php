<?php

use GuzzleHttp\Exception\ClientException;

require 'app/vendor/autoload.php';


class ClassStudentsRelationSEDDataSource extends SedDataSource
{

    /**
     * Summary of getRelacaoClasses
     * @param InRelacaoClasses $inRelacaoClasses
     * @return OutRelacaoClasses|OutErro
     * @throws Exception
     */
    function getRelacaoClasses(InRelacaoClasses $inRelacaoClasses){
        try {
            $url = '/ncaapi/api/RelacaoAlunosClasse/RelacaoClasses';
            $response = $this->getApiResponse('GET', $url, $inRelacaoClasses);
            return OutRelacaoClasses::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of getClassroom
     * @param InFormacaoClasse $inClassroom
     * @return OutFormacaoClasse|OutErro
     * @throws Exception
     */
    public function getClassroom(InFormacaoClasse $inClassroom)
    {
        try {
            $url = '/ncaapi/api/RelacaoAlunosClasse/FormacaoClasse';
            $response = $this->getApiResponse('GET', $url, $inClassroom);
            return OutFormacaoClasse::fromJson($response);
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
    private function getApiResponse($HTTPMethod, $url, $data) {
        $response = $this->client->request($HTTPMethod, $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
    
        return json_decode($response->getBody()->getContents(), true);
    }
}
