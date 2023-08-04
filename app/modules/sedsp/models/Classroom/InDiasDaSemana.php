<?php

class InDiasDaSemana implements JsonSerializable
{
	public $inFlagSegunda;
	public $inHoraIniAulaSegunda;
	public $inHoraFimAulaSegunda;
	public $inFlagTerca;
	public $inHoraIniAulaTerca;
	public $inHoraFimAulaTerca;
	public $inFlagQuarta;
	public $inHoraIniAulaQuarta;
	public $inHoraFimAulaQuarta;
	public $inFlagQuinta;
	public $inHoraIniAulaQuinta;
	public $inHoraFimAulaQuinta;
	public $inFlagSexta;
	public $inHoraIniAulaSexta;
	public $inHoraFimAulaSexta;
	public $inFlagSabado;
	public $inHoraIniAulaSabado;
	public $inHoraFimAulaSabado;

	public function __construct(
		string $inFlagSegunda,
		string $inHoraIniAulaSegunda,
		string $inHoraFimAulaSegunda,
		string $inFlagTerca,
		string $inHoraIniAulaTerca,
		string $inHoraFimAulaTerca,
		string $inFlagQuarta,
		string $inHoraIniAulaQuarta,
		string $inHoraFimAulaQuarta,
		string $inFlagQuinta,
		string $inHoraIniAulaQuinta,
		string $inHoraFimAulaQuinta,
		string $inFlagSexta,
		string $inHoraIniAulaSexta,
		string $inHoraFimAulaSexta,
		string $inFlagSabado,
		string $inHoraIniAulaSabado,
		string $inHoraFimAulaSabado
	) {
		$this->inFlagSegunda = $inFlagSegunda;
		$this->inHoraIniAulaSegunda = $inHoraIniAulaSegunda;
		$this->inHoraFimAulaSegunda = $inHoraFimAulaSegunda;
		$this->inFlagTerca = $inFlagTerca;
		$this->inHoraIniAulaTerca = $inHoraIniAulaTerca;
		$this->inHoraFimAulaTerca = $inHoraFimAulaTerca;
		$this->inFlagQuarta = $inFlagQuarta;
		$this->inHoraIniAulaQuarta = $inHoraIniAulaQuarta;
		$this->inHoraFimAulaQuarta = $inHoraFimAulaQuarta;
		$this->inFlagQuinta = $inFlagQuinta;
		$this->inHoraIniAulaQuinta = $inHoraIniAulaQuinta;
		$this->inHoraFimAulaQuinta = $inHoraFimAulaQuinta;
		$this->inFlagSexta = $inFlagSexta;
		$this->inHoraIniAulaSexta = $inHoraIniAulaSexta;
		$this->inHoraFimAulaSexta = $inHoraFimAulaSexta;
		$this->inFlagSabado = $inFlagSabado;
		$this->inHoraIniAulaSabado = $inHoraIniAulaSabado;
		$this->inHoraFimAulaSabado = $inHoraFimAulaSabado;
	}

	/**
	 * Get the value of inFlagSegunda
	 */
	public function getInFlagSegunda()
	{
		return $this->inFlagSegunda;
	}

	/**
	 * Set the value of inFlagSegunda
	 */
	public function setInFlagSegunda($inFlagSegunda): self
	{
		$this->inFlagSegunda = $inFlagSegunda;

		return $this;
	}

	/**
	 * Get the value of inHoraIniAulaSegunda
	 */
	public function getInHoraIniAulaSegunda()
	{
		return $this->inHoraIniAulaSegunda;
	}

	/**
	 * Set the value of inHoraIniAulaSegunda
	 */
	public function setInHoraIniAulaSegunda($inHoraIniAulaSegunda): self
	{
		$this->inHoraIniAulaSegunda = $inHoraIniAulaSegunda;

		return $this;
	}

	/**
	 * Get the value of inHoraFimAulaSegunda
	 */
	public function getInHoraFimAulaSegunda()
	{
		return $this->inHoraFimAulaSegunda;
	}

	/**
	 * Set the value of inHoraFimAulaSegunda
	 */
	public function setInHoraFimAulaSegunda($inHoraFimAulaSegunda): self
	{
		$this->inHoraFimAulaSegunda = $inHoraFimAulaSegunda;

		return $this;
	}

	/**
	 * Get the value of inFlagTerca
	 */
	public function getInFlagTerca()
	{
		return $this->inFlagTerca;
	}

	/**
	 * Set the value of inFlagTerca
	 */
	public function setInFlagTerca($inFlagTerca): self
	{
		$this->inFlagTerca = $inFlagTerca;

		return $this;
	}

	/**
	 * Get the value of inHoraIniAulaTerca
	 */
	public function getInHoraIniAulaTerca()
	{
		return $this->inHoraIniAulaTerca;
	}

	/**
	 * Set the value of inHoraIniAulaTerca
	 */
	public function setInHoraIniAulaTerca($inHoraIniAulaTerca): self
	{
		$this->inHoraIniAulaTerca = $inHoraIniAulaTerca;

		return $this;
	}

	/**
	 * Get the value of inHoraFimAulaTerca
	 */
	public function getInHoraFimAulaTerca()
	{
		return $this->inHoraFimAulaTerca;
	}

	/**
	 * Set the value of inHoraFimAulaTerca
	 */
	public function setInHoraFimAulaTerca($inHoraFimAulaTerca): self
	{
		$this->inHoraFimAulaTerca = $inHoraFimAulaTerca;

		return $this;
	}

	/**
	 * Get the value of inFlagQuarta
	 */
	public function getInFlagQuarta()
	{
		return $this->inFlagQuarta;
	}

	/**
	 * Set the value of inFlagQuarta
	 */
	public function setInFlagQuarta($inFlagQuarta): self
	{
		$this->inFlagQuarta = $inFlagQuarta;

		return $this;
	}

	/**
	 * Get the value of inHoraIniAulaQuarta
	 */
	public function getInHoraIniAulaQuarta()
	{
		return $this->inHoraIniAulaQuarta;
	}

	/**
	 * Set the value of inHoraIniAulaQuarta
	 */
	public function setInHoraIniAulaQuarta($inHoraIniAulaQuarta): self
	{
		$this->inHoraIniAulaQuarta = $inHoraIniAulaQuarta;

		return $this;
	}

	/**
	 * Get the value of inHoraFimAulaQuarta
	 */
	public function getInHoraFimAulaQuarta()
	{
		return $this->inHoraFimAulaQuarta;
	}

	/**
	 * Set the value of inHoraFimAulaQuarta
	 */
	public function setInHoraFimAulaQuarta($inHoraFimAulaQuarta): self
	{
		$this->inHoraFimAulaQuarta = $inHoraFimAulaQuarta;

		return $this;
	}

	/**
	 * Get the value of inFlagQuinta
	 */
	public function getInFlagQuinta()
	{
		return $this->inFlagQuinta;
	}

	/**
	 * Set the value of inFlagQuinta
	 */
	public function setInFlagQuinta($inFlagQuinta): self
	{
		$this->inFlagQuinta = $inFlagQuinta;

		return $this;
	}

	/**
	 * Get the value of inHoraIniAulaQuinta
	 */
	public function getInHoraIniAulaQuinta()
	{
		return $this->inHoraIniAulaQuinta;
	}

	/**
	 * Set the value of inHoraIniAulaQuinta
	 */
	public function setInHoraIniAulaQuinta($inHoraIniAulaQuinta): self
	{
		$this->inHoraIniAulaQuinta = $inHoraIniAulaQuinta;

		return $this;
	}

	/**
	 * Get the value of inHoraFimAulaQuinta
	 */
	public function getInHoraFimAulaQuinta()
	{
		return $this->inHoraFimAulaQuinta;
	}

	/**
	 * Set the value of inHoraFimAulaQuinta
	 */
	public function setInHoraFimAulaQuinta($inHoraFimAulaQuinta): self
	{
		$this->inHoraFimAulaQuinta = $inHoraFimAulaQuinta;

		return $this;
	}

	/**
	 * Get the value of inFlagSexta
	 */
	public function getInFlagSexta()
	{
		return $this->inFlagSexta;
	}

	/**
	 * Set the value of inFlagSexta
	 */
	public function setInFlagSexta($inFlagSexta): self
	{
		$this->inFlagSexta = $inFlagSexta;

		return $this;
	}

	/**
	 * Get the value of inHoraIniAulaSexta
	 */
	public function getInHoraIniAulaSexta()
	{
		return $this->inHoraIniAulaSexta;
	}

	/**
	 * Set the value of inHoraIniAulaSexta
	 */
	public function setInHoraIniAulaSexta($inHoraIniAulaSexta): self
	{
		$this->inHoraIniAulaSexta = $inHoraIniAulaSexta;

		return $this;
	}

	/**
	 * Get the value of inHoraFimAulaSexta
	 */
	public function getInHoraFimAulaSexta()
	{
		return $this->inHoraFimAulaSexta;
	}

	/**
	 * Set the value of inHoraFimAulaSexta
	 */
	public function setInHoraFimAulaSexta($inHoraFimAulaSexta): self
	{
		$this->inHoraFimAulaSexta = $inHoraFimAulaSexta;

		return $this;
	}

	/**
	 * Get the value of inFlagSabado
	 */
	public function getInFlagSabado()
	{
		return $this->inFlagSabado;
	}

	/**
	 * Set the value of inFlagSabado
	 */
	public function setInFlagSabado($inFlagSabado): self
	{
		$this->inFlagSabado = $inFlagSabado;

		return $this;
	}

	/**
	 * Get the value of inHoraIniAulaSabado
	 */
	public function getInHoraIniAulaSabado()
	{
		return $this->inHoraIniAulaSabado;
	}

	/**
	 * Set the value of inHoraIniAulaSabado
	 */
	public function setInHoraIniAulaSabado($inHoraIniAulaSabado): self
	{
		$this->inHoraIniAulaSabado = $inHoraIniAulaSabado;

		return $this;
	}

	/**
	 * Get the value of inHoraFimAulaSabado
	 */
	public function getInHoraFimAulaSabado()
	{
		return $this->inHoraFimAulaSabado;
	}

	/**
	 * Set the value of inHoraFimAulaSabado
	 */
	public function setInHoraFimAulaSabado($inHoraFimAulaSabado): self
	{
		$this->inHoraFimAulaSabado = $inHoraFimAulaSabado;

		return $this;
	}

	function jsonSerialize() {
        return get_object_vars($this);
    }
}