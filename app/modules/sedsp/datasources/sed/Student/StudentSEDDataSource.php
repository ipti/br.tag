<?php

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;


Yii::import('application.modules.sedsp.models.Student.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * Summary of StudentSEDDataSource
 */
class StudentSEDDataSource extends SedDataSource
{

    /**
     * ===========================
     * GET REQUEST METHODS
     * ===========================
     */


    /**
     * Summary of getStudentRA
     * @param InConsultaRA $inConsultaRA
     * @return OutConsultaRA|OutErro
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    public function getStudentRA($inConsultaRA)
    {
        try {
            $url = '/ncaapi/api/Aluno/ConsultaRA';
            $response = $this->getApiResponse('GET', $url, $inConsultaRA);
            return OutConsultaRA::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of getAllStudentsRA
     * @param mixed $students
     * @return mixed
     */
    public function getAllStudentsRA($students){

        $promises = [];
        foreach (array_slice($students, 0, 5) as $student) {
            $promises[] = $this->getStudentRA($student->name, $student->birthday, $student->filiation_1);
        }

        $data = GuzzleHttp\Promise\Utils::all($promises)->then(function (array $responses){
            $data = [];
            foreach ($responses as $response) {
                 $data[] = json_decode($response->getBody()->getContents(), true);
            }
            return $data;
        })->wait(true);

        return $data;
    }

    /**
     * Summary of getListStudents
     * @param InListarAlunos $inListarAlunos
     * @return OutListarAluno|OutErro
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    public function getListStudents(InListarAlunos $inListarAlunos)
    {
        try {
            $url = '/ncaapi/api/Aluno/ListarAlunos';
            $data = [
                "inFiltrosNomes" => $inListarAlunos->getInFiltrosNomes(),
                "inDocumentos" => $inListarAlunos->getInDocumentos()
            ];

            $response = $this->getApiResponse('GET', $url, $data);
            return OutListarAluno::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param InAluno $inAluno Objeto contendo informações do aluno.
     * @return OutExibirFichaAluno|OutErro Retorna um objeto OutAluno em caso de sucesso ou OutErro em caso de erro.
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    public function exibirFichaAluno(InAluno $inAluno)
    {
        try {
            $url = '/ncaapi/api/Aluno/ExibirFichaAluno';
            $response = $this->getApiResponse('GET', $url, ["inAluno" => $inAluno]);

            return OutExibirFichaAluno::fromJson($response);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Summary of getConsultarResponsavelAluno
     * @param InResponsavelAluno
     * @return OutConsultarResponsavelAluno|OutErro
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    public function getConsultarResponsavelAluno(InResponsavelAluno $inConsultarResponsavelAluno)
    {
        try {
            $url = '/ncaapi/api/Aluno/ConsultarResponsavelAluno';
            $data = [
                "InDocumentosAluno" => $inConsultarResponsavelAluno->getInDocumentosAluno(),
                "inAluo" => $inConsultarResponsavelAluno->getInAluno()
            ];

            $response = $this->getApiResponse('GET', $url,  $data);
            return OutConsultarResponsavelAluno::fromJson($response);
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
     * Summary of addStudentToSed
     * @param InFichaAluno $inFichaAluno
     * @return OutFichaAluno|RequestException
     * @throws Exception
     */
    public function addStudentToSed(InFichaAluno $inFichaAluno)
    {
        try{
            $url = '/ncaapi/api/Aluno/FichaAluno';
            $response = $this->getApiResponse('POST', $url, $inFichaAluno);

            return OutFichaAluno::fromJson($response);
        } catch(RequestException $clienteException) {
            return $clienteException;
        } catch(Exception $exception) {
            throw $exception;
        }
    }



    /**
     * Summary of editStudent
     * @param InManutencao $inManutencao
     * @return OutHandleApiResult
     */
    public function editStudent(InManutencao $inManutencao)
    {
        try{
            $url = '/ncaapi/api/Aluno/Manutencao';
            $response = $this->getApiResponse('POST', $url, $inManutencao);

            return OutHandleApiResult::fromJson($response);
        } catch(RequestException $clienteException) {
            echo 'Erro durante a requisição: ' . $clienteException->getMessage();
        } catch(Exception $exception) {
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
