<?php 

class InDeficiencia implements JsonSerializable
{
	public $inCodNecessidade;
	public $inMobilidadeReduzida;
	public $inTipoMobilidadeReduzida;
	public $inCuidador;
	public $inTipoCuidador;
	public $inProfSaude;
	public $inTipoProfSaude;

	public function __construct(
		string $inCodNecessidade,
		int $inMobilidadeReduzida,
		string $inTipoMobilidadeReduzida,
		int $inCuidador,
		string $inTipoCuidador,
		int $inProfSaude,
		string $inTipoProfSaude
	) {
		$this->inCodNecessidade = $inCodNecessidade;
		$this->inMobilidadeReduzida = $inMobilidadeReduzida;
		$this->inTipoMobilidadeReduzida = $inTipoMobilidadeReduzida;
		$this->inCuidador = $inCuidador;
		$this->inTipoCuidador = $inTipoCuidador;
		$this->inProfSaude = $inProfSaude;
		$this->inTipoProfSaude = $inTipoProfSaude;
	}

	/**
	 * Get the value of inCodNecessidade
	 */
	public function getInCodNecessidade()
	{
		return $this->inCodNecessidade;
	}

	/**
	 * Set the value of inCodNecessidade
	 */
	public function setInCodNecessidade($inCodNecessidade): self
	{
		$this->inCodNecessidade = $inCodNecessidade;

		return $this;
	}

	/**
	 * Get the value of inMobilidadeReduzida
	 */
	public function getInMobilidadeReduzida()
	{
		return $this->inMobilidadeReduzida;
	}

	/**
	 * Set the value of inMobilidadeReduzida
	 */
	public function setInMobilidadeReduzida($inMobilidadeReduzida): self
	{
		$this->inMobilidadeReduzida = $inMobilidadeReduzida;

		return $this;
	}

	/**
	 * Get the value of inTipoMobilidadeReduzida
	 */
	public function getInTipoMobilidadeReduzida()
	{
		return $this->inTipoMobilidadeReduzida;
	}

	/**
	 * Set the value of inTipoMobilidadeReduzida
	 */
	public function setInTipoMobilidadeReduzida($inTipoMobilidadeReduzida): self
	{
		$this->inTipoMobilidadeReduzida = $inTipoMobilidadeReduzida;

		return $this;
	}

	/**
	 * Get the value of inCuidador
	 */
	public function getInCuidador()
	{
		return $this->inCuidador;
	}

	/**
	 * Set the value of inCuidador
	 */
	public function setInCuidador($inCuidador): self
	{
		$this->inCuidador = $inCuidador;

		return $this;
	}

	/**
	 * Get the value of inTipoCuidador
	 */
	public function getInTipoCuidador()
	{
		return $this->inTipoCuidador;
	}

	/**
	 * Set the value of inTipoCuidador
	 */
	public function setInTipoCuidador($inTipoCuidador): self
	{
		$this->inTipoCuidador = $inTipoCuidador;

		return $this;
	}

	/**
	 * Get the value of inProfSaude
	 */
	public function getInProfSaude()
	{
		return $this->inProfSaude;
	}

	/**
	 * Set the value of inProfSaude
	 */
	public function setInProfSaude($inProfSaude): self
	{
		$this->inProfSaude = $inProfSaude;

		return $this;
	}

	/**
	 * Get the value of inTipoProfSaude
	 */
	public function getInTipoProfSaude()
	{
		return $this->inTipoProfSaude;
	}

	/**
	 * Set the value of inTipoProfSaude
	 */
	public function setInTipoProfSaude($inTipoProfSaude): self
	{
		$this->inTipoProfSaude = $inTipoProfSaude;

		return $this;
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}

