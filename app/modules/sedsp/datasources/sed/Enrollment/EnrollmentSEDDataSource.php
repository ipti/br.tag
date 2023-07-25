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
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/Matricula/ListarMatriculasRA', [
                'body' => json_encode($alunoRequestBody)
            ]);
            
            $alunoListEnrollment = json_decode($apiResponse->getBody()->getContents());
            return new OutListaMatriculaRA($alunoListEnrollment);
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }
}
