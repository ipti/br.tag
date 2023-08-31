<?php
use GuzzleHttp\Exception\ClientException;
require_once 'app/vendor/autoload.php';

class ClassroomSEDDataSource extends SedDataSource
{

    /**
     * ===========================
     * GET REQUEST METHODS
     * ===========================
     */

    /**
     * Summary of getConsultClass
     * @param InConsultaTurmaClasse $inConsultClass
     * @return OutConsultaTurmaClasse|OutErro
     * @throws Exception
     */
    function getConsultClass($inConsultClass) 
    {
        try {
            $url = 'ncaapi/api/TurmaClasse/ConsultaTurmaClasse';
            $response = $this->getApiResponse('GET', $url, $inConsultClass);
            return OutConsultaTurmaClasse::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
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
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addIncluirTurmaClasse($inIncluirTurmaClasse)
    {
        try {
            $url = '/ncaapi/api/TurmaClasse/IncluirTurmaClasse';
            $response = $this->getApiResponse('POST', $url, $inIncluirTurmaClasse);        
            return OutHandleApiResult::fromJson($response);
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
