<?php

class OutEnderecoResidencial
{
	private $outLogradouro;
    private $outNumero;
    private $outAreaLogradouro;
    private $outComplemento;
    private $outBairro;
    private $outNomeCidade;
    private $outUFCidade;
    private $outLatitude;
    private $outLongitude;
    private $outCep;
    private $outCodMunicipioDNE;
    private $outCodArea;
    private $outCodLocalizacao;
    private $outLocalizacaoDiferenciada;

	/**
	 * Summary of __construct
	 * @param OutEnderecoResidencial $enderecoResidencial
	 */
	public function __construct($enderecoResidencial) {
		$this->outLogradouro = $enderecoResidencial->outLogradouro;
		$this->outNumero = $enderecoResidencial->outNumero;
		$this->outAreaLogradouro = $enderecoResidencial->outAreaLogradouro;
		$this->outComplemento = $enderecoResidencial->outComplemento;
		$this->outBairro = $enderecoResidencial->outBairro;
		$this->outNomeCidade = $enderecoResidencial->outNomeCidade;
		$this->outUFCidade = $enderecoResidencial->outUFCidade;
		$this->outLatitude = $enderecoResidencial->outLatitude;
		$this->outLongitude = $enderecoResidencial->outLongitude;
		$this->outCep = $enderecoResidencial->outCep;
		$this->outCodMunicipioDNE = $enderecoResidencial->outCodMunicipioDNE;
		$this->outCodArea = $enderecoResidencial->outCodArea;
		$this->outCodLocalizacao = $enderecoResidencial->outCodLocalizacao;
		$this->outLocalizacaoDiferenciada = $enderecoResidencial->outLocalizacaoDiferenciada;
	}

	public function getOutLogradouro(): string
	{
		return $this->outLogradouro;
	}

	public function getOutNumero(): string
	{
		return $this->outNumero;
	}

	public function getOutAreaLogradouro(): string
	{
		return $this->outAreaLogradouro;
	}

	public function getOutComplemento(): string
	{
		return $this->outComplemento;
	}

	public function getOutBairro(): string
	{
		return $this->outBairro;
	}

	public function getOutNomeCidade(): string
	{
		return $this->outNomeCidade;
	}

	public function getOutUfCidade(): string
	{
		return $this->outUFCidade;
	}

	public function getOutLatitude(): string
	{
		return $this->outLatitude;
	}

	public function getOutLongitude(): string
	{
		return $this->outLongitude;
	}

	public function getOutCep(): string
	{
		return $this->outCep;
	}

	public function getOutCodMunicipioDne(): string
	{
		return $this->outCodMunicipioDNE;
	}

	public function getOutCodArea(): string
	{
		return $this->outCodArea;
	}

	public function getOutCodLocalizacao(): string
	{
		return $this->outCodLocalizacao;
	}

	public function getOutLocalizacaoDiferenciada(): string
	{
		return $this->outLocalizacaoDiferenciada;
	}
}