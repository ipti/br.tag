<?php

class InEscola implements JsonSerializable
{
    public $inNomeEscola;
	public $inBairro;
	public $inEndereco;
	public $inMunicipio;

	public function __construct(
		?string $inNomeEscola,
		?string $inBairro,
		?string $inEndereco,
		?string $inMunicipio
	) {
		$this->inNomeEscola = $inNomeEscola;
		$this->inBairro = $inBairro;
		$this->inEndereco = $inEndereco;
		$this->inMunicipio = $inMunicipio;
	}

	public function getInNomeEscola(): ?string
	{
		return $this->inNomeEscola;
	}

	public function getInBairro(): ?string
	{
		return $this->inBairro;
	}

	public function getInEndereco(): ?string
	{
		return $this->inEndereco;
	}

	public function getInMunicipio(): ?string
	{
		return $this->inMunicipio;
	}

	public function setInNomeEscola(?string $inNomeEscola): self
	{
		$this->inNomeEscola = $inNomeEscola;
		return $this;
	}

	public function setInBairro(?string $inBairro): self
	{
		$this->inBairro = $inBairro;
		return $this;
	}

	public function setInEndereco(?string $inEndereco): self
	{
		$this->inEndereco = $inEndereco;
		return $this;
	}

	public function setInMunicipio(?string $inMunicipio): self
	{
		$this->inMunicipio = $inMunicipio;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNomeEscola'] ?? null,
			$data['inBairro'] ?? null,
			$data['inEndereco'] ?? null,
			$data['inMunicipio'] ?? null
		);
	}
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
