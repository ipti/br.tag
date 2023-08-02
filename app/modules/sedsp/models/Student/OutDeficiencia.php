<?php

class OutDeficiencia
{
	public $outMobilidadeReduzida;
	public $outDescMobilidadeReduzida;
	public $outCuidador;
	public $outDescCuidador;
	public $outProfSaude;
	public $outDescProfSaude;

	public function __construct(
		string $outMobilidadeReduzida,
		string $outDescMobilidadeReduzida,
		string $outCuidador,
		string $outDescCuidador,
		string $outProfSaude,
		string $outDescProfSaude
	) {
		$this->outMobilidadeReduzida = $outMobilidadeReduzida;
		$this->outDescMobilidadeReduzida = $outDescMobilidadeReduzida;
		$this->outCuidador = $outCuidador;
		$this->outDescCuidador = $outDescCuidador;
		$this->outProfSaude = $outProfSaude;
		$this->outDescProfSaude = $outDescProfSaude;
	}

	/**
	 * Get the value of outMobilidadeReduzida
	 */
	public function getOutMobilidadeReduzida()
	{
		return $this->outMobilidadeReduzida;
	}

	/**
	 * Set the value of outMobilidadeReduzida
	 */
	public function setOutMobilidadeReduzida($outMobilidadeReduzida): self
	{
		$this->outMobilidadeReduzida = $outMobilidadeReduzida;

		return $this;
	}

	/**
	 * Get the value of outDescMobilidadeReduzida
	 */
	public function getOutDescMobilidadeReduzida()
	{
		return $this->outDescMobilidadeReduzida;
	}

	/**
	 * Set the value of outDescMobilidadeReduzida
	 */
	public function setOutDescMobilidadeReduzida($outDescMobilidadeReduzida): self
	{
		$this->outDescMobilidadeReduzida = $outDescMobilidadeReduzida;

		return $this;
	}

	/**
	 * Get the value of outCuidador
	 */
	public function getOutCuidador()
	{
		return $this->outCuidador;
	}

	/**
	 * Set the value of outCuidador
	 */
	public function setOutCuidador($outCuidador): self
	{
		$this->outCuidador = $outCuidador;

		return $this;
	}

	/**
	 * Get the value of outDescCuidador
	 */
	public function getOutDescCuidador()
	{
		return $this->outDescCuidador;
	}

	/**
	 * Set the value of outDescCuidador
	 */
	public function setOutDescCuidador($outDescCuidador): self
	{
		$this->outDescCuidador = $outDescCuidador;

		return $this;
	}

	/**
	 * Get the value of outProfSaude
	 */
	public function getOutProfSaude()
	{
		return $this->outProfSaude;
	}

	/**
	 * Set the value of outProfSaude
	 */
	public function setOutProfSaude($outProfSaude): self
	{
		$this->outProfSaude = $outProfSaude;

		return $this;
	}

	/**
	 * Get the value of outDescProfSaude
	 */
	public function getOutDescProfSaude()
	{
		return $this->outDescProfSaude;
	}

	/**
	 * Set the value of outDescProfSaude
	 */
	public function setOutDescProfSaude($outDescProfSaude): self
	{
		$this->outDescProfSaude = $outDescProfSaude;

		return $this;
	}
}