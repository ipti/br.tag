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
		?string $outLogradouro,
		?string $outNumero,
		?string $outBairro,
		?string $outNomeCidade,
		?string $outLatitude,
		?string $outLongitude,
		?string $outCep
	) {
		$this->outLogradouro = $outLogradouro;
		$this->outNumero = $outNumero;
		$this->outBairro = $outBairro;
		$this->outNomeCidade = $outNomeCidade;
		$this->outLatitude = $outLatitude;
		$this->outLongitude = $outLongitude;
		$this->outCep = $outCep;
	}

	public function getOutLogradouro(): ?string
	{
		return $this->outLogradouro;
	}

	public function getOutNumero(): ?string
	{
		return $this->outNumero;
	}

	public function getOutBairro(): ?string
	{
		return $this->outBairro;
	}

	public function getOutNomeCidade(): ?string
	{
		return $this->outNomeCidade;
	}

	public function getOutLatitude(): ?string
	{
		return $this->outLatitude;
	}

	public function getOutLongitude(): ?string
	{
		return $this->outLongitude;
	}

	public function getOutCep(): ?string
	{
		return $this->outCep;
	}

	public function setOutLogradouro(?string $outLogradouro): self
	{
		$this->outLogradouro = $outLogradouro;
		return $this;
	}

	public function setOutNumero(?string $outNumero): self
	{
		$this->outNumero = $outNumero;
		return $this;
	}

	public function setOutBairro(?string $outBairro): self
	{
		$this->outBairro = $outBairro;
		return $this;
	}

	public function setOutNomeCidade(?string $outNomeCidade): self
	{
		$this->outNomeCidade = $outNomeCidade;
		return $this;
	}

	public function setOutLatitude(?string $outLatitude): self
	{
		$this->outLatitude = $outLatitude;
		return $this;
	}

	public function setOutLongitude(?string $outLongitude): self
	{
		$this->outLongitude = $outLongitude;
		return $this;
	}

	public function setOutCep(?string $outCep): self
	{
		$this->outCep = $outCep;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outLogradouro'] ?? null,
			$data['outNumero'] ?? null,
			$data['outBairro'] ?? null,
			$data['outNomeCidade'] ?? null,
			$data['outLatitude'] ?? null,
			$data['outLongitude'] ?? null,
			$data['outCep'] ?? null
		);
	}
}