<?php 

class InDocumentos implements JsonSerializable
{
	private $inNumDoctoCivil;

	private $inDigitoDoctoCivil;

	private $inUfDoctoCivil;

	private $inDataEmissaoDoctoCivil;

	private $inNumNis;

	private $inCodigoInep;

	private $inCpf;

	private $inJustificativaDocumentos;

	/**
	 * @param string|null $inNumDoctoCivil
	 * @param string|null $inDigitoDoctoCivil
	 * @param string|null $inUfDoctoCivil
	 * @param string|null $inDataEmissaoDoctoCivil
	 * @param string|null $inNumNis
	 * @param string|null $inCodigoInep
	 * @param string|null $inCpf
	 * @param string|null $inJustificativaDocumentos
	 */
	public function __construct(
		?string $inNumDoctoCivil = null,
		?string $inDigitoDoctoCivil = null,
		?string $inUfDoctoCivil = null,
		?string $inDataEmissaoDoctoCivil = null,
		?string $inNumNis = null,
		?string $inCodigoInep = null,
		?string $inCpf = null,
		?string $inJustificativaDocumentos = null
	) {
		$this->inNumDoctoCivil = $inNumDoctoCivil;
		$this->inDigitoDoctoCivil = $inDigitoDoctoCivil;
		$this->inUfDoctoCivil = $inUfDoctoCivil;
		$this->inDataEmissaoDoctoCivil = $inDataEmissaoDoctoCivil;
		$this->inNumNis = $inNumNis;
		$this->inCodigoInep = $inCodigoInep;
		$this->inCpf = $inCpf;
		$this->inJustificativaDocumentos = $inJustificativaDocumentos;
	}

	/**
	 * @param string|null $inNumDoctoCivil
	 * @return self
	 */
	public function setInNumDoctoCivil(?string $inNumDoctoCivil): self
	{
		$this->inNumDoctoCivil = $inNumDoctoCivil;
		return $this;
	}

	/**
	 * @param string|null $inDigitoDoctoCivil
	 * @return self
	 */
	public function setInDigitoDoctoCivil(?string $inDigitoDoctoCivil): self
	{
		$this->inDigitoDoctoCivil = $inDigitoDoctoCivil;
		return $this;
	}

	/**
	 * @param string|null $inUfDoctoCivil
	 * @return self
	 */
	public function setInUfDoctoCivil(?string $inUfDoctoCivil): self
	{
		$this->inUfDoctoCivil = $inUfDoctoCivil;
		return $this;
	}

	/**
	 * @param string|null $inDataEmissaoDoctoCivil
	 * @return self
	 */
	public function setInDataEmissaoDoctoCivil(?string $inDataEmissaoDoctoCivil): self
	{
		$this->inDataEmissaoDoctoCivil = $inDataEmissaoDoctoCivil;
		return $this;
	}

	/**
	 * @param string|null $inNumNis
	 * @return self
	 */
	public function setInNumNis(?string $inNumNis): self
	{
		$this->inNumNis = $inNumNis;
		return $this;
	}

	/**
	 * @param string|null $inCodigoInep
	 * @return self
	 */
	public function setInCodigoInep(?string $inCodigoInep): self
	{
		$this->inCodigoInep = $inCodigoInep;
		return $this;
	}

	/**
	 * @param string|null $inCpf
	 * @return self
	 */
	public function setInCpf(?string $inCpf): self
	{
		$this->inCpf = $inCpf;
		return $this;
	}

	/**
	 * @param string|null $inJustificativaDocumentos
	 * @return self
	 */
	public function setInJustificativaDocumentos(?string $inJustificativaDocumentos): self
	{
		$this->inJustificativaDocumentos = $inJustificativaDocumentos;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNumDoctoCivil'] ?? null,
			$data['outDigitoDoctoCivil'] ?? null,
			$data['outUFDoctoCivil'] ?? null,
			$data['outDataEmissaoDoctoCivil'] ?? null,
			$data['outNumNIS'] ?? null,
			$data['outCodigoINEP'] ?? null,
			$data['outCPF'] ?? null,
			$data['outJustificativaDocumentos'] ?? null
		);
	}

	public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}

?>