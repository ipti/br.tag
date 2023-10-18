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
    public function getConsultClass(InConsultaTurmaClasse $inConsultClass)
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
    public function incluirTurmaClasse(InIncluirTurmaClasse $inIncluirTurmaClasse)
    {
        try {
            $url = 'ncaapi/api/TurmaClasse/IncluirTurmaClasse';
            $response = $this->getApiResponse('POST', $url, $inIncluirTurmaClasse);
            return OutHandleApiResult::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of manutencaoTurmaClassePOST
     * @param InManutencaoTurmaClasse $inManutencaoTurmaClasse
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function manutencaoTurmaClasse(InManutencaoTurmaClasse $inManutencaoTurmaClasse)
    {
        try {
            $url = 'ncaapi/api/TurmaClasse/ManutencaoTurmaClasse';
            $response = $this->getApiResponse('POST', $url, $inManutencaoTurmaClasse);
            return OutHandleApiResult::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of excluirTurmaClassePOST
     * @param InExcluirTurmaClasse $inExcluirTurmaClasse
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function excluirTurmaClasse(InExcluirTurmaClasse $inExcluirTurmaClasse)
    {
        try {
            $url = 'ncaapi/api/TurmaClasse/ExcluirTurmaClasse';
            $response = $this->getApiResponse('POST', $url, $inExcluirTurmaClasse);
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
