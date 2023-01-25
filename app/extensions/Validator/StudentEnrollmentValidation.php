<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

class studentEnrollmentValidation extends Register
{
    public function __construct()
    {
    }

    public function multiLevel($value, $demand)
    {
        $result = $this->isAllowed($demand, ['12', '13', '22', '23', '24', '72', '56', '64']);
        if ($result['status']) {
            if ($demand == '12' || $demand == '13') {
                $result = $this->isAllowed($value, ['4', '5', '6', '7', '8', '9', '10', '11']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }

            if ($demand == '22' || $demand == '23') {
                $result = $this->isAllowed($value, ['14', '15', '16', '17', '18', '19', '20', '21', '41']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }

            if ($demand == '24') {
                $result = $this->isAllowed($value, ['4', '5', '6', '7', '8', '9', '10', '11',
                    '14', '15', '16', '17', '18', '19', '20', '21', '41']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }

            if ($demand == '72') {
                $result = $this->isAllowed($value, ['69', '70']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }

            if ($demand == '56') {
                $result = $this->isAllowed($value, ['1', '2', '4', '5', '6', '7', '8', '9', '10', '11',
                    '14', '15', '16', '17', '18', '19', '20', '21', '41']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }

            if ($demand == '64') {
                $result = $this->isAllowed($value, ['39', '40']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro']];
                }
            }
        } else {
            if ($value != null) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function publicTransportation($value, $pedagogical_mediation_type)
    {
        if ($value == '1' || $value == '2') {
            if (!($value == '1' || $value == '0')) {
                return ['status' => false, 'erro' => "value $value não é disponível"];
            }
        } else {
            if ($value != null) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function vehiculesTypes($public_transport, $types)
    {
        if ($public_transport == '1') {
            $result = $this->checkRangeOfArray($types, ['0', '1']);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }

            $result = $this->allowedNumberOfTypes($types, '1', 3);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        } else {
            foreach ($types as $key => $value) {
                if ($value != null) {
                    return ['status' => false, 'erro' => "value $value deveria ser nulo"];
                }
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function studentEntryForm($value, $administrative_dependence, $edcenso_svm)
    {
        $edcenso_svm_allowed_values = ['39', '40', '64', '30', '31', '32', '33', '34', '74'];
        $first_result = $this->isAllowed($edcenso_svm, $edcenso_svm_allowed_values);

        if ($administrative_dependence == '1' && $first_result['status']) {
            $allowed_values = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $second_result = $this->isAllowed($value, $allowed_values);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        } else {
            if ($value != null) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function isValidMultiClassroom($classroomStage, $enrollmentStage)
    {
        switch ($classroomStage) {
            case 3:
                if ($enrollmentStage !== '1' && $enrollmentStage !== '2') {
                    return ['status' => false, 'erro' => 'quando for Educação Infantil Unificada, a etapa de ensino é OBRIGATÓRIA e deve ser Creche ou Pré-escola.'];
                }
                break;
            case 22:
            case 23:
                if ($enrollmentStage !== '14' && $enrollmentStage !== '15' && $enrollmentStage !== '16' && $enrollmentStage !== '17' && $enrollmentStage !== '18' && $enrollmentStage !== '19' && $enrollmentStage !== '20' && $enrollmentStage !== '21' && $enrollmentStage !== '41') {
                    return ['status' => false, 'erro' => 'quando for Ensino Fundamental de 9 Anos - Multi ou Correção de Fluxo, a etapa de ensino é OBRIGATÓRIA e deve ser derivada do Ensino Fundamental de 9 Anos.'];
                }
                break;
            case 72:
                if ($enrollmentStage !== '69' && $enrollmentStage !== '70') {
                    return ['status' => false, 'erro' => "quando for EJA - Ensino Fundamental - Anos Iniciais e Anos Finais, a etapa de ensino é OBRIGATÓRIA e deve ser EJA, 'Anos Iniciais' ou 'Anos Finais'."];
                }
                break;
            case 56:
                if ($enrollmentStage !== '1' && $enrollmentStage !== '2' && $enrollmentStage !== '14' && $enrollmentStage !== '15' && $enrollmentStage !== '16' && $enrollmentStage !== '17' && $enrollmentStage !== '18' && $enrollmentStage !== '19' && $enrollmentStage !== '20' && $enrollmentStage !== '21' && $enrollmentStage !== '41') {
                    return ['status' => false, 'erro' => "quando for Educação Infantil e Ensino Fundamental (8 e 9 anos) Multietapa, a etapa de ensino é OBRIGATÓRIA e deve ser alguma derivada da 'Educação Infantil' ou 'Ensino Fundamental de 9 Anos'."];
                }
                break;
            case 64:
                if ($enrollmentStage !== '39' && $enrollmentStage !== '40') {
                    return ['status' => false, 'erro' => 'quando for Educação Profissional Mista - Concomitante e Subsequente , a etapa de ensino é OBRIGATÓRIA e deve ser Curso Técnico - Concomitante ou Subsequente.'];
                }
                break;
        }
        return ['status' => true, 'erro' => ''];
    }

    public function hasAEETypeSelected($aee, $aeeTypes)
    {
        if ($aee == 1) {
            $result = $this->atLeastOne($aeeTypes);
            if (!$result['status']) {
                return ['status' => false, 'erro' => 'Quando o tipo de atendimento da turma for AEE, deve-se selecionar ao menos uma opção.'];
            }
        }
        return ['status' => true, 'erro' => ''];
    }
}
