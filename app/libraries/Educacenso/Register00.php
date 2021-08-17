<?php
class Register00
{
    public static function export()
    {
        $registers = [];

        $register = [];

        $attributes = SchoolIdentification::model()->findByPk(Yii::app()->user->school)->attributes;

        $attributes['id'] = $attributes['inep_id'];

        foreach ($attributes as $column => $value) {
            if (empty($value)) {
                $alias = EdcensoAlias::model()->findByAttributes(['year' => 2021, 'register' => '00', 'attr' => $column]);
                $attributes[$column] = $alias->default;
                if (isset($alias->corder)) {
                    $register[$alias->corder] = '';
                }
            }
        }

        $attributes['initial_date'] = '25/02/2021';
        $attributes['final_date'] = '12/12/2021';

        if ($attributes['situation'] == '1') {
            $attributes['regulation'] = '2';
        }

        if (empty($attributes['inep_head_school'])) {
            $attributes['offer_or_linked_unity'] = '0';
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
            $attributes['private_school_category'] = '';
        }

        // Caso o municipio seja Brasilia, o campo municipal não pode ser preenchido com o valor 1 (Sim)
        if ($attributes['edcenso_city_fk'] == '5300108' && $attributes['regulation_organ_municipal'] == '1') {
            $attributes['regulation_organ_municipal'] = '0';
        }

        if (! in_array($attributes['regulation'], array('1', '2'))) {
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
                $attributes['regulation_organ_municipal'] = '';
            }
        }

        foreach ($attributes as $column => $value) {
            $alias = EdcensoAlias::model()->findByAttributes(['year' => 2021, 'register' => '00', 'attr' => $column]);
            if (isset($alias->corder)) {
                $register[$alias->corder] = $value;
            }
        }

        ksort($register);
        array_push($registers, implode('|', $register));

        return $registers;
    }
}