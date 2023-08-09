<?php

class InListarAlunos implements JsonSerializable
{
    public $inFiltrosNomes;
	public $inDataNascimento;
	public $inDocumentos;

	public function __construct(
		?InFiltrosNomes $inFiltrosNomes,
		?string $inDataNascimento,
		?InDocumentos $inDocumentos
	) {
		$this->inFiltrosNomes = $inFiltrosNomes;
		$this->inDataNascimento = $inDataNascimento;
		$this->inDocumentos = $inDocumentos;
	}

	public function getInFiltrosNomes(): ?InFiltrosNomes
	{
		return $this->inFiltrosNomes;
	}

	public function getInDataNascimento(): ?string
	{
		return $this->inDataNascimento;
	}

	public function getInDocumentos(): ?InDocumentos
	{
		return $this->inDocumentos;
	}

	public function setInFiltrosNomes(?InFiltrosNomes $inFiltrosNomes): self
	{
		$this->inFiltrosNomes = $inFiltrosNomes;
		return $this;
	}

	public function setInDataNascimento(?string $inDataNascimento): self
	{
		$this->inDataNascimento = $inDataNascimento;
		return $this;
	}

	public function setInDocumentos(?InDocumentos $inDocumentos): self
	{
		$this->inDocumentos = $inDocumentos;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			($data['inFiltrosNomes'] ?? null) !== null ? InFiltrosNomes::fromJson($data['inFiltrosNomes']) : null,
			$data['inDataNascimento'] ?? null,
			($data['inDocumentos'] ?? null) !== null ? InDocumentos::fromJson($data['inDocumentos']) : null
		);
	}

    function jsonSerialize()
	{
		return get_object_vars($this);
	}
}