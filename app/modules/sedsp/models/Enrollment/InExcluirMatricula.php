<?php
class InExcluirMatricula implements JsonSerializable
{
	public ?InAluno $inAluno;
	public ?string $inNumClasse;

	public function __construct(?InAluno $inAluno = null, ?string $inNumClasse = null)
	{
		$this->inAluno = $inAluno;
		$this->inNumClasse = $inNumClasse;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInNumClasse(): ?string
	{
		return $this->inNumClasse;
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

	public static function fromJson(array $data): self
	{
		return new self(
			($data['inAluno'] ?? null) !== null ? InAluno::fromJson($data['inAluno']) : null,
			$data['inNumClasse'] ?? null
		);
	}

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}