<?php
use GuzzleHttp\Exception\ClientException;

require 'app/vendor/autoload.php';


class EnrollmentSEDDataSource extends SedDataSource
{
    const LENGTH_IN_NUM_RA = 12;
    const LENGTH_IN_DIGITO_RA = 2;
    const LENGTH_IN_SIGLA_UFRA = 2;

    /**
     * Summary of getListarMatriculasRA
     * @param InAluno $inAluno
     * @return OutListaMatriculaRA|OutErro
     */
    function getListarMatriculasRA($inAluno)
    {
        try {
            if (empty($inAluno->inNumRA) || empty($inAluno->inSiglaUFRA)) {
                throw new InvalidArgumentException("Entrada inválida: dados incompletos.");
            }

            if (strlen($inAluno->inNumRA) > self::LENGTH_IN_NUM_RA || 
                isset($inAluno->inSiglaUFRA) ? (strlen($inAluno->inSiglaUFRA) > self::LENGTH_IN_SIGLA_UFRA) : false || 
                strlen($inAluno->inDigitoRA) > self::LENGTH_IN_DIGITO_RA) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            }

            $url = '/ncaapi/api/Matricula/ListarMatriculasRA';
            $response = $this->getApiResponse('GET', $url, ["inAluno" => $inAluno]);         
            return OutListaMatriculaRA::fromJson($response);

        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }

    /**
     * Summary of addInscreverAluno
     * @param InscreverAluno $inscreverAluno
     * @return OutHandleApiResult|OutErro
     */
    function addInscreverAluno(InscreverAluno $inscreverAluno)
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

        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }

    /**
     * Summary of addMatricularAluno
     * @param InMatricularAluno $inMatricularAluno
     * @return OutHandleApiResult|OutErro
     */
    function addMatricularAluno(InMatricularAluno $inMatricularAluno)
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

        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }

    /**
     * Summary of getExibirMatriculaClasseRA
     * @param InExibirMatriculaClasseRA $inExibirMatriculaClasseRA
     * @return OutExibirMatriculaClasseRA|OutErro
     */
    function getExibirMatriculaClasseRA(InExibirMatriculaClasseRA $inExibirMatriculaClasseRA)
    {
        try{
            $url = '/ncaapi/api/Matricula/ExibirMatriculaClasseRA';
            $response = $this->getApiResponse('POST', $url, $inExibirMatriculaClasseRA);
            return OutExibirMatriculaClasseRA::fromJson($response);

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
    function getApiResponse($HTTPMethod, $url, $data) {
        $response = $this->client->request($HTTPMethod, $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
    
        return json_decode($response->getBody()->getContents(), true);
    }
}
