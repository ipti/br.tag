<?php

$DS = \DIRECTORY_SEPARATOR;

require_once __DIR__.$DS.'register.php';

// registro 40
class InstructorDocumentsAndAddressValidation extends Register
{
    // campo 5
    public function isCPFValid($cpfStr)
    {
        if ('' !== $cpfStr && null !== $cpfStr) {
            $cpf = "$cpfStr";
            if (false !== strpos($cpf, '-')) {
                $cpf = str_replace('-', '', $cpf);
            }
            if (false !== strpos($cpf, '.')) {
                $cpf = str_replace('.', '', $cpf);
            }
            $sum = 0;
            $cpf = str_split($cpf);
            $cpftrueverifier = [];
            $cpfnumbers = array_splice($cpf, 0, 9);
            $cpfdefault = [10, 9, 8, 7, 6, 5, 4, 3, 2];
            for ($i = 0; $i <= 8; ++$i) {
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
            for ($i = 0; $i <= 9; ++$i) {
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

            if (1 == count(array_unique($cpfver)) || $cpfver == [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0]) {
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

    // campo 7
    public function isCEPValid($cep)
    {
        // retira espacos em branco
        $cep = trim($cep);
        // expressao regular para avaliar o cep
        $avaliaCep = preg_match('/^[0-9]{5}[0-9]{3}$/', $cep);

        if (null == $cep) {
            return ['status' => false, 'erro' => 'O campo CEP é uma informação obrigatória.'];
        }
        if (8 != strlen($cep) || !$avaliaCep) {
            return ['status' => false, 'erro' => 'O campo CEP está com tamanho diferente do especificado.'];
        }
        if (!is_numeric($cep)) {
            return ['status' => false, 'erro' => 'O campo CEP foi preenchido com valor inválido.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    // campo 8, 9, 10, 11, 12, 13
    public function isAdressValid($field, $cep, $allowed_lenght)
    {
        $regex = '/^[0-9 a-z.,-ºª ]+$/';
        if (null == $cep) {
            if (null == $field) {
                return ['status' => false, 'erro' => 'O campo não pode ser nulo.'];
            }
        } elseif (strlen($field) > $allowed_lenght || '' === $field) {
            return ['status' => false, 'erro' => 'O campo está com tamanho incorreto.'];
        } elseif (!preg_match($regex, $field)) {
            return ['status' => false, 'erro' => 'O campo foi preenchido com valor inválido.'];
        } elseif (null == $field) {
            return ['status' => false, 'erro' => 'O campo não pode ser nulo.'];
        }

        return ['status' => true, 'erro' => ''];
    }
}
