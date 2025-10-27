<?php

$DS = DIRECTORY_SEPARATOR;

//Validações para a tabela school_structure
require_once dirname(__FILE__) . $DS . 'register.php';

class SchoolStructureValidation extends Register
{
    public function __construct()
    {
    }

    //campo 12
    public function buildingOccupationStatus($collun3, $collun8, $value)
    {
        if ($collun3 == 1) {
            if (!($value == 1 || $value == 2 || $value == 3)) {
                return [
                    'status' => false,
                    'erro' => "operation_location_building é 1. Valor $value não está enre as õpções"
                ];
            }
        } elseif ($collun3 != 1 && $collun8 != 1) {
            if ($value != null) {
                return ['status' => false, 'erro' => "Valor $value deveria ser nulo"];
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 13
    public function sharedBuildingSchool($collun3, $value)
    {
        if ($collun3 == 1) {
            if ($value == 0 || $value == 1) {
                return ['status' => true, 'erro' => ''];
            } else {
                return ['status' => false, 'erro' => "valor $value não permitido"];
            }
        } else {
            if ($value != null) {
                return ['status' => false, 'erro' => "operation_location_building não é 1. Valor $value deveria ser nulo"];
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 14 à 19
    public function sharedSchoolInep($collun13, $inepId, $sharedSchoolsInepIds)
    {
        if ($collun13 == 1) {
            foreach ($sharedSchoolsInepIds as $schoolInepId) {
                if ($schoolInepId !== null) {
                    $result = $this->isEqual(
                        substr($inepId, 0, 2),
                        substr($schoolInepId, 0, 2),
                        'Escolas não são do mesmo estado'
                    );
                    if ($result['status']) {
                        if ($inepId == $schoolInepId) {
                            return ['status' => false, 'erro' => 'Não se deve inserir a mesma escola.'];
                        }
                    } else {
                        return ['status' => false, 'erro' => 'Não são do mesmo UF.'];
                    }
                }
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 20
    public function consumedWater($value)
    {
        if ($value == 1 || $value == 2) {
            return ['status' => true, 'erro' => ''];
        }
        $value = $this->ifNull($value);
        return ['status' => false, 'erro' => "Valor $value não está entre as opções"];
    }

    public function supply($supplyLocations)
    {
        $len = sizeof($supplyLocations);

        $result = $this->atLeastOne($supplyLocations);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ($supplyLocations[$len - 1] == '1') { //ultimo campo
            for ($i = 0; $i < ($len - 1); $i++) { //primeiros campos
                if ($supplyLocations[$i] == '1') {
                    return [
                        'status' => false,
                        'erro' => 'Quando o campo de inexistência é marcado, nenhum outro deve estar.'
                    ];
                }
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 69
    public function schoolsCount($collun3, $value)
    {
        if ($collun3 == 1 && $value === 0) {
            return ['status' => false, 'erro' => 'Campo deve ser preenchido com um número maior que 0.'];
        }
        return ['status' => true, 'erro' => ''];
    }

    public function usedSchoolsCount($collun3, $value)
    {
        if ($collun3 == 1 && $value > '0') {
            return ['status' => false, 'erro' => 'Campo deve ser preenchido'];
        }
        return ['status' => true, 'erro' => ''];
    }

    //71 à 83
    public function equipmentAmounts($amounts)
    {
        foreach ($amounts as $value) {
            if (!$value == null) {
                $result = $this->isGreaterThan($value, '0');
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }

                $result = $this->isGreaterThan(strlen($value), '4');
                if ($result['status']) {
                    return ['status' => false, 'erro' => "Valor $value maior que 4 dígitos"];
                }
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //86
    public function internetAccess($collun, $value)
    {
        if ($collun != null) {
            if (!in_array($value, ['0', '1'])) {
                $value = $this->ifNull($value);
                return ['status' => false, 'erro' => "valor $value não permitido"];
            }
        } else {
            if ($value != null) {
                return ['status' => false, 'erro' => "Coluna 82 é nulo. Valor $value deve ser nulo"];
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 87
    public function bandwidth($collun, $value)
    {
        if ($collun == 1 && !($value == '0' || $value == '1')) {
                return ['status' => false, 'erro' => "valor $value não permitido"];
        }
        return ['status' => true, 'erro' => ''];
    }

    //89
    public function schoolFeeding($value)
    {
        if ($value === null) {
            return ['status' => false, 'erro' => 'Campo obrigatório.'];
        }
        return ['status' => true, 'erro' => ''];
    }

    //92 à 95
    // falta validação 'bruta'

    public function checkModalities(
        $collun90,
        $collun91,
        $modalities,
        $areThereStudentsByModalitie,
        $areThereInstructorsByModalitie
    ) {
        if (($collun90 != 2 && $collun91 != 2) && !($collun90 == 1 && $collun91 == 1)) {
           $result = $this->atLeastOne($modalities);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }

        foreach ($modalities as $key => $value) {
            if ($value == '1') {
                if (!$areThereStudentsByModalitie[$key]) {
                    return [
                        'status' => false,
                        'erro' => "$key é 1 e não há estudantes nessa modalidade"
                    ];
                }
                if (!$areThereInstructorsByModalitie[$key]) {
                    return [
                        'status' => false,
                        'erro' => "$key é 1 e não há instrutores nessa modalidade"
                    ];
                }
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    //94

    public function hasReadingCornerClassroomCount($column90, $column91, $column94)
    {
        if ((empty($column90) || empty($column91)) && !empty($column94)) {
            return ['status' => false, 'erro' => 'O campo foi preenchido quando deveria não ser preenchido.'];
        }
        if ($column94 > 4) {
            return ['status' => false, 'erro' => 'Numero máximo de salas de cantinho de leitura é 4'];
        }
    }

    //96
    //falta validação 'bruta'
    public function schoolCicle($value, $numberOfSchools)
    {
        if ($numberOfSchools > 0) {
            if (!($value == 0 || $value == 1)) {
                $value = $this->ifNull($value);
                return ['status' => false, 'erro' => "Valor $value não permitido"];
            }
        } else {
            if ($value != null) {
                return ['status' => false, 'erro' => "Valor $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    //97
    public function differentiatedLocation($collun0029, $value)
    {
        if (!in_array($value, ['1', '2', '3', '4', '5', '6', '7'])) {
            $value = $this->ifNull($value);
            return ['status' => false, 'erro' => "Valor $value não permitido"];
        }

        if ($collun0029 == 1) {
            if ($value == 1) {
                return [
                    'status' => false,
                    'erro' => "Valor $value não permitido
													pois coluna 29 do registro é $collun0029"
                ];
            }
        } elseif ($collun0029 == 2) {
            if ($value != 1) {
                return [
                    'status' => false,
                    'erro' => "Valor $value não permitido
													pois coluna 29 do registro é $collun0029"
                ];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function materials($itens)
    {
        $result = $this->checkRangeOfArray($itens, ['0', '1']);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $result = $this->atLeastOne($itens);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $result = $this->exclusive($itens);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        return ['status' => true, 'erro' => ''];
    }

    //102 e 103

    public function languages($collun101, $languages)
    {
        if ($collun101 != '1') {
            foreach ($languages as $value) {
                if ($value != null) {
                    return ['status' => false, 'erro' => 'Valor deveria ser nulo'];
                }
            }
        } else {
            $result = $this->atLeastOne($languages);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    //104

    public function edcensoNativeLanguages($collun102, $value)
    {
        if ($collun102 != '1' && ($value != null)) {
            return ['status' => false, 'erro' => "Valor deveria ser nulo pois coluna 102 é $collun102"];
        }
        if (empty($array)) {
            $value = $this->ifNull($value);
            return [
                'status' => false,
                'erro' => "Valor $value não está entre os valores de edcenso_native_languages"
            ];
        }
        return ['status' => true, 'erro' => ''];
    }

    //107

    public function pedagogicalFormation($value, $numberOfClassrooms)
    {
        if (!($value == 0 || $value == 1)) {
            $value = $this->ifNull($value);
            return ['status' => false, 'erro' => "Valor $value não permitido"];
        }

        if ($numberOfClassrooms == 0 && ($value != 0)) {

            $value = $this->ifNull($value);
            return ['status' => false, 'erro' => "Valor $value não permitido"];
        }

        return ['status' => true, 'erro' => ''];
    }
}
