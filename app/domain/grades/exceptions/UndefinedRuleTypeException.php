<?php


final class UndefinedRuleTypeException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null) {
        parent::__construct("Modelo de regras não definido para realização do calculo de notas", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
