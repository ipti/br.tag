<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

class studentIdentificationValidation extends Register
{
    public function __construct()
    {
    }

    //campo 08
    public function validateBirthday($date, $currentyear, $classroomStage)
    {
        $result = $this->validateDateformart($date);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $mdy = explode('/', $date);

        $result = $this->isGreaterThan($mdy[2], 1910);
        if (!$result['status']) {
            return ['status' => false, 'erro' => "O ano de nascimento '$mdy[2]' foi preenchido incorretamente."];
        }

        $result = $this->isNotGreaterThan($mdy[2], $currentyear);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $currentDate = new DateTime('now');
        $birthdayDate = DateTime::createFromFormat('d/m/Y', $date);
        $interval = $birthdayDate->diff($currentDate);
        if ($classroomStage == 1 && $interval->y > 6) {
            return ['status' => false, 'erro' => "O aluno não pode ter mais de 06 anos e estar matriculado em uma turma com etapa de ensino 'Creche'."];
        } elseif ($classroomStage == 20 && ($interval->y < 9 || $interval->y > 50)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 09 anos ou mais de 50 anos e estar matriculado em uma turma do 7º Ano do Ensino Fundamental.'];
        } elseif (($classroomStage == 69 || $classroomStage == 70 || $classroomStage == 72) && ($interval->y < 12 || $interval->y > 94)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma EJA do Ensino Fundamental.'];
        } elseif ($classroomStage == 73 && ($interval->y < 12 || $interval->y > 94)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma FIC Integrado à Modalidade EJA do Ensino Fundamental.'];
        } elseif ($classroomStage == 68 && ($interval->y < 12 || $interval->y > 94)) {
            return ['status' => false, 'erro' => 'O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma FIC Concomitante.'];
        }
        return ['status' => true, 'erro' => ''];

        $result = $stiv->checkBirthdayForClassroom($edcenso_svm, $studentIdentification['birthday']);
        if (!$result['status']) {
            array_push($log, ['birthday' => $result['erro']]);
        }

        return ['status' => true, 'erro' => ''];
    }

    public function specialNeeds($value, $allowedvalues, $requirement)
    {
        $result = $this->isAllowed($value, $allowedvalues);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ($requirement == '1') {
            if ($value != '1') {
                return ['status' => false, 'erro' => 'Valor deveria ser 1 pois estudante possui deficiência'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function inNeedOfResources($hasDeficiency, $deficiencies, $resources)
    {
        if ($hasDeficiency == 1) {
            $atLeastOneDeficiency = $this->atLeastOne($deficiencies);
            $atLeastOneResource = $this->atLeastOne($resources);
            if ($atLeastOneDeficiency['status'] && $atLeastOneResource['status']) {
                if ($resources[0] == 1 && ($deficiencies[0] != 1 && $deficiencies[1] != 1 && $deficiencies[4] != 1 && $deficiencies[5] != 1 && $deficiencies[6] != 1 && $deficiencies[8] != 1)) {
                    return ['status' => false, 'erro' => 'Auxílio ledor não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.'];
                } elseif ($resources[0] == 1 && $deficiencies[2] == 1) {
                    return ['status' => false, 'erro' => 'Auxílio ledor não pode ser selecionado quando o campo Surdez for selecionado.'];
                } elseif ($resources[1] == 1 && ($deficiencies[0] != 1 && $deficiencies[1] != 1 && $deficiencies[4] != 1 && $deficiencies[5] != 1 && $deficiencies[6] != 1 && $deficiencies[8] != 1)) {
                    return ['status' => false, 'erro' => 'Auxílio transcrição não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.'];
                } elseif ($resources[2] == 1 && $deficiencies[4] != 1) {
                    return ['status' => false, 'erro' => 'Guia-Intérprete não pode ser selecionado quando o campo Surdocegueira não for selecionado.'];
                } elseif ($resources[3] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Tradutor-Intérprete de Libras não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif ($resources[3] == 1 && $deficiencies[0] == 1) {
                    return ['status' => false, 'erro' => 'Tradutor-Intérprete de Libras não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif ($resources[4] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Leitura Labial não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif ($resources[4] == 1 && $deficiencies[0] == 1) {
                    return ['status' => false, 'erro' => 'Leitura Labial não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif ($resources[5] == 1 && ($deficiencies[1] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando nenhum dos campos Baixa visão e Surdocegueira for selecionado.'];
                } elseif ($resources[5] == 1 && $deficiencies[0] == 1) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif ($resources[5] == 1 && $resources[6] == 1) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Prova Ampliada (Fonte 24) for selecionado.'];
                } elseif ($resources[5] == 1 && $resources[10] == 1) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Prova em Braille for selecionado.'];
                } elseif ($resources[6] == 1 && ($deficiencies[1] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 24) não pode ser selecionado quando nenhum dos campos Baixa visão e Surdocegueira for selecionado.'];
                } elseif ($resources[6] == 1 && $deficiencies[0] == 1) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 24) não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif ($resources[6] == 1 && $resources[10] == 1) {
                    return ['status' => false, 'erro' => 'Prova Ampliada (Fonte 24) não pode ser selecionado quando o campo Prova em Braille for selecionado.'];
                } elseif ($resources[7] == 1 && ($deficiencies[0] != 1 && $deficiencies[1] != 1 && $deficiencies[4] != 1 && $deficiencies[5] != 1 && $deficiencies[6] != 1 && $deficiencies[8] != 1)) {
                    return ['status' => false, 'erro' => 'CD com áudio para deficiente visual não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.'];
                } elseif ($resources[7] == 1 && $deficiencies[2] == 1) {
                    return ['status' => false, 'erro' => 'CD com áudio para deficiente visual não pode ser selecionado quando o campo Surdez for selecionado.'];
                } elseif ($resources[8] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Prova de Língua Portuguesa não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif ($resources[8] == 1 && $deficiencies[0] == 1) {
                    return ['status' => false, 'erro' => 'Prova de Língua Portuguesa não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif ($resources[9] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Vídeo em Libras não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.'];
                } elseif ($resources[9] == 1 && $deficiencies[0] == 1) {
                    return ['status' => false, 'erro' => 'Vídeo em Libras não pode ser selecionado quando o campo Cegueira for selecionado.'];
                } elseif ($resources[10] == 1 && ($deficiencies[0] != 1 && $deficiencies[4] != 1)) {
                    return ['status' => false, 'erro' => 'Prova em Braille não pode ser selecionado quando nenhum dos campos Cegueira e Surdocegueira for selecionado.'];
                } elseif ($resources[11] == 1 && ($deficiencies[0] == 1 || $deficiencies[4] == 1)) {
                    return ['status' => false, 'erro' => "O campo 'Nenhum' não pode ser selecionado quando algum dos campos Cegueira e Surdocegueira for selecionado."];
                }
            } else {
                return ['status' => false, 'erro' => 'Quando for selecionado um tipo de deficiência (exceto superdotação), é preciso selecionar pelo menos um recurso, ou vice-versa.'];
            }
        }
        return ['status' => true, 'erro' => ''];
    }
}
