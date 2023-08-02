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
		string $outNumRA,
		string $outDigitoRA,
		string $outSiglaUFRA,
		string $outNomeAluno,
		string $outNumAluno,
		string $outDataNascimento,
		string $outGrauNivel,
		string $outSerieNivel,
		string $outCodSitMatricula,
		string $outDescSitMatricula
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
	 * Get the value of outNumRA
	 */
	public function getOutNumRA()
	{
		return $this->outNumRA;
	}

	/**
	 * Set the value of outNumRA
	 */
	public function setOutNumRA($outNumRA): self
	{
		$this->outNumRA = $outNumRA;

		return $this;
	}

	/**
	 * Get the value of outDigitoRA
	 */
	public function getOutDigitoRA()
	{
		return $this->outDigitoRA;
	}

	/**
	 * Set the value of outDigitoRA
	 */
	public function setOutDigitoRA($outDigitoRA): self
	{
		$this->outDigitoRA = $outDigitoRA;

		return $this;
	}

	/**
	 * Get the value of outSiglaUFRA
	 */
	public function getOutSiglaUFRA()
	{
		return $this->outSiglaUFRA;
	}

	/**
	 * Set the value of outSiglaUFRA
	 */
	public function setOutSiglaUFRA($outSiglaUFRA): self
	{
		$this->outSiglaUFRA = $outSiglaUFRA;

		return $this;
	}

	/**
	 * Get the value of outNomeAluno
	 */
	public function getOutNomeAluno()
	{
		return $this->outNomeAluno;
	}

	/**
	 * Set the value of outNomeAluno
	 */
	public function setOutNomeAluno($outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;

		return $this;
	}

	/**
	 * Get the value of outNumAluno
	 */
	public function getOutNumAluno()
	{
		return $this->outNumAluno;
	}

	/**
	 * Set the value of outNumAluno
	 */
	public function setOutNumAluno($outNumAluno): self
	{
		$this->outNumAluno = $outNumAluno;

		return $this;
	}

	/**
	 * Get the value of outDataNascimento
	 */
	public function getOutDataNascimento()
	{
		return $this->outDataNascimento;
	}

	/**
	 * Set the value of outDataNascimento
	 */
	public function setOutDataNascimento($outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;

		return $this;
	}

	/**
	 * Get the value of outGrauNivel
	 */
	public function getOutGrauNivel()
	{
		return $this->outGrauNivel;
	}

	/**
	 * Set the value of outGrauNivel
	 */
	public function setOutGrauNivel($outGrauNivel): self
	{
		$this->outGrauNivel = $outGrauNivel;

		return $this;
	}

	/**
	 * Get the value of outSerieNivel
	 */
	public function getOutSerieNivel()
	{
		return $this->outSerieNivel;
	}

	/**
	 * Set the value of outSerieNivel
	 */
	public function setOutSerieNivel($outSerieNivel): self
	{
		$this->outSerieNivel = $outSerieNivel;

		return $this;
	}

	/**
	 * Get the value of outCodSitMatricula
	 */
	public function getOutCodSitMatricula()
	{
		return $this->outCodSitMatricula;
	}

	/**
	 * Set the value of outCodSitMatricula
	 */
	public function setOutCodSitMatricula($outCodSitMatricula): self
	{
		$this->outCodSitMatricula = $outCodSitMatricula;

		return $this;
	}

	/**
	 * Get the value of outDescSitMatricula
	 */
	public function getOutDescSitMatricula()
	{
		return $this->outDescSitMatricula;
	}

	/**
	 * Set the value of outDescSitMatricula
	 */
	public function setOutDescSitMatricula($outDescSitMatricula): self
	{
		$this->outDescSitMatricula = $outDescSitMatricula;

		return $this;
	}
}