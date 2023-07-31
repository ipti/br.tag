<?php

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;


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





    /**
     * ===========================
     * POST REQUEST METHODS
     * ===========================
     */

    

    /**
     * Summary of addStudent
     * @param InFichaAluno $inFichaAluno
     * @return OutErro|ResponseInterface
     * 
     * @throws InvalidArgumentException
     * @throws Exception
     */
    function addStudent(InFichaAluno $inFichaAluno)
    {
        try{
            $inFichaAlunoBody = [
                'inDadosPessoais' => [
                    'inNomeAluno' => $inFichaAluno->inDadosPessoais->inNomeAluno,
                    'inNomeMae' => $inFichaAluno->inDadosPessoais->inNomeMae,
                    'inNomePai' => isset($inFichaAluno->inDadosPessoais->inNomePai) ? $inFichaAluno->inDadosPessoais->inNomePai: null,
                    'inNomeSocial' => isset($inFichaAluno->inDadosPessoais->inNomeSocial) ? $inFichaAluno->inDadosPessoais->inNomeSocial : null,
                    'inNomeAfetivo' => isset($inFichaAluno->inDadosPessoais->inNomeAfetivo) ? $inFichaAluno->inDadosPessoais->inNomeAfetivo : null,
                    'inDataNascimento' => $inFichaAluno->inDadosPessoais->inDataNascimento,
                    'inCorRaca' => $inFichaAluno->inDadosPessoais->inCorRaca,
                    'inSexo' => $inFichaAluno->inDadosPessoais->inSexo,
                    'inBolsaFamilia' => isset($inFichaAluno->inDadosPessoais->inBolsaFamilia) ? $inFichaAluno->inDadosPessoais->inBolsaFamilia : null,
                    'inQuilombola' => isset($inFichaAluno->inDadosPessoais->inQuilombola) ? $inFichaAluno->inDadosPessoais->inQuilombola : null,
                    'inPossuiInternet' => $inFichaAluno->inDadosPessoais->inPossuiInternet,
                    'inPossuiNotebookSmartphoneTablet' => $inFichaAluno->inDadosPessoais->inPossuiNotebookSmartphoneTablet,
                    'inTipoSanguineo' => isset($inFichaAluno->inDadosPessoais->inTipoSanguineo) ? $inFichaAluno->inDadosPessoais->inTipoSanguineo : null,
                    'inDoadorOrgaos' => isset($inFichaAluno->inDadosPessoais->inDoadorOrgaos) ? $inFichaAluno->inDadosPessoais->inDoadorOrgaos : null,
                    'inNumeroCNS' => isset($inFichaAluno->inDadosPessoais->inNumeroCns) ? $inFichaAluno->inDadosPessoais->inNumeroCns : null,
                    'inEmail' => isset($inFichaAluno->inDadosPessoais->inEmail) ? $inFichaAluno->inDadosPessoais->inEmail : null,
                    'inNacionalidade' => $inFichaAluno->inDadosPessoais->inNacionalidade,
                    'inNomeMunNascto' => $inFichaAluno->inDadosPessoais->inNomeMunNascto,
                    'inUFMunNascto' => ($inFichaAluno->inDadosPessoais->inNacionalidade == 1) ? $inFichaAluno->inDadosPessoais->inUfMunNascto: null,
                    'inCodMunNasctoDNE' => isset($inFichaAluno->inDadosPessoais->inCodMunNasctoDne) ? $inFichaAluno->inDadosPessoais->inCodMunNasctoDne : null,
                    'inDataEntradaPais' => $inFichaAluno->inDadosPessoais->inDataEntradaPais,
                    'inCodPaisOrigem' => $inFichaAluno->inDadosPessoais->inCodPaisOrigem,
                    'inPaisOrigem' => $inFichaAluno->inDadosPessoais->inPaisOrigem,
                ],            
                'inDocumentos' => [
                    'inCPF' => $inFichaAluno->inDocumentos->inCPF ?? null,
                    'inNumNIS' => $inFichaAluno->inDocumentos->inNumNIS ?? null,
                    'inNumDoctoCivil' => $inFichaAluno->inDocumentos->inNumRG ?? null,
                    'inDigitoDoctoCivil' => $inFichaAluno->inDocumentos->inDigitoRG,
                    'inUFDoctoCivil' => $inFichaAluno->inDocumentos->inUFRG ?? null,
                    'inDataEmissaoDoctoCivil' => $inFichaAluno->inDocumentos->inDataEmissaoDoctoCivil,
                    'inJustificativaDocumentos' => $inFichaAluno->inDocumentos->inJustificativaDocumentos,
                    'inCodigoINEP' => $inFichaAluno->inDocumentos->inNumINEP ?? null,
                ],                
                'inCertidaoNova' => [
                    'inCertMatr01' => $inFichaAluno->inCertidaoNova->inCertMatr01,
                    'inCertMatr02' => $inFichaAluno->inCertidaoNova->inCertMatr02,
                    'inCertMatr03' => $inFichaAluno->inCertidaoNova->inCertMatr03,
                    'inCertMatr04' => $inFichaAluno->inCertidaoNova->inCertMatr04,
                    'inCertMatr05' => $inFichaAluno->inCertidaoNova->inCertMatr05,
                    'inCertMatr06' => $inFichaAluno->inCertidaoNova->inCertMatr06,
                    'inCertMatr07' => $inFichaAluno->inCertidaoNova->inCertMatr07,
                    'inCertMatr08' => $inFichaAluno->inCertidaoNova->inCertMatr08,
                    'inCertMatr09' => $inFichaAluno->inCertidaoNova->inCertMatr09,
                    'inDataEmissaoCertidao' => $inFichaAluno->inCertidaoNova->inDataEmissaoCertidao,
                ],                
                'inCertidaoAntiga' => [
                    'inNumCertidao' => $inFichaAluno->inCertidaoAntiga->inNumCertidao,
                    'inNumLivroReg' => $inFichaAluno->inCertidaoAntiga->inLivro,
                    'inFolhaRegNum' => $inFichaAluno->inCertidaoAntiga->inFolha,
                    'inNomeMunComarca' => $inFichaAluno->inCertidaoAntiga->inMunicipioComarca,
                    'inDistritoNasc' => $inFichaAluno->inCertidaoAntiga->inDistritoCertidao,
                    'inUFComarca' => $inFichaAluno->inCertidaoAntiga->inUfComarca,
                    'inDataEmissaoCertidao' => $inFichaAluno->inCertidaoAntiga->inDataEmissaoCertidao,               
                ],
                'inEnderecoResidencial' => [
                    'inLogradouro' => $inFichaAluno->inEnderecoResidencial->inLogradouro,
                    'inNumero' => $inFichaAluno->inEnderecoResidencial->inNumero,
                    'inBairro' => $inFichaAluno->inEnderecoResidencial->inBairro,
                    'inNomeCidade' => $inFichaAluno->inEnderecoResidencial->inNomeCidade,
                    'inUFCidade' => $inFichaAluno->inEnderecoResidencial->inUFCidade,
                    'inComplemento' => $inFichaAluno->inEnderecoResidencial->inComplemento ?? null,
                    'inCep' => $inFichaAluno->inEnderecoResidencial->inCep,
                    'inAreaLogradouro' => $inFichaAluno->inEnderecoResidencial->inAreaLogradouro,
                    'inCodLocalizacaoDiferenciada' => $inFichaAluno->inEnderecoResidencial->inCodLocalizacaoDiferenciada ?? null,
                    'inCodMunicipioDNE' => $inFichaAluno->inEnderecoResidencial->inCodMunicipioDNE ?? null,
                    'inLatitude' => $inFichaAluno->inEnderecoResidencial->inLatitude,
                    'inLongitude' => $inFichaAluno->inEnderecoResidencial->inLongitude,                
                ],
                'inDeficiencia' => [
                    'inCodNecessidade' => $inFichaAluno->inDeficiencia->inCodNecessidade ?? null,
                    'inMobilidadeReduzida' => $inFichaAluno->inDeficiencia->inMobilidadeReduzida,
                    'inTipoMobilidadeReduzida' => $inFichaAluno->inDeficiencia->inTipoMobilidadeReduzida ?? null,
                    'inCuidador' => $inFichaAluno->inDeficiencia->inCuidador ?? null,
                    'inTipoCuidador' => $inFichaAluno->inDeficiencia->inTipoCuidador ?? null,
                    'inProfSaude' => $inFichaAluno->inDeficiencia->inProfSaude ?? null,
                    'inTipoProfSaude' => $inFichaAluno->inDeficiencia->inTipoProfSaude ?? null,                
                ],
                'inRecursoAvaliacao' => [
                    'inNenhum' => $inFichaAluno->inRecursoAvaliacao->inNenhum ?? null,
                    'inAuxilioLeitor' => $inFichaAluno->inRecursoAvaliacao->inAuxilioLeitor ?? null,
                    'inAuxilioTranscricao' => $inFichaAluno->inRecursoAvaliacao->inAuxilioTranscricao ?? null,
                    'inGuiaInterprete' => $inFichaAluno->inRecursoAvaliacao->inGuiaInterprete ?? null,
                    'inInterpreteLibras' => $inFichaAluno->inRecursoAvaliacao->inInterpreteLibras ?? null,
                    'inLeituraLabial' => $inFichaAluno->inRecursoAvaliacao->inLeituraLabial ?? null,
                    'inProvaBraile' => $inFichaAluno->inRecursoAvaliacao->inProvaBraile ?? null,
                    'inProvaAmpliada' => $inFichaAluno->inRecursoAvaliacao->inProvaAmpliada ?? null,
                    'inFonteProva' => $inFichaAluno->inRecursoAvaliacao->inFonteProva ?? null,
                    'inProvaVideoLibras' => $inFichaAluno->inRecursoAvaliacao->inProvaVideoLibras ?? null,
                    'inCdAudioDefVisual' => $inFichaAluno->inRecursoAvaliacao->inCdAudioDefVisual ?? null,
                    'inProvaLinguaPortuguesa' => $inFichaAluno->inRecursoAvaliacao->inProvaLinguaPortuguesa ?? null,                
                ],
                'inRastreio' => [
                    'inUsuarioRemoto' => $inFichaAluno->inRastreio->inUsuarioRemoto ?? null,
                    'inNomeUsuario' => $inFichaAluno->inRastreio->inNomeUsuario ?? null,
                    'inNumCPF' => $inFichaAluno->inRastreio->inNumCPF ?? null,
                    'inLocalPerfilAcesso' => $inFichaAluno->inRastreio->inLocalPerfilAcesso ?? null,
                ]
            ];

        }catch(InvalidArgumentException $invalidArgumentException) {
            throw $invalidArgumentException;
        }catch(ClientException $clienteException) {
            return new OutErro($clienteException);
        }catch(Exception $exception) {
            throw $exception;
        }
        

        $apiResponse = $this->client->request('POST', '/ncaapi/api/Aluno/FichaAluno', [
            'body' => json_encode($inFichaAlunoBody)
        ]);

        return $apiResponse;
    }
}
