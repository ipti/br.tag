<?php

class InConsultClass implements JsonSerializable
{
    /** @var string */
    public $inAnoLetivo;

    /** @var string */
    public $inNumClasse;

    public function __construct($inAnoLetivo, $inNumClasse) {
        $this->inAnoLetivo = $inAnoLetivo;
        $this->inNumClasse = $inNumClasse;
    }
    function jsonSerialize() {
        return get_object_vars($this);
    }
}
