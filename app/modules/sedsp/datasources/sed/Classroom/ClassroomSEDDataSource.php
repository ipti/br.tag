<?php
use GuzzleHttp\Exception\ClientException;
require_once 'app/vendor/autoload.php';

class ClassroomSEDDataSource extends SedDataSource
{
    const LENGTH_IN_NUM_CLASS = 9;
    const LENGTH_IN_ANO_LETIVO = 4;


    /**
     * ===========================
     * GET REQUEST METHODS
     * ===========================
     */

    /**
     * Summary of getConsultClass
     * @param InConsultaTurmaClasse $inConsultClass
     * @return OutConsultaTurmaClasse|OutErro
     */
    function getConsultClass($inConsultClass) 
    {
        try {
            if (empty($inConsultClass->inAnoLetivo) || empty($inConsultClass->inNumClasse)) {
                throw new InvalidArgumentException("Entrada inválida: dados incompletos.");
            }

            if (strlen($inConsultClass->inAnoLetivo) > self::LENGTH_IN_ANO_LETIVO || strlen($inConsultClass->inNumClasse) > self::LENGTH_IN_NUM_CLASS) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            };

            $url = 'ncaapi/api/TurmaClasse/ConsultaTurmaClasse';
            $response = $this->getApiResponse('GET', $url, $inConsultClass);
            return OutConsultaTurmaClasse::fromJson($response);

        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }


    /**
     * ===========================
     * POST REQUEST METHODS
     * ===========================
     */

    /**
     * Summary of incluirTurmaClassePOST
     * @param InIncluirTurmaClasse $inIncluirTurmaClasse
     * @return OutHandleApiResult
     */
    function addIncluirTurmaClasse($inIncluirTurmaClasse)
    {
        $url = '/ncaapi/api/TurmaClasse/IncluirTurmaClasse';
        $response = $this->getApiResponse('POST', $url, $inIncluirTurmaClasse);
       
        return OutHandleApiResult::fromJson($response);
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
