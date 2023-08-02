<?php

class InDocumentos implements JsonSerializable
{
    public $outCodINEP;
	public $outCPF;
	public $outNumNIS;
	public $outNumDoctoCivil;
	public $outDigitoDoctoCivil;
	public $outUFDoctoCivil;
	public $outDataEmissaoDoctoCivil;
	public $outDataEmissaoCertidao;

	public function __construct(
		string $outCodINEP,
		string $outCPF,
		string $outNumNIS,
		string $outNumDoctoCivil,
		string $outDigitoDoctoCivil,
		string $outUFDoctoCivil,
		string $outDataEmissaoDoctoCivil,
		string $outDataEmissaoCertidao
	) {
		$this->outCodINEP = $outCodINEP;
		$this->outCPF = $outCPF;
		$this->outNumNIS = $outNumNIS;
		$this->outNumDoctoCivil = $outNumDoctoCivil;
		$this->outDigitoDoctoCivil = $outDigitoDoctoCivil;
		$this->outUFDoctoCivil = $outUFDoctoCivil;
		$this->outDataEmissaoDoctoCivil = $outDataEmissaoDoctoCivil;
		$this->outDataEmissaoCertidao = $outDataEmissaoCertidao;
	}

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

    /**
     * Get the value of outCodINEP
     */
    public function getOutCodINEP()
    {
        return $this->outCodINEP;
    }

    /**
     * Set the value of outCodINEP
     */
    public function setOutCodINEP($outCodINEP): self
    {
        $this->outCodINEP = $outCodINEP;

        return $this;
    }

	/**
	 * Get the value of outCPF
	 */
	public function getOutCPF()
	{
		return $this->outCPF;
	}

	/**
	 * Set the value of outCPF
	 */
	public function setOutCPF($outCPF): self
	{
		$this->outCPF = $outCPF;

		return $this;
	}

	/**
	 * Get the value of outNumNIS
	 */
	public function getOutNumNIS()
	{
		return $this->outNumNIS;
	}

	/**
	 * Set the value of outNumNIS
	 */
	public function setOutNumNIS($outNumNIS): self
	{
		$this->outNumNIS = $outNumNIS;

		return $this;
	}

	/**
	 * Get the value of outNumDoctoCivil
	 */
	public function getOutNumDoctoCivil()
	{
		return $this->outNumDoctoCivil;
	}

	/**
	 * Set the value of outNumDoctoCivil
	 */
	public function setOutNumDoctoCivil($outNumDoctoCivil): self
	{
		$this->outNumDoctoCivil = $outNumDoctoCivil;

		return $this;
	}

	/**
	 * Get the value of outDigitoDoctoCivil
	 */
	public function getOutDigitoDoctoCivil()
	{
		return $this->outDigitoDoctoCivil;
	}

	/**
	 * Set the value of outDigitoDoctoCivil
	 */
	public function setOutDigitoDoctoCivil($outDigitoDoctoCivil): self
	{
		$this->outDigitoDoctoCivil = $outDigitoDoctoCivil;

		return $this;
	}

	/**
	 * Get the value of outUFDoctoCivil
	 */
	public function getOutUFDoctoCivil()
	{
		return $this->outUFDoctoCivil;
	}

	/**
	 * Set the value of outUFDoctoCivil
	 */
	public function setOutUFDoctoCivil($outUFDoctoCivil): self
	{
		$this->outUFDoctoCivil = $outUFDoctoCivil;

		return $this;
	}

	/**
	 * Get the value of outDataEmissaoDoctoCivil
	 */
	public function getOutDataEmissaoDoctoCivil()
	{
		return $this->outDataEmissaoDoctoCivil;
	}

	/**
	 * Set the value of outDataEmissaoDoctoCivil
	 */
	public function setOutDataEmissaoDoctoCivil($outDataEmissaoDoctoCivil): self
	{
		$this->outDataEmissaoDoctoCivil = $outDataEmissaoDoctoCivil;

		return $this;
	}

	/**
	 * Get the value of outDataEmissaoCertidao
	 */
	public function getOutDataEmissaoCertidao()
	{
		return $this->outDataEmissaoCertidao;
	}

	/**
	 * Set the value of outDataEmissaoCertidao
	 */
	public function setOutDataEmissaoCertidao($outDataEmissaoCertidao): self
	{
		$this->outDataEmissaoCertidao = $outDataEmissaoCertidao;

		return $this;
	}
}