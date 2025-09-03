<?php


final class CantSaveGradeResultsException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null) {
        parent::__construct("Não foi possivel atualizar as notas finais com a nova estrutura de unidades", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
