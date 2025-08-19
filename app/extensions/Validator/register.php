<?php

/**
 *
 */
class Register

{

    function __construct()
    {
    }

    function isEmpty($value)
    {
        if (trim($value) === '' || !isset($value)) {
            return array('status' => true, 'erro' => '');
        }
        return array('status' => false, 'erro' => 'O valor nao eh vazio');
    }

    function isNull($x)
    {
        if ($x == null) {
            return array("status" => true, "erro" => "");
        }
        return array("status" => false, "erro" => "Valor não é nulo");

    }


    function ifNull($value)
    {
        if ($value == null)
            $value = "nulo";
        return $value;
    }

    //campo 1002
    function isEqual($x, $y, $msg)
    {

        $result = $this->isNUll($x);

        if ($result['status']) {
            return array("status" => false, "erro" => "valor é nulo");
        }
        if ($x != $y) {
            return array("status" => false, "erro" => $msg);
        }
        return array("status" => true, "erro" => "");
    }

    //campo 1003 à 1011, 1033 à 1038
    function atLeastOne($items)
    {
        $number_of_ones = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if (@$items[$i] == "1")
                $number_of_ones++;
        }
        if ($number_of_ones == 0) {
            return array("status" => false, "erro" => "Selecione ao menos uma opção.");
        }
        return array("status" => true, "erro" => "");
    }

    function atLeastOneNotEmpty($items)
    {
        $number_of_not_empty = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i] != "")
                $number_of_not_empty++;
        }
        if ($number_of_not_empty == 0) {
            return array("status" => false, "erro" => "Não há nenhum valor preenchido");
        }
        return array("status" => true, "erro" => "");
    }

    function moreThanTwo($items)
    {

        $number_of_ones = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i] == "1")
                $number_of_ones++;
        }
        if ($number_of_ones < 2) {
            return array("status" => false, "erro" => "Não há mais de um valor marcado");
        }
        return array("status" => true, "erro" => "");

    }


    //campo 1001, 3001, 4001, 6001
    function isRegister($number, $value)
    {
        $result = $this->isEqual($value, $number, "O tipo de registro não deveria ser $value e sim $number");
        if (!$result["status"]) {
            return array("status" => false, "erro" => $result['erro']);
        }

        return array("status" => true, "erro" => "");
    }

    //campo 1002, 3002, 4002, 6002
    function isAllowedInepId($inep_id, $allowed_inep_ids)
    {
        if (!in_array($inep_id, $allowed_inep_ids)) {
            return array("status" => false, "erro" => "ID INEP $inep_id não está entre os permitidos");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 3003, 6003
    function isNumericOfSize($allowed_length, $value)
    {
        if (is_numeric($value)) {
            $len = strlen($value);
            if ($len != $allowed_length) {
                return array("status" => false, "erro" => "Campo deveria ter $allowed_length caracteres ao invés de $len.");
            }
        } else {
            $value = $this->ifNull($value);
            return array("status" => false, "erro" => "valor $value deve ser constituído apenas de números. Remova quaisquer letras, símbolos ou espaços em branco.");
        }

        return array("status" => true, "erro" => "");
    }

    //1070, 1088
    function isGreaterThan($value, $target)
    {

        if ($value <= $target) {
            $value = $this->ifNull($value);
            return array("status" => false, "erro" => "Valor $value não é maior que o alvo.");
        }
        return array("status" => true, "erro" => "");
    }

    //3004, 6004
    function isNotGreaterThan($value, $target)
    {

        $result = $this->isGreaterThan(strlen($value), $target);
        if ($result['status']) {
            return array("status" => false, "erro" => "Valor $value é maior que o alvo.");
        }

        return array("status" => true, "erro" => "");
    }

    function onlyAlphabet($value)
    {

        $regex = "/^[a-zA-Z ]+$/";
        if (!preg_match($regex, $value)) {
            return array("status" => false, "erro" => "'$value' contém caracteres inválidos. Letras minúsculas, cedilhas, números e/ou acentos não são permitidos.");
        }

        return array("status" => true, "erro" => "");

    }

    //3005, 6005
    function isNameValid($value, $target, $cpf = null)
    {

        $result = $this->isGreaterThan(strlen($value), $target);
        if ($result['status']) {
            return array("status" => false, "erro" => "Número de caracteres maior que o permitido.");
        }

        if ($value !== "" && $value !== null) {
            // $result = $this->onlyAlphabet($value);
            // if (!$result['status']) {
            //     return array("status" => false, "erro" => $result['erro']);
            // }
            $result = $this->checkNameRules($value);
            if (!$result['status']) {
                return array("status" => false, "erro" => $result['erro']);
            }
        }

        return array("status" => true, "erro" => "");
    }


    function validateEmailFormat($email)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array("status" => false, "erro" => "'$email' contém caracteres inválidos");
        }

        return array("status" => true, "erro" => "");

    }

    function validateDateformart($date)
    {

        if ($date == '' || $date == null) {
            return array("status" => false, "erro" => "Campo obrigatório.");
        }

        //separa data em dia, mês e ano
        $mdy = explode('/', $date);

        // verifica se a data é valida. Mês-dia-ano
        if (!checkdate($mdy[1], $mdy[0], $mdy[2])) {
            return array("status" => false, "erro" => "'$date' está inválida");
        }

        return array("status" => true, "erro" => "");

    }

    function getAge($birthyear, $currentyear)
    {
        $age = $currentyear - $birthyear;
        return $age;
    }

    function isOlderThan($target_age, $birthyear, $currentyear)
    {

        $age = $this->getAge($birthyear, $currentyear);
        $result = $this->isGreaterThan($age, $target_age);
        if (!$result['status']) {
            return array("status" => false, "erro" => "idade $age é menor que a permitida ($target_age)");
        }

        return array("status" => true, "erro" => "");

    }

    function isYoungerThan($target_age, $birthyear, $currentyear)
    {

        $age = $this->getAge($birthyear, $currentyear);
        $result = $this->isNotGreaterThan($age, $target_age);
        if (!$result['status']) {
            return array("status" => false, "erro" => "idade $age é maior que o permitido ($target_age)");
        }

        return array("status" => true, "erro" => "");

    }

    //campo 1020, 3009, 6007
    function oneOfTheValues($value)
    {
        if ($value == 1 || $value == 2) {
            return array("status" => true, "erro" => "");
        }
        $value = $this->ifNull($value);
        return array("status" => false, "erro" => "Preencha o campo com uma opção válida");

    }


    //10101, 10105, 10106, 3010, 6008
    function isAllowed($value, $allowed_values)
    {
        if (!in_array($value, $allowed_values)) {
            $value = $this->ifNull($value);
            return array("status" => false,
                "erro" => "Preencha o campo com uma opção válida");
        }
        return array("status" => true, "erro" => "");
    }

    function isCPFValid($cpfStr)
    {
        if ($cpfStr !== "") {
            $cpf = "$cpfStr";
            if (strpos($cpf, "-") !== false) {
                $cpf = str_replace("-", "", $cpf);
            }
            if (strpos($cpf, ".") !== false) {
                $cpf = str_replace(".", "", $cpf);
            }
            $sum = 0;
            $cpf = str_split($cpf);
            $cpftrueverifier = array();
            $cpfnumbers = array_splice($cpf, 0, 9);
            $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
            for ($i = 0; $i <= 8; $i++) {
                $sum += $cpfnumbers[$i] * $cpfdefault[$i];
            }
            $sumresult = $sum % 11;
            if ($sumresult < 2) {
                $cpftrueverifier[0] = 0;
            } else {
                $cpftrueverifier[0] = 11 - $sumresult;
            }
            $sum = 0;
            $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
            $cpfnumbers[9] = $cpftrueverifier[0];
            for ($i = 0; $i <= 9; $i++) {
                $sum += $cpfnumbers[$i] * $cpfdefault[$i];
            }
            $sumresult = $sum % 11;
            if ($sumresult < 2) {
                $cpftrueverifier[1] = 0;
            } else {
                $cpftrueverifier[1] = 11 - $sumresult;
            }
            $returner = false;
            if ($cpf == $cpftrueverifier) {
                $returner = true;
            }


            $cpfver = array_merge($cpfnumbers, $cpf);

            if (count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0)) {

                $returner = false;

            }
            if (!$returner) {
                return array("status" => false, "erro" => "'$cpfStr' inválido.");
            }
        }
        return array("status" => true, "erro" => "");
    }

    function checkNameRules($value)
    {
        if (!preg_match('/^[A-zÀ-ú \']+$/', $value)) {
            return array("status" => false, "erro" => "'$value' inválido. Nome deve conter apenas letras.");
        }

        if (str_word_count($value) < 2) {
            return array("status" => false, "erro" => "'$value' não contém mais que 2 palavras.");
        }

        if (preg_match('/(.)\1{3,}/', $value)) {
            return array("status" => false, "erro" => "'$value' contém 4 ou mais caracteres repetidos (podendo ser letras ou caracteres em branco).");
        }

        return array("status" => true, "erro" => "");
    }

    //3011, 3012, 3013, 6009.6010, 6011
    function validateFiliation($filiation, $filiation_mother, $filiation_father, $cpf = null, $high_limit = null)
    {

        $result = $this->isAllowed($filiation, array("0", "1"));
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        if ($filiation == "1") {

            if (!($filiation_mother != "" || $filiation_father != "")) {
                return array("status" => false, "erro" => "Uma das filiações deve ser preenchida");
            }

            if ($filiation_mother == $filiation_father) {
                return array("status" => false, "erro" => "As filiações não podem ser idênticas");
            }

        } else {

            if (!($filiation_mother == null && $filiation_father == null)) {
                return array("status" => false, "erro" => "Ambas filiãções deveriam ser nulas campo 11 é 0");
            }

        }

        return array("status" => true, "erro" => "");

    }

    //3014, 3015, 6012, 6013
    function checkNation($nationality, $nation, $allowedvalues)
    {

        if (!in_array($nationality, $allowedvalues)) {
            return array("status" => false, "erro" => "Campo obrigatório. Selecione uma das opções.");
        }

        if ($nationality == 1 || $nationality == 2) {
            if ($nation != "76") {
                return array("status" => false, "erro" => "País de origem deveria ser Brasil");
            }
        } else {
            if ($nation == "76") {
                return array("status" => false, "erro" => "País de origem não deveria ser Brasil");
            }
        }

        return array("status" => true, "erro" => "");

    }
    

    function ufcity($nationality, $nation, $city)
    {

        if ($nationality == 1 && ($nation == "" || !isset($city))) {
            return array("status" => false, "erro" => "Cidade deveria ser preenchida");
        } else if (!($city == "" || isset($city))) {
            return array("status" => false, "erro" => "Cidade não deveria ser preenchida");
        }
    
        return array("status" => true, "erro" => "");
    }

    function exclusiveDeficiency($deficiencyName, $deficiency, $excludingdeficiencies)
    {
        switch ($deficiencyName) {
            case "Cegueira":
                $excludingDeficiencyNames = "Baixa visão, surdez e surdocegueira";
                break;
            case "Baixa Visão":
            case "Deficiência Auditiva":
                $excludingDeficiencyNames = "Surdocegueira";
                break;
            case "Surdez":
                $excludingDeficiencyNames = "Deficiência auditiva e surdocegueira";
                break;
        }
        $result = $this->atLeastOne($excludingdeficiencies);
        if ($result['status']) {
            if ($deficiency != "0") {
                return array("status" => false, "erro" => "Opção " . $deficiencyName . " é incompatível com o campo " . $excludingDeficiencyNames . ".");
            }
        }

        return array("status" => true, "erro" => "");

    }

    function checkDeficiencies($hasdeficiency, $deficiencies, $excludingdeficiencies)
    {

        if ($hasdeficiency == "1") {

            $result = $this->atLeastOne($deficiencies);
            if (!$result['status']) {
                return array("status" => false, "erro" => $result['erro']);
            }
            foreach ($excludingdeficiencies as $arrayOfExcludingDeficiencies) {
                foreach ($arrayOfExcludingDeficiencies as $deficiencyName => $deficiencies) {
                    foreach ($deficiencies as $deficiency => $excluded) {
                        $result = $this->exclusiveDeficiency($deficiencyName, $deficiency, $excluded);
                        if (!$result['status']) {
                            return array("status" => false, "erro" => $result['erro']);
                        }
                    }
                }
            }
        }

        return array("status" => true, "erro" => "");

    }

    function checkMultiple($hasdeficiency, $multipleDeficiencies, $deficiencies)
    {

        if ($hasdeficiency == "1") {
            $result = $this->moreThanTwo($deficiencies);
            if ($result['status']) {
                if ($multipleDeficiencies != "1") {
                    return array("status" => false, "erro" => "Opção 'Deficiência múltipla' não foi selecionada, mas o Educacenso enquadra as deficiências selecionadas como deficiência múltipla. Selecione esta opção.");
                }
            } else {
                if ($multipleDeficiencies != "0") {
                    return array("status" => false, "erro" => "Opção 'Deficiência múltipla' foi selecionada, mas as opções selecionadas, segundo o Educacenso, não refletem em deficiência múltipla. Desmarque esta opção.");
                }
            }
        }

        return array("status" => true, "erro" => "");

    }

    function ifDemandsCheckValues($demand, $value, $allowed_values)
    {

        if ($demand == '1') {
            $result = $this->isAllowed($value, $allowed_values);
            if (!$result['status']) {
                return array("status" => false, "erro" => $result['erro']);
            }
        } else {
            if ($value != null) {
                return array("status" => false, "erro" => "value $value deveria ser nulo");
            }
        }

        return array("status" => true, "erro" => "");

    }

    function allowedNumberOfTypes($items, $value, $limit)
    {

        $number_of_ones = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i] == $value)
                $number_of_ones++;
        }
        if ($number_of_ones > $limit) {
            return array("status" => false, "erro" => "Há valores marcados além do permitido");
        }
        return array("status" => true, "erro" => "");
    }


    //Registro 10 ( 21 à 25, 26 à 29, 30 à 32, 39 à 68 )
    function checkRangeOfArray($array, $allowed_values)
    {
        foreach ($array as $key => $value) {
            $result = $this->isAllowed($value, $allowed_values);
            if (!$result["status"]) {
                return array("status" => false, "erro" => "Valor $value de ordem $key não está entre as opções");
            }
        }
        return array("status" => true, "erro" => "");
    }

    function adaptedArrayCount($items)
    {
        $result = array();

        foreach ($items as $key => $value) {
            if ($value != null) {
                if (array_key_exists($value, $result)) {
                    $result[$value] += 1;
                } else {
                    $result[$value] = 1;
                }
            }
        }

        return $result;
    }

    //1098 à 10100
    function exclusive($items)
    {

        $count = $this->adaptedArrayCount($items);
        if (!empty($count)) {
            if (max($count) > 1)
                return array("status" => false, "erro" => "Há mais de um valor marcado");
        }

        return array("status" => true, "erro" => "");

    }

    function isAreaOfResidenceValid($area_of_residence)
    {
        if ($area_of_residence != 1 && $area_of_residence != 2) {
            return array("status" => false, "erro" => "O campo é obrigatório.");
        }

        return array("status" => true, "erro" => "");
    }

    public function isValidPersonInepId($inepId, $schoolInepId)
    {
        if ($inepId == $schoolInepId) {
            return array("status" => false, "erro" => "O ID INEP não pode ser igual ao ID INEP da escola.");
        } else {
            $result = $this->isNumericOfSize(12, $inepId);
            if (!$result["status"]) {
                return array("status" => false, "erro" => $result["erro"]);
            }
        }
        return array("status" => true, "erro" => "");
    }
}

?>
