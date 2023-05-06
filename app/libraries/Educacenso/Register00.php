<?php

class Register00
{
    private static function sanitizeString($string)
    {
        $wh = ['ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ô', 'Ú', 'Û', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '°', '.'];
        $by = ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'n', 'n', 'c', 'C', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];

        return str_replace($wh, $by, $string);
    }

    public static function export($year)
    {
        $registers = [];

        $register = [];

        $attributes = SchoolIdentification::model()->findByPk(Yii::app()->user->school)->attributes;

        $attributes['name'] = trim(strtoupper(self::sanitizeString($attributes['name'])));

        if ($attributes["address_complement"] !== "" && $attributes["address_complement"] !== null) {
            $attributes["address_complement"] = strtoupper($attributes["address_complement"]);
        }

        $attributes['situation'] = '1';
        if ($attributes['situation'] == '1') {
            $attributes['regulation'] = '2';
            $attributes['initial_date'] = '25/02/' . $year;
            $attributes['final_date'] = '12/12/' . $year;
        } else {
            $attributes['initial_date'] = '';
            $attributes['final_date'] = '';
        }

        $hasInepHeadSchool = false;
        if (empty($attributes['inep_head_school']) || $attributes['inep_head_school'] == null) {
            $attributes['offer_or_linked_unity'] = '0';
        } else {
            $attributes['offer_or_linked_unity'] = '1';
            $hasInepHeadSchool = true;
        }
        if (!$hasInepHeadSchool && ($attributes['ies_code'] != null && !empty($attributes['ies_code']))) {
            $attributes['offer_or_linked_unity'] = '2';
        }

        if (empty($attributes['ddd']) || $attributes["ddd"] == null) {
            $attributes["phone_number"] = '';
            $attributes["other_phone_number"] = '';
        }

        if (!empty($attributes['latitude']) && !empty($attributes['longitude'])) {
            $attributes['latitude'] = str_replace(',', '.', $attributes['latitude']);
            $attributes['longitude'] = str_replace(',', '.', $attributes['longitude']);

            if (!($attributes['latitude'] >= -33.75208 && $attributes['latitude'] <= 5.271841 && $attributes['longitude'] >= -73.99045 && $attributes['longitude'] <= -32.39091)) {
                $attributes['latitude'] = $attributes['longitude'] = '';
            }
        } else {
            $attributes['latitude'] = $attributes['longitude'] = '';
        }

        if (!empty($attributes['edcenso_district_fk'])) {
            $school = SchoolIdentification::model()->findByPk($attributes['inep_id']);
            $attributes['edcenso_district_fk'] = $school->edcensoDistrictFk->code;
            $attributes['edcenso_district_fk'] = 05;
        }

        // O campo Categorial da Escola Privada não pode ser preenchido quando o campo dependencia administrativa não for preenchido com o valor 4 (Privada)
        if ($attributes['administrative_dependence'] != '4') {
            $attributes['private_school_business_or_individual'] = '';
            $attributes['private_school_syndicate_or_association'] = '';
            $attributes['private_school_ong_or_oscip'] = '';
            $attributes['private_school_non_profit_institutions'] = '';
            $attributes['private_school_s_system'] = '';
            $attributes['private_school_organization_civil_society'] = '';
            $attributes['private_school_category'] = '';
        }

        // Caso o municipio seja Brasilia, o campo municipal não pode ser preenchido com o valor 1 (Sim)
        if ($attributes['edcenso_city_fk'] == '5300108' && $attributes['regulation_organ_municipal'] == '1') {
            $attributes['regulation_organ_municipal'] = '0';
        }

        if (!in_array($attributes['regulation'], array('1', '2'))) {
            $attributes['regulation_organ_federal'] = '';
            $attributes['regulation_organ_state'] = '';
            $attributes['regulation_organ_municipal'] = '';
        } else {
            if (in_array($attributes['administrative_dependence'], array('2', '3'))) {
                $attributes['regulation_organ_federal'] = '0';
            }
            if (in_array($attributes['administrative_dependence'], array('1', '2'))) {
                $attributes['regulation_organ_municipal'] = '0';
            }
            if ($attributes['regulation_organ_federal'] == '1') {
                $attributes['regulation_organ_municipal'] = '0';
            }

            if ($attributes['regulation_organ_municipal'] == '0' && $attributes['regulation_organ_state'] == '0' && $attributes['regulation_organ_federal'] == '0') {
                if ($attributes['administrative_dependence'] == "1") {
                    $attributes['regulation_organ_federal'] = '1';
                } else if ($attributes['administrative_dependence'] == "3") {
                    $attributes['regulation_organ_municipal'] = '1';
                } else {
                    $attributes['regulation_organ_state'] = '1';
                }
            }
            if ($attributes['regulation_organ_state'] == null) {
                $attributes['regulation_organ_state'] = '0';
            }
            if ($attributes['regulation_organ_municipal'] == null) {
                $attributes['regulation_organ_municipal'] = '0';
            }
        }


        $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 0 order by corder', [":year" => $year]);
        foreach ($edcensoAliases as $edcensoAlias) {
            $register[$edcensoAlias->corder] = $edcensoAlias->default;
            if ($edcensoAlias["attr"] != null && $attributes[$edcensoAlias["attr"]] !== $edcensoAlias->default) {
                $register[$edcensoAlias->corder] = $attributes[$edcensoAlias["attr"]];
            }
        }

        array_push($registers, implode('|', $register));

        return $registers;
    }
}