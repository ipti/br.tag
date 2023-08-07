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
		?string $inLogradouro,
		?string $inNumero,
		?string $inBairro,
		?string $inNomeCidade,
		?string $inUFCidade,
		?string $inComplemento,
		?string $inCep,
		?string $inAreaLogradouro,
		?string $inCodLocalizacaoDiferenciada,
		?string $inCodMunicipioDNE,
		?string $inLatitude,
		?string $inLongitude
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

	public function getInLogradouro(): ?string
	{
		return $this->inLogradouro;
	}

	public function getInNumero(): ?string
	{
		return $this->inNumero;
	}

	public function getInBairro(): ?string
	{
		return $this->inBairro;
	}

	public function getInNomeCidade(): ?string
	{
		return $this->inNomeCidade;
	}

	public function getInUfCidade(): ?string
	{
		return $this->inUFCidade;
	}

	public function getInComplemento(): ?string
	{
		return $this->inComplemento;
	}

	public function getInCep(): ?string
	{
		return $this->inCep;
	}

	public function getInAreaLogradouro(): ?string
	{
		return $this->inAreaLogradouro;
	}

	public function getInCodLocalizacaoDiferenciada(): ?string
	{
		return $this->inCodLocalizacaoDiferenciada;
	}

	public function getInCodMunicipioDne(): ?string
	{
		return $this->inCodMunicipioDNE;
	}

	public function getInLatitude(): ?string
	{
		return $this->inLatitude;
	}

	public function getInLongitude(): ?string
	{
		return $this->inLongitude;
	}

	public function setInLogradouro(?string $inLogradouro): self
	{
		$this->inLogradouro = $inLogradouro;
		return $this;
	}

	public function setInNumero(?string $inNumero): self
	{
		$this->inNumero = $inNumero;
		return $this;
	}

	public function setInBairro(?string $inBairro): self
	{
		$this->inBairro = $inBairro;
		return $this;
	}

	public function setInNomeCidade(?string $inNomeCidade): self
	{
		$this->inNomeCidade = $inNomeCidade;
		return $this;
	}

	public function setInUfCidade(?string $inUFCidade): self
	{
		$this->inUFCidade = $inUFCidade;
		return $this;
	}

	public function setInComplemento(?string $inComplemento): self
	{
		$this->inComplemento = $inComplemento;
		return $this;
	}

	public function setInCep(?string $inCep): self
	{
		$this->inCep = $inCep;
		return $this;
	}

	public function setInAreaLogradouro(?string $inAreaLogradouro): self
	{
		$this->inAreaLogradouro = $inAreaLogradouro;
		return $this;
	}

	public function setInCodLocalizacaoDiferenciada(?string $inCodLocalizacaoDiferenciada): self
	{
		$this->inCodLocalizacaoDiferenciada = $inCodLocalizacaoDiferenciada;
		return $this;
	}

	public function setInCodMunicipioDne(?string $inCodMunicipioDNE): self
	{
		$this->inCodMunicipioDNE = $inCodMunicipioDNE;
		return $this;
	}

	public function setInLatitude(?string $inLatitude): self
	{
		$this->inLatitude = $inLatitude;
		return $this;
	}

	public function setInLongitude(?string $inLongitude): self
	{
		$this->inLongitude = $inLongitude;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inLogradouro'] ?? null,
			$data['inNumero'] ?? null,
			$data['inBairro'] ?? null,
			$data['inNomeCidade'] ?? null,
			$data['inUFCidade'] ?? null,
			$data['inComplemento'] ?? null,
			$data['inCep'] ?? null,
			$data['inAreaLogradouro'] ?? null,
			$data['inCodLocalizacaoDiferenciada'] ?? null,
			$data['inCodMunicipioDNE'] ?? null,
			$data['inLatitude'] ?? null,
			$data['inLongitude'] ?? null
		);
	}
	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}