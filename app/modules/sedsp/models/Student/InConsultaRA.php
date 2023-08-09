<?php

class InConsultaRA 
{
    public $inCodEscola;
	public $inNomeAluno;
	public $inNomeMae;
	public $inDataNascimento;

	public function __construct(
		?string $inCodEscola,
		?string $inNomeAluno,
		?string $inNomeMae,
		?string $inDataNascimento
	) {
		$this->inCodEscola = $inCodEscola;
		$this->inNomeAluno = $inNomeAluno;
		$this->inNomeMae = $inNomeMae;
		$this->inDataNascimento = $inDataNascimento;
	}

	public function getInCodEscola(): ?string
	{
		return $this->inCodEscola;
	}

	public function getInNomeAluno(): ?string
	{
		return $this->inNomeAluno;
	}

	public function getInNomeMae(): ?string
	{
		return $this->inNomeMae;
	}

	public function getInDataNascimento(): ?string
	{
		return $this->inDataNascimento;
	}

	public function setInCodEscola(?string $inCodEscola): self
	{
		$this->inCodEscola = $inCodEscola;
		return $this;
	}

	public function setInNomeAluno(?string $inNomeAluno): self
	{
		$this->inNomeAluno = $inNomeAluno;
		return $this;
	}

	public function setInNomeMae(?string $inNomeMae): self
	{
		$this->inNomeMae = $inNomeMae;
		return $this;
	}

	public function setInDataNascimento(?string $inDataNascimento): self
	{
		$this->inDataNascimento = $inDataNascimento;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inCodEscola'] ?? null,
			$data['inNomeAluno'] ?? null,
			$data['inNomeMae'] ?? null,
			$data['inDataNascimento'] ?? null
		);
	}

    function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
