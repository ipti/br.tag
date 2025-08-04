<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

//registro 40
class InstructorDocumentsAndAddressValidation extends Register
{
    //campo 5
    function isCPFValid($cpfStr)
    {
        if ($cpfStr !== '' && $cpfStr !== null) {
            $cpf = "$cpfStr";
            if (strpos($cpf, '-') !== false) {
                $cpf = str_replace('-', '', $cpf);
            }
            if (strpos($cpf, '.') !== false) {
                $cpf = str_replace('.', '', $cpf);
            }
            $sum = 0;
            $cpf = str_split($cpf);
            $cpftrueverifier = [];
            $cpfnumbers = array_splice($cpf, 0, 9);
            $cpfdefault = [10, 9, 8, 7, 6, 5, 4, 3, 2];
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
            $cpfdefault = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
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

            if (count(array_unique($cpfver)) == 1 || $cpfver == [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0]) {
                $returner = false;
            }
            if (!$returner) {
                return ['status' => false, 'erro' => "'$cpfStr' inválido."];
            }
        } else {
            return ['status' => false, 'erro' => 'O campo CPF é obrigatório.'];
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 7
    function isCEPValid($cep)
    {
        // retira espacos em branco
        $cep = trim($cep);
        // expressao regular para avaliar o cep
        $avaliaCep = preg_match('/^[0-9]{5}[0-9]{3}$/', $cep);

        $verification = $this->verifyCep($cep, $avaliaCep);

        if ($verification != null) {
            return $verification;
        }

        return ['status' => true, 'erro' => ''];
    }

    private function verifyCep($cep, $avaliaCep)
    {
        $isInvalidValue = $this->checkCepInvalidValue($cep);
        if ($isInvalidValue != null) {
            return $isInvalidValue;
        }
        if (strlen($cep) != 8 || !$avaliaCep) {
            return ['status' => false, 'erro' => 'O campo CEP está com tamanho diferente do especificado.'];
        }
        return null;
    }

    private function checkCepInvalidValue($cep)
    {
        if ($cep == null) {
            return ['status' => false, 'erro' => 'O campo CEP é uma informação obrigatória.'];
        }
        if (!is_numeric($cep)) {
            return ['status' => false, 'erro' => 'O campo CEP foi preenchido com valor inválido.'];
        }

        return array("status" => true, "erro" => "");
    }

    //campo 8, 9, 10, 11, 12, 13
    function isAdressValid($field, $cep, $allowed_lenght)
    {
        $regex = '/^[0-9 a-z.,-ºª ]+$/';
        $checkAdress = $this->checkAdress($field, $regex, $allowedLenght);
        if ($cep == null && $field == null) {
            return ['status' => false, 'erro' => 'O campo não pode ser nulo.'];
        }
        if ($checkAdress != null) {
            return $checkAdress;
        }
        return ['status' => true, 'erro' => ''];
    }

    private function checkAdress($field, $regex, $allowedLenght)
    {
        if (strlen($field) > $allowedLenght || strlen($field) <= 0) {
            return ['status' => false, 'erro' => 'O campo está com tamanho incorreto.'];
        }
        $checkAdressType = $this->checkAdressTypeIsValid($field, $regex);
        if ($checkAdressType != null) {
            return $checkAdressType;
        }
        return null;
    }

    private function checkAdressTypeIsValid($field, $regex)
    {
        if (!preg_match($regex, $field)) {
            return ['status' => false, 'erro' => 'O campo foi preenchido com valor inválido.'];
        }
        if ($field == null) {
            return ['status' => false, 'erro' => 'O campo não pode ser nulo.'];
        }
        return null;
    }
}
