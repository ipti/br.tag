<?php

class InMatriculaTrocar implements JsonSerializable
{
    public ?string $inAnoLetivo;
	public ?string $inDataTroca;
	public ?string $inNumAluno;
	public ?string $inNumClasseOrigem;
	public ?string $inNumClasseDestino;

	public function __construct(
		?string $inAnoLetivo,
		?string $inDataTroca,
		?string $inNumAluno,
		?string $inNumClasseOrigem,
		?string $inNumClasseDestino
	) {
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inDataTroca = $inDataTroca;
		$this->inNumAluno = $inNumAluno;
		$this->inNumClasseOrigem = $inNumClasseOrigem;
		$this->inNumClasseDestino = $inNumClasseDestino;
	}

	public function getInAnoLetivo(): ?string
	{
		return $this->inAnoLetivo;
	}

	public function getInDataTroca(): ?string
	{
		return $this->inDataTroca;
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

	public function setInAnoLetivo(?string $inAnoLetivo): self
	{
		$this->inAnoLetivo = $inAnoLetivo;
		return $this;
	}

	public function setInDataTroca(?string $inDataTroca): self
	{
		$this->inDataTroca = $inDataTroca;
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
			$data['inAnoLetivo'] ?? null,
			$data['inDataTroca'] ?? null,
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
