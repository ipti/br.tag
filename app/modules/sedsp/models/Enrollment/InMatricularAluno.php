<?php

class InMatricularAluno implements JsonSerializable
{
    public $inAnoLetivo;
	public $inAluno;
	public $inMatricula;
	public $inNivelEnsino;

	public function __construct(
		?string $inAnoLetivo,
		?InAluno $inAluno,
		?InMatricula $inMatricula,
		?InNivelEnsino $inNivelEnsino
	) {
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inAluno = $inAluno;
		$this->inMatricula = $inMatricula;
		$this->inNivelEnsino = $inNivelEnsino;
	}

	public function getInAnoLetivo(): ?string
	{
		return $this->inAnoLetivo;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInMatricula(): ?InMatricula
	{
		return $this->inMatricula;
	}

	public function getInNivelEnsino(): ?InNivelEnsino
	{
		return $this->inNivelEnsino;
	}

	public function setInAnoLetivo(?string $inAnoLetivo): self
	{
		$this->inAnoLetivo = $inAnoLetivo;
		return $this;
	}

	public function setInAluno(?InAluno $inAluno): self
	{
		$this->inAluno = $inAluno;
		return $this;
	}

	public function setInMatricula(?InMatricula $inMatricula): self
	{
		$this->inMatricula = $inMatricula;
		return $this;
	}

	public function setInNivelEnsino(?InNivelEnsino $inNivelEnsino): self
	{
		$this->inNivelEnsino = $inNivelEnsino;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inAnoLetivo'] ?? null,
			($data['inAluno'] ?? null) !== null ? InAluno::fromJson($data['inAluno']) : null,
			($data['inMatricula'] ?? null) !== null ? InMatricula::fromJson($data['inMatricula']) : null,
			($data['inNivelEnsino'] ?? null) !== null ? InNivelEnsino::fromJson($data['inNivelEnsino']) : null
		);
	}

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
