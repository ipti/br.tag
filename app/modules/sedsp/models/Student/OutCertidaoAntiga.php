<?php

class OutCertidaoAntiga
{
	public $outNumCertidao;
	public $outNumLivroReg;
	public $outFolhaRegNum;
	public $outNomeMunComarca;
	public $outUFComarca;
	public $outDistritoNasc;

	public function __construct(
		string $outNumCertidao,
		string $outNumLivroReg,
		string $outFolhaRegNum,
		string $outNomeMunComarca,
		string $outUFComarca,
		string $outDistritoNasc
	) {
		$this->outNumCertidao = $outNumCertidao;
		$this->outNumLivroReg = $outNumLivroReg;
		$this->outFolhaRegNum = $outFolhaRegNum;
		$this->outNomeMunComarca = $outNomeMunComarca;
		$this->outUFComarca = $outUFComarca;
		$this->outDistritoNasc = $outDistritoNasc;
	}

	/**
	 * Get the value of outNumCertidao
	 */
	public function getOutNumCertidao()
	{
		return $this->outNumCertidao;
	}

	/**
	 * Set the value of outNumCertidao
	 */
	public function setOutNumCertidao($outNumCertidao): self
	{
		$this->outNumCertidao = $outNumCertidao;

		return $this;
	}

	/**
	 * Get the value of outNumLivroReg
	 */
	public function getOutNumLivroReg()
	{
		return $this->outNumLivroReg;
	}

	/**
	 * Set the value of outNumLivroReg
	 */
	public function setOutNumLivroReg($outNumLivroReg): self
	{
		$this->outNumLivroReg = $outNumLivroReg;

		return $this;
	}

	/**
	 * Get the value of outFolhaRegNum
	 */
	public function getOutFolhaRegNum()
	{
		return $this->outFolhaRegNum;
	}

	/**
	 * Set the value of outFolhaRegNum
	 */
	public function setOutFolhaRegNum($outFolhaRegNum): self
	{
		$this->outFolhaRegNum = $outFolhaRegNum;

		return $this;
	}

	/**
	 * Get the value of outNomeMunComarca
	 */
	public function getOutNomeMunComarca()
	{
		return $this->outNomeMunComarca;
	}

	/**
	 * Set the value of outNomeMunComarca
	 */
	public function setOutNomeMunComarca($outNomeMunComarca): self
	{
		$this->outNomeMunComarca = $outNomeMunComarca;

		return $this;
	}

	/**
	 * Get the value of outUFComarca
	 */
	public function getOutUFComarca()
	{
		return $this->outUFComarca;
	}

	/**
	 * Set the value of outUFComarca
	 */
	public function setOutUFComarca($outUFComarca): self
	{
		$this->outUFComarca = $outUFComarca;

		return $this;
	}

	/**
	 * Get the value of outDistritoNasc
	 */
	public function getOutDistritoNasc()
	{
		return $this->outDistritoNasc;
	}

	/**
	 * Set the value of outDistritoNasc
	 */
	public function setOutDistritoNasc($outDistritoNasc): self
	{
		$this->outDistritoNasc = $outDistritoNasc;

		return $this;
	}
}