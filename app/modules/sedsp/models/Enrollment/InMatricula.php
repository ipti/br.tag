<?php

class InMatricula implements JsonSerializable
{
	public $inAnoLetivo;
	public $inDataTroca;
	public $inNumAluno;
	public $inNumClasseOrigem;
	public $inNumClasseDestino;

	public function __construct(
		string $inAnoLetivo,
		string $inDataTroca,
		string $inNumAluno,
		string $inNumClasseOrigem,
		string $inNumClasseDestino
	) {
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inDataTroca = $inDataTroca;
		$this->inNumAluno = $inNumAluno;
		$this->inNumClasseOrigem = $inNumClasseOrigem;
		$this->inNumClasseDestino = $inNumClasseDestino;
	}

	/**
	 * Get the value of inAnoLetivo
	 */
	public function getInAnoLetivo()
	{
		return $this->inAnoLetivo;
	}

	/**
	 * Set the value of inAnoLetivo
	 */
	public function setInAnoLetivo($inAnoLetivo): self
	{
		$this->inAnoLetivo = $inAnoLetivo;

		return $this;
	}

	/**
	 * Get the value of inDataTroca
	 */
	public function getInDataTroca()
	{
		return $this->inDataTroca;
	}

	/**
	 * Set the value of inDataTroca
	 */
	public function setInDataTroca($inDataTroca): self
	{
		$this->inDataTroca = $inDataTroca;

		return $this;
	}

	/**
	 * Get the value of inNumAluno
	 */
	public function getInNumAluno()
	{
		return $this->inNumAluno;
	}

	/**
	 * Set the value of inNumAluno
	 */
	public function setInNumAluno($inNumAluno): self
	{
		$this->inNumAluno = $inNumAluno;

		return $this;
	}

	/**
	 * Get the value of inNumClasseOrigem
	 */
	public function getInNumClasseOrigem()
	{
		return $this->inNumClasseOrigem;
	}

	/**
	 * Set the value of inNumClasseOrigem
	 */
	public function setInNumClasseOrigem($inNumClasseOrigem): self
	{
		$this->inNumClasseOrigem = $inNumClasseOrigem;

		return $this;
	}

	/**
	 * Get the value of inNumClasseDestino
	 */
	public function getInNumClasseDestino()
	{
		return $this->inNumClasseDestino;
	}

	/**
	 * Set the value of inNumClasseDestino
	 */
	public function setInNumClasseDestino($inNumClasseDestino): self
	{
		$this->inNumClasseDestino = $inNumClasseDestino;

		return $this;
	}

	function jsonSerialize() {
        return get_object_vars($this);
    }
}