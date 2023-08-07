<?php

class OutAluno
{
	public $outNumRA;
	public $outDigitoRA;
	public $outSiglaUFRA;
	public $outNomeAluno;
	public $outNomeMae;

	public function __construct(
		?string $outNumRA,
		?string $outDigitoRA,
		?string $outSiglaUFRA,
		?string $outNomeAluno,
		?string $outNomeMae
	) {
		$this->outNumRA = $outNumRA;
		$this->outDigitoRA = $outDigitoRA;
		$this->outSiglaUFRA = $outSiglaUFRA;
		$this->outNomeAluno = $outNomeAluno;
		$this->outNomeMae = $outNomeMae;
	}

	public function getOutNumRa(): ?string
	{
		return $this->outNumRA;
	}

	public function getOutDigitoRa(): ?string
	{
		return $this->outDigitoRA;
	}

	public function getOutSiglaUfra(): ?string
	{
		return $this->outSiglaUFRA;
	}

	public function getOutNomeAluno(): ?string
	{
		return $this->outNomeAluno;
	}

	public function getOutNomeMae(): ?string
	{
		return $this->outNomeMae;
	}

	public function setOutNumRa(?string $outNumRA): self
	{
		$this->outNumRA = $outNumRA;
		return $this;
	}

	public function setOutDigitoRa(?string $outDigitoRA): self
	{
		$this->outDigitoRA = $outDigitoRA;
		return $this;
	}

	public function setOutSiglaUfra(?string $outSiglaUFRA): self
	{
		$this->outSiglaUFRA = $outSiglaUFRA;
		return $this;
	}

	public function setOutNomeAluno(?string $outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;
		return $this;
	}

	public function setOutNomeMae(?string $outNomeMae): self
	{
		$this->outNomeMae = $outNomeMae;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNumRA'] ?? null,
			$data['outDigitoRA'] ?? null,
			$data['outSiglaUFRA'] ?? null,
			$data['outNomeAluno'] ?? null,
			$data['outNomeMae'] ?? null
		);
	}
}
