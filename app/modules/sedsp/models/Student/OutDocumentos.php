<?php

class OutDocumentos
{
    private $outCodINEP;
    private $outCPF;
    private $outDataEmissaoDoctoCivil;
    private $outDataEmissaoCertidao;
    private $outNumeroCNS;

	/**
	 * Summary of __construct
	 * @param OutDocumentos $documentos
	 */
	public function __construct($documentos) {
		$this->outCodINEP = $documentos->outCodINEP;
		$this->outCPF = $documentos->outCPF;
		$this->outDataEmissaoDoctoCivil = $documentos->outDataEmissaoDoctoCivil;
		$this->outDataEmissaoCertidao = $documentos->outDataEmissaoCertidao;
		$this->outNumeroCNS = $documentos->outNumeroCNS;
	}
	

    /**
     * Get the value of outCodINEP
     */
    public function getOutCodINEP()
    {
        return $this->outCodINEP;
    }

    /**
     * Set the value of outCodINEP
     */
    public function setOutCodINEP($outCodINEP): self
    {
        $this->outCodINEP = $outCodINEP;

        return $this;
    }

    /**
     * Get the value of outCPF
     */
    public function getOutCPF()
    {
        return $this->outCPF;
    }

    /**
     * Set the value of outCPF
     */
    public function setOutCPF($outCPF): self
    {
        $this->outCPF = $outCPF;

        return $this;
    }

    /**
     * Get the value of outDataEmissaoDoctoCivil
     */
    public function getOutDataEmissaoDoctoCivil()
    {
        return $this->outDataEmissaoDoctoCivil;
    }

    /**
     * Set the value of outDataEmissaoDoctoCivil
     */
    public function setOutDataEmissaoDoctoCivil($outDataEmissaoDoctoCivil): self
    {
        $this->outDataEmissaoDoctoCivil = $outDataEmissaoDoctoCivil;

        return $this;
    }

    /**
     * Get the value of outDataEmissaoCertidao
     */
    public function getOutDataEmissaoCertidao()
    {
        return $this->outDataEmissaoCertidao;
    }

    /**
     * Set the value of outDataEmissaoCertidao
     */
    public function setOutDataEmissaoCertidao($outDataEmissaoCertidao): self
    {
        $this->outDataEmissaoCertidao = $outDataEmissaoCertidao;

        return $this;
    }

    /**
     * Get the value of outNumeroCNS
     */
    public function getOutNumeroCNS()
    {
        return $this->outNumeroCNS;
    }

    /**
     * Set the value of outNumeroCNS
     */
    public function setOutNumeroCNS($outNumeroCNS): self
    {
        $this->outNumeroCNS = $outNumeroCNS;

        return $this;
    }
}