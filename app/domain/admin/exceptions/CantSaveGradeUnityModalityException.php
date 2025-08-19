<?php


final class CantSaveGradeUnityModalityException extends Exception
{
    public function __construct($modalityModel, $code = 0, Throwable $previous = null) {
        $messages = Yii::app()->utils->stringfyValidationErrors($modalityModel);
        parent::__construct("Não foi possivel salvar a modalidade de avaliação em uma unidade dessa estrutura de avaliação \n". $messages, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
