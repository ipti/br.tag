<?php

class InNivelEnsino
{
	public $inCodSerieAno;
	public $inCodTipoEnsino;

	public function __construct(string $inCodSerieAno, string $inCodTipoEnsino)
	{
		$this->inCodSerieAno = $inCodSerieAno;
		$this->inCodTipoEnsino = $inCodTipoEnsino;
	}
    

	/**
	 * Get the value of inCodSerieAno
	 */
	public function getInCodSerieAno()
	{
		return $this->inCodSerieAno;
	}

	/**
	 * Set the value of inCodSerieAno
	 */
	public function setInCodSerieAno($inCodSerieAno): self
	{
		$this->inCodSerieAno = $inCodSerieAno;

		return $this;
	}

	/**
	 * Get the value of inCodTipoEnsino
	 */
	public function getInCodTipoEnsino()
	{
		return $this->inCodTipoEnsino;
	}

	/**
	 * Set the value of inCodTipoEnsino
	 */
	public function setInCodTipoEnsino($inCodTipoEnsino): self
	{
		$this->inCodTipoEnsino = $inCodTipoEnsino;

		return $this;
	}
}