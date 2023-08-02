<?php 

class InCertidaoNova implements JsonSerializable
{
	public $inCertMatr01;
	public $inCertMatr02;
	public $inCertMatr03;
	public $inCertMatr04;
	public $inCertMatr05;
	public $inCertMatr06;
	public $inCertMatr07;
	public $inCertMatr08;
	public $inCertMatr09;
	public $inDataEmissaoCertidao;

	public function __construct(
		string $inCertMatr01,
		string $inCertMatr02,
		string $inCertMatr03,
		string $inCertMatr04,
		string $inCertMatr05,
		string $inCertMatr06,
		string $inCertMatr07,
		string $inCertMatr08,
		string $inCertMatr09,
		string $inDataEmissaoCertidao
	) {
		$this->inCertMatr01 = $inCertMatr01;
		$this->inCertMatr02 = $inCertMatr02;
		$this->inCertMatr03 = $inCertMatr03;
		$this->inCertMatr04 = $inCertMatr04;
		$this->inCertMatr05 = $inCertMatr05;
		$this->inCertMatr06 = $inCertMatr06;
		$this->inCertMatr07 = $inCertMatr07;
		$this->inCertMatr08 = $inCertMatr08;
		$this->inCertMatr09 = $inCertMatr09;
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
	}
	
	public function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

	/**
	 * Get the value of inCertMatr01
	 */
	public function getInCertMatr01()
	{
		return $this->inCertMatr01;
	}

	/**
	 * Set the value of inCertMatr01
	 */
	public function setInCertMatr01($inCertMatr01): self
	{
		$this->inCertMatr01 = $inCertMatr01;

		return $this;
	}

	/**
	 * Get the value of inCertMatr02
	 */
	public function getInCertMatr02()
	{
		return $this->inCertMatr02;
	}

	/**
	 * Set the value of inCertMatr02
	 */
	public function setInCertMatr02($inCertMatr02): self
	{
		$this->inCertMatr02 = $inCertMatr02;

		return $this;
	}

	/**
	 * Get the value of inCertMatr03
	 */
	public function getInCertMatr03()
	{
		return $this->inCertMatr03;
	}

	/**
	 * Set the value of inCertMatr03
	 */
	public function setInCertMatr03($inCertMatr03): self
	{
		$this->inCertMatr03 = $inCertMatr03;

		return $this;
	}

	/**
	 * Get the value of inCertMatr04
	 */
	public function getInCertMatr04()
	{
		return $this->inCertMatr04;
	}

	/**
	 * Set the value of inCertMatr04
	 */
	public function setInCertMatr04($inCertMatr04): self
	{
		$this->inCertMatr04 = $inCertMatr04;

		return $this;
	}

	/**
	 * Get the value of inCertMatr05
	 */
	public function getInCertMatr05()
	{
		return $this->inCertMatr05;
	}

	/**
	 * Set the value of inCertMatr05
	 */
	public function setInCertMatr05($inCertMatr05): self
	{
		$this->inCertMatr05 = $inCertMatr05;

		return $this;
	}

	/**
	 * Get the value of inCertMatr06
	 */
	public function getInCertMatr06()
	{
		return $this->inCertMatr06;
	}

	/**
	 * Set the value of inCertMatr06
	 */
	public function setInCertMatr06($inCertMatr06): self
	{
		$this->inCertMatr06 = $inCertMatr06;

		return $this;
	}

	/**
	 * Get the value of inCertMatr07
	 */
	public function getInCertMatr07()
	{
		return $this->inCertMatr07;
	}

	/**
	 * Set the value of inCertMatr07
	 */
	public function setInCertMatr07($inCertMatr07): self
	{
		$this->inCertMatr07 = $inCertMatr07;

		return $this;
	}

	/**
	 * Get the value of inCertMatr08
	 */
	public function getInCertMatr08()
	{
		return $this->inCertMatr08;
	}

	/**
	 * Set the value of inCertMatr08
	 */
	public function setInCertMatr08($inCertMatr08): self
	{
		$this->inCertMatr08 = $inCertMatr08;

		return $this;
	}

	/**
	 * Get the value of inCertMatr09
	 */
	public function getInCertMatr09()
	{
		return $this->inCertMatr09;
	}

	/**
	 * Set the value of inCertMatr09
	 */
	public function setInCertMatr09($inCertMatr09): self
	{
		$this->inCertMatr09 = $inCertMatr09;

		return $this;
	}

	/**
	 * Get the value of inDataEmissaoCertidao
	 */
	public function getInDataEmissaoCertidao()
	{
		return $this->inDataEmissaoCertidao;
	}

	/**
	 * Set the value of inDataEmissaoCertidao
	 */
	public function setInDataEmissaoCertidao($inDataEmissaoCertidao): self
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;

		return $this;
	}
}
