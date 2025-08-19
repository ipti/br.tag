<?php

class InscreverAluno implements JsonSerializable
{
    public $inAluno;
	public $inInscricao;
	public $inNivelEnsino;

	public function __construct(
		?InAluno $inAluno,
		?InInscricao $inInscricao,
		?InNivelEnsino $inNivelEnsino
	) {
		$this->inAluno = $inAluno;
		$this->inInscricao = $inInscricao;
		$this->inNivelEnsino = $inNivelEnsino;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInInscricao(): ?InInscricao
	{
		return $this->inInscricao;
	}

	public function getInNivelEnsino(): ?InNivelEnsino
	{
		return $this->inNivelEnsino;
	}

	public function setInAluno(?InAluno $inAluno): self
	{
		$this->inAluno = $inAluno;
		return $this;
	}

	public function setInInscricao(?InInscricao $inInscricao): self
	{
		$this->inInscricao = $inInscricao;
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
			($data['inInscricao'] ?? null) !== null ? InInscricao::fromJson($data['inInscricao']) : null,
			($data['inNivelEnsino'] ?? null) !== null ? InNivelEnsino::fromJson($data['inNivelEnsino']) : null
		);
	}
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
