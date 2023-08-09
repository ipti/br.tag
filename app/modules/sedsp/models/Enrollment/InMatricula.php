<?php

class InMatricula implements JsonSerializable
{
	public $inDataInicioMatricula;
	public $inNumAluno;
	public $inNumClasse;

	public function __construct(
		?string $inDataInicioMatricula,
		?string $inNumAluno,
		?string $inNumClasse
	) {
		$this->inDataInicioMatricula = $inDataInicioMatricula;
		$this->inNumAluno = $inNumAluno;
		$this->inNumClasse = $inNumClasse;
	}

	public function getInDataInicioMatricula(): ?string
	{
		return $this->inDataInicioMatricula;
	}

	public function getInNumAluno(): ?string
	{
		return $this->inNumAluno;
	}

	public function getInNumClasse(): ?string
	{
		return $this->inNumClasse;
	}

	public function setInDataInicioMatricula(?string $inDataInicioMatricula): self
	{
		$this->inDataInicioMatricula = $inDataInicioMatricula;
		return $this;
	}

	public function setInNumAluno(?string $inNumAluno): self
	{
		$this->inNumAluno = $inNumAluno;
		return $this;
	}

	public function setInNumClasse(?string $inNumClasse): self
	{
		$this->inNumClasse = $inNumClasse;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inDataInicioMatricula'] ?? null,
			$data['inNumAluno'] ?? null,
			$data['inNumClasse'] ?? null
		);
	}

	function jsonSerialize() {
        return get_object_vars($this);
    }
}