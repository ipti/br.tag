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

	public function __construct(
		string $inLogradouro,
		string $inNumero,
		string $inBairro,
		string $inNomeCidade,
		string $inUFCidade,
		string $inComplemento,
		string $inCep,
		string $inAreaLogradouro,
		string $inCodLocalizacaoDiferenciada,
		string $inCodMunicipioDNE,
		string $inLatitude,
		string $inLongitude
	) {
		$this->inLogradouro = $inLogradouro;
		$this->inNumero = $inNumero;
		$this->inBairro = $inBairro;
		$this->inNomeCidade = $inNomeCidade;
		$this->inUFCidade = $inUFCidade;
		$this->inComplemento = $inComplemento;
		$this->inCep = $inCep;
		$this->inAreaLogradouro = $inAreaLogradouro;
		$this->inCodLocalizacaoDiferenciada = $inCodLocalizacaoDiferenciada;
		$this->inCodMunicipioDNE = $inCodMunicipioDNE;
		$this->inLatitude = $inLatitude;
		$this->inLongitude = $inLongitude;
	}
    public function jsonSerialize()
    {
		$filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

	/**
	 * Get the value of inLogradouro
	 */
	public function getInLogradouro()
	{
		return $this->inLogradouro;
	}

	/**
	 * Set the value of inLogradouro
	 */
	public function setInLogradouro($inLogradouro): self
	{
		$this->inLogradouro = $inLogradouro;

		return $this;
	}

	/**
	 * Get the value of inNumero
	 */
	public function getInNumero()
	{
		return $this->inNumero;
	}

	/**
	 * Set the value of inNumero
	 */
	public function setInNumero($inNumero): self
	{
		$this->inNumero = $inNumero;

		return $this;
	}

	/**
	 * Get the value of inBairro
	 */
	public function getInBairro()
	{
		return $this->inBairro;
	}

	/**
	 * Set the value of inBairro
	 */
	public function setInBairro($inBairro): self
	{
		$this->inBairro = $inBairro;

		return $this;
	}

	/**
	 * Get the value of inNomeCidade
	 */
	public function getInNomeCidade()
	{
		return $this->inNomeCidade;
	}

	/**
	 * Set the value of inNomeCidade
	 */
	public function setInNomeCidade($inNomeCidade): self
	{
		$this->inNomeCidade = $inNomeCidade;

		return $this;
	}

	/**
	 * Get the value of inUFCidade
	 */
	public function getInUFCidade()
	{
		return $this->inUFCidade;
	}

	/**
	 * Set the value of inUFCidade
	 */
	public function setInUFCidade($inUFCidade): self
	{
		$this->inUFCidade = $inUFCidade;

		return $this;
	}

	/**
	 * Get the value of inComplemento
	 */
	public function getInComplemento()
	{
		return $this->inComplemento;
	}

	/**
	 * Set the value of inComplemento
	 */
	public function setInComplemento($inComplemento): self
	{
		$this->inComplemento = $inComplemento;

		return $this;
	}

	/**
	 * Get the value of inCep
	 */
	public function getInCep()
	{
		return $this->inCep;
	}

	/**
	 * Set the value of inCep
	 */
	public function setInCep($inCep): self
	{
		$this->inCep = $inCep;

		return $this;
	}

	/**
	 * Get the value of inAreaLogradouro
	 */
	public function getInAreaLogradouro()
	{
		return $this->inAreaLogradouro;
	}

	/**
	 * Set the value of inAreaLogradouro
	 */
	public function setInAreaLogradouro($inAreaLogradouro): self
	{
		$this->inAreaLogradouro = $inAreaLogradouro;

		return $this;
	}

	/**
	 * Get the value of inCodLocalizacaoDiferenciada
	 */
	public function getInCodLocalizacaoDiferenciada()
	{
		return $this->inCodLocalizacaoDiferenciada;
	}

	/**
	 * Set the value of inCodLocalizacaoDiferenciada
	 */
	public function setInCodLocalizacaoDiferenciada($inCodLocalizacaoDiferenciada): self
	{
		$this->inCodLocalizacaoDiferenciada = $inCodLocalizacaoDiferenciada;

		return $this;
	}

	/**
	 * Get the value of inCodMunicipioDNE
	 */
	public function getInCodMunicipioDNE()
	{
		return $this->inCodMunicipioDNE;
	}

	/**
	 * Set the value of inCodMunicipioDNE
	 */
	public function setInCodMunicipioDNE($inCodMunicipioDNE): self
	{
		$this->inCodMunicipioDNE = $inCodMunicipioDNE;

		return $this;
	}

	/**
	 * Get the value of inLatitude
	 */
	public function getInLatitude()
	{
		return $this->inLatitude;
	}

	/**
	 * Set the value of inLatitude
	 */
	public function setInLatitude($inLatitude): self
	{
		$this->inLatitude = $inLatitude;

		return $this;
	}

	/**
	 * Get the value of inLongitude
	 */
	public function getInLongitude()
	{
		return $this->inLongitude;
	}

	/**
	 * Set the value of inLongitude
	 */
	public function setInLongitude($inLongitude): self
	{
		$this->inLongitude = $inLongitude;

		return $this;
	}
}