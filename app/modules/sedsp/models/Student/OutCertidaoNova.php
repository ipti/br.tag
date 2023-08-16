<?php

class OutCertidaoNova
{
	public $outCertMatr01;
	public $outCertMatr02;
	public $outCertMatr03;
	public $outCertMatr04;
	public $outCertMatr05;
	public $outCertMatr06;
	public $outCertMatr07;
	public $outCertMatr08;
	public $outCertMatr09;

	public function __construct(
		?string $outCertMatr01,
		?string $outCertMatr02,
		?string $outCertMatr03,
		?string $outCertMatr04,
		?string $outCertMatr05,
		?string $outCertMatr06,
		?string $outCertMatr07,
		?string $outCertMatr08,
		?string $outCertMatr09
	) {
		$this->outCertMatr01 = $outCertMatr01;
		$this->outCertMatr02 = $outCertMatr02;
		$this->outCertMatr03 = $outCertMatr03;
		$this->outCertMatr04 = $outCertMatr04;
		$this->outCertMatr05 = $outCertMatr05;
		$this->outCertMatr06 = $outCertMatr06;
		$this->outCertMatr07 = $outCertMatr07;
		$this->outCertMatr08 = $outCertMatr08;
		$this->outCertMatr09 = $outCertMatr09;
	}

	public function getOutCertMatr01(): ?string
	{
		return $this->outCertMatr01;
	}

	public function getOutCertMatr02(): ?string
	{
		return $this->outCertMatr02;
	}

	public function getOutCertMatr03(): ?string
	{
		return $this->outCertMatr03;
	}

	public function getOutCertMatr04(): ?string
	{
		return $this->outCertMatr04;
	}

	public function getOutCertMatr05(): ?string
	{
		return $this->outCertMatr05;
	}

	public function getOutCertMatr06(): ?string
	{
		return $this->outCertMatr06;
	}

	public function getOutCertMatr07(): ?string
	{
		return $this->outCertMatr07;
	}

	public function getOutCertMatr08(): ?string
	{
		return $this->outCertMatr08;
	}

	public function getOutCertMatr09(): ?string
	{
		return $this->outCertMatr09;
	}

	public function setOutCertMatr01(?string $outCertMatr01): self
	{
		$this->outCertMatr01 = $outCertMatr01;
		return $this;
	}

	public function setOutCertMatr02(?string $outCertMatr02): self
	{
		$this->outCertMatr02 = $outCertMatr02;
		return $this;
	}

	public function setOutCertMatr03(?string $outCertMatr03): self
	{
		$this->outCertMatr03 = $outCertMatr03;
		return $this;
	}

	public function setOutCertMatr04(?string $outCertMatr04): self
	{
		$this->outCertMatr04 = $outCertMatr04;
		return $this;
	}

	public function setOutCertMatr05(?string $outCertMatr05): self
	{
		$this->outCertMatr05 = $outCertMatr05;
		return $this;
	}

	public function setOutCertMatr06(?string $outCertMatr06): self
	{
		$this->outCertMatr06 = $outCertMatr06;
		return $this;
	}

	public function setOutCertMatr07(?string $outCertMatr07): self
	{
		$this->outCertMatr07 = $outCertMatr07;
		return $this;
	}

	public function setOutCertMatr08(?string $outCertMatr08): self
	{
		$this->outCertMatr08 = $outCertMatr08;
		return $this;
	}

	public function setOutCertMatr09(?string $outCertMatr09): self
	{
		$this->outCertMatr09 = $outCertMatr09;
		return $this;
	}

 /**
  * Summary of fromJson
  * @param array $data
  * @return OutCertidaoNova
  */
	public static function fromJson(array $data): self
    {
        return new self(
            $data['outCertMatr01'] ?? null,
            $data['outCertMatr02'] ?? null,
            $data['outCertMatr03'] ?? null,
            $data['outCertMatr04'] ?? null,
            $data['outCertMatr05'] ?? null,
            $data['outCertMatr06'] ?? null,
            $data['outCertMatr07'] ?? null,
            $data['outCertMatr08'] ?? null,
            $data['outCertMatr09'] ?? null
        );
    }
}