<?php

class InRastreio implements JsonSerializable
{
    public $inUsuarioRemoto;
	public $inNomeUsuario;
	public $inNumCPF;
	public $inLocalPerfilAcesso;

	public function __construct(
		string $inUsuarioRemoto,
		string $inNomeUsuario,
		string $inNumCPF,
		string $inLocalPerfilAcesso
	) {
		$this->inUsuarioRemoto = $inUsuarioRemoto;
		$this->inNomeUsuario = $inNomeUsuario;
		$this->inNumCPF = $inNumCPF;
		$this->inLocalPerfilAcesso = $inLocalPerfilAcesso;
	}

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

    /**
     * Get the value of inUsuarioRemoto
     */
    public function getInUsuarioRemoto()
    {
        return $this->inUsuarioRemoto;
    }

    /**
     * Set the value of inUsuarioRemoto
     */
    public function setInUsuarioRemoto($inUsuarioRemoto): self
    {
        $this->inUsuarioRemoto = $inUsuarioRemoto;

        return $this;
    }

	/**
	 * Get the value of inNomeUsuario
	 */
	public function getInNomeUsuario()
	{
		return $this->inNomeUsuario;
	}

	/**
	 * Set the value of inNomeUsuario
	 */
	public function setInNomeUsuario($inNomeUsuario): self
	{
		$this->inNomeUsuario = $inNomeUsuario;

		return $this;
	}

	/**
	 * Get the value of inNumCPF
	 */
	public function getInNumCPF()
	{
		return $this->inNumCPF;
	}

	/**
	 * Set the value of inNumCPF
	 */
	public function setInNumCPF($inNumCPF): self
	{
		$this->inNumCPF = $inNumCPF;

		return $this;
	}

	/**
	 * Get the value of inLocalPerfilAcesso
	 */
	public function getInLocalPerfilAcesso()
	{
		return $this->inLocalPerfilAcesso;
	}

	/**
	 * Set the value of inLocalPerfilAcesso
	 */
	public function setInLocalPerfilAcesso($inLocalPerfilAcesso): self
	{
		$this->inLocalPerfilAcesso = $inLocalPerfilAcesso;

		return $this;
	}
}