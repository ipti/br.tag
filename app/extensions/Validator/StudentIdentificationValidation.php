<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

class studentIdentificationValidation extends Register
{
    public function __construct()
    {
    }

    // campo 08
    public function validateBirthday($date, $currentyear, $classroomStage)
    {
        $result = $this->validateDateformart($date);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $mdy = explode('/', $date);

        $result = $this->isGreaterThan($mdy[2], 1910);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $date . ': O ano de nascimento foi preenchido incorretamente.'];
        }

        $result = $this->isNotGreaterThan($mdy[2], $currentyear);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ($mdy[2] == date('Y')) {
            return ['status' => false, 'erro' => $date . ': o ano de nascimento do aluno não pode ser o ano atual.'];
        }

        $currentDate = new DateTime('now');
        $birthdayDate = DateTime::createFromFormat('d/m/Y', $date);
        $interval = $birthdayDate->diff($currentDate);
        if (1 == $classroomStage && $interval->y > 6) {
            return ['status' => false, 'erro' => "O aluno não pode ter mais de 06 anos e estar matriculado em uma turma com etapa de ensino 'Creche'."];
        } elseif (20 == $classroomStage && ($interval->y < 9 || $interval->y > 50)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 09 anos ou mais de 50 anos e estar matriculado em uma turma do 7º Ano do Ensino Fundamental.'];
        } elseif ((69 == $classroomStage || 70 == $classroomStage || 72 == $classroomStage) && ($interval->y < 12 || $interval->y > 94)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma EJA do Ensino Fundamental.'];
        } elseif (19 == $classroomStage && ($interval->y < 8 || $interval->y > 50)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 08 anos ou mais de 50 anos e estar matriculado em uma turma do 6º Ano do Ensino Fundamental.'];
        } elseif ((25 == $classroomStage || 35 == $classroomStage) && ($interval->y < 12 || $interval->y > 58)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 58 anos e estar matriculado em uma turma do 1º Ano do Ensino Médio.'];
        } elseif ((26 == $classroomStage || 36 == $classroomStage) && ($interval->y < 12 || $interval->y > 58)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 58 anos e estar matriculado em uma turma do 2º Ano do Ensino Médio.'];
        } elseif ((27 == $classroomStage || 37 == $classroomStage) && ($interval->y < 12 || $interval->y > 58)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 58 anos e estar matriculado em uma turma do 3º Ano do Ensino Médio.'];
        } elseif ((28 == $classroomStage || 38 == $classroomStage) && ($interval->y < 12 || $interval->y > 58)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 58 anos e estar matriculado em uma turma do 4º Ano do Ensino Médio.'];
        } elseif (29 == $classroomStage && ($interval->y < 12 || $interval->y > 58)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 58 anos e estar matriculado em uma turma do do Ensino Médio não seriada.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function specialNeeds($value, $allowedvalues, $requirement)
    {
        $result = $this->isAllowed($value, $allowedvalues);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ('1' == $requirement) {
            if ('1' != $value) {
                return ['status' => false, 'erro' => 'Valor deveria ser 1 pois estudante possui deficiência'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function inNeedOfResources($hasDeficiency, $deficiencies, $resources, $disorder)
    {
        if (1 == $hasDeficiency) {
            $atLeastOneDeficiency = $this->atLeastOne($deficiencies);
            $atLeastOneResource = $this->atLeastOne($resources);
            $atLeastOneDisorder = $this->atLeastOne($disorder);
            if (($atLeastOneDeficiency['status'] || $atLeastOneDisorder['status']) && $atLeastOneResource['status']) {
                if (1 == $resources[0] && (1 != $deficiencies[0] && 1 != $deficiencies[1] && 1 != $deficiencies[4] && 1 != $deficiencies[5] && 1 != $deficiencies[6] && 1 != $deficiencies[8])) {
                    return ['status' => false, 'erro' => 'Auxílio ledor não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.'];
                } elseif (1 == $resources[0] && 1 == $deficiencies[2]) {
                    return ['status' => false, 'erro' => 'Auxílio ledor não pode ser selecionado quando o campo Surdez for selecionado.'];
                } elseif (1 == $resources[1] && (1 != $deficiencies[0] && 1 != $deficiencies[1] && 1 != $deficiencies[4] && 1 != $deficiencies[5] && 1 != $deficiencies[6] && 1 != $deficiencies[8])) {
                    return ['status' => false, 'erro' => 'Auxílio transcrição não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.'];
                } elseif (1 == $resources[2] && 1 != $deficiencies[4]) {
                    return ['status' => false, 'erro' => 'Guia-Intérprete não pode ser selecionado quando o campo Surdocegueira não for selecionado.'];
                } elseif (1 == $resources[3] && (1 != $deficiencies[2] && 1 != $deficiencies[3] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Tradutor-Intérprete de Libras não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[3] && 1 == $deficiencies[0]) {
                    return ['status' => false, 'erro' => 'Tradutor-Intérprete de Libras não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif (1 == $resources[4] && (1 != $deficiencies[2] && 1 != $deficiencies[3] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Leitura Labial não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[4] && 1 == $deficiencies[0]) {
                    return ['status' => false, 'erro' => 'Leitura Labial não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif (1 == $resources[5] && (1 != $deficiencies[1] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando nenhum dos campos Baixa visão e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[5] && 1 == $deficiencies[0]) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif (1 == $resources[5] && 1 == $resources[6]) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Prova Ampliada (Fonte 24) for selecionado.'];
                } elseif (1 == $resources[5] && 1 == $resources[10]) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Prova em Braille for selecionado.'];
                } elseif (1 == $resources[6] && (1 != $deficiencies[1] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 24) não pode ser selecionado quando nenhum dos campos Baixa visão e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[6] && 1 == $deficiencies[0]) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 24) não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif (1 == $resources[6] && 1 == $resources[10]) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 24) não pode ser selecionado quando o campo Prova em Braille for selecionado.'];
                } elseif (1 == $resources[7] && (1 != $deficiencies[0] && 1 != $deficiencies[1] && 1 != $deficiencies[4] && 1 != $deficiencies[5] && 1 != $deficiencies[6] && 1 != $deficiencies[8])) {
                    return ['status' => false, 'erro' => 'CD com áudio para deficiente visual não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.'];
                } elseif (1 == $resources[7] && 1 == $deficiencies[2]) {
                    return ['status' => false, 'erro' => 'CD com áudio para deficiente visual não pode ser selecionado quando o campo Surdez for selecionado.'];
                } elseif (1 == $resources[8] && (1 != $deficiencies[2] && 1 != $deficiencies[3] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Prova de Língua Portuguesa não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[8] && 1 == $deficiencies[0]) {
                    return ['status' => false, 'erro' => 'Prova de Língua Portuguesa não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif (1 == $resources[9] && (1 != $deficiencies[2] && 1 != $deficiencies[3] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Vídeo em Libras não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[9] && 1 == $deficiencies[0]) {
                    return ['status' => false, 'erro' => 'Vídeo em Libras não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif (1 == $resources[10] && (1 != $deficiencies[0] && 1 != $deficiencies[4])) {
                    return ['status' => false, 'erro' => 'Prova em Braille não pode ser selecionado quando nenhum dos campos Cegueira e Surdocegueira for selecionado.'];
                } elseif (1 == $resources[11] && (1 == $deficiencies[0] || 1 == $deficiencies[4])) {
                    return ['status' => false, 'erro' => "O campo 'Nenhum' não pode ser selecionado quando algum dos campos Cegueira e Surdocegueira for selecionado."];
                }
            } else {
                return ['status' => false, 'erro' => 'Quando for selecionado um tipo de deficiência (exceto superdotação) ou um Transtorno que impacta o desenvolvimento da aprendizagem, é preciso selecionar pelo menos um recurso, ou vice-versa.'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }
}
