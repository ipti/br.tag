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


	/**
	 * @param ?string $inCertMatr01
	 * @param ?string $inCertMatr02
	 * @param ?string $inCertMatr03
	 * @param ?string $inCertMatr04
	 * @param ?string $inCertMatr05
	 * @param ?string $inCertMatr06
	 * @param ?string $inCertMatr07
	 * @param ?string $inCertMatr08
	 * @param ?string $inCertMatr09
	 * @param ?string $inDataEmissaoCertidao
	 */
	public function __construct($inCertidaoNova) {
		$inCertidaoNova = (object) $inCertidaoNova;
		$this->inCertMatr01 = $inCertidaoNova->inCertMatr01;
		$this->inCertMatr02 = $inCertidaoNova->inCertMatr02;
		$this->inCertMatr03 = $inCertidaoNova->inCertMatr03;
		$this->inCertMatr04 = $inCertidaoNova->inCertMatr04;
		$this->inCertMatr05 = $inCertidaoNova->inCertMatr05;
		$this->inCertMatr06 = $inCertidaoNova->inCertMatr06;
		$this->inCertMatr07 = $inCertidaoNova->inCertMatr07;
		$this->inCertMatr08 = $inCertidaoNova->inCertMatr08;
		$this->inCertMatr09 = $inCertidaoNova->inCertMatr09;
		$this->inDataEmissaoCertidao = $inCertidaoNova->inDataEmissaoCertidao;
	}

	/**
	 * @param string|null $inCertMatr01
	 * @return self
	 */
	public function setInCertMatr01(?string $inCertMatr01): self
	{
		$this->inCertMatr01 = $inCertMatr01;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr02
	 * @return self
	 */
	public function setInCertMatr02(?string $inCertMatr02): self
	{
		$this->inCertMatr02 = $inCertMatr02;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr03
	 * @return self
	 */
	public function setInCertMatr03(?string $inCertMatr03): self
	{
		$this->inCertMatr03 = $inCertMatr03;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr04
	 * @return self
	 */
	public function setInCertMatr04(?string $inCertMatr04): self
	{
		$this->inCertMatr04 = $inCertMatr04;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr05
	 * @return self
	 */
	public function setInCertMatr05(?string $inCertMatr05): self
	{
		$this->inCertMatr05 = $inCertMatr05;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr06
	 * @return self
	 */
	public function setInCertMatr06(?string $inCertMatr06): self
	{
		$this->inCertMatr06 = $inCertMatr06;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr07
	 * @return self
	 */
	public function setInCertMatr07(?string $inCertMatr07): self
	{
		$this->inCertMatr07 = $inCertMatr07;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr08
	 * @return self
	 */
	public function setInCertMatr08(?string $inCertMatr08): self
	{
		$this->inCertMatr08 = $inCertMatr08;
		return $this;
	}

	/**
	 * @param string|null $inCertMatr09
	 * @return self
	 */
	public function setInCertMatr09(?string $inCertMatr09): self
	{
		$this->inCertMatr09 = $inCertMatr09;
		return $this;
	}

	/**
	 * @param string|null $inDataEmissaoCertidao
	 * @return self
	 */
	public function setInDataEmissaoCertidao(?string $inDataEmissaoCertidao): self
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
		return $this;
	}

	public function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}
