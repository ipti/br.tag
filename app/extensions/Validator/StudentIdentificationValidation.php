<?php

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) . $DS . "register.php");

class studentIdentificationValidation extends Register
{

    function __construct()
    {
    }

    //campo 08
    function validateBirthday($date, $currentyear, $classroomStage)
    {

        $result = $this->validateDateformart($date);
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        $mdy = explode('/', $date);

        $result = $this->isGreaterThan($mdy[2], 1910);
        if (!$result['status']) {
            return array("status" => false, "erro" => $date . ": O ano de nascimento foi preenchido incorretamente.");
        }

        $result = $this->isNotGreaterThan($mdy[2], $currentyear);
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        if ($mdy[2] == date("Y")) {
            return array("status" => false, "erro" => $date . ": o ano de nascimento do aluno não pode ser o ano atual.");
        }

        $currentDate = new DateTime("now");
        $birthdayDate = DateTime::createFromFormat('d/m/Y', $date);
        $interval = $birthdayDate->diff($currentDate);
        if ($classroomStage == 1 && $interval->y > 6) {
            return array("status" => false, "erro" => "O aluno não pode ter mais de 06 anos e estar matriculado em uma turma com etapa de ensino 'Creche'.");
        } else if ($classroomStage == 20 && ($interval->y < 9 || $interval->y > 50)) {
            return array("status" => false, "erro" => "O aluno não pode ter menos de 09 anos ou mais de 50 anos e estar matriculado em uma turma do 7º Ano do Ensino Fundamental.");
        } else if (($classroomStage == 69 || $classroomStage == 70 || $classroomStage == 72) && ($interval->y < 12 || $interval->y > 94)) {
            return array("status" => false, "erro" => "O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma EJA do Ensino Fundamental.");
        } else if ($classroomStage == 73 && ($interval->y < 12 || $interval->y > 94)) {
            return array("status" => false, "erro" => "O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma FIC Integrado à Modalidade EJA do Ensino Fundamental.");
        } else if ($classroomStage == 68 && ($interval->y < 12 || $interval->y > 94)) {
            return array("status" => false, "erro" => "O aluno não pode ter menos de 12 anos ou mais de 94 anos e estar matriculado em uma turma FIC Concomitante.");
        } else if ($classroomStage == 19 && ($interval->y < 8 || $interval->y > 50)) {
            return array("status" => false, "erro" => "O aluno não pode ter menos de 08 anos ou mais de 50 anos e estar matriculado em uma turma do 6º Ano do Ensino Fundamental.");
        }
        return array("status" => true, "erro" => "");
    }

    function specialNeeds($value, $allowedvalues, $requirement)
    {

        $result = $this->isAllowed($value, $allowedvalues);
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        if ($requirement == '1') {
            if ($value != '1') {
                return array("status" => false, "erro" => "Valor deveria ser 1 pois estudante possui deficiência");
            }
        }

        return array("status" => true, "erro" => "");

    }

    function inNeedOfResources($hasDeficiency, $deficiencies, $resources)
    {
        if ($hasDeficiency == 1) {
            $atLeastOneDeficiency = $this->atLeastOne($deficiencies);
            $atLeastOneResource = $this->atLeastOne($resources);
            if ($atLeastOneDeficiency["status"] && $atLeastOneResource["status"]) {
                if ($resources[0] == 1 && ($deficiencies[0] != 1 && $deficiencies[1] != 1 && $deficiencies[4] != 1 && $deficiencies[5] != 1 && $deficiencies[6] != 1 && $deficiencies[8] != 1)) {
                    return array("status" => false, "erro" => "Auxílio ledor não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.");
                } else if ($resources[0] == 1 && $deficiencies[2] == 1) {
                    return array("status" => false, "erro" => "Auxílio ledor não pode ser selecionado quando o campo Surdez for selecionado.");
                } else if ($resources[1] == 1 && ($deficiencies[0] != 1 && $deficiencies[1] != 1 && $deficiencies[4] != 1 && $deficiencies[5] != 1 && $deficiencies[6] != 1 && $deficiencies[8] != 1)) {
                    return array("status" => false, "erro" => "Auxílio transcrição não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.");
                } else if ($resources[2] == 1 && $deficiencies[4] != 1) {
                    return array("status" => false, "erro" => "Guia-Intérprete não pode ser selecionado quando o campo Surdocegueira não for selecionado.");
                } else if ($resources[3] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Tradutor-Intérprete de Libras não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.");
                } else if ($resources[3] == 1 && $deficiencies[0] == 1) {
                    return array("status" => false, "erro" => "Tradutor-Intérprete de Libras não pode ser selecionado quando o campo Cegueira for selecionado.");
                } else if ($resources[4] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Leitura Labial não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.");
                } else if ($resources[4] == 1 && $deficiencies[0] == 1) {
                    return array("status" => false, "erro" => "Leitura Labial não pode ser selecionado quando o campo Cegueira for selecionado.");
                } else if ($resources[5] == 1 && ($deficiencies[1] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 18) não pode ser selecionado quando nenhum dos campos Baixa visão e Surdocegueira for selecionado.");
                } else if ($resources[5] == 1 && $deficiencies[0] == 1) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Cegueira for selecionado.");
                } else if ($resources[5] == 1 && $resources[6] == 1) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Prova Ampliada (Fonte 24) for selecionado.");
                } else if ($resources[5] == 1 && $resources[10] == 1) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 18) não pode ser selecionado quando o campo Prova em Braille for selecionado.");
                } else if ($resources[6] == 1 && ($deficiencies[1] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 24) não pode ser selecionado quando nenhum dos campos Baixa visão e Surdocegueira for selecionado.");
                } else if ($resources[6] == 1 && $deficiencies[0] == 1) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 24) não pode ser selecionado quando o campo Cegueira for selecionado.");
                } else if ($resources[6] == 1 && $resources[10] == 1) {
                    return array("status" => false, "erro" => "Prova Ampliada (Fonte 24) não pode ser selecionado quando o campo Prova em Braille for selecionado.");
                } else if ($resources[7] == 1 && ($deficiencies[0] != 1 && $deficiencies[1] != 1 && $deficiencies[4] != 1 && $deficiencies[5] != 1 && $deficiencies[6] != 1 && $deficiencies[8] != 1)) {
                    return array("status" => false, "erro" => "CD com áudio para deficiente visual não pode ser selecionado quando nenhum dos campos Cegueira, Baixa visão, Surdocegueira, Deficiência Física, Deficiência Intelectual e Autismo for selecionado.");
                } else if ($resources[7] == 1 && $deficiencies[2] == 1) {
                    return array("status" => false, "erro" => "CD com áudio para deficiente visual não pode ser selecionado quando o campo Surdez for selecionado.");
                } else if ($resources[8] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Prova de Língua Portuguesa não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.");
                } else if ($resources[8] == 1 && $deficiencies[0] == 1) {
                    return array("status" => false, "erro" => "Prova de Língua Portuguesa não pode ser selecionado quando o campo Cegueira for selecionado.");
                } else if ($resources[9] == 1 && ($deficiencies[2] != 1 && $deficiencies[3] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Vídeo em Libras não pode ser selecionado quando nenhum dos campos Surdez, Deficiência Auditiva e Surdocegueira for selecionado.");
                } else if ($resources[9] == 1 && $deficiencies[0] == 1) {
                    return array("status" => false, "erro" => "Vídeo em Libras não pode ser selecionado quando o campo Cegueira for selecionado.");
                } else if ($resources[10] == 1 && ($deficiencies[0] != 1 && $deficiencies[4] != 1)) {
                    return array("status" => false, "erro" => "Prova em Braille não pode ser selecionado quando nenhum dos campos Cegueira e Surdocegueira for selecionado.");
                } else if ($resources[11] == 1 && ($deficiencies[0] == 1 || $deficiencies[4] == 1)) {
                    return array("status" => false, "erro" => "O campo 'Nenhum' não pode ser selecionado quando algum dos campos Cegueira e Surdocegueira for selecionado.");

                }
            } else {
                return array("status" => false, "erro" => "Quando for selecionado um tipo de deficiência (exceto superdotação), é preciso selecionar pelo menos um recurso, ou vice-versa.");
            }
        }
        return array("status" => true, "erro" => "");
    }
}

?>