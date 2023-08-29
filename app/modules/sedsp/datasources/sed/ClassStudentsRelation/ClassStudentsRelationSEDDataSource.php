<?php

use GuzzleHttp\Exception\ClientException;

require 'app/vendor/autoload.php';


class ClassStudentsRelationSEDDataSource extends SedDataSource
{
    const LENGTH_IN_NUM_CLASS = 9;
    const LENGTH_IN_ANO_LETIVO = 4;
    
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
     * Summary of getClassroom
     * @param InFormacaoClasse $inClassroom
     * @return OutFormacaoClasse|OutErro
     */
    public function getClassroom(InFormacaoClasse $inClassroom)
    {
        try {
            if (empty($inClassroom->inNumClasse)) {
                throw new InvalidArgumentException("Entrada inválida: dado incompleto.");
            }

            if (strlen($inClassroom->inNumClasse) > self::LENGTH_IN_NUM_CLASS) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            };

            $url = '/ncaapi/api/RelacaoAlunosClasse/FormacaoClasse';
            $response = $this->getApiResponse('GET', $url, $inClassroom);
            return OutFormacaoClasse::fromJson($response);

        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
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
