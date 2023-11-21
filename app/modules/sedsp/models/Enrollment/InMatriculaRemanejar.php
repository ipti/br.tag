<?php 

class InMatriculaRemanejar implements JsonSerializable
{
    public ?string $inDataMovimento;
	public ?string $inNumAluno;
	public ?string $inNumClasseOrigem;
	public ?string $inNumClasseDestino;

	public function __construct(
		?string $inDataMovimento,
		?string $inNumAluno,
		?string $inNumClasseOrigem,
		?string $inNumClasseDestino
	) {
		$this->inDataMovimento = $inDataMovimento;
		$this->inNumAluno = $inNumAluno;
		$this->inNumClasseOrigem = $inNumClasseOrigem;
		$this->inNumClasseDestino = $inNumClasseDestino;
	}

	public function getInDataMovimento(): ?string
	{
		return $this->inDataMovimento;
	}

	public function getInNumAluno(): ?string
	{
		return $this->inNumAluno;
	}

	public function getInNumClasseOrigem(): ?string
	{
		return $this->inNumClasseOrigem;
	}

	public function getInNumClasseDestino(): ?string
	{
		return $this->inNumClasseDestino;
	}

	public function setInDataMovimento(?string $inDataMovimento): self
	{
		$this->inDataMovimento = $inDataMovimento;
		return $this;
	}

	public function setInNumAluno(?string $inNumAluno): self
	{
		$this->inNumAluno = $inNumAluno;
		return $this;
	}

	public function setInNumClasseOrigem(?string $inNumClasseOrigem): self
	{
		$this->inNumClasseOrigem = $inNumClasseOrigem;
		return $this;
	}

	public function setInNumClasseDestino(?string $inNumClasseDestino): self
	{
		$this->inNumClasseDestino = $inNumClasseDestino;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inDataMovimento'] ?? null,
			$data['inNumAluno'] ?? null,
			$data['inNumClasseOrigem'] ?? null,
			$data['inNumClasseDestino'] ?? null
		);
	}
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
