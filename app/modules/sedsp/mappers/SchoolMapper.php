<?php

Yii::import('application.modules.sedsp.models.*');
class SchoolMapper
{
    private const CODIGO_UF = '35';

    /**
     * Summary of parseToTAGSchool
     * @param OutEscola $outEscola
     * @return array<SchoolIdentification>
     */
    public static function parseToTAGSchool(OutEscola $outEscola)
    {
        $result = [];

        $outEscolas = $outEscola->outEscolas[0];

        $schoolId = self::mapToTAGInepId($outEscolas->outCodEscola);
        $schoolTag = SchoolIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $schoolId]);
        if ($schoolTag == null) {
            $schoolTag = new SchoolIdentification();
            $schoolTag->inep_id = $schoolId;
            $schoolTag->regulation = 1;
            $schoolTag->edcenso_uf_fk = intval(EdcensoUf::model()->find('acronym = :acronym', [':acronym' => 'SP'])->id);
            $schoolTag->location = 1;
            $schoolTag->offer_or_linked_unity = 1;
            $schoolTag->id_difflocation = 7;
        }
        $schoolTag->name = $outEscolas->outDescNomeEscola;
        $schoolTag->edcenso_city_fk = intval(EdcensoCity::model()->find('name = :name', [':name' => $outEscolas->outDescMunicipio])->id);
        $schoolTag->edcenso_district_fk = intval(EdcensoDistrict::model()->find('name = :name', [':name' => $outEscolas->outNomeDistrito])->id);
        if ($outEscolas->outNomeRedeEnsino == 'FEDERAL') {
            $schoolTag->administrative_dependence = 1;
        }
        if ($outEscolas->outNomeRedeEnsino == 'ESTADUAL') {
            $schoolTag->administrative_dependence = 2;
        }
        if ($outEscolas->outNomeRedeEnsino == 'MUNICIPAL') {
            $schoolTag->administrative_dependence = 3;
        }
        if ($outEscolas->outNomeRedeEnsino == 'PRIVADA') {
            $schoolTag->administrative_dependence = 4;
        }
        $schoolTag->latitude = $outEscolas->outLatitude;
        $schoolTag->longitude = $outEscolas->outLongitude;
        $schoolTag->cep = str_replace('-', '', $outEscolas->outCEP);
        $schoolTag->address = $outEscolas->outTipoLogradouro . ' ' . $outEscolas->outDescEndereco;
        $schoolTag->address_number = $outEscolas->outNumero;
        $schoolTag->address_complement = $outEscolas->outDescComplemento;
        $schoolTag->address_neighborhood = $outEscolas->outDescBairro;

        $result['SchoolUnities'] = [];
        foreach ($outEscolas->getOutUnidades() as $outUnidade) {
            $schoolUnityTag = SedspSchoolUnities::model()->find('code = :code', [':code' => $outUnidade->getOutCodUnidade()]);
            if ($schoolUnityTag == null) {
                $schoolUnityTag = new SedspSchoolUnities();
                $schoolUnityTag->code = $outUnidade->getOutCodUnidade();
                $schoolUnityTag->school_inep_id_fk = $schoolId;
            }
            $schoolUnityTag->description = $outUnidade->getOutDescNomeUnidade();
            array_push($result['SchoolUnities'], $schoolUnityTag);
        }

        $result['SchoolIdentification'] = $schoolTag;

        return $result;
    }

    public static function mapToTAGInepId($sedInepId)
    {
        if (strlen($sedInepId) < 6) {
            return self::CODIGO_UF . '0' . $sedInepId;
        }
        return self::CODIGO_UF . $sedInepId;
    }

    public static function mapToSEDInepId($tagInepId)
    {
        return strval(intval(substr($tagInepId, 2)));
    }
}
