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
	
	public function getOutCodInep(): string
	{
		return $this->outCodINEP;
	}

	public function getOutCpf(): string
	{
		return $this->outCPF;
	}

	public function getOutDataEmissaoDoctoCivil(): string
	{
		return $this->outDataEmissaoDoctoCivil;
	}

	public function getOutDataEmissaoCertidao(): string
	{
		return $this->outDataEmissaoCertidao;
	}

	public function getOutNumeroCns(): string
	{
		return $this->outNumeroCNS;
	}
}