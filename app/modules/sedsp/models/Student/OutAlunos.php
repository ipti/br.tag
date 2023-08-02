<?php

class OutAlunos
{
	public $outNumRA;
	public $outDigitoRA;
	public $outSiglaUFRA;
	public $outNomeAluno;
	public $outNumAluno;
	public $outDataNascimento;
	public $outGrauNivel;
	public $outSerieNivel;
	public $outCodSitMatricula;
	public $outDescSitMatricula;

	public function __construct(
		?string $outNumRA,
		?string $outDigitoRA,
		?string $outSiglaUFRA,
		?string $outNomeAluno,
		?string $outNumAluno,
		?string $outDataNascimento,
		?string $outGrauNivel,
		?string $outSerieNivel,
		?string $outCodSitMatricula,
		?string $outDescSitMatricula
	) {
		$this->outNumRA = $outNumRA;
		$this->outDigitoRA = $outDigitoRA;
		$this->outSiglaUFRA = $outSiglaUFRA;
		$this->outNomeAluno = $outNomeAluno;
		$this->outNumAluno = $outNumAluno;
		$this->outDataNascimento = $outDataNascimento;
		$this->outGrauNivel = $outGrauNivel;
		$this->outSerieNivel = $outSerieNivel;
		$this->outCodSitMatricula = $outCodSitMatricula;
		$this->outDescSitMatricula = $outDescSitMatricula;
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

	public function getOutNumAluno(): ?string
	{
		return $this->outNumAluno;
	}

	public function getOutDataNascimento(): ?string
	{
		return $this->outDataNascimento;
	}

	public function getOutGrauNivel(): ?string
	{
		return $this->outGrauNivel;
	}

	public function getOutSerieNivel(): ?string
	{
		return $this->outSerieNivel;
	}

	public function getOutCodSitMatricula(): ?string
	{
		return $this->outCodSitMatricula;
	}

	public function getOutDescSitMatricula(): ?string
	{
		return $this->outDescSitMatricula;
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

	public function setOutNumAluno(?string $outNumAluno): self
	{
		$this->outNumAluno = $outNumAluno;
		return $this;
	}

	public function setOutDataNascimento(?string $outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;
		return $this;
	}

	public function setOutGrauNivel(?string $outGrauNivel): self
	{
		$this->outGrauNivel = $outGrauNivel;
		return $this;
	}

	public function setOutSerieNivel(?string $outSerieNivel): self
	{
		$this->outSerieNivel = $outSerieNivel;
		return $this;
	}

	public function setOutCodSitMatricula(?string $outCodSitMatricula): self
	{
		$this->outCodSitMatricula = $outCodSitMatricula;
		return $this;
	}

	public function setOutDescSitMatricula(?string $outDescSitMatricula): self
	{
		$this->outDescSitMatricula = $outDescSitMatricula;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNumRA'] ?? null,
			$data['outDigitoRA'] ?? null,
			$data['outSiglaUFRA'] ?? null,
			$data['outNomeAluno'] ?? null,
			$data['outNumAluno'] ?? null,
			$data['outDataNascimento'] ?? null,
			$data['outGrauNivel'] ?? null,
			$data['outSerieNivel'] ?? null,
			$data['outCodSitMatricula'] ?? null,
			$data['outDescSitMatricula'] ?? null
		);
	}	
}