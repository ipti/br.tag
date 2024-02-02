<?php


final class CantSaveGradeRulesException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null) {
        parent::__construct("Não foi possivel salvar regras de aprovação para essa estrutura", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
