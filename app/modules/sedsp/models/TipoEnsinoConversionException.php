<?php

class TipoEnsinoConversionException extends Exception
{
    public function __construct($codTipoEnsino, $codSerieAno) {
        $message = "Tipo de ensino não tem etapa equivalente no mapa de conversão $codTipoEnsino -> $codSerieAno";
        parent::__construct($message, 1);
    }
}
