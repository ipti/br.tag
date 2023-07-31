<?php

class InEnderecoResidencial implements JsonSerializable
{
	public $inLogradouro;
	public $inNumero;
	public $inBairro;
	public $inNomeCidade;
	public $inUFCidade;
	public $inComplemento;
	public $inCep;
	public $inAreaLogradouro;
	public $inCodLocalizacaoDiferenciada;
	public $inCodMunicipioDNE;
	public $inLatitude;
	public $inLongitude;

	public function __construct($inEnderecoResidencial) {
        $inEnderecoResidencial = (object) $inEnderecoResidencial;
		$this->inLogradouro = $inEnderecoResidencial->inLogradouro;
		$this->inNumero = $inEnderecoResidencial->inNumero;
		$this->inBairro = $inEnderecoResidencial->inBairro;
		$this->inNomeCidade = $inEnderecoResidencial->inNomeCidade;
		$this->inUFCidade = $inEnderecoResidencial->inUFCidade;
		$this->inComplemento = $inEnderecoResidencial->inComplemento;
		$this->inCep = $inEnderecoResidencial->inCep;
		$this->inAreaLogradouro = $inEnderecoResidencial->inAreaLogradouro;
		$this->inCodLocalizacaoDiferenciada = $inEnderecoResidencial->inCodLocalizacaoDiferenciada;
		$this->inCodMunicipioDNE = $inEnderecoResidencial->inCodMunicipioDNE;
		$this->inLatitude = $inEnderecoResidencial->inLatitude;
		$this->inLongitude = $inEnderecoResidencial->inLongitude;
	}

	public function getInLogradouro(): string
	{
		return $this->inLogradouro;
	}

	public function getInNumero(): string
	{
		return $this->inNumero;
	}

	public function getInBairro(): string
	{
		return $this->inBairro;
	}

	public function getInNomeCidade(): string
	{
		return $this->inNomeCidade;
	}

	public function getInUfCidade(): string
	{
		return $this->inUFCidade;
	}

	public function getInComplemento(): string
	{
		return $this->inComplemento;
	}

	public function getInCep(): string
	{
		return $this->inCep;
	}

	public function getInAreaLogradouro(): string
	{
		return $this->inAreaLogradouro;
	}

	public function getInCodLocalizacaoDiferenciada(): string
	{
		return $this->inCodLocalizacaoDiferenciada;
	}

	public function getInCodMunicipioDne(): string
	{
		return $this->inCodMunicipioDNE;
	}

	public function getInLatitude(): string
	{
		return $this->inLatitude;
	}

	public function getInLongitude(): string
	{
		return $this->inLongitude;
	}

    public function jsonSerialize()
    {
		$filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}