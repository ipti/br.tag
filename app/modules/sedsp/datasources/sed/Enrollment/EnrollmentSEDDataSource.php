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
                'body' => json_encode(["inAluno" => $inAluno])
            ])->getBody()->getContents(), true);
            
            return OutListaMatriculaRA::fromJson($apiResponse);
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }
}
