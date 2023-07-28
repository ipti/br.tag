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
     * 
     * Summary of getStudentRA
     * @param ?string $inCodEscola
     * @param InDadosPessoais $inAluno
     * @return OutDadosPessoais|OutErro
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     * 
     */
    public function getStudentRA($inCodEscola, $inAluno)
    {
        try {
            foreach ($inAluno as $key) {
               if (!isset($key)) {
                throw new InvalidArgumentException("Entrada inválida: dados incompletos.");             
               }
            }

            $studentRequestBody = [
                'inCodEscola' => $inCodEscola, 
                'inNomeAluno' => $inAluno->inNomeAluno, 
                'inNomeMae' => $inAluno->inNomeMae,
                'inDataNascimento' => $inAluno->inDataNascimento
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ConsultaRA', [
                'body' => json_encode($studentRequestBody)
            ]);
        
            return new OutDadosPessoais(json_decode($apiResponse->getBody()->getContents()));
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function addStudent($student_sed){
        $promise = $this->client->request('POST', '/ncaapi/api/Aluno/FichaAluno', [
            'body' => json_encode($student_sed)
        ]);
        return $promise;
    }


    /**
     * 
     * Summary of getAllStudentsRA
     * @param mixed $students
     * @return mixed
     * 
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
     * 
     * Summary of getListStudents
     * @param InListarAlunos $inListarAlunos
     * @return array<OutListarAlunos>|OutErro
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     * 
     */
    function getListStudents(InListarAlunos $inListarAlunos)
    {

        try {
            $listaRequestBody = [
                'inFiltrosNomes' => [
                    'inNomeAluno' => $inListarAlunos->inDadosPessoais->inNomeAluno ?? null, 
                    'inNomeSocial' => $inListarAlunos->inDadosPessoais->inNomeSocial ?? null, 
                    'inNomeMae' => $inListarAlunos->inDadosPessoais->inNomeMae ?? null,
                    'inNomePai' => $inListarAlunos->inDadosPessoais->inNomePai ?? null,
                    'inDataNascimento' => $inListarAlunos->inDadosPessoais->inDataNascimento ?? null
                ],
                'inDocumentos' => [
                    'inNumRG' => $inListarAlunos->inDocumentos->inNumRG ?? null,
                    'inDigitoRG' => $inListarAlunos->inDocumentos->inDigitoRG ?? null,
                    'inUFRG' => $inListarAlunos->inDocumentos->inUFRG ?? null,
                    'inCPF' => $inListarAlunos->inDocumentos->inCPF ?? null,
                    'inNumNIS' => $inListarAlunos->inDocumentos->inNumNIS ?? null,
                    'inNumINEP' => $inListarAlunos->inDocumentos->inNumINEP ?? null, 
                    'inNumCertidaoNova' => $inListarAlunos->inDocumentos->inNumCertidaoNova ?? null
                ]
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ListarAlunos', [
                'body' => json_encode($listaRequestBody)
            ]);
            
            $inListaAlunos = json_decode($apiResponse->getBody()->getContents());

            $outListaAlunos = [];
            foreach ($inListaAlunos->outListaAlunos as $inListaAluno) { 
                $outListaAlunos[] = new OutListarAlunos($inListaAluno);           
            }

            return $outListaAlunos;
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 
     * @param InAluno $inAluno Objeto contendo informações do aluno.
     * @return OutAluno|OutErro Retorna um objeto OutAluno em caso de sucesso ou OutErro em caso de erro.
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     * 
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

            $alunoRequestBody = [
                'inAluno' => [
                    'inNumRA' => $inAluno->inNumRA, 
                    'inDigitoRA' => $inAluno->inDigitoRA ?? null, 
                    'InSiglaUFRA' => $inAluno->inSiglaUFRA
                ]
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ExibirFichaAluno', [
                'body' => json_encode($alunoRequestBody)
            ]);
            
            $aluno = json_decode($apiResponse->getBody()->getContents());
            return new OutAluno($aluno);
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
     * @return OutResponsaveis|OutErro
     * 
     * @throws InvalidArgumentException Se os dados de entrada forem inválidos.
     * @throws Exception Se ocorrer um erro desconhecido.
     * 
     */
    function getConsultarResponsavelAluno(InResponsavelAluno $inConsultarResponsavelAluno)
    {
        try {
            $responsavelRequestBody = [
                'InDocumentosAluno' => [
                    'inCPF' => $inConsultarResponsavelAluno->inDocumentosAluno->inCPF ?? null,
                    'inNRRG' => $inConsultarResponsavelAluno->inDocumentosAluno->inNumRG ?? null,
                    'inUFRG' => isset($inConsultarResponsavelAluno->inDocumentosAluno->inUFRG) ? $inConsultarResponsavelAluno->inDocumentosAluno->inUFRG : null
                ],
                'inAluno' => [
                    'inNumRA' => $inConsultarResponsavelAluno->inAluno->inNumRA ?? null,
                    'inDigitoRA' => isset($inConsultarResponsavelAluno->inAluno->inNumRA) ? $inConsultarResponsavelAluno->inAluno->inDigitoRA : null,
                    'inSiglaUFRA' => isset($inConsultarResponsavelAluno->inAluno->inNumRA) ? $inConsultarResponsavelAluno->inAluno->inSiglaUFRA : null
                ],
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ConsultarResponsavelAluno', [
                'body' => json_encode($responsavelRequestBody)
            ]);
            
            $responsavelAluno = json_decode($apiResponse->getBody()->getContents());
            return new OutResponsaveis($responsavelAluno);
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
