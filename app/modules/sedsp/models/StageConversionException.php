<?php

class StageConversionException extends Exception
{
    public function __construct($stage) {
        $message = "Etapa não tem tipo de ensino no mapa de conversão $stage";
        parent::__construct($message, 1);
    }
}
