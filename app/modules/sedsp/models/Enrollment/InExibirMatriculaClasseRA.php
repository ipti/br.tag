<?php

class InExibirMatriculaClasseRA implements JsonSerializable
{
    public $inAluno;
	public $inNumClasse;
	public $inSituacao;
	public $inDataInicioMatricula;

	public function __construct(
		?InAluno $inAluno,
		?string $inNumClasse,
		?string $inSituacao,
		?string $inDataInicioMatricula
	) {
		$this->inAluno = $inAluno;
		$this->inNumClasse = $inNumClasse;
		$this->inSituacao = $inSituacao;
		$this->inDataInicioMatricula = $inDataInicioMatricula;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInNumClasse(): ?string
	{
		return $this->inNumClasse;
	}

	public function getInSituacao(): ?string
	{
		return $this->inSituacao;
	}

	public function getInDataInicioMatricula(): ?string
	{
		return $this->inDataInicioMatricula;
	}

	public function setInAluno(?InAluno $inAluno): self
	{
		$this->inAluno = $inAluno;
		return $this;
	}

	public function setInNumClasse(?string $inNumClasse): self
	{
		$this->inNumClasse = $inNumClasse;
		return $this;
	}

	public function setInSituacao(?string $inSituacao): self
	{
		$this->inSituacao = $inSituacao;
		return $this;
	}

	public function setInDataInicioMatricula(?string $inDataInicioMatricula): self
	{
		$this->inDataInicioMatricula = $inDataInicioMatricula;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			($data['inAluno'] ?? null) !== null ? InAluno::fromJson($data['inAluno']) : null,
			$data['inNumClasse'] ?? null,
			$data['inSituacao'] ?? null,
			$data['inDataInicioMatricula'] ?? null
		);
	}
    
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
