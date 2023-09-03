<?php

Yii::import('application.modules.sedsp.models.*');
class SchoolMapper
{
    private const CODIGO_UF = "35";
    /**
     * Summary of parseToTAGSchool
     * @param OutEscola $outEscola
     * @return array<SchoolIdentification>
     */
    public static function parseToTAGSchool(OutEscola $outEscola)
    {
        $result = [];

        $outEscolas = $outEscola->outEscolas[0];

        $school_tag = new SchoolIdentification;
        $school_tag->name = $outEscolas->outDescNomeEscola;
        $school_tag->regulation = 1;
        $school_tag->edcenso_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => "SP"])->id);
        $school_tag->inep_id = self::mapInepId($outEscolas->outCodEscola);
        $school_tag->edcenso_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outEscolas->outDescMunicipio])->id);
        $school_tag->edcenso_district_fk = intval(EdcensoDistrict::model()->find("name = :name", [":name" => $outEscolas->outNomeDistrito])->id);
        if ($outEscolas->outNomeRedeEnsino == "FEDERAL") {
            $school_tag->administrative_dependence = 1;
        }
        if ($outEscolas->outNomeRedeEnsino == "ESTADUAL") {
            $school_tag->administrative_dependence = 2;
        }
        if ($outEscolas->outNomeRedeEnsino == "MUNICIPAL") {
            $school_tag->administrative_dependence = 3;
        }
        if ($outEscolas->outNomeRedeEnsino == "PRIVADA") {
            $school_tag->administrative_dependence = 4;
        }
        $school_tag->location = 1;
        $school_tag->offer_or_linked_unity = 1;
        $school_tag->id_difflocation = 7;
        $school_tag->latitude = $outEscolas->outLatitude;
        $school_tag->longitude = $outEscolas->outLongitude;
        $school_tag->cep = str_replace("-", "", $outEscolas->outCEP);
        $school_tag->address = $outEscolas->outTipoLogradouro . " " . $outEscolas->outDescEndereco;
        $school_tag->address_number = $outEscolas->outNumero;
        $school_tag->address_complement = $outEscolas->outDescComplemento;

        $result["SchoolIdentification"] = $school_tag;

        return $result;
    }

    public static function mapInepId($sedInepId)
    {
        if (sizeof($sedInepId) < 6) {
            return CODIGO_UF . "0" . $sedInepId;
        }
        return CODIGO_UF . $sedInepId;
    }
}

?>