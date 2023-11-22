<?php
use GuzzleHttp\Exception\ClientException;

require_once 'app/vendor/autoload.php';
Yii::import('application.modules.sedsp.models.*');

class EnrollmentSEDDataSource extends SedDataSource
{
    /**
     * Summary of getListarMatriculasRA
     * @param InAluno $inAluno
     * @return OutListaMatriculaRA|OutErro
     * @throws Exception
     */
    public function getListarMatriculasRA(InAluno $inAluno)
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

    public function addExcluirMatricula(InExcluirMatricula $inExcluirMatricula)
    {
        try{
            $url = '/ncaapi/api/Matricula/ExcluirMatricula';

            $response = $this->getApiResponse('POST', $url, $inExcluirMatricula);
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
            $response = $this->getApiResponse('GET', $url, $inExibirMatriculaClasseRA);
            return OutExibirMatriculaClasseRA::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of addRemanejarMatricula
     * @param InRemanejarMatricula $inRemanejarMatricula
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addRemanejarMatricula(InRemanejarMatricula $inRemanejarMatricula)
    {
        try{
            $url = '/ncaapi/api/Matricula/RemanejarMatricula';
            $response = $this->getApiResponse('POST', $url, $inRemanejarMatricula);
            return OutHandleApiResult::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of addReclassificarMatricula
     * @param InReclassificarMatricula $inReclassificarMatricula
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addReclassificarMatricula(InReclassificarMatricula $inReclassificarMatricula)
    {
        try{
            $url = '/ncaapi/api/Matricula/ReclassificarMatricula';
            $response = $this->getApiResponse('POST', $url, $inReclassificarMatricula);
            return OutHandleApiResult::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    /**
     * Summary of addBaixarMatricula
     * @param InBaixarMatricula $inBaixarMatricula
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addBaixarMatricula(InBaixarMatricula $inBaixarMatricula)
    {
        try{
            $url = '/ncaapi/api/Matricula/BaixarMatricula';
            $response = $this->getApiResponse('POST', $url, $inBaixarMatricula);
            return OutHandleApiResult::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of addBaixarMatricula
     * @param InTrocarAlunoEntreClasses $inTrocarAlunoEntreClasses
     * @return OutHandleApiResult|OutErro
     * @throws Exception
     */
    public function addTrocarAlunoEntreClasses(InTrocarAlunoEntreClasses $inTrocarAlunoEntreClasses)
    {
        try{
            $url = '/ncaapi/api/Matricula/TrocarAlunoEntreClasses';

            $data = [
                "inAluno" => $inTrocarAlunoEntreClasses->getInAluno(),
                "inMatricula" => $inTrocarAlunoEntreClasses->getInMatricula(),
                "inNivelEnsino" => $inTrocarAlunoEntreClasses->getInNivelEnsino()
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
     * @param mixed $httpMethod
     * @param mixed $url
     * @param mixed $data
     * @return mixed
     */
    private function getApiResponse($httpMethod, $url, $data) {
        $response = $this->client->request($httpMethod, $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
    
        return json_decode($response->getBody()->getContents(), true);
    }
}
