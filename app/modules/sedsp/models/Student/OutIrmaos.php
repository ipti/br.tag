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
		string $outNomeAluno,
		string $outDataNascimento,
		string $outNumRA,
		string $outDigitoRA,
		string $outSiglaUFRA,
		string $outGemeo
	) {
		$this->outNomeAluno = $outNomeAluno;
		$this->outDataNascimento = $outDataNascimento;
		$this->outNumRA = $outNumRA;
		$this->outDigitoRA = $outDigitoRA;
		$this->outSiglaUFRA = $outSiglaUFRA;
		$this->outGemeo = $outGemeo;
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
	 * Get the value of outGemeo
	 */
	public function getOutGemeo()
	{
		return $this->outGemeo;
	}

	/**
	 * Set the value of outGemeo
	 */
	public function setOutGemeo($outGemeo): self
	{
		$this->outGemeo = $outGemeo;

		return $this;
	}
}