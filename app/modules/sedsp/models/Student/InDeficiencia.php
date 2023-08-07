<?php 

class InDeficiencia implements JsonSerializable
{
	public $inCodNecessidade;
	public $inMobilidadeReduzida;
	public $inTipoMobilidadeReduzida;
	public $inCuidador;
	public $inTipoCuidador;
	public $inProfSaude;
	public $inTipoProfSaude;

	public function __construct(
		?string $inCodNecessidade,
		?int $inMobilidadeReduzida,
		?string $inTipoMobilidadeReduzida,
		?int $inCuidador,
		?string $inTipoCuidador,
		?int $inProfSaude,
		?string $inTipoProfSaude
	) {
		$this->inCodNecessidade = $inCodNecessidade;
		$this->inMobilidadeReduzida = $inMobilidadeReduzida;
		$this->inTipoMobilidadeReduzida = $inTipoMobilidadeReduzida;
		$this->inCuidador = $inCuidador;
		$this->inTipoCuidador = $inTipoCuidador;
		$this->inProfSaude = $inProfSaude;
		$this->inTipoProfSaude = $inTipoProfSaude;
	}

	public function getInCodNecessidade(): ?string
	{
		return $this->inCodNecessidade;
	}

	public function getInMobilidadeReduzida(): ?int
	{
		return $this->inMobilidadeReduzida;
	}

	public function getInTipoMobilidadeReduzida(): ?string
	{
		return $this->inTipoMobilidadeReduzida;
	}

	public function getInCuidador(): ?int
	{
		return $this->inCuidador;
	}

	public function getInTipoCuidador(): ?string
	{
		return $this->inTipoCuidador;
	}

	public function getInProfSaude(): ?int
	{
		return $this->inProfSaude;
	}

	public function getInTipoProfSaude(): ?string
	{
		return $this->inTipoProfSaude;
	}

	public function setInCodNecessidade(?string $inCodNecessidade): self
	{
		$this->inCodNecessidade = $inCodNecessidade;
		return $this;
	}

	public function setInMobilidadeReduzida(?int $inMobilidadeReduzida): self
	{
		$this->inMobilidadeReduzida = $inMobilidadeReduzida;
		return $this;
	}

	public function setInTipoMobilidadeReduzida(?string $inTipoMobilidadeReduzida): self
	{
		$this->inTipoMobilidadeReduzida = $inTipoMobilidadeReduzida;
		return $this;
	}

	public function setInCuidador(?int $inCuidador): self
	{
		$this->inCuidador = $inCuidador;
		return $this;
	}

	public function setInTipoCuidador(?string $inTipoCuidador): self
	{
		$this->inTipoCuidador = $inTipoCuidador;
		return $this;
	}

	public function setInProfSaude(?int $inProfSaude): self
	{
		$this->inProfSaude = $inProfSaude;
		return $this;
	}

	public function setInTipoProfSaude(?string $inTipoProfSaude): self
	{
		$this->inTipoProfSaude = $inTipoProfSaude;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inCodNecessidade'] ?? null,
			$data['inMobilidadeReduzida'] ?? null,
			$data['inTipoMobilidadeReduzida'] ?? null,
			$data['inCuidador'] ?? null,
			$data['inTipoCuidador'] ?? null,
			$data['inProfSaude'] ?? null,
			$data['inTipoProfSaude'] ?? null
		);
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}

