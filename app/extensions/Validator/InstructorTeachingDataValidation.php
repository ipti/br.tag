<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

class instructorTeachingDataValidation extends Register
{
    public function checkRole($value, $pedagogicalMediationType, $assistanceType, $statusInstructor, $statusStudent)
    {
        $result = $this->isAllowed($value, ['1', '2', '3', '4', '5', '6', '7', '8', '9']);
        $translate = ['1' => 'Docente', '2' => 'Auxiliar/assistente educacional', '3' => 'Profissional/monitor de atividade complementar', '4' => 'Tradutor e Intérprete de Libras', '5' => 'EAD - Docente Titular', '6' => 'EAD - Docente Tutor', '7' => 'Guia-Intérprete', '8' => 'Profissional de apoio escolar para aluno(a) com deficiência', '9' => 'Docente Substituto'];
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if (($pedagogicalMediationType != '1') && !($value == '5' || $value == '6')) {
            return ['status' => false, 'erro' => "$translate[$value] indisponível devido ao tipo de mediação Didático-Pedagógica"];
        }

        if (($assistanceType == '4' || $assistanceType == '5') && $value == '2') {
            return ['status' => false, 'erro' => "$translate[$value] indisponível devido ao tipo de atendimento"];
        }

        if ($pedagogicalMediationType != '3' && ($value == '5' || $value == '6')) {
            return ['status' => false, 'erro' => "$translate[$value] indisponível devido ao tipo de atendimento"];
        }

        if (($value == '6' || $value == '4') && $statusInstructor != '1') {
            return ['status' => false, 'erro' => 'Não há instrutores além do tipo 4 e 6'];
        }

        if ($value == '4' && $statusStudent != '1') {
            return ['status' => false, 'erro' => 'Não há alunos ou instrutores com deficiência'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function checkContactType($value, $role, $administrativeDependence)
    {
        if (in_array($role, ['1', '5', '6']) && in_array($administrativeDependence, ['1', '2', '3'])) {
            $result = $this->isAllowed($value, ['1', '2', '3', '4']);
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

    public function disciplineOne($disciplineCodeOne, $role, $assistanceType, $edcensoSvm)
    {
        if ((in_array($role, ['1', '5']) && !in_array($assistanceType, ['4', '5']) && !in_array($edcensoSvm, ['1', '2', '3', '65']))&& $disciplineCodeOne == null) {
            return ['status' => false, 'erro' => 'value não deveria ser nulo'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function checkDisciplineCode($disciplinesCodes, $role, $assistanceType, $edcensoSvm, $disciplines)
    {
        if (!empty($disciplinesCodes)) {
            $result = $this->exclusive($disciplinesCodes);

            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }

        if (!(in_array($role, ['1', '5']) ||
                in_array($assistanceType, ['4', '5']) ||
                in_array($edcensoSvm, ['1', '2', '3', '65']))) {
            foreach ($disciplinesCodes as $key => $value) {
                if ($value != null) {
                    return ['status' => false, 'erro' => "value de $value de ordem $key deveria ser nulo"];
                }
            }
        }
        return ['status' => true, 'erro' => ''];
    }
}
