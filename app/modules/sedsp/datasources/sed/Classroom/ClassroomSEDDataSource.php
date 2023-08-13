<?php
use GuzzleHttp\Exception\ClientException;
require_once 'app/vendor/autoload.php';

class ClassroomSEDDataSource extends SedDataSource
{
    const LENGTH_IN_NUM_CLASS = 9;
    const LENGTH_IN_ANO_LETIVO = 4;

    public function getClassroomRA($school_id, $year)
    {
        $promise = $this->client->requestAsync('GET', '/ncaapi/api/Aluno/ConsultaRA', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer YYlW35bvTjLdVc+j6ozpvBFHy/t8PLTGb4i6oeMwqgOOlO0XqSSbNKBGlVXzJbCMhkjugFNWg7KFwPGlUY0TZNgmi51SrVlPgj4vrKmiF6g0vvx5NYfLR0uVM5RpzKtZ2RpsAzdLpSHks7AdAv/bofUMpGDWHHe9MxjpDSWcLg39G1uVEUXtIaGFVEdQzvbJ+UWJ4fW4ZEkwxouk/NTYj48pCqI+ckRZTPP2uNmgpSW64ZniTC1TYKTGuMKQIThw0tJxsbWrfXK8AiUT8EHpff62udurJxfvdComB8hfVhDLbFbvm3WCZQLCNN86AKnc9+gM6wedWNIFu4EKkBV0ZQLF2DIHXkpFo7LSn8FGBhnZmVhwhI4TVlxyFSkrdg76eIZoaXEhBB5CG0tTOgeUxA=='
            ],
            'body' => json_encode([
                "inAnoLetivo" => $year,
                "inCodEscola" => $school_id
            ])
        ]);

        return $promise;
    }

/*     public function getAllClassroomsRA($classrooms)
    {

        $promises = [];
        foreach (array_slice($classrooms, 0, 5) as $key => $classroom) {
            $promises[] = $this->getClassroomRA($classroom->name, $classroom->birthday, $classroom->filiation_1);
        }

        $data = GuzzleHttp\Promise\Utils::all($promises)->then(function (array $responses) {
            $data = [];
            foreach ($responses as $response) {
                $data[] = json_decode($response->getBody()->getContents(), true);
            }
            return $data;
        })->wait(true);

        return $data;
    } */





    /**
     * ===========================
     * GET REQUEST METHODS
     * ===========================
     */

    /**
     * Summary of getConsultClass
     * @param InConsultaTurmaClasse $inConsultClass
     * @return OutConsultaTurmaClasse|OutErro
     */
    function getConsultClass($inConsultClass) 
    {
        try {
            if (empty($inConsultClass->inAnoLetivo) || empty($inConsultClass->inNumClasse)) {
                throw new InvalidArgumentException("Entrada inválida: dados incompletos.");
            }

            if (strlen($inConsultClass->inAnoLetivo) > self::LENGTH_IN_ANO_LETIVO || strlen($inConsultClass->inNumClasse) > self::LENGTH_IN_NUM_CLASS) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            };

            $url = 'ncaapi/api/TurmaClasse/ConsultaTurmaClasse';
            $response = $this->getApiResponse('GET', $url, $inConsultClass);
            return OutConsultaTurmaClasse::fromJson($response);

        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }


    /**
     * ===========================
     * POST REQUEST METHODS
     * ===========================
     */

    /**
     * Summary of incluirTurmaClassePOST
     * @param InIncluirTurmaClasse $inIncluirTurmaClasse
     * @return OutHandleApiResult
     */
    function addIncluirTurmaClasse($inIncluirTurmaClasse)
    {
        $url = '/ncaapi/api/TurmaClasse/IncluirTurmaClasse';
        $response = $this->getApiResponse('POST', $url, $inIncluirTurmaClasse);
       
        return OutHandleApiResult::fromJson($response);
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
