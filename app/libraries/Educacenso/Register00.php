<?php

class Register00
{
    private const SCHOOL_YEAR_DEFAULT_DATES_BY_YEAR = [
        2026 => [
            'initial_date' => '29/05/2025',
            'final_date' => '27/05/2026',
        ],
    ];

    private const SCHOOL_YEAR_DATE_LIMITS_BY_YEAR = [
        2026 => [
            'minimum' => '29/05/2025',
            'maximum' => '27/05/2026',
        ],
    ];

    private static function sanitizeString($string)
    {
        $wh = ['ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ô', 'Ú', 'Û', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '°', '.'];
        $by = ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'n', 'n', 'c', 'C', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];

        return str_replace($wh, $by, $string);
    }

    private static function configureSchoolYearDates(array &$attributes, $year)
    {
        $year = (int) $year;
        $attributes['initial_date'] = self::resolveSchoolYearDate($attributes['initial_date'] ?? null, $year, 'initial_date');
        $attributes['final_date'] = self::resolveSchoolYearDate($attributes['final_date'] ?? null, $year, 'final_date');

        self::validateSchoolYearDates($attributes['initial_date'], $attributes['final_date'], $year);
    }

    private static function resolveSchoolYearDate($date, $year, $field)
    {
        if ($date === null || $date === '') {
            return self::SCHOOL_YEAR_DEFAULT_DATES_BY_YEAR[$year][$field] ?? ($field === 'initial_date' ? '25/02/' . $year : '12/12/' . $year);
        }

        return self::normalizeDate($date);
    }

    private static function normalizeDate($date)
    {
        foreach (['d/m/Y', 'Y-m-d'] as $format) {
            $dateTime = DateTime::createFromFormat('!' . $format, $date);
            if ($dateTime instanceof DateTime && $dateTime->format($format) === $date) {
                return $dateTime->format('d/m/Y');
            }
        }

        throw new InvalidArgumentException('Data do calendario letivo invalida para exportacao do Censo Escolar.');
    }

    private static function validateSchoolYearDates($initialDate, $finalDate, $year)
    {
        $initialDateTime = self::parseDate($initialDate);
        $finalDateTime = self::parseDate($finalDate);

        if ($finalDateTime <= $initialDateTime) {
            throw new InvalidArgumentException('A data final do calendario letivo deve ser posterior a data inicial.');
        }

        if (!isset(self::SCHOOL_YEAR_DATE_LIMITS_BY_YEAR[(int) $year])) {
            return;
        }

        $minimumDateTime = self::parseDate(self::SCHOOL_YEAR_DATE_LIMITS_BY_YEAR[(int) $year]['minimum']);
        $maximumDateTime = self::parseDate(self::SCHOOL_YEAR_DATE_LIMITS_BY_YEAR[(int) $year]['maximum']);

        if ($initialDateTime < $minimumDateTime || $initialDateTime > $maximumDateTime || $finalDateTime < $minimumDateTime || $finalDateTime > $maximumDateTime) {
            throw new InvalidArgumentException('As datas do calendario letivo de 2026 devem estar entre 29/05/2025 e 27/05/2026.');
        }
    }

    private static function parseDate($date)
    {
        $dateTime = DateTime::createFromFormat('!d/m/Y', $date);
        if (!$dateTime instanceof DateTime || $dateTime->format('d/m/Y') !== $date) {
            throw new InvalidArgumentException('Data do calendario letivo invalida para exportacao do Censo Escolar.');
        }

        return $dateTime;
    }

    private static function ensureRegisterGroupHasSelectedValue(array &$register, array $corders, $defaultCorder)
    {
        foreach ($corders as $corder) {
            if (($register[$corder] ?? null) === '1' || ($register[$corder] ?? null) === 1) {
                return;
            }
        }

        foreach ($corders as $corder) {
            if (!isset($register[$corder]) || $register[$corder] === null || $register[$corder] === '') {
                $register[$corder] = '0';
            }
        }

        $register[$defaultCorder] = '1';
    }

    public static function export($year)
    {
        $registers = [];

        $register = [];

        $attributes = SchoolIdentification::model()->findByPk(Yii::app()->user->school)->attributes;

        $attributes['name'] = trim(strtoupper(self::sanitizeString($attributes['name'])));

        if ($attributes['address_complement'] !== '' && $attributes['address_complement'] !== null) {
            $attributes['address_complement'] = strtoupper($attributes['address_complement']);
        }

        $attributes['situation'] = '1';
        if ($attributes['situation'] == '1') {
            $attributes['regulation'] = '2';
            self::configureSchoolYearDates($attributes, $year);
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

        if (empty($attributes['ddd']) || $attributes['ddd'] == null) {
            $attributes['phone_number'] = '';
            $attributes['other_phone_number'] = '';
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
            // $school = SchoolIdentification::model()->findByPk($attributes['inep_id']);
            // $attributes['edcenso_district_fk'] = str_pad($school->edcensoDistrictFk->code, 2, "0", STR_PAD_LEFT);
            $attributes['edcenso_district_fk'] = '05';
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

        if (!in_array($attributes['regulation'], ['1', '2'])) {
            $attributes['regulation_organ_federal'] = '';
            $attributes['regulation_organ_state'] = '';
            $attributes['regulation_organ_municipal'] = '';
        } else {
            if (in_array($attributes['administrative_dependence'], ['2', '3'])) {
                $attributes['regulation_organ_federal'] = '0';
            }
            if (in_array($attributes['administrative_dependence'], ['1', '2'])) {
                $attributes['regulation_organ_municipal'] = '0';
            }
            if ($attributes['regulation_organ_federal'] == '1') {
                $attributes['regulation_organ_municipal'] = '0';
            }

            if ($attributes['regulation_organ_municipal'] == '0' && $attributes['regulation_organ_state'] == '0' && $attributes['regulation_organ_federal'] == '0') {
                if ($attributes['administrative_dependence'] == '1') {
                    $attributes['regulation_organ_federal'] = '1';
                } elseif ($attributes['administrative_dependence'] == '3') {
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

        $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 0 order by corder', [':year' => $year]);
        foreach ($edcensoAliases as $edcensoAlias) {
            $register[$edcensoAlias->corder] = $edcensoAlias->default;
            if ($edcensoAlias['attr'] != null && $attributes[$edcensoAlias['attr']] !== $edcensoAlias->default) {
                $register[$edcensoAlias->corder] = $attributes[$edcensoAlias['attr']];
            }
        }

        if ((int) $year === 2026) {
            self::ensureRegisterGroupHasSelectedValue($register, range(22, 25), 22);
            self::ensureRegisterGroupHasSelectedValue($register, range(26, 31), 26);
        }

        array_push($registers, EducacensoRegisterFormatter::format(0, $register, $year));

        return $registers;
    }
}
