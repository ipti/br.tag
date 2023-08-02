<?php

class OutTelefones
{
	public $outDDDNumero;
	public $outNumero;
	public $outTipoTelefone;
	public $outDescTipoTelefone;
	public $outComplemento;
	public $outSMS;

	public function __construct(
		string $outDDDNumero,
		string $outNumero,
		string $outTipoTelefone,
		string $outDescTipoTelefone,
		string $outComplemento,
		string $outSMS
	) {
		$this->outDDDNumero = $outDDDNumero;
		$this->outNumero = $outNumero;
		$this->outTipoTelefone = $outTipoTelefone;
		$this->outDescTipoTelefone = $outDescTipoTelefone;
		$this->outComplemento = $outComplemento;
		$this->outSMS = $outSMS;
	}

	/**
	 * Get the value of outDDDNumero
	 */
	public function getOutDDDNumero()
	{
		return $this->outDDDNumero;
	}

	/**
	 * Set the value of outDDDNumero
	 */
	public function setOutDDDNumero($outDDDNumero): self
	{
		$this->outDDDNumero = $outDDDNumero;

		return $this;
	}

	/**
	 * Get the value of outNumero
	 */
	public function getOutNumero()
	{
		return $this->outNumero;
	}

	/**
	 * Set the value of outNumero
	 */
	public function setOutNumero($outNumero): self
	{
		$this->outNumero = $outNumero;

		return $this;
	}

	/**
	 * Get the value of outTipoTelefone
	 */
	public function getOutTipoTelefone()
	{
		return $this->outTipoTelefone;
	}

	/**
	 * Set the value of outTipoTelefone
	 */
	public function setOutTipoTelefone($outTipoTelefone): self
	{
		$this->outTipoTelefone = $outTipoTelefone;

		return $this;
	}

	/**
	 * Get the value of outDescTipoTelefone
	 */
	public function getOutDescTipoTelefone()
	{
		return $this->outDescTipoTelefone;
	}

	/**
	 * Set the value of outDescTipoTelefone
	 */
	public function setOutDescTipoTelefone($outDescTipoTelefone): self
	{
		$this->outDescTipoTelefone = $outDescTipoTelefone;

		return $this;
	}

	/**
	 * Get the value of outComplemento
	 */
	public function getOutComplemento()
	{
		return $this->outComplemento;
	}

	/**
	 * Set the value of outComplemento
	 */
	public function setOutComplemento($outComplemento): self
	{
		$this->outComplemento = $outComplemento;

		return $this;
	}

	/**
	 * Get the value of outSMS
	 */
	public function getOutSMS()
	{
		return $this->outSMS;
	}

	/**
	 * Set the value of outSMS
	 */
	public function setOutSMS($outSMS): self
	{
		$this->outSMS = $outSMS;

		return $this;
	}
}