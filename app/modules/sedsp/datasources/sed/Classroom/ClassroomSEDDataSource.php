<?php
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
     * Summary of getClassroom
     * @param InClassroom $inClassroom
     * @return OutClassroom|OutErro
     */
    public function getClassroom($inClassroom)
    {
        try {
            if (empty($inClassroom->inNumClasse)) {
                throw new InvalidArgumentException("Entrada inválida: dado incompleto.");
            }

            if (strlen($inClassroom->inNumClasse) > self::LENGTH_IN_NUM_CLASS) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            };

            $classroomRequestBody = [
                'inNumClasse' => $inClassroom->inNumClasse
            ];
    
            $apiResponse = $this->client->request('GET', '/ncaapi/api/RelacaoAlunosClasse/FormacaoClasse', [
                'body' => json_encode($classroomRequestBody)
            ]);
            
            $classroom = json_decode($apiResponse->getBody()->getContents());
            return new OutClassroom($classroom);
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }

    /**
     * Summary of getConsultClass
     * @param InConsultClass $inConsultClass
     * @return OutConsultClass|OutErro
     */
    function getConsultClass($inConsultClass) 
    {
        try {
            if (empty($consultClass->inAnoLetivo) || empty($consultClass->inNumClasse)) {
                throw new InvalidArgumentException("Entrada inválida: dados incompletos.");
            }

            if (strlen($inConsultClass->inAnoLetivo) > self::LENGTH_IN_ANO_LETIVO || strlen($inConsultClass->inNumClasse) > self::LENGTH_IN_NUM_CLASS) {
                throw new InvalidArgumentException("Entrada inválida: tamanho máximo excedido.");
            };

            $consultClassRequestBody = [
                'inAnoLetivo' => $inConsultClass->inAnoLetivo,
                'inNumClasse' => $inConsultClass->inNumClasse
            ];

            $apiResponse = $this->client->request('GET', 'ncaapi/api/TurmaClasse/ConsultaTurmaClasse', [
                'body' => json_encode($consultClassRequestBody)
            ]);
   
            $consultClass = json_decode($apiResponse->getBody()->getContents());  
            return new OutConsultClass($consultClass);          
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }
}
