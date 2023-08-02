<?php

class OutEnderecoResidencial
{
	public $outLogradouro;
	public $outNumero;
	public $outCodArea;
	public $outAreaLogradouro;
	public $outComplemento;
	public $outBairro;
	public $outNomeCidade;
	public $outUFCidade;
	public $outCodMunicipioDNE;
	public $outLatitude;
	public $outLongitude;
	public $outCep;
	public $outCodLocalizacao;
	public $outLocalizacaoDiferenciada;

	public function __construct(
		string $outLogradouro,
		string $outNumero,
		string $outCodArea,
		string $outAreaLogradouro,
		string $outComplemento,
		string $outBairro,
		string $outNomeCidade,
		string $outUFCidade,
		string $outCodMunicipioDNE,
		string $outLatitude,
		string $outLongitude,
		string $outCep,
		string $outCodLocalizacao,
		string $outLocalizacaoDiferenciada
	) {
		$this->outLogradouro = $outLogradouro;
		$this->outNumero = $outNumero;
		$this->outCodArea = $outCodArea;
		$this->outAreaLogradouro = $outAreaLogradouro;
		$this->outComplemento = $outComplemento;
		$this->outBairro = $outBairro;
		$this->outNomeCidade = $outNomeCidade;
		$this->outUFCidade = $outUFCidade;
		$this->outCodMunicipioDNE = $outCodMunicipioDNE;
		$this->outLatitude = $outLatitude;
		$this->outLongitude = $outLongitude;
		$this->outCep = $outCep;
		$this->outCodLocalizacao = $outCodLocalizacao;
		$this->outLocalizacaoDiferenciada = $outLocalizacaoDiferenciada;
	}

	/**
	 * Get the value of outLogradouro
	 */
	public function getOutLogradouro()
	{
		return $this->outLogradouro;
	}

	/**
	 * Set the value of outLogradouro
	 */
	public function setOutLogradouro($outLogradouro): self
	{
		$this->outLogradouro = $outLogradouro;

		return $this;
	}

	/**
	 * Get the value of outNumero
	 */
	public function getOutNumero()
	{
		return $this->outNumero;
	}

	/**
	 * Set the value of outNumero
	 */
	public function setOutNumero($outNumero): self
	{
		$this->outNumero = $outNumero;

		return $this;
	}

	/**
	 * Get the value of outCodArea
	 */
	public function getOutCodArea()
	{
		return $this->outCodArea;
	}

	/**
	 * Set the value of outCodArea
	 */
	public function setOutCodArea($outCodArea): self
	{
		$this->outCodArea = $outCodArea;

		return $this;
	}

	/**
	 * Get the value of outAreaLogradouro
	 */
	public function getOutAreaLogradouro()
	{
		return $this->outAreaLogradouro;
	}

	/**
	 * Set the value of outAreaLogradouro
	 */
	public function setOutAreaLogradouro($outAreaLogradouro): self
	{
		$this->outAreaLogradouro = $outAreaLogradouro;

		return $this;
	}

	/**
	 * Get the value of outComplemento
	 */
	public function getOutComplemento()
	{
		return $this->outComplemento;
	}

	/**
	 * Set the value of outComplemento
	 */
	public function setOutComplemento($outComplemento): self
	{
		$this->outComplemento = $outComplemento;

		return $this;
	}

	/**
	 * Get the value of outBairro
	 */
	public function getOutBairro()
	{
		return $this->outBairro;
	}

	/**
	 * Set the value of outBairro
	 */
	public function setOutBairro($outBairro): self
	{
		$this->outBairro = $outBairro;

		return $this;
	}

	/**
	 * Get the value of outNomeCidade
	 */
	public function getOutNomeCidade()
	{
		return $this->outNomeCidade;
	}

	/**
	 * Set the value of outNomeCidade
	 */
	public function setOutNomeCidade($outNomeCidade): self
	{
		$this->outNomeCidade = $outNomeCidade;

		return $this;
	}

	/**
	 * Get the value of outUFCidade
	 */
	public function getOutUFCidade()
	{
		return $this->outUFCidade;
	}

	/**
	 * Set the value of outUFCidade
	 */
	public function setOutUFCidade($outUFCidade): self
	{
		$this->outUFCidade = $outUFCidade;

		return $this;
	}

	/**
	 * Get the value of outCodMunicipioDNE
	 */
	public function getOutCodMunicipioDNE()
	{
		return $this->outCodMunicipioDNE;
	}

	/**
	 * Set the value of outCodMunicipioDNE
	 */
	public function setOutCodMunicipioDNE($outCodMunicipioDNE): self
	{
		$this->outCodMunicipioDNE = $outCodMunicipioDNE;

		return $this;
	}

	/**
	 * Get the value of outLatitude
	 */
	public function getOutLatitude()
	{
		return $this->outLatitude;
	}

	/**
	 * Set the value of outLatitude
	 */
	public function setOutLatitude($outLatitude): self
	{
		$this->outLatitude = $outLatitude;

		return $this;
	}

	/**
	 * Get the value of outLongitude
	 */
	public function getOutLongitude()
	{
		return $this->outLongitude;
	}

	/**
	 * Set the value of outLongitude
	 */
	public function setOutLongitude($outLongitude): self
	{
		$this->outLongitude = $outLongitude;

		return $this;
	}

	/**
	 * Get the value of outCep
	 */
	public function getOutCep()
	{
		return $this->outCep;
	}

	/**
	 * Set the value of outCep
	 */
	public function setOutCep($outCep): self
	{
		$this->outCep = $outCep;

		return $this;
	}

	/**
	 * Get the value of outCodLocalizacao
	 */
	public function getOutCodLocalizacao()
	{
		return $this->outCodLocalizacao;
	}

	/**
	 * Set the value of outCodLocalizacao
	 */
	public function setOutCodLocalizacao($outCodLocalizacao): self
	{
		$this->outCodLocalizacao = $outCodLocalizacao;

		return $this;
	}

	/**
	 * Get the value of outLocalizacaoDiferenciada
	 */
	public function getOutLocalizacaoDiferenciada()
	{
		return $this->outLocalizacaoDiferenciada;
	}

	/**
	 * Set the value of outLocalizacaoDiferenciada
	 */
	public function setOutLocalizacaoDiferenciada($outLocalizacaoDiferenciada): self
	{
		$this->outLocalizacaoDiferenciada = $outLocalizacaoDiferenciada;

		return $this;
	}
}