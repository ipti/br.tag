<?php

use GuzzleHttp\Exception\ClientException;

require 'app/vendor/autoload.php';

/**
 * Summary of StudentSEDDataSource
 */
class StudentSEDDataSource extends SedDataSource
{
    const LENGTH_IN_NUM_RA = 12;
    const LENGTH_IN_DIGITO_RA = 2;
    const LENGTH_IN_SIGLA_UFRA = 2;



    /**
     * ===========================
     * GET REQUEST METHODS
     * ===========================
     */


    /**
     * Summary of getStudentRA
     * @param InConsultaRA $inAluno
     * @return OutConsultaRA|OutErro
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    public function getStudentRA($inConsultaRA)
    {
        try {
            $url = '/ncaapi/api/Aluno/ConsultaRA';
            $response = $this->getApiResponse('GET', $url, $inConsultaRA);
            return OutConsultaRA::fromJson($response);  
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        } catch (ClientException $e) {
            return new OutErro($e);
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
        foreach (array_slice($students, 0, 5) as $key => $student) {
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
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    function getListStudents(InListarAlunos $inListarAlunos)
    {
        try {
            $url = '/ncaapi/api/Aluno/ListarAlunos';
            $data = [
                "inFiltrosNomes" => $inListarAlunos->getInFiltrosNomes(),
                "inDocumentos" => $inListarAlunos->getInDocumentos()
            ];

            $response = $this->getApiResponse('GET', $url, $data);
            return OutListarAluno::fromJson($response);

        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param InAluno $inAluno Objeto contendo informações do aluno.
     * @return OutExibirFichaAluno|OutErro Retorna um objeto OutAluno em caso de sucesso ou OutErro em caso de erro.
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     */
    function exibirFichaAluno(InAluno $inAluno)
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

            $url = '/ncaapi/api/Aluno/ExibirFichaAluno';
            $response = $this->getApiResponse('GET', $url, ["inAluno" => $inAluno]);
          
            return outExibirFichaAluno::fromJson($response);
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
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
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido. 
     */
    function getConsultarResponsavelAluno(InResponsavelAluno $inConsultarResponsavelAluno)
    {  
        try {
            $url = '/ncaapi/api/Aluno/ConsultarResponsavelAluno';
            $data = [
                "InDocumentosAluno" => $inConsultarResponsavelAluno->getInDocumentosAluno(),
                "inAluo" => $inConsultarResponsavelAluno->getInAluno()
            ];

            $response = $this->getApiResponse('GET', $url,  $data);     
            return OutConsultarResponsavelAluno::fromJson($response);

        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
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
     * Summary of addStudent
     * @param InFichaAluno $inFichaAluno
     * @return OutFichaAluno|OutErro
     * 
     * @throws InvalidArgumentException
     * @throws Exception
     */
    function addStudent(InFichaAluno $inFichaAluno)
    {
        try{
            $url = '/ncaapi/api/Aluno/FichaAluno';
            $response = $this->getApiResponse('POST', $url, $inFichaAluno);

            return OutFichaAluno::fromJson($response);

        }catch(InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        }catch(GuzzleHttp\Exception\RequestException $clienteException) {
            return new OutErro($clienteException);
        }catch(Exception $exception) {
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
