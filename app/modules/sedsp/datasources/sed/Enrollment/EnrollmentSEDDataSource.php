<?php

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
    
            $apiResponse = json_decode($this->client->request('GET', '/ncaapi/api/Matricula/ListarMatriculasRA', [
                'body' => json_encode($inAluno)
            ])->getBody()->getContents());
            
            $outListMatricula = [];
            foreach ($apiResponse->outListaMatriculas as $matricula) {
                $outListMatricula[] = new OutListaMatriculas(
                    $matricula->outAnoLetivo,
                    $matricula->outMunicipio,
                    $matricula->outNomeRedeEnsino,
                    $matricula->outCodEscola,
                    $matricula->outCodUnidade,
                    $matricula->outDescNomeAbrevEscola,
                    $matricula->outNumClasse,
                    $matricula->outNumAluno,
                    $matricula->outCodTurno,
                    $matricula->outDescricaoTurno,
                    $matricula->outCodTipoEnsino,
                    $matricula->outDescTipoEnsino,
                    $matricula->outCodSerieAno,
                    $matricula->outDescSerieAno,
                    $matricula->outGrauNivel,
                    $matricula->outSerieNivel,
                    $matricula->outTurma,
                    $matricula->outDescTurma,
                    $matricula->outCodHabilitacao,
                    $matricula->outDescHabilitacao,
                    $matricula->outDataInicioMatricula,
                    $matricula->outDataFimMatricula,
                    $matricula->outDataInclusaoMatricula,
                    $matricula->outCodSitMatricula,
                    $matricula->outDescSitMatricula,
                    $matricula->outCodSitTranspEscolar,
                    $matricula->outDescSitTranspEscolar
               );
            }
            
            return new OutListaMatriculaRA(
                new OutAluno(
                    $apiResponse->outNumRA,
                    $apiResponse->outDigitoRA,
                    $apiResponse->outSiglaUFRA,
                    $apiResponse->outNomeAluno,
                    $apiResponse->outNomeMae,
                    $apiResponse->outNomePai,
                    $apiResponse->outDataNascimento
                ),
                $outListMatricula,
                $apiResponse->outProcessoID
            );
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }
}
