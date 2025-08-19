<?php

class CertidaoNasc
{
	public $inLivroCertidaoNasc;
	public $inFolhaCertidaoNasc;
	public $inNumCertidaoNasc;

	public function __construct(
		?string $inLivroCertidaoNasc,
		?string $inFolhaCertidaoNasc,
		?string $inNumCertidaoNasc
	) {
		$this->inLivroCertidaoNasc = $inLivroCertidaoNasc;
		$this->inFolhaCertidaoNasc = $inFolhaCertidaoNasc;
		$this->inNumCertidaoNasc = $inNumCertidaoNasc;
	}

	public function getInLivroCertidaoNasc(): ?string
	{
		return $this->inLivroCertidaoNasc;
	}

	public function getInFolhaCertidaoNasc(): ?string
	{
		return $this->inFolhaCertidaoNasc;
	}

	public function getInNumCertidaoNasc(): ?string
	{
		return $this->inNumCertidaoNasc;
	}

	public function setInLivroCertidaoNasc(?string $inLivroCertidaoNasc): self
	{
		$this->inLivroCertidaoNasc = $inLivroCertidaoNasc;
		return $this;
	}

	public function setInFolhaCertidaoNasc(?string $inFolhaCertidaoNasc): self
	{
		$this->inFolhaCertidaoNasc = $inFolhaCertidaoNasc;
		return $this;
	}

	public function setInNumCertidaoNasc(?string $inNumCertidaoNasc): self
	{
		$this->inNumCertidaoNasc = $inNumCertidaoNasc;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inLivroCertidaoNasc'] ?? null,
			$data['inFolhaCertidaoNasc'] ?? null,
			$data['inNumCertidaoNasc'] ?? null
		);
	}
}