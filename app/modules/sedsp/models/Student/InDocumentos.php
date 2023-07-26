<?php

declare(strict_types=1);

class InDocumentos
{
    /**
     * @var ?string $inNumRG
     */
    public $inNumRG;

    /**
     * @var ?string $inDigitoRG
     */
    public $inDigitoRG;

    /**
     * @var ?string $inUFRG
     */
    public $inUFRG;

    /**
     * @var ?string $inCPF
     */
    public $inCPF;

    /**
     * @var ?string $inNumNIS
     */
    public $inNumNIS;

    /**
     * @var ?string $inNumINEP
     */
    public $inNumINEP;

    /**
     * @var ?string $inNumCertidaoNova
     */
    public $inNumCertidaoNova;



    /**
     * InListarAlunos constructor.
     * @param ?string $inNumRG
     * @param ?string $inDigitoRG
     * @param ?string $inUFRG
     * @param ?string $inCPF
     * @param ?string $inNumNIS
     * @param ?string $inNumINEP
     * @param ?string $inNumCertidaoNova
     */
    public function __construct($inDocumentos) {
        $this->inNumRG = $inDocumentos->inNumRG;
        $this->inDigitoRG = $inDocumentos->inDigitoRG;
        $this->inUFRG = $inDocumentos->inUFRG;
        $this->inCPF = $inDocumentos->inCPF;
        $this->inNumNIS = $inDocumentos->inNumNIS;
        $this->inNumINEP = $inDocumentos->inNumINEP;
        $this->inNumCertidaoNova = $inDocumentos->inNumCertidaoNova;
    } 
}