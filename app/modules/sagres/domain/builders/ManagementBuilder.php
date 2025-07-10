<?php

use SagresEdu\CabecalhoTType;


class ManagementBuilder
{

    private CabecalhoTType $cabecalhoType;
    private $referenceYear;
    private $month;

    public function __construct($referenceYear, $month)
    {
        $this->cabecalhoType = new CabecalhoTType;
        $this->referenceYear = $referenceYear;
        $this->month = $month;
    }

    public function build(): CabecalhoTType
    {

        $managementData = new \ManagementExtractor();
        $managementId = $managementData->getManagementId();
        $management = $managementData->execute($managementId);
        $finalDay = $this->getFinalDay($this->referenceYear, $this->month);
        $this->cabecalhoType
            ->setCodigoUnidGestora($management->cod_unidade_gestora)
            ->setNomeUnidGestora($management->name_unidade_gestora)
            ->setMesReferencia($this->month)
            ->setAnoReferencia($this->referenceYear)
            ->setDiaInicPresContas((int) 01)
            ->setDiaFinaPresContas($finalDay)
            ->setCpfGestor(str_replace([".", "-"], "", $management->cpf_gestor))
            ->setCpfResponsavel(str_replace([".", "-"], "", $management->cpf_responsavel))
            ->setVersaoXml(1);

        return $this->cabecalhoType;
    }

    private function getFinalDay($referenceYear, $month)
    {
        $day = (int) date(format: 't', timestamp: strtotime("$this->referenceYear-$this->month-01"));
        return $this->ajustarUltimoDiaUtil($this->referenceYear, $this->month, $day);
    }


    private function ajustarUltimoDiaUtil($referenceYear, $month, $finalDay)
    {
        $url = "https://brasilapi.com.br/api/feriados/v1/" . $referenceYear;
        $responseFeriados = file_get_contents($url);

        if ($responseFeriados !== false) {
            $datas = json_decode($responseFeriados, true);
            if ($datas !== null) {
                foreach ($datas as $data) {
                    $mes = (int) substr($data['date'], 5, 2);
                    if ($mes < $month)
                        continue;
                    if ($mes > $month)
                        break;

                    $day = (int) substr($data['date'], -2);
                    if ($day === $finalDay) {
                        $finalDay -= 1;
                    }
                }
            }
        }
        return $finalDay;
    }
}
?>