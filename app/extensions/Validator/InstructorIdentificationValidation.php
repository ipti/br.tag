<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

class InstructorIdentificationValidation extends Register
{
    public function __construct()
    {
        // code...
    }

    //3006
    public function isEmailValid($value, $target)
    {
        if ($value != '') {
            $result = $this->isGreaterThan(strlen($value), $target);
            if ($result['status']) {
                return ['status' => false, 'erro' => "'$value' contém número de caracteres maior que o permitido."];
            }

            $result = $this->validateEmailFormat($value);
            if (!$result['status']) {
                return ['status' => false, 'erro' => $result['erro']];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    //campo 08
    public function validateBirthday($date, $lowLimit, $highLimit, $currentyear)
    {
        $result = $this->validateDateformart($date);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $mdy = explode('/', $date);

        $result = $this->isOlderThan($lowLimit, $mdy[2], $currentyear);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        $result = $this->isYoungerThan($highLimit, $mdy[2], $currentyear);
        if (!$result['status']) {
            return ['status' => false, 'erro' => $result['erro']];
        }

        return ['status' => true, 'erro' => ''];
    }
}
