<?php

class InFiltrosNomes implements JsonSerializable
{
	public $inNomeAluno;
	public $inNomeSocial;
	public $inNomeMae;
	public $inNomePai;

	public function __construct(
		?string $inNomeAluno,
		?string $inNomeSocial,
		?string $inNomeMae,
		?string $inNomePai
	) {
		$this->inNomeAluno = $inNomeAluno;
		$this->inNomeSocial = $inNomeSocial;
		$this->inNomeMae = $inNomeMae;
		$this->inNomePai = $inNomePai;
	}

	public function getInNomeAluno(): ?string
	{
		return $this->inNomeAluno;
	}

	public function getInNomeSocial(): ?string
	{
		return $this->inNomeSocial;
	}

	public function getInNomeMae(): ?string
	{
		return $this->inNomeMae;
	}

	public function getInNomePai(): ?string
	{
		return $this->inNomePai;
	}

	public function setInNomeAluno(?string $inNomeAluno): self
	{
		$this->inNomeAluno = $inNomeAluno;
		return $this;
	}

	public function setInNomeSocial(?string $inNomeSocial): self
	{
		$this->inNomeSocial = $inNomeSocial;
		return $this;
	}

	public function setInNomeMae(?string $inNomeMae): self
	{
		$this->inNomeMae = $inNomeMae;
		return $this;
	}

	public function setInNomePai(?string $inNomePai): self
	{
		$this->inNomePai = $inNomePai;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNomeAluno'] ?? null,
			$data['inNomeSocial'] ?? null,
			$data['inNomeMae'] ?? null,
			$data['inNomePai'] ?? null
		);
	}

    public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}