<?php


final class CantSaveGradeUnityModalityException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null) {
        parent::__construct("Não foi possivel salvar a modalidade de avaliação em uma unidade dessa estrutura de avaliação", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
