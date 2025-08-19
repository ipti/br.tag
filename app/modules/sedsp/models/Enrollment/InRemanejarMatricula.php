<?php 

class InRemanejarMatricula implements JsonSerializable
{
    public ?InAluno $inAluno;
	public ?InMatriculaRemanejar $inMatriculaRemanejar;
	public ?InNivelEnsino $inNivelEnsino;
	public ?string $inAnoLetivo;

	public function __construct(
		?InAluno $inAluno,
		?InMatriculaRemanejar $inMatriculaRemanejar,
		?InNivelEnsino $inNivelEnsino,
		?string $inAnoLetivo
	) {
		$this->inAluno = $inAluno;
		$this->inMatriculaRemanejar = $inMatriculaRemanejar;
		$this->inNivelEnsino = $inNivelEnsino;
		$this->inAnoLetivo = $inAnoLetivo;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInMatriculaRemanejar(): ?InMatriculaRemanejar
	{
		return $this->inMatriculaRemanejar;
	}

	public function getInNivelEnsino(): ?InNivelEnsino
	{
		return $this->inNivelEnsino;
	}

	public function getInAnoLetivo(): ?string
	{
		return $this->inAnoLetivo;
	}

	public function setInAluno(?InAluno $inAluno): self
	{
		$this->inAluno = $inAluno;
		return $this;
	}

	public function setInMatriculaRemanejar(?InMatriculaRemanejar $inMatriculaRemanejar): self
	{
		$this->inMatriculaRemanejar = $inMatriculaRemanejar;
		return $this;
	}

	public function setInNivelEnsino(?InNivelEnsino $inNivelEnsino): self
	{
		$this->inNivelEnsino = $inNivelEnsino;
		return $this;
	}

	public function setInAnoLetivo(?string $inAnoLetivo): self
	{
		$this->inAnoLetivo = $inAnoLetivo;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			($data['inAluno'] ?? null) !== null ? InAluno::fromJson($data['inAluno']) : null,
			($data['inMatriculaRemanejar'] ?? null) !== null ? InMatriculaRemanejar::fromJson($data['inMatriculaRemanejar']) : null,
			($data['inNivelEnsino'] ?? null) !== null ? InNivelEnsino::fromJson($data['inNivelEnsino']) : null,
			$data['inAnoLetivo'] ?? null
		);
	}
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
