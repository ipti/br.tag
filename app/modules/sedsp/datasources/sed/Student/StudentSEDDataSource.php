<?php


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
     * @param string $name
     * @param string $birthday
     * @param string $mothersName
     * @return DadosAluno|OutErro
     * 
     */
    public function getStudentRA($name, $birthday, $mothersName,$force)
    {
        if($force){
            $name = '';
        }
        $body = array("inNomeAluno" => $name,
        "inNomeMae" => $mothersName,
        "inDataNascimento" => $birthday);
        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ConsultaRA', [
                'body' => json_encode($body)
            ]);
            return new DadosAluno($name, $response->getBody()->getContents());
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
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
     * Summary of getStudentWithRA
     * @param mixed $RA
     * @return mixed
     * 
     */
    public function getStudentWithRA($RA)
    {
        $body['inAluno'] = array("inNumRA" => $RA,
        "inSiglaUFRA" => "SP");
        try {
            $response = $this->client->request('GET', '/ncaapi/api/Aluno/ExibirFichaAluno', [
                'body' => json_encode($body)
            ]);
            return $response;
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        }
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
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }

    /**
     * 
     * Obtém informações de um aluno através da API.
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
            if (empty($inAluno->inNumRa) || empty($inAluno->inSiglaUfra)) {
                throw new InvalidArgumentException("Entrada inválida: dados incompletos.");
            }

            if (strlen($inAluno->inNumRa) > self::LENGTH_IN_NUM_RA || 
                isset($inAluno->inSiglaUfra) ? (strlen($inAluno->inSiglaUfra) > self::LENGTH_IN_SIGLA_UFRA) : false || 
                strlen($inAluno->inDigitoRa) > self::LENGTH_IN_DIGITO_RA) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            }

            $alunoRequestBody = [
                'inAluno' => [
                    'inNumRA' => $inAluno->inNumRa, 
                    'inDigitoRA' => $inAluno->inDigitoRa ?? null, 
                    'InSiglaUFRA' => $inAluno->inSiglaUfra
                ]
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ExibirFichaAluno', [
                'body' => json_encode($alunoRequestBody)
            ]);
            
            $aluno = json_decode($apiResponse->getBody()->getContents());
            return new OutAluno($aluno);
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }   

    /**
     * 
     * Summary of getConsultarResponsavelAluno
     * @param InConsultarResponsavelAluno
     * @return OutConsultarResponsavelAluno|OutErro
     * 
     */
    function getConsultarResponsavelAluno(InConsultarResponsavelAluno $inConsultarResponsavelAluno)
    {
        try {
            $responsavelRequestBody = [
                'inAluno' => [
                    'inNumRA' => $inConsultarResponsavelAluno->inAluno->inNumRA ?? null,
                    'inDigitoRa' => isset($inConsultarResponsavelAluno->inAluno->inNumRA) ? $inConsultarResponsavelAluno->inAluno->inDigitoRa: null,
                    'inSiglaUFRa' => isset($inConsultarResponsavelAluno->inAluno->inDigitoRa) ? $inConsultarResponsavelAluno->inAluno->inSiglaUFRa : null
                ],
                'InDocumentosAluno' => [
                    'inCPF' => $inConsultarResponsavelAluno->InDocumentosAluno->inCPF ?? null,
                    'inNRRG' => $inConsultarResponsavelAluno->InDocumentosAluno->inNRRG ?? null,
                    'inUFRG' => isset($inConsultarResponsavelAluno->InDocumentosAluno->inNRRG) ? $inConsultarResponsavelAluno->InDocumentosAluno->inUFRG: null
                ]
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Aluno/ConsultarResponsavelAluno', [
                'body' => json_encode($responsavelRequestBody)
            ]);
            
            $responsavelAluno = json_decode($apiResponse->getBody()->getContents());
            return new OutConsultarResponsavelAluno($responsavelAluno);
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }
}
