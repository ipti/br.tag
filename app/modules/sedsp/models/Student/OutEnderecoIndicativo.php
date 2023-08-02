<?php

class OutEnderecoIndicativo
{
	public $outLogradouro;
	public $outNumero;
	public $outBairro;
	public $outNomeCidade;
	public $outLatitude;
	public $outLongitude;
	public $outCep;

	public function __construct(
		string $outLogradouro,
		string $outNumero,
		string $outBairro,
		string $outNomeCidade,
		string $outLatitude,
		string $outLongitude,
		string $outCep
	) {
		$this->outLogradouro = $outLogradouro;
		$this->outNumero = $outNumero;
		$this->outBairro = $outBairro;
		$this->outNomeCidade = $outNomeCidade;
		$this->outLatitude = $outLatitude;
		$this->outLongitude = $outLongitude;
		$this->outCep = $outCep;
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
}