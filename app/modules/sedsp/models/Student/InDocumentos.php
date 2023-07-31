<?php

class InDocumentos implements JsonSerializable
{
    /**
     * @var string $inNumRG
     */
    public $inNumRG;

    /**
     * @var string $inDigitoRG
     */
    public $inDigitoRG;

    /**
     * @var string $inUFRG
     */
    public $inUFRG;

    /**
     * @var string $inCPF
     */
    public $inCPF;

    /**
     * @var string $inNumNIS
     */
    public $inNumNIS;

    /** @var string */
    public $inDataEmissaoDoctoCivil;

    /** @var string */
    public $inJustificativaDocumentos;

    /**
     * @var string $inNumINEP
     */
    public $inNumINEP;

    /**
     * @var string $inNumCertidaoNova
     */
    public $inNumCertidaoNova;





    /**
     * Summary of __construct
     * @param mixed $inDocumentos
     */
    public function __construct($inDocumentos) {
        $inDocumentos = (object) $inDocumentos;
        $this->inNumRG = $inDocumentos->inNumRG;
        $this->inDigitoRG = $inDocumentos->inDigitoRG;
        $this->inUFRG = $inDocumentos->inUFRG;
        $this->inCPF = $inDocumentos->inCPF;
        $this->inNumNIS = $inDocumentos->inNumNIS;
        $this->inDataEmissaoDoctoCivil = $inDocumentos->inDataEmissaoDoctoCivil;
        $this->inJustificativaDocumentos = $inDocumentos->inJustificativaDocumentos;
        $this->inNumINEP = $inDocumentos->inNumINEP;
        $this->inNumCertidaoNova = $inDocumentos->inNumCertidaoNova;
    } 

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}