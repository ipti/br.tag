<?php

class OutFichaAluno
{
    public $outAluno;
	public $outSucesso;
	public $outErro;
	public $outProcessoID;

	public function __construct(
		?OutAluno $outAluno,
		?string $outSucesso,
		?string $outErro,
		?string $outProcessoID
	) {
		$this->outAluno = $outAluno;
		$this->outSucesso = $outSucesso;
		$this->outErro = $outErro;
		$this->outProcessoID = $outProcessoID;
	}

	public function getOutAluno(): ?OutAluno
	{
		return $this->outAluno;
	}

	public function getOutSucesso(): ?string
	{
		return $this->outSucesso;
	}

	public function getOutErro(): ?string
	{
		return $this->outErro;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	public function setOutAluno(?OutAluno $outAluno): self
	{
		$this->outAluno = $outAluno;
		return $this;
	}

	public function setOutSucesso(?string $outSucesso): self
	{
		$this->outSucesso = $outSucesso;
		return $this;
	}

	public function setOutErro(?string $outErro): self
	{
		$this->outErro = $outErro;
		return $this;
	}

	public function setOutProcessoId(?string $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			($data['outAluno'] ?? null) !== null ? OutAluno::fromJson($data['outAluno']) : null,
			$data['outSucesso'] ?? null,
			$data['outErro'] ?? null,
			$data['outProcessoID'] ?? null
		);
	}
}
