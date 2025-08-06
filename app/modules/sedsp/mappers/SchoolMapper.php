<?php

Yii::import('application.modules.sedsp.models.*');
class SchoolMapper
{
    private const CODIGO_UF = '35';

    /**
     * Summary of parseToTAGSchool.
     * @return array<SchoolIdentification>
     */
    public static function parseToTAGSchool(OutEscola $outEscola)
    {
        $result = [];

        $outEscolas = $outEscola->outEscolas[0];

        $school_id = self::mapToTAGInepId($outEscolas->outCodEscola);
        $school_tag = SchoolIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $school_id]);
        if (null == $school_tag) {
            $school_tag = new SchoolIdentification();
            $school_tag->inep_id = $school_id;
            $school_tag->regulation = 1;
            $school_tag->edcenso_uf_fk = (int) EdcensoUf::model()->find('acronym = :acronym', [':acronym' => 'SP'])->id;
            $school_tag->location = 1;
            $school_tag->offer_or_linked_unity = 1;
            $school_tag->id_difflocation = 7;
        }
        $school_tag->name = $outEscolas->outDescNomeEscola;
        $school_tag->edcenso_city_fk = (int) EdcensoCity::model()->find('name = :name', [':name' => $outEscolas->outDescMunicipio])->id;
        $school_tag->edcenso_district_fk = (int) EdcensoDistrict::model()->find('name = :name', [':name' => $outEscolas->outNomeDistrito])->id;
        if ('FEDERAL' == $outEscolas->outNomeRedeEnsino) {
            $school_tag->administrative_dependence = 1;
        }
        if ('ESTADUAL' == $outEscolas->outNomeRedeEnsino) {
            $school_tag->administrative_dependence = 2;
        }
        if ('MUNICIPAL' == $outEscolas->outNomeRedeEnsino) {
            $school_tag->administrative_dependence = 3;
        }
        if ('PRIVADA' == $outEscolas->outNomeRedeEnsino) {
            $school_tag->administrative_dependence = 4;
        }
        $school_tag->latitude = $outEscolas->outLatitude;
        $school_tag->longitude = $outEscolas->outLongitude;
        $school_tag->cep = str_replace('-', '', $outEscolas->outCEP);
        $school_tag->address = $outEscolas->outTipoLogradouro.' '.$outEscolas->outDescEndereco;
        $school_tag->address_number = $outEscolas->outNumero;
        $school_tag->address_complement = $outEscolas->outDescComplemento;
        $school_tag->address_neighborhood = $outEscolas->outDescBairro;

        $result['SchoolUnities'] = [];
        foreach ($outEscolas->getOutUnidades() as $outUnidade) {
            $school_unity_tag = SedspSchoolUnities::model()->find('code = :code', [':code' => $outUnidade->getOutCodUnidade()]);
            if (null == $school_unity_tag) {
                $school_unity_tag = new SedspSchoolUnities();
                $school_unity_tag->code = $outUnidade->getOutCodUnidade();
                $school_unity_tag->school_inep_id_fk = $school_id;
            }
            $school_unity_tag->description = $outUnidade->getOutDescNomeUnidade();
            $result['SchoolUnities'][] = $school_unity_tag;
        }

        $result['SchoolIdentification'] = $school_tag;

        return $result;
    }

    public static function mapToTAGInepId($sedInepId)
    {
        if (strlen($sedInepId) < 6) {
            return self::CODIGO_UF.'0'.$sedInepId;
        }

        return self::CODIGO_UF.$sedInepId;
    }

    public static function mapToSEDInepId($tagInepId)
    {
        return (string) ((int) substr($tagInepId, 2));
    }
}
