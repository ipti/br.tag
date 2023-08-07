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
     * @param InFormacaoClasse $inClassroom
     * @return OutFormacaoClasse|OutErro
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
    
            $apiResponse = json_decode($this->client->request('GET', '/ncaapi/api/RelacaoAlunosClasse/FormacaoClasse', [
                'body' => json_encode($inClassroom)
            ])->getBody()->getContents(), true);

            return OutFormacaoClasse::fromJson($apiResponse);
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

            $apiResponse = json_decode($this->client->request('GET', 'ncaapi/api/TurmaClasse/ConsultaTurmaClasse', [
                'body' => json_encode($inConsultClass)
            ])->getBody()->getContents());
   
            return new OutConsultaTurmaClasse(
                intval($apiResponse->outAnoLetivo),
                intval($apiResponse->outCodEscola),
                $apiResponse->outNomeEscola,
                intval($apiResponse->outCodUnidade),
                intval($apiResponse->outCodTipoClasse),
                intval($apiResponse->outCodTurno),
                $apiResponse->outDescricaoTurno,
                intval($apiResponse->outTurma),
                $apiResponse->outDescricaoTurma,
                intval($apiResponse->outNrCapacidadeFisicaMaxima),
                intval($apiResponse->outNrAlunosAtivos),
                $apiResponse->outDataInicioAula,
                $apiResponse->outDataFimAula,
                $apiResponse->outHorarioInicioAula,
                $apiResponse->outHorarioFimAula,
                intval($apiResponse->outCodDuracao),
                intval($apiResponse->outCodHabilitacao),
                $apiResponse->outAtividadesComplementar,
                intval($apiResponse->outCodTipoEnsino),
                $apiResponse->outNomeTipoEnsino,
                $apiResponse->outNumeroSala,
                intval($apiResponse->outCodSerieAno),
                $apiResponse->outDescricaoSerieAno,
                new OutDiasSemana(
                    $apiResponse->outDiasSemana->outFlagSegunda,
                    $apiResponse->outDiasSemana->outHoraIniAulaSegunda,
                    $apiResponse->outDiasSemana->outHoraFimAulaSegunda,
                    $apiResponse->outDiasSemana->outFlagTerca,
                    $apiResponse->outDiasSemana->outHoraIniAulaTerca,
                    $apiResponse->outDiasSemana->outHoraFimAulaTerca,
                    $apiResponse->outDiasSemana->outFlagQuarta,
                    $apiResponse->outDiasSemana->outHoraIniAulaQuarta,
                    $apiResponse->outDiasSemana->outHoraFimAulaQuarta,
                    $apiResponse->outDiasSemana->outFlagQuinta,
                    $apiResponse->outDiasSemana->outHoraIniAulaQuinta,
                    $apiResponse->outDiasSemana->outHoraFimAulaQuinta,
                    $apiResponse->outDiasSemana->outFlagSexta,
                    $apiResponse->outDiasSemana->outHoraIniAulaSexta,
                    $apiResponse->outDiasSemana->outHoraFimAulaSexta,
                    $apiResponse->outDiasSemana->outFlagSabado
                ) 
            );          
        } catch (InvalidArgumentException $e) {
            return new OutErro($e);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return new OutErro($e);
        } catch (Exception $e) {
            return new OutErro($e);
        }
    }

    /**
     * Summary of incluirTurmaClassePOST
     * @param InClassroom $inClassroom
     * @return void
     */
    function incluirTurmaClassePOST($inClassroom)
    {
        $apiResponse = $this->client->request('POST', '/ncaapi/api/TurmaClasse/IncluirTurmaClasse', [
            'body' => json_encode($inClassroom)
        ]);
        return $apiResponse;
    }
}
