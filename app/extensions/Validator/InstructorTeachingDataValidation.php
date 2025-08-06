<?php

$DS = \DIRECTORY_SEPARATOR;

require_once __DIR__.$DS.'register.php';

class InstructorTeachingDataValidation extends Register
{
    public function checkRole($value, $pedagogical_mediation_type, $assistance_type, $status_instructor, $status_student)
    {
        $result = $this->isAllowed($value, ['1', '2', '3', '4', '5', '6', '7', '8', '9']);
        $translate = ['1' => 'Docente', '2' => 'Auxiliar/assistente educacional', '3' => 'Profissional/monitor de atividade complementar', '4' => 'Tradutor e Intérprete de Libras', '5' => 'EAD - Docente Titular', '6' => 'EAD - Docente Tutor', '7' => 'Guia-Intérprete', '8' => 'Profissional de apoio escolar para aluno(a) com deficiência', '9' => 'Docente Substituto'];
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ('1' != $pedagogical_mediation_type) {
            if (!('5' == $value || '6' == $value)) {
                return ['status' => false, 'erro' => "$translate[$value] indisponível devido ao tipo de mediação Didático-Pedagógica"];
            }
        }

        if ('4' == $assistance_type || '5' == $assistance_type) {
            if ('2' == $value) {
                return ['status' => false, 'erro' => "$translate[$value] indisponível devido ao tipo de atendimento"];
            }
        }

        if ('3' != $pedagogical_mediation_type) {
            if ('5' == $value || '6' == $value) {
                return ['status' => false, 'erro' => "$translate[$value] indisponível devido ao tipo de atendimento"];
            }
        }

        if ('6' == $value || '4' == $value) {
            if ('1' != $status_instructor) {
                return ['status' => false, 'erro' => 'Não há instrutores além do tipo 4 e 6'];
            }
        }

        if ('4' == $value) {
            if ('1' != $status_student) {
                return ['status' => false, 'erro' => 'Não há alunos ou instrutores com deficiência'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function checkContactType($value, $role, $administrative_dependence)
    {
        if (in_array($role, ['1', '5', '6']) && in_array($administrative_dependence, ['1', '2', '3'])) {
            $result = $this->isAllowed($value, ['1', '2', '3', '4']);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        } else {
            if (null != $value) {
                return ['status' => false, 'erro' => "value $value deveria ser nulo"];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function disciplineOne($discipline_code_one, $role, $assistance_type, $edcenso_svm)
    {
        if (in_array($role, ['1', '5']) && !in_array($assistance_type, ['4', '5']) && !in_array($edcenso_svm, ['1', '2', '3', '65'])) {
            if (null == $discipline_code_one) {
                return ['status' => false, 'erro' => 'value não deveria ser nulo'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function checkDisciplineCode($disciplines_codes, $role, $assistance_type, $edcenso_svm, $disciplines)
    {
        if (!empty($disciplines_codes)) {
            $result = $this->exclusive($disciplines_codes);

            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }

        if (!(in_array($role, ['1', '5']) ||
                in_array($assistance_type, ['4', '5']) ||
                in_array($edcenso_svm, ['1', '2', '3', '65']))) {
            foreach ($disciplines_codes as $key => $value) {
                if (null != $value) {
                    return ['status' => false, 'erro' => "value de $value de ordem $key deveria ser nulo"];
                }
            }
        }

        /*$result = $this->checkRangeOfArray($disciplines_codes, $disciplines);
        if(!$result['status']){
                return array("status"=>false,"erro"=>$result['erro']);
        }*/

        return ['status' => true, 'erro' => ''];
    }
}
