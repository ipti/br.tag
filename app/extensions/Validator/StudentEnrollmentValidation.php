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
            if ('12' == $demand || '13' == $demand) {
                $result = $this->isAllowed($value, ['4', '5', '6', '7', '8', '9', '10', '11']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
                }
            }

            if ('22' == $demand || '23' == $demand) {
                $result = $this->isAllowed($value, ['14', '15', '16', '17', '18', '19', '20', '21', '41']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
                }
            }

            if ('24' == $demand) {
                $result = $this->isAllowed($value, [
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9',
                    '10',
                    '11',
                    '14',
                    '15',
                    '16',
                    '17',
                    '18',
                    '19',
                    '20',
                    '21',
                    '41',
                ]);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
                }
            }

            if ('72' == $demand) {
                $result = $this->isAllowed($value, ['69', '70']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
                }
            }

            if ('56' == $demand) {
                $result = $this->isAllowed($value, [
                    '1',
                    '2',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9',
                    '10',
                    '11',
                    '14',
                    '15',
                    '16',
                    '17',
                    '18',
                    '19',
                    '20',
                    '21',
                    '41',
                ]);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
                }
            }

            if ('64' == $demand) {
                $result = $this->isAllowed($value, ['39', '40']);
                if (!$result['status']) {
                    return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
                }
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo", 'type' => 'normal'];
            }
        }

        return ['status' => true, 'erro' => '', 'type' => 'normal'];
    }

    public function publicTransportation($value, $pedagogical_mediation_type)
    {
        if ('1' == $value || '2' == $value) {
            if (!('1' == $value || '0' == $value)) {
                return ['status' => false, 'erro' => "value $value não é disponível", 'type' => 'normal'];
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo", 'type' => 'normal'];
            }
        }

        return ['status' => true, 'erro' => '', 'type' => 'normal'];
    }

    public function vehiculesTypes($public_transport, $types)
    {
        if ('1' == $public_transport) {
            $result = $this->checkRangeOfArray($types, ['0', '1']);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
            }

            $result = $this->allowedNumberOfTypes($types, '1', 3);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
            }
        } else {
            foreach ($types as $key => $value) {
                if (null != $value) {
                    return ['status' => false, 'erro' => "value $value deveria ser nulo", 'type' => 'normal'];
                }
            }
        }

        return ['status' => true, 'erro' => '', 'type' => 'normal'];
    }

    public function studentEntryForm($value, $administrative_dependence, $edcenso_svm)
    {
        $edcenso_svm_allowed_values = ['39', '40', '64', '30', '31', '32', '33', '34', '74'];
        $first_result = $this->isAllowed($edcenso_svm, $edcenso_svm_allowed_values);

        if ('1' == $administrative_dependence && $first_result['status']) {
            $allowed_values = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $second_result = $this->isAllowed($value, $allowed_values);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro'], 'type' => 'normal'];
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo", 'type' => 'normal'];
            }
        }

        return ['status' => true, 'erro' => '', 'type' => 'normal'];
    }

    public function isValidMultiClassroom($classroomStage, $enrollmentStage)
    {
        $strEnrollmentStage = strval($enrollmentStage);
        switch ($classroomStage) {
            case 3:
                if ('1' !== $strEnrollmentStage && '2' !== $strEnrollmentStage) {
                    return ['status' => false, 'erro' => 'em turmas de Educação Infantil Unificada, o campo ETAPA DE ENSINO na matrícula é OBRIGATÓRIA e deve ser Creche ou Pré-escola.', 'type' => 'normal'];
                }
                break;
            case 22:
            case 23:
                if ('14' !== $strEnrollmentStage && '15' !== $strEnrollmentStage && '16' !== $strEnrollmentStage && '17' !== $strEnrollmentStage && '18' !== $strEnrollmentStage && '19' !== $strEnrollmentStage && '20' !== $strEnrollmentStage && '21' !== $strEnrollmentStage && '41' !== $strEnrollmentStage) {
                    return ['status' => false, 'erro' => 'em turmas de Ensino Fundamental de 9 Anos - Multi ou Correção de Fluxo, o campo ETAPA DE ENSINO na matrícula é OBRIGATÓRIA e deve ser derivada do Ensino Fundamental de 9 Anos.', 'type' => 'batchUpdate'];
                }
                break;
            case 72:
                if ('69' !== $strEnrollmentStage && '70' !== $strEnrollmentStage) {
                    return ['status' => false, 'erro' => "em turmas de EJA - Ensino Fundamental - Anos Iniciais e Anos Finais, o campo ETAPA DE ENSINO na matrícula é OBRIGATÓRIA e deve ser EJA, 'Anos Iniciais' ou 'Anos Finais'.", 'type' => 'normal'];
                }
                break;
            case 56:
                if ('1' !== $strEnrollmentStage && '2' !== $strEnrollmentStage && '14' !== $strEnrollmentStage && '15' !== $strEnrollmentStage && '16' !== $strEnrollmentStage && '17' !== $strEnrollmentStage && '18' !== $strEnrollmentStage && '19' !== $strEnrollmentStage && '20' !== $strEnrollmentStage && '21' !== $strEnrollmentStage && '41' !== $strEnrollmentStage) {
                    return ['status' => false, 'erro' => "em turmas de Educação Infantil e Ensino Fundamental (8 e 9 anos) Multietapa, o campo ETAPA DE ENSINO na matrícula é OBRIGATÓRIA e deve ser alguma derivada da 'Educação Infantil' ou 'Ensino Fundamental de 9 Anos'.", 'type' => 'normal'];
                }
                break;
            case 64:
                if ('39' !== $strEnrollmentStage && '40' !== $strEnrollmentStage) {
                    return ['status' => false, 'erro' => 'em turmas de Educação Profissional Mista - Concomitante e Subsequente , o campo ETAPA DE ENSINO na matrícula é OBRIGATÓRIA e deve ser Curso Técnico - Concomitante ou Subsequente.', 'type' => 'normal'];
                }
                break;
        }

        return ['status' => true, 'erro' => '', 'type' => 'normal'];
    }

    public function hasAEETypeSelected($aee, $aeeTypes)
    {
        if (1 == $aee) {
            $result = $this->atLeastOne($aeeTypes);
            if (!$result['status']) {
                return ['status' => false, 'erro' => 'Quando o tipo de atendimento da turma for AEE, deve-se selecionar ao menos uma opção.', 'type' => 'normal'];
            }
        }

        return ['status' => true, 'erro' => '', 'type' => 'normal'];
    }
}
