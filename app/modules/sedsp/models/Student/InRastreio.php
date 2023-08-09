<?php

class InRastreio implements JsonSerializable
{
	public $inUsuarioRemoto;
	public $inNomeUsuario;
	public $inNumCPF;
	public $inLocalPerfilAcesso;

	public function __construct(
		?string $inUsuarioRemoto,
		?string $inNomeUsuario,
		?string $inNumCPF,
		?string $inLocalPerfilAcesso
	) {
		$this->inUsuarioRemoto = $inUsuarioRemoto;
		$this->inNomeUsuario = $inNomeUsuario;
		$this->inNumCPF = $inNumCPF;
		$this->inLocalPerfilAcesso = $inLocalPerfilAcesso;
	}

	public function getInUsuarioRemoto(): ?string
	{
		return $this->inUsuarioRemoto;
	}

	public function getInNomeUsuario(): ?string
	{
		return $this->inNomeUsuario;
	}

	public function getInNumCpf(): ?string
	{
		return $this->inNumCPF;
	}

	public function getInLocalPerfilAcesso(): ?string
	{
		return $this->inLocalPerfilAcesso;
	}

	public function setInUsuarioRemoto(?string $inUsuarioRemoto): self
	{
		$this->inUsuarioRemoto = $inUsuarioRemoto;
		return $this;
	}

	public function setInNomeUsuario(?string $inNomeUsuario): self
	{
		$this->inNomeUsuario = $inNomeUsuario;
		return $this;
	}

	public function setInNumCpf(?string $inNumCPF): self
	{
		$this->inNumCPF = $inNumCPF;
		return $this;
	}

	public function setInLocalPerfilAcesso(?string $inLocalPerfilAcesso): self
	{
		$this->inLocalPerfilAcesso = $inLocalPerfilAcesso;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inUsuarioRemoto'] ?? null,
			$data['inNomeUsuario'] ?? null,
			$data['inNumCPF'] ?? null,
			$data['inLocalPerfilAcesso'] ?? null
		);
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}