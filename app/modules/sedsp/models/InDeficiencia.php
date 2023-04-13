<?php 

class InDeficiencia implements JsonSerializable
{
	private $inCodNecessidade;

	private $inMobilidadeReduzida;

	private $inTipoMobilidadeReduzida;

	private $inCuidador;

	private $inTipoCuidador;

	private $inProfSaude;

	private $inTipoProfSaude;

	/**
	 * @param string|null $inCodNecessidade
	 * @param int|null $inMobilidadeReduzida
	 * @param string|null $inTipoMobilidadeReduzida
	 * @param int|null $inCuidador
	 * @param string|null $inTipoCuidador
	 * @param int|null $inProfSaude
	 * @param string|null $inTipoProfSaude
	 */
	public function __construct(
		?string $inCodNecessidade = null,
		?int $inMobilidadeReduzida = 0,
		?string $inTipoMobilidadeReduzida = null,
		?int $inCuidador = 0,
		?string $inTipoCuidador = null,
		?int $inProfSaude = 0,
		?string $inTipoProfSaude = null
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
	 * @param string|null $inCodNecessidade
	 * @return self
	 */
	public function setInCodNecessidade(?string $inCodNecessidade): self
	{
		$this->inCodNecessidade = $inCodNecessidade;
		return $this;
	}

	/**
	 * @param int|null $inMobilidadeReduzida
	 * @return self
	 */
	public function setInMobilidadeReduzida(?int $inMobilidadeReduzida): self
	{
		$this->inMobilidadeReduzida = $inMobilidadeReduzida;
		return $this;
	}

	/**
	 * @param string|null $inTipoMobilidadeReduzida
	 * @return self
	 */
	public function setInTipoMobilidadeReduzida(?string $inTipoMobilidadeReduzida): self
	{
		$this->inTipoMobilidadeReduzida = $inTipoMobilidadeReduzida;
		return $this;
	}

	/**
	 * @param int|null $inCuidador
	 * @return self
	 */
	public function setInCuidador(?int $inCuidador): self
	{
		$this->inCuidador = $inCuidador;
		return $this;
	}

	/**
	 * @param string|null $inTipoCuidador
	 * @return self
	 */
	public function setInTipoCuidador(?string $inTipoCuidador): self
	{
		$this->inTipoCuidador = $inTipoCuidador;
		return $this;
	}

	/**
	 * @param int|null $inProfSaude
	 * @return self
	 */
	public function setInProfSaude(?int $inProfSaude): self
	{
		$this->inProfSaude = $inProfSaude;
		return $this;
	}

	/**
	 * @param string|null $inTipoProfSaude
	 * @return self
	 */
	public function setInTipoProfSaude(?string $inTipoProfSaude): self
	{
		$this->inTipoProfSaude = $inTipoProfSaude;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodNecessidade'] ?? null,
			$data['outMobilidadeReduzida'] ?? null,
			$data['outTipoMobilidadeReduzida'] ?? null,
			$data['outCuidador'] ?? null,
			$data['outTipoCuidador'] ?? null,
			$data['outProfSaude'] ?? null,
			$data['outTipoProfSaude'] ?? null
		);
	}

	public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}


?>