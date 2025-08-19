<?php


final class NoActiveStudentsException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null) {
        parent::__construct("Não há estudantes ativos matriculados na turma.", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
