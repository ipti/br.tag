<?php 

class InTrocarAlunoEntreClasses implements JsonSerializable
{
    public ?InAluno $inAluno;
	public ?InMatriculaTrocar $inMatriculaTrocar;
	public ?InNivelEnsino $inNivelEnsino;

	public function __construct(
		?InAluno $inAluno,
		?InMatriculaTrocar $inMatriculaTrocar,
		?InNivelEnsino $inNivelEnsino
	) {
		$this->inAluno = $inAluno;
		$this->inMatriculaTrocar = $inMatriculaTrocar;
		$this->inNivelEnsino = $inNivelEnsino;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInMatricula(): ?InMatriculaTrocar
	{
		return $this->inMatriculaTrocar;
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

	public function setInMatricula(?InMatriculaTrocar $inMatriculaTrocar): self
	{
		$this->inMatriculaTrocar = $inMatriculaTrocar;
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
			($data['inMatriculaTrocar'] ?? null) !== null ? InMatriculaTrocar::fromJson($data['inMatriculaTrocar']) : null,
			($data['inNivelEnsino'] ?? null) !== null ? InNivelEnsino::fromJson($data['inNivelEnsino']) : null
		);
	}

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
