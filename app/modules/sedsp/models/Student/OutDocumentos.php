<?php

class OutDocumentos
{
    public $outCodINEP;
	public $outCPF;
	public $outNumNIS;
	public $outNumDoctoCivil;
	public $outDigitoDoctoCivil;
	public $outUFDoctoCivil;
	public $outDataEmissaoDoctoCivil;
	public $outDataEmissaoCertidao;

	public function __construct(
		?string $outCodINEP,
		?string $outCPF,
		?string $outNumNIS,
		?string $outNumDoctoCivil,
		?string $outDigitoDoctoCivil,
		?string $outUFDoctoCivil,
		?string $outDataEmissaoDoctoCivil,
		?string $outDataEmissaoCertidao
	) {
		$this->outCodINEP = $outCodINEP;
		$this->outCPF = $outCPF;
		$this->outNumNIS = $outNumNIS;
		$this->outNumDoctoCivil = $outNumDoctoCivil;
		$this->outDigitoDoctoCivil = $outDigitoDoctoCivil;
		$this->outUFDoctoCivil = $outUFDoctoCivil;
		$this->outDataEmissaoDoctoCivil = $outDataEmissaoDoctoCivil;
		$this->outDataEmissaoCertidao = $outDataEmissaoCertidao;
	}

	public function getOutCodInep(): ?string
	{
		return $this->outCodINEP;
	}

	public function getOutCpf(): ?string
	{
		return $this->outCPF;
	}

	public function getOutNumNis(): ?string
	{
		return $this->outNumNIS;
	}

	public function getOutNumDoctoCivil(): ?string
	{
		return $this->outNumDoctoCivil;
	}

	public function getOutDigitoDoctoCivil(): ?string
	{
		return $this->outDigitoDoctoCivil;
	}

	public function getOutUfDoctoCivil(): ?string
	{
		return $this->outUFDoctoCivil;
	}

	public function getOutDataEmissaoDoctoCivil(): ?string
	{
		return $this->outDataEmissaoDoctoCivil;
	}

	public function getOutDataEmissaoCertidao(): ?string
	{
		return $this->outDataEmissaoCertidao;
	}

	public function setOutCodInep(?string $outCodINEP): self
	{
		$this->outCodINEP = $outCodINEP;
		return $this;
	}

	public function setOutCpf(?string $outCPF): self
	{
		$this->outCPF = $outCPF;
		return $this;
	}

	public function setOutNumNis(?string $outNumNIS): self
	{
		$this->outNumNIS = $outNumNIS;
		return $this;
	}

	public function setOutNumDoctoCivil(?string $outNumDoctoCivil): self
	{
		$this->outNumDoctoCivil = $outNumDoctoCivil;
		return $this;
	}

	public function setOutDigitoDoctoCivil(?string $outDigitoDoctoCivil): self
	{
		$this->outDigitoDoctoCivil = $outDigitoDoctoCivil;
		return $this;
	}

	public function setOutUfDoctoCivil(?string $outUFDoctoCivil): self
	{
		$this->outUFDoctoCivil = $outUFDoctoCivil;
		return $this;
	}

	public function setOutDataEmissaoDoctoCivil(?string $outDataEmissaoDoctoCivil): self
	{
		$this->outDataEmissaoDoctoCivil = $outDataEmissaoDoctoCivil;
		return $this;
	}

	public function setOutDataEmissaoCertidao(?string $outDataEmissaoCertidao): self
	{
		$this->outDataEmissaoCertidao = $outDataEmissaoCertidao;
		return $this;
	}

    /**
     * Summary of fromJson
     * @param array $data
     * @return OutDocumentos
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['outCodINEP'] ?? null,
            $data['outCPF'] ?? null,
            $data['outNumNIS'] ?? null,
            $data['outNumDoctoCivil'] ?? null,
            $data['outDigitoDoctoCivil'] ?? null,
            $data['outUFDoctoCivil'] ?? null,
            $data['outDataEmissaoDoctoCivil'] ?? null,
            $data['outDataEmissaoCertidao'] ?? null
        );
    }
}