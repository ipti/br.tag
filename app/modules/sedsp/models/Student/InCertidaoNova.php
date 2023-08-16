<?php 

class InCertidaoNova implements JsonSerializable
{
	public $inCertMatr01;
	public $inCertMatr02;
	public $inCertMatr03;
	public $inCertMatr04;
	public $inCertMatr05;
	public $inCertMatr06;
	public $inCertMatr07;
	public $inCertMatr08;
	public $inCertMatr09;
	public $inDataEmissaoCertidao;

	public function __construct(
		?string $inCertMatr01,
		?string $inCertMatr02,
		?string $inCertMatr03,
		?string $inCertMatr04,
		?string $inCertMatr05,
		?string $inCertMatr06,
		?string $inCertMatr07,
		?string $inCertMatr08,
		?string $inCertMatr09,
		?string $inDataEmissaoCertidao
	) {
		$this->inCertMatr01 = $inCertMatr01;
		$this->inCertMatr02 = $inCertMatr02;
		$this->inCertMatr03 = $inCertMatr03;
		$this->inCertMatr04 = $inCertMatr04;
		$this->inCertMatr05 = $inCertMatr05;
		$this->inCertMatr06 = $inCertMatr06;
		$this->inCertMatr07 = $inCertMatr07;
		$this->inCertMatr08 = $inCertMatr08;
		$this->inCertMatr09 = $inCertMatr09;
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
	}

	public function getInCertMatr01(): ?string
	{
		return $this->inCertMatr01;
	}

	public function getInCertMatr02(): ?string
	{
		return $this->inCertMatr02;
	}

	public function getInCertMatr03(): ?string
	{
		return $this->inCertMatr03;
	}

	public function getInCertMatr04(): ?string
	{
		return $this->inCertMatr04;
	}

	public function getInCertMatr05(): ?string
	{
		return $this->inCertMatr05;
	}

	public function getInCertMatr06(): ?string
	{
		return $this->inCertMatr06;
	}

	public function getInCertMatr07(): ?string
	{
		return $this->inCertMatr07;
	}

	public function getInCertMatr08(): ?string
	{
		return $this->inCertMatr08;
	}

	public function getInCertMatr09(): ?string
	{
		return $this->inCertMatr09;
	}

	public function getInDataEmissaoCertidao(): ?string
	{
		return $this->inDataEmissaoCertidao;
	}

	public function setInCertMatr01(?string $inCertMatr01): self
	{
		$this->inCertMatr01 = $inCertMatr01;
		return $this;
	}

	public function setInCertMatr02(?string $inCertMatr02): self
	{
		$this->inCertMatr02 = $inCertMatr02;
		return $this;
	}

	public function setInCertMatr03(?string $inCertMatr03): self
	{
		$this->inCertMatr03 = $inCertMatr03;
		return $this;
	}

	public function setInCertMatr04(?string $inCertMatr04): self
	{
		$this->inCertMatr04 = $inCertMatr04;
		return $this;
	}

	public function setInCertMatr05(?string $inCertMatr05): self
	{
		$this->inCertMatr05 = $inCertMatr05;
		return $this;
	}

	public function setInCertMatr06(?string $inCertMatr06): self
	{
		$this->inCertMatr06 = $inCertMatr06;
		return $this;
	}

	public function setInCertMatr07(?string $inCertMatr07): self
	{
		$this->inCertMatr07 = $inCertMatr07;
		return $this;
	}

	public function setInCertMatr08(?string $inCertMatr08): self
	{
		$this->inCertMatr08 = $inCertMatr08;
		return $this;
	}

	public function setInCertMatr09(?string $inCertMatr09): self
	{
		$this->inCertMatr09 = $inCertMatr09;
		return $this;
	}

	public function setInDataEmissaoCertidao(?string $inDataEmissaoCertidao): self
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inCertMatr01'] ?? null,
			$data['inCertMatr02'] ?? null,
			$data['inCertMatr03'] ?? null,
			$data['inCertMatr04'] ?? null,
			$data['inCertMatr05'] ?? null,
			$data['inCertMatr06'] ?? null,
			$data['inCertMatr07'] ?? null,
			$data['inCertMatr08'] ?? null,
			$data['inCertMatr09'] ?? null,
			$data['inDataEmissaoCertidao'] ?? null
		);
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
