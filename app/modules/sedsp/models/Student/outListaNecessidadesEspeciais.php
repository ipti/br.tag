<?php

class OutListaNecessidadesEspeciais
{
	public $outCodNecesEspecial;
	public $outNomeNecesEspecial;

	public function __construct(string $outCodNecesEspecial, string $outNomeNecesEspecial)
	{
		$this->outCodNecesEspecial = $outCodNecesEspecial;
		$this->outNomeNecesEspecial = $outNomeNecesEspecial;
	}

	/**
	 * Get the value of outCodNecesEspecial
	 */
	public function getOutCodNecesEspecial()
	{
		return $this->outCodNecesEspecial;
	}

	/**
	 * Set the value of outCodNecesEspecial
	 */
	public function setOutCodNecesEspecial($outCodNecesEspecial): self
	{
		$this->outCodNecesEspecial = $outCodNecesEspecial;

		return $this;
	}

	/**
	 * Get the value of outNomeNecesEspecial
	 */
	public function getOutNomeNecesEspecial()
	{
		return $this->outNomeNecesEspecial;
	}

	/**
	 * Set the value of outNomeNecesEspecial
	 */
	public function setOutNomeNecesEspecial($outNomeNecesEspecial): self
	{
		$this->outNomeNecesEspecial = $outNomeNecesEspecial;

		return $this;
	}
}