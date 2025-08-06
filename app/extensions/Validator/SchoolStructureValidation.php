<?php

$DS = \DIRECTORY_SEPARATOR;

// Validações para a tabela school_structure
require_once __DIR__.$DS.'register.php';

class SchoolStructureValidation extends Register
{
    public function __construct()
    {
    }

    // campo 12
    public function buildingOccupationStatus($collun3, $collun8, $value)
    {
        if (1 == $collun3) {
            if (!(1 == $value || 2 == $value || 3 == $value)) {
                return [
                    'status' => false,
                    'erro' => "operation_location_building é 1. Valor $value não está enre as õpções",
                ];
            }
        } elseif (1 != $collun3 && 1 != $collun8) {
            if (null != $value) {
                return ['status' => false, 'erro' => "Valor $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // campo 13
    public function sharedBuildingSchool($collun3, $value)
    {
        if (1 == $collun3) {
            if (0 == $value || 1 == $value) {
                return ['status' => true, 'erro' => ''];
            } else {
                return ['status' => false, 'erro' => "valor $value não permitido"];
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "operation_location_building não é 1. Valor $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // campo 14 à 19
    public function sharedSchoolInep($collun13, $inep_id, $shared_schools_inep_ids)
    {
        if (1 == $collun13) {
            foreach ($shared_schools_inep_ids as $school_inep_id) {
                if (null !== $school_inep_id) {
                    $result = $this->isEqual(
                        substr($inep_id, 0, 2),
                        substr($school_inep_id, 0, 2),
                        'Escolas não são do mesmo estado'
                    );
                    if ($result['status']) {
                        if ($inep_id == $school_inep_id) {
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

    // campo 20
    public function consumedWater($value)
    {
        if (1 == $value || 2 == $value) {
            return ['status' => true, 'erro' => ''];
        }
        $value = $this->ifNull($value);

        return ['status' => false, 'erro' => "Valor $value não está entre as opções"];
    }

    public function supply($supply_locations)
    {
        $len = count($supply_locations);

        $result = $this->atLeastOne($supply_locations);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ('1' == $supply_locations[$len - 1]) { // ultimo campo
            for ($i = 0; $i < ($len - 1); ++$i) { // primeiros campos
                if ('1' == $supply_locations[$i]) {
                    return [
                        'status' => false,
                        'erro' => 'Quando o campo de inexistência é marcado, nenhum outro deve estar.',
                    ];
                }
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // campo 69
    public function schoolsCount($collun3, $value)
    {
        if (1 == $collun3) {
            if (0 === $value) {
                return ['status' => false, 'erro' => 'Campo deve ser preenchido com um número maior que 0.'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function usedSchoolsCount($collun3, $value)
    {
        if (1 == $collun3) {
            if ($value > '0') {
                return ['status' => false, 'erro' => 'Campo deve ser preenchido'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // 71 à 83
    public function equipmentAmounts($amounts)
    {
        foreach ($amounts as $key => $value) {
            if (null == !$value) {
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

    // 86
    public function internetAccess($collun, $value)
    {
        if (null != $collun) {
            if (!in_array($value, ['0', '1'])) {
                $value = $this->ifNull($value);

                return ['status' => false, 'erro' => "valor $value não permitido"];
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "Coluna 82 é nulo. Valor $value deve ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // campo 87
    public function bandwidth($collun, $value)
    {
        if (1 == $collun) {
            if (!('0' == $value || '1' == $value)) {
                return ['status' => false, 'erro' => "valor $value não permitido"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // 89
    public function schoolFeeding($value)
    {
        if (null === $value) {
            return ['status' => false, 'erro' => 'Campo obrigatório.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    // 92 à 95
    // falta validação 'bruta'

    public function checkModalities(
        $collun90,
        $collun91,
        $modalities,
        $are_there_students_by_modalitie,
        $are_there_instructors_by_modalitie
    ) {
        if (2 != $collun90 && 2 != $collun91) {
            if (!(1 == $collun90 && 1 == $collun91)) {
                $result = $this->atLeastOne($modalities);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }
        }

        foreach ($modalities as $key => $value) {
            if ('1' == $value) {
                if (!$are_there_students_by_modalitie[$key]) {
                    return [
                        'status' => false,
                        'erro' => "$key é 1 e não há estudantes nessa modalidade",
                    ];
                }
                if (!$are_there_instructors_by_modalitie[$key]) {
                    return [
                        'status' => false,
                        'erro' => "$key é 1 e não há instrutores nessa modalidade",
                    ];
                }
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // 94

    public function hasReadingCornerClassroomCount($column90, $column91, $column94)
    {
        if ((empty($column90) || empty($column91)) && !empty($column94)) {
            return ['status' => false, 'erro' => 'O campo foi preenchido quando deveria não ser preenchido.'];
        }
        if ($column94 > 4) {
            return ['status' => false, 'erro' => 'Numero máximo de salas de cantinho de leitura é 4'];
        }
    }

    // 96
    // falta validação 'bruta'
    public function schoolCicle($value, $number_of_schools)
    {
        if ($number_of_schools > 0) {
            if (!(0 == $value || 1 == $value)) {
                $value = $this->ifNull($value);

                return ['status' => false, 'erro' => "Valor $value não permitido"];
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "Valor $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // 97
    public function differentiatedLocation($collun0029, $value)
    {
        if (!in_array($value, ['1', '2', '3', '4', '5', '6', '7'])) {
            $value = $this->ifNull($value);

            return ['status' => false, 'erro' => "Valor $value não permitido"];
        }

        if (1 == $collun0029) {
            if (1 == $value) {
                return [
                    'status' => false,
                    'erro' => "Valor $value não permitido
													pois coluna 29 do registro é $collun0029",
                ];
            }
        } elseif (2 == $collun0029) {
            if (1 != $value) {
                return [
                    'status' => false,
                    'erro' => "Valor $value não permitido
													pois coluna 29 do registro é $collun0029",
                ];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function materials($itens)
    {
        $len = count($itens);

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

    // 102 e 103

    public function languages($collun101, $languages)
    {
        if ('1' != $collun101) {
            foreach ($languages as $key => $value) {
                if (null != $value) {
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

    // 104

    public function edcensoNativeLanguages($collun102, $value)
    {
        if ('1' != $collun102) {
            if (null != $value) {
                return ['status' => false, 'erro' => "Valor deveria ser nulo pois coluna 102 é $collun102"];
            }
        } else {
            $sql = "SELECT * FROM edcenso_native_languages WHERE id = '$value' ";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            if (empty($array)) {
                $value = $this->ifNull($value);

                return [
                    'status' => false,
                    'erro' => "Valor $value não está entre os valores de edcenso_native_languages",
                ];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    // 107

    public function pedagogicalFormation($value, $number_of_classrooms)
    {
        if (!(0 == $value || 1 == $value)) {
            $value = $this->ifNull($value);

            return ['status' => false, 'erro' => "Valor $value não permitido"];
        }

        if (0 == $number_of_classrooms) {
            if (0 != $value) {
                $value = $this->ifNull($value);

                return ['status' => false, 'erro' => "Valor $value não permitido"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }
}// fim de classe
