<?php

class InDocumentosAluno implements JsonSerializable
{
    private $inNRRG;
    private $inUFRG;
    private $inCPF;

    /**
     * Summary of __construct
     * @param ?string $inNRRG
     * @param mixed $inUFRG
     * @param ?mixed $inCPF
     */
    public function __construct($inNRRG, $inUFRG, $inCPF)
    {
        $this->inNRRG = $inNRRG;
        isset($inNRRG)?$this->inUFRG = $inUFRG: $this->inUFRG = null;
        $this->inCPF = $inCPF;
    }

    function jsonSerialize() 
    {
        return get_object_vars($this);
    }
}
