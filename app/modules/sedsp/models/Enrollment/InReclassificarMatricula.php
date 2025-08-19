<?php

class InReclassificarMatricula implements JsonSerializable
{
    public ?InAluno $inAluno;
	public ?InMatricula $inMatricula;
	public ?InNivelEnsino $inNivelEnsino;

	public function __construct(
		?InAluno $inAluno,
		?InMatricula $inMatricula,
		?InNivelEnsino $inNivelEnsino
	) {
		$this->inAluno = $inAluno;
		$this->inMatricula = $inMatricula;
		$this->inNivelEnsino = $inNivelEnsino;
	}

	public function get_inAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function get_inMatricula(): ?InMatricula
	{
		return $this->inMatricula;
	}

	public function get_inNivelEnsino(): ?InNivelEnsino
	{
		return $this->inNivelEnsino;
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
