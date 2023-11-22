<?php

class InBaixarMatricula implements JsonSerializable
{
    public ?InAluno $inAluno;
	public ?string $inTipoBaixa;
	public ?string $inMotivoBaixa;
	public ?string $inDataBaixa;
	public ?string $inNumClasse;

	public function __construct(
		?InAluno $inAluno,
		?string $inTipoBaixa,
		?string $inMotivoBaixa,
		?string $inDataBaixa,
		?string $inNumClasse
	) {
		$this->inAluno = $inAluno;
		$this->inTipoBaixa = $inTipoBaixa;
		$this->inMotivoBaixa = $inMotivoBaixa;
		$this->inDataBaixa = $inDataBaixa;
		$this->inNumClasse = $inNumClasse;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
	}

	public function getInTipoBaixa(): ?string
	{
		return $this->inTipoBaixa;
	}

	public function getInMotivoBaixa(): ?string
	{
		return $this->inMotivoBaixa;
	}

	public function getInDataBaixa(): ?string
	{
		return $this->inDataBaixa;
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

	public function setInTipoBaixa(?string $inTipoBaixa): self
	{
		$this->inTipoBaixa = $inTipoBaixa;
		return $this;
	}

	public function setInMotivoBaixa(?string $inMotivoBaixa): self
	{
		$this->inMotivoBaixa = $inMotivoBaixa;
		return $this;
	}

	public function setInDataBaixa(?string $inDataBaixa): self
	{
		$this->inDataBaixa = $inDataBaixa;
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
			$data['inTipoBaixa'] ?? null,
			$data['inMotivoBaixa'] ?? null,
			$data['inDataBaixa'] ?? null,
			$data['inNumClasse'] ?? null
		);
	}

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
