<?php


final class CantSaveGradeUnityException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null) {
        parent::__construct("Não foi possivel salvar uma unidade para esse estrutura", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
