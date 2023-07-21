<?php 

class InCertidaoNova implements JsonSerializable
{
	private $inCertMatr01;

	private $inCertMatr02;

	private $inCertMatr03;

	private $inCertMatr04;

	private $inCertMatr05;

	private $inCertMatr06;

	private $inCertMatr07;

	private $inCertMatr08;

	private $inCertMatr09;

	private $inDataEmissaoCertidao;

	/**
	 * @param string|null $inCertMatr01
	 * @param string|null $inCertMatr02
	 * @param string|null $inCertMatr03
	 * @param string|null $inCertMatr04
	 * @param string|null $inCertMatr05
	 * @param string|null $inCertMatr06
	 * @param string|null $inCertMatr07
	 * @param string|null $inCertMatr08
	 * @param string|null $inCertMatr09
	 * @param string|null $inDataEmissaoCertidao
	 */
	public function __construct(
		?string $inCertMatr01,
		?string $inCertMatr02,
		?string $inCertMatr03,
		?string $inCertMatr04,
		?string $inCertMatr05,
		?string $inCertMatr06,
		?string $inCertMatr07,
		?string $inCertMatr08,
		?string $inCertMatr09,
		?string $inDataEmissaoCertidao
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

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCertMatr01'] ?? null,
			$data['outCertMatr02'] ?? null,
			$data['outCertMatr03'] ?? null,
			$data['outCertMatr04'] ?? null,
			$data['outCertMatr05'] ?? null,
			$data['outCertMatr06'] ?? null,
			$data['outCertMatr07'] ?? null,
			$data['outCertMatr08'] ?? null,
			$data['outCertMatr09'] ?? null,
			$data['outDataEmissaoCertidao'] ?? null
		);
	}

	public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
?>