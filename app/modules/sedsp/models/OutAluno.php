<?php

class OutAluno
{
	/**
	 * @var string|null
	 */
	public $outNumRA;

	/**
	 * @var string|null
	 */
	public $outDigitoRA;

	/**
	 * @var string|null
	 */
	public $outSiglaUFRA;

	/**
	 * @var string|null
	 */
	public $outNomeAluno;

	/**
	 * @var string|null
	 */
	public $outNumAluno;

	/**
	 * @var string|null
	 */
	public $outDataNascimento;

	/**
	 * @var string|null
	 */
	public $outGrauNivel;

	/**
	 * @var string|null
	 */
	public $outSerieNivel;

	/**
	 * @var string|null
	 */
	public $outCodSitMatricula;

	/**
	 * @var string|null
	 */
	public $outDescSitMatricula;

	/**
	 * @param string|null $outNumRA
	 * @param string|null $outDigitoRA
	 * @param string|null $outSiglaUFRA
	 * @param string|null $outNomeAluno
	 * @param string|null $outNumAluno
	 * @param string|null $outDataNascimento
	 * @param string|null $outGrauNivel
	 * @param string|null $outSerieNivel
	 * @param string|null $outCodSitMatricula
	 * @param string|null $outDescSitMatricula
	 */
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

	/**
	 * @return string|null
	 */
	public function getOutNumRa(): ?string
	{
		return $this->outNumRA;
	}

	/**
	 * @return string|null
	 */
	public function getOutDigitoRa(): ?string
	{
		return $this->outDigitoRA;
	}

	/**
	 * @return string|null
	 */
	public function getOutSiglaUfra(): ?string
	{
		return $this->outSiglaUFRA;
	}

	/**
	 * @return string|null
	 */
	public function getOutNomeAluno(): ?string
	{
		return $this->outNomeAluno;
	}

	/**
	 * @return string|null
	 */
	public function getOutNumAluno(): ?string
	{
		return $this->outNumAluno;
	}

	/**
	 * @return string|null
	 */
	public function getOutDataNascimento(): ?string
	{
		return $this->outDataNascimento;
	}

	/**
	 * @return string|null
	 */
	public function getOutGrauNivel(): ?string
	{
		return $this->outGrauNivel;
	}

	/**
	 * @return string|null
	 */
	public function getOutSerieNivel(): ?string
	{
		return $this->outSerieNivel;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodSitMatricula(): ?string
	{
		return $this->outCodSitMatricula;
	}

	/**
	 * @return string|null
	 */
	public function getOutDescSitMatricula(): ?string
	{
		return $this->outDescSitMatricula;
	}

	/**
	 * @param string|null $outNumRA
	 * @return self
	 */
	public function setOutNumRa(?string $outNumRA): self
	{
		$this->outNumRA = $outNumRA;
		return $this;
	}

	/**
	 * @param string|null $outDigitoRA
	 * @return self
	 */
	public function setOutDigitoRa(?string $outDigitoRA): self
	{
		$this->outDigitoRA = $outDigitoRA;
		return $this;
	}

	/**
	 * @param string|null $outSiglaUFRA
	 * @return self
	 */
	public function setOutSiglaUfra(?string $outSiglaUFRA): self
	{
		$this->outSiglaUFRA = $outSiglaUFRA;
		return $this;
	}

	/**
	 * @param string|null $outNomeAluno
	 * @return self
	 */
	public function setOutNomeAluno(?string $outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;
		return $this;
	}

	/**
	 * @param string|null $outNumAluno
	 * @return self
	 */
	public function setOutNumAluno(?string $outNumAluno): self
	{
		$this->outNumAluno = $outNumAluno;
		return $this;
	}

	/**
	 * @param string|null $outDataNascimento
	 * @return self
	 */
	public function setOutDataNascimento(?string $outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;
		return $this;
	}

	/**
	 * @param string|null $outGrauNivel
	 * @return self
	 */
	public function setOutGrauNivel(?string $outGrauNivel): self
	{
		$this->outGrauNivel = $outGrauNivel;
		return $this;
	}

	/**
	 * @param string|null $outSerieNivel
	 * @return self
	 */
	public function setOutSerieNivel(?string $outSerieNivel): self
	{
		$this->outSerieNivel = $outSerieNivel;
		return $this;
	}

	/**
	 * @param string|null $outCodSitMatricula
	 * @return self
	 */
	public function setOutCodSitMatricula(?string $outCodSitMatricula): self
	{
		$this->outCodSitMatricula = $outCodSitMatricula;
		return $this;
	}

	/**
	 * @param string|null $outDescSitMatricula
	 * @return self
	 */
	public function setOutDescSitMatricula(?string $outDescSitMatricula): self
	{
		$this->outDescSitMatricula = $outDescSitMatricula;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
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
?>