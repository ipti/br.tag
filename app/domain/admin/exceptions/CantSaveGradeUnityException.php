<?php


final class CantSaveGradeUnityException extends Exception
{
    public function __construct(GradeUnity $unity, $code = 0, Throwable $previous = null) {
        parent::__construct("Não foi possivel salvar a unidade: ". $unity->name ." para etapa de código: ". $unity->edcensoStageVsModalityFk->name, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
