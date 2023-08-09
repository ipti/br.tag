<?php

class OutIrmaos
{
	public $outNomeAluno;
	public $outDataNascimento;
	public $outNumRA;
	public $outDigitoRA;
	public $outSiglaUFRA;
	public $outGemeo;

	public function __construct(
		?string $outNomeAluno,
		?string $outDataNascimento,
		?string $outNumRA,
		?string $outDigitoRA,
		?string $outSiglaUFRA,
		?string $outGemeo
	) {
		$this->outNomeAluno = $outNomeAluno;
		$this->outDataNascimento = $outDataNascimento;
		$this->outNumRA = $outNumRA;
		$this->outDigitoRA = $outDigitoRA;
		$this->outSiglaUFRA = $outSiglaUFRA;
		$this->outGemeo = $outGemeo;
	}

	public function getOutNomeAluno(): ?string
	{
		return $this->outNomeAluno;
	}

	public function getOutDataNascimento(): ?string
	{
		return $this->outDataNascimento;
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

	public function getOutGemeo(): ?string
	{
		return $this->outGemeo;
	}

	public function setOutNomeAluno(?string $outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;
		return $this;
	}

	public function setOutDataNascimento(?string $outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;
		return $this;
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

	public function setOutGemeo(?string $outGemeo): self
	{
		$this->outGemeo = $outGemeo;
		return $this;
	}

 /**
  * Summary of fromJson
  * @param array $data
  * @return OutIrmaos
  */
	public static function fromJson(array $data): self
    {
        return new self(
            $data['outNomeAluno'] ?? null,
            $data['outDataNascimento'] ?? null,
            $data['outNumRA'] ?? null,
            $data['outDigitoRA'] ?? null,
            $data['outSiglaUFRA'] ?? null,
            $data['outGemeo'] ?? null
        );
    }
}