<?php

class InDocumentos implements JsonSerializable
{
    public $inNumRG;
	public $inDigitoRG;
	public $inUFRG;
	public $inCPF;
	public $inNumNIS;
	public $inNumINEP;
	public $inNumCertidaoNova;
	public $CertidaoNasc;

	public function __construct(
		?string $inNumRG,
		?string $inDigitoRG,
		?string $inUFRG,
		?string $inCPF,
		?string $inNumNIS,
		?string $inNumINEP,
		?string $inNumCertidaoNova,
		?CertidaoNasc $CertidaoNasc
	) {
		$this->inNumRG = $inNumRG;
		$this->inDigitoRG = $inDigitoRG;
		$this->inUFRG = $inUFRG;
		$this->inCPF = $inCPF;
		$this->inNumNIS = $inNumNIS;
		$this->inNumINEP = $inNumINEP;
		$this->inNumCertidaoNova = $inNumCertidaoNova;
		$this->CertidaoNasc = $CertidaoNasc;
	}

	public function getInNumRg(): ?string
	{
		return $this->inNumRG;
	}

	public function getInDigitoRg(): ?string
	{
		return $this->inDigitoRG;
	}

	public function getInUfrg(): ?string
	{
		return $this->inUFRG;
	}

	public function getInCpf(): ?string
	{
		return $this->inCPF;
	}

	public function getInNumNis(): ?string
	{
		return $this->inNumNIS;
	}

	public function getInNumInep(): ?string
	{
		return $this->inNumINEP;
	}

	public function getInNumCertidaoNova(): ?string
	{
		return $this->inNumCertidaoNova;
	}

	public function getCertidaoNasc(): ?CertidaoNasc
	{
		return $this->CertidaoNasc;
	}

	public function setInNumRg(?string $inNumRG): self
	{
		$this->inNumRG = $inNumRG;
		return $this;
	}

	public function setInDigitoRg(?string $inDigitoRG): self
	{
		$this->inDigitoRG = $inDigitoRG;
		return $this;
	}

	public function setInUfrg(?string $inUFRG): self
	{
		$this->inUFRG = $inUFRG;
		return $this;
	}

	public function setInCpf(?string $inCPF): self
	{
		$this->inCPF = $inCPF;
		return $this;
	}

	public function setInNumNis(?string $inNumNIS): self
	{
		$this->inNumNIS = $inNumNIS;
		return $this;
	}

	public function setInNumInep(?string $inNumINEP): self
	{
		$this->inNumINEP = $inNumINEP;
		return $this;
	}

	public function setInNumCertidaoNova(?string $inNumCertidaoNova): self
	{
		$this->inNumCertidaoNova = $inNumCertidaoNova;
		return $this;
	}

	public function setCertidaoNasc(?CertidaoNasc $CertidaoNasc): self
	{
		$this->CertidaoNasc = $CertidaoNasc;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNumRG'] ?? null,
			$data['inDigitoRG'] ?? null,
			$data['inUFRG'] ?? null,
			$data['inCPF'] ?? null,
			$data['inNumNIS'] ?? null,
			$data['inNumINEP'] ?? null,
			$data['inNumCertidaoNova'] ?? null,
			($data['CertidaoNasc'] ?? null) !== null ? CertidaoNasc::fromJson($data['CertidaoNasc']) : null
		);
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}