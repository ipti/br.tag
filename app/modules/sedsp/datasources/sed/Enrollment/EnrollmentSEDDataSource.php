<?php
use GuzzleHttp\Exception\ClientException;

require_once 'app/vendor/autoload.php';


class EnrollmentSEDDataSource extends SedDataSource
{
    /**
     * Summary of getListarMatriculasRA
     * @param InAluno $inAluno
     * @return OutListaMatriculaRA|OutErro
     * @throws Exception
     */
    public function getListarMatriculasRA($inAluno)
    {
        try {
            $url = '/ncaapi/api/Matricula/ListarMatriculasRA';
            $response = $this->getApiResponse('GET', $url, ["inAluno" => $inAluno]);         
            return OutListaMatriculaRA::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of addInscreverAluno
     * @param InscreverAluno $inscreverAluno
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addInscreverAluno(InscreverAluno $inscreverAluno)
    {
        try{
            $url = '/ncaapi/api/Inscricao/InscreverAluno';
            $data = [
                "inAluno" => $inscreverAluno->getInAluno(),
                "inInscricao" => $inscreverAluno->getInInscricao(),
                "inNivelEnsino" => $inscreverAluno->getInNivelEnsino()
            ];

            $response = $this->getApiResponse('POST', $url, $data);
            return OutHandleApiResult::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of addMatricularAluno
     * @param InMatricularAluno $inMatricularAluno
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addMatricularAluno(InMatricularAluno $inMatricularAluno)
    {
        try{

            $url = '/ncaapi/api/Matricula/MatricularAluno';
            $data = [
                "inAnoLetivo" => $inMatricularAluno->getInAnoLetivo(),
                "inAluno" => $inMatricularAluno->getInAluno(),
                "inMatricula" => $inMatricularAluno->getInMatricula(),
                "inNivelEnsino" => $inMatricularAluno->getInNivelEnsino()
            ];
            $response = $this->getApiResponse('POST', $url, $data);
            return OutHandleApiResult::fromJson($response);

        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of getExibirMatriculaClasseRA
     * @param InExibirMatriculaClasseRA $inExibirMatriculaClasseRA
     * @return OutExibirMatriculaClasseRA|OutErro
     * @throws Exception
     */
    public function getExibirMatriculaClasseRA(InExibirMatriculaClasseRA $inExibirMatriculaClasseRA)
    {
        try{
            $url = '/ncaapi/api/Matricula/ExibirMatriculaClasseRA';
            $response = $this->getApiResponse('POST', $url, $inExibirMatriculaClasseRA);
            return OutExibirMatriculaClasseRA::fromJson($response);
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
