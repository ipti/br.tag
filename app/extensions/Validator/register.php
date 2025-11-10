<?php

class Register
{
    public function __construct()
    {
        // empty constructor
    }

    public function isEmpty($value)
    {
        if (trim((string) ($value ?? '')) === '') {
            return ['status' => true, 'erro' => ''];
        }
        return ['status' => false, 'erro' => 'O valor nao eh vazio'];
    }

    public function isNull($x)
    {
        if ($x == null) {
            return ['status' => true, 'erro' => ''];
        }
        return ['status' => false, 'erro' => 'Valor não é nulo'];
    }

    public function ifNull($value)
    {
        if ($value == null) {
            $value = 'nulo';
        }
        return $value;
    }

    //campo 1002
    public function isEqual($x, $y, $msg)
    {
        $result = $this->isNUll($x);

        if ($result['status']) {
            return ['status' => false, 'erro' => 'valor é nulo'];
        }
        if ($x != $y) {
            return ['status' => false, 'erro' => $msg];
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 1003 à 1011, 1033 à 1038
    public function atLeastOne($items)
    {
        $numberOfOnes = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if (@$items[$i] == '1') {
                $numberOfOnes++;
            }
        }
        if ($numberOfOnes == 0) {
            return ['status' => false, 'erro' => 'Selecione ao menos uma opção.'];
        }
        return ['status' => true, 'erro' => ''];
    }

    public function atLeastOneNotEmpty($items)
    {
        $numberOfNotEmpty = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i] != '') {
                $numberOfNotEmpty++;
            }
        }
        if ($numberOfNotEmpty == 0) {
            return ['status' => false, 'erro' => 'Não há nenhum valor preenchido'];
        }
        return ['status' => true, 'erro' => ''];
    }

    public function moreThanTwo($items)
    {
        $numberOfOnes = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i] == '1') {
                $numberOfOnes++;
            }
        }
        if ($numberOfOnes < 2) {
            return ['status' => false, 'erro' => 'Não há mais de um valor marcado'];
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 1001, 3001, 4001, 6001
    public function isRegister($number, $value)
    {
        $result = $this->isEqual($value, $number, "O tipo de registro não deveria ser $value e sim $number");
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        return ['status' => true, 'erro' => ''];
    }

    //campo 1002, 3002, 4002, 6002
    public function isAllowedInepId($inepId, $allowedInepIds)
    {
        if (!in_array($inepId, $allowedInepIds)) {
            return ['status' => false, 'erro' => "ID INEP $inepId não está entre os permitidos"];
        }

        return ['status' => true, 'erro' => ''];
    }

    //campo 3003, 6003
    public function isNumericOfSize($allowedLength, $value)
    {
        if (is_numeric($value)) {
            $len = strlen($value);
            if ($len != $allowedLength) {
                return ['status' => false, 'erro' => "Campo deveria ter $allowedLength caracteres ao invés de $len."];
            }
        } else {
            $value = $this->ifNull($value);
            return ['status' => false, 'erro' => "valor $value deve ser constituído apenas de números. Remova quaisquer letras, símbolos ou espaços em branco."];
        }

        return ['status' => true, 'erro' => ''];
    }

    //1070, 1088
    public function isGreaterThan($value, $target)
    {
        if ($value <= $target) {
            $value = $this->ifNull($value);
            return ['status' => false, 'erro' => "Valor $value não é maior que o alvo."];
        }
        return ['status' => true, 'erro' => ''];
    }

    //3004, 6004
    public function isNotGreaterThan($value, $target)
    {
        $result = $this->isGreaterThan(strlen($value), $target);
        if ($result['status']) {
            return ['status' => false, 'erro' => "Valor $value é maior que o alvo."];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function onlyAlphabet($value)
    {
        $regex = '/^[a-zA-Z ]+$/';
        if (!preg_match($regex, $value)) {
            return ['status' => false, 'erro' => "'$value' contém caracteres inválidos. Letras minúsculas, cedilhas, números e/ou acentos não são permitidos."];
        }

        return ['status' => true, 'erro' => ''];
    }

    //3005, 6005
    public function isNameValid($value, $target)
    {
        $result = $this->isGreaterThan(strlen($value), $target);
        if ($result['status']) {
            return ['status' => false, 'erro' => 'Número de caracteres maior que o permitido.'];
        }

        if ($value !== '' && $value !== null) {
            $result = $this->checkNameRules($value);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function validateEmailFormat($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => false, 'erro' => "'$email' contém caracteres inválidos"];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function validateDateformart($date)
    {
        if ($date == '' || $date == null) {
            return ['status' => false, 'erro' => 'Campo obrigatório.'];
        }

        $mdy = explode('/', $date);

        if (!checkdate($mdy[1], $mdy[0], $mdy[2])) {
            return ['status' => false, 'erro' => "'$date' está inválida"];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function getAge($birthyear, $currentyear)
    {
        return $currentyear - $birthyear;
    }

    public function isOlderThan($targetAge, $birthyear, $currentyear)
    {
        $age = $this->getAge($birthyear, $currentyear);
        $result = $this->isGreaterThan($age, $targetAge);
        if (!$result['status']) {
            return ['status' => false, 'erro' => "idade $age é menor que a permitida ($targetAge)"];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function isYoungerThan($targetAge, $birthyear, $currentyear)
    {
        $age = $this->getAge($birthyear, $currentyear);
        $result = $this->isNotGreaterThan($age, $targetAge);
        if (!$result['status']) {
            return ['status' => false, 'erro' => "idade $age é maior que o permitido ($targetAge)"];
        }

        return ['status' => true, 'erro' => ''];
    }

    //campo 1020, 3009, 6007
    public function oneOfTheValues($value)
    {
        if ($value == 1 || $value == 2) {
            return ['status' => true, 'erro' => ''];
        }
        $value = $this->ifNull($value);
        return ['status' => false, 'erro' => 'Preencha o campo com uma opção válida'];
    }

    //10101, 10105, 10106, 3010, 6008
    public function isAllowed($value, $allowedValues)
    {
        if (!in_array($value, $allowedValues)) {
            $value = $this->ifNull($value);
            return ['status' => false,
                'erro' => 'Preencha o campo com uma opção válida'];
        }
        return ['status' => true, 'erro' => ''];
    }

    public function isCPFValid($cpfStr)
    {
        if ($cpfStr !== '') {
            $cpf = strval($cpfStr);
            if (strpos($cpf, '-') !== false) {
                $cpf = str_replace('-', '', $cpf);
            }
            if (strpos($cpf, '.') !== false) {
                $cpf = str_replace('.', '', $cpf);
            }
            $sum = 0;
            $cpf = str_split($cpf);
            $cpfTrueVerifier = [];
            $cpfNumbers = array_splice($cpf, 0, 9);
            $cpfDefault = [10, 9, 8, 7, 6, 5, 4, 3, 2];
            for ($i = 0; $i <= 8; $i++) {
                $sum += $cpfNumbers[$i] * $cpfDefault[$i];
            }
            $sumResult = $sum % 11;
            $cpfTrueVerifier[0] = $sumResult < 2 ? 0 : 11 - $sumResult;
            $sum = 0;
            $cpfDefault = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
            $cpfNumbers[9] = $cpfTrueVerifier[0];
            for ($i = 0; $i <= 9; $i++) {
                $sum += $cpfNumbers[$i] * $cpfDefault[$i];
            }
            $sumResult = $sum % 11;
            $cpfTrueVerifier[1] = $sumResult < 2 ? 0 : 11 - $sumResult;

            $returner = false;
            if ($cpf == $cpfTrueVerifier) {
                $returner = true;
            }

            $cpfver = array_merge($cpfNumbers, $cpf);

            if (count(array_unique($cpfver)) == 1 || $cpfver == [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0]) {
                $returner = false;
            }
            if (!$returner) {
                return ['status' => false, 'erro' => "'$cpfStr' inválido."];
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    public function checkNameRules($value)
    {
        if (!preg_match('/^[A-zÀ-ú \']+$/', $value)) {
            return ['status' => false, 'erro' => "'$value' inválido. Nome deve conter apenas letras."];
        }

        if (str_word_count($value) < 2) {
            return ['status' => false, 'erro' => "'$value' não contém mais que 2 palavras."];
        }

        if (preg_match('/(.)\1{3,}/', $value)) {
            return ['status' => false, 'erro' => "'$value' contém 4 ou mais caracteres repetidos (podendo ser letras ou caracteres em branco)."];
        }

        return ['status' => true, 'erro' => ''];
    }

    //3011, 3012, 3013, 6009.6010, 6011
    public function validateFiliation($filiation, $filiationMother, $filiationFather)
    {
        $result = $this->isAllowed($filiation, ['0', '1']);
        $errorMessage = "";
        $errorStatus = true;

        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        if ($filiation == '1') {
            if (!($filiationMother != '' || $filiationFather != '')) {
                $errorMessage = 'Uma das filiações deve ser preenchida';
                $errorStatus = false;
            } elseif ($filiationMother == $filiationFather) {
                $errorMessage = 'As filiações não podem ser idênticas';
                $errorStatus = false;
            }

        } else {
            if (!($filiationMother == null && $filiationFather == null)) {
                $errorStatus = false;
                $errorMessage = 'Ambas as filiações deveriam ser nulas compo 11 é 0';
            }
        }

        return ['status' => $errorStatus, 'erro' => $errorMessage];
    }

    //3014, 3015, 6012, 6013
    public function checkNation($nationality, $nation, $allowedValues)
    {
        if (!in_array($nationality, $allowedValues)) {
            return ['status' => false, 'erro' => 'Campo obrigatório. Selecione uma das opções.'];
        }

        if ($nationality == 1 || $nationality == 2) {
            if ($nation != '76') {
                return ['status' => false, 'erro' => 'País de origem deveria ser Brasil'];
            }
        } else {
            if ($nation == '76') {
                return ['status' => false, 'erro' => 'País de origem não deveria ser Brasil'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function ufcity($nationality, $nation, $city)
    {
        if ($nationality == 1 && ($nation == '' || !isset($city))) {
            return ['status' => false, 'erro' => 'Cidade deveria ser preenchida'];
        } elseif (!($city == '' || isset($city))) {
            return ['status' => false, 'erro' => 'Cidade não deveria ser preenchida'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function exclusiveDeficiency($deficiencyName, $deficiency, $excludingDeficiencies)
    {
        switch ($deficiencyName) {
            case 'Cegueira':
                $excludingDeficiencyNames = 'Baixa visão, surdez e surdocegueira';
                break;
            case 'Baixa Visão':
            case 'Deficiência Auditiva':
                $excludingDeficiencyNames = 'Surdocegueira';
                break;
            case 'Surdez':
                $excludingDeficiencyNames = 'Deficiência auditiva e surdocegueira';
                break;
            default:
                break;
        }
        $result = $this->atLeastOne($excludingDeficiencies);
        if ($result['status'] && $deficiency != '0') {
            return ['status' => false, 'erro' => 'Opção ' . $deficiencyName . ' é incompatível com o campo ' . $excludingDeficiencyNames . '.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function checkDeficiencies($hasDeficiency, $deficiencies, $excludingDeficiencies)
    {
        if ($hasDeficiency == '1') {
            $result = $this->atLeastOne($deficiencies);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
            foreach ($excludingDeficiencies as $arrayOfExcludingDeficiencies) {
                foreach ($arrayOfExcludingDeficiencies as $deficiencyName => $deficiencies) {
                    foreach ($deficiencies as $deficiency => $excluded) {
                        $result = $this->exclusiveDeficiency($deficiencyName, $deficiency, $excluded);
                        if (!$result['status']) {
                            return ['status' => false, 'erro' => $result['erro']];
                        }
                    }
                }
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function checkMultiple($hasDeficiency, $multipleDeficiencies, $deficiencies)
    {
        if ($hasDeficiency == '1') {
            $result = $this->moreThanTwo($deficiencies);
            if ($result['status']) {
                if ($multipleDeficiencies != '1') {
                    return ['status' => false, 'erro' => "Opção 'Deficiência múltipla' não foi selecionada, mas o Educacenso enquadra as deficiências selecionadas como deficiência múltipla. Selecione esta opção."];
                }
            } else {
                if ($multipleDeficiencies != '0') {
                    return ['status' => false, 'erro' => "Opção 'Deficiência múltipla' foi selecionada, mas as opções selecionadas, segundo o Educacenso, não refletem em deficiência múltipla. Desmarque esta opção."];
                }
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    public function ifDemandsCheckValues($demand, $value, $allowedValues)
    {
        if ($demand == '1') {
            $result = $this->isAllowed($value, $allowedValues);
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

    public function allowedNumberOfTypes($items, $value, $limit)
    {
        $numberOfOnes = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i] == $value) {
                $numberOfOnes++;
            }
        }
        if ($numberOfOnes > $limit) {
            return ['status' => false, 'erro' => 'Há valores marcados além do permitido'];
        }
        return ['status' => true, 'erro' => ''];
    }

    //Registro 10 ( 21 à 25, 26 à 29, 30 à 32, 39 à 68 )
    public function checkRangeOfArray($array, $allowedValues)
    {
        foreach ($array as $key => $value) {
            $result = $this->isAllowed($value, $allowedValues);
            if (!$result['status']) {
                return ['status' => false, 'erro' => "Valor $value de ordem $key não está entre as opções"];
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    public function adaptedArrayCount($items)
    {
        $result = [];

        foreach ($items as $value) {
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
    public function exclusive($items)
    {
        $count = $this->adaptedArrayCount($items);
        if (!empty($count) && max($count) > 1) {
            return ['status' => false, 'erro' => 'Há mais de um valor marcado'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function isAreaOfResidenceValid($areaOfResidence)
    {
        if ($areaOfResidence != 1 && $areaOfResidence != 2) {
            return ['status' => false, 'erro' => 'O campo é obrigatório.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function isValidPersonInepId($inepId, $schoolInepId)
    {
        if ($inepId == $schoolInepId) {
            return ['status' => false, 'erro' => 'O ID INEP não pode ser igual ao ID INEP da escola.'];
        } else {
            $result = $this->isNumericOfSize(12, $inepId);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }
        return ['status' => true, 'erro' => ''];
    }
}
