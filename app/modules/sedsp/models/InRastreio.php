<?php 

class InRastreio implements JsonSerializable
{
	private $inUsuarioRemoto;

	private $inNomeUsuario;

	private $inNumCpf;

	private $inLocalPerfilAcesso;

	/**
	 * @param string|null $inUsuarioRemoto
	 * @param string|null $inNomeUsuario
	 * @param string|null $inNumCpf
	 * @param string|null $inLocalPerfilAcesso
	 */
	public function __construct(
		?string $inUsuarioRemoto = null,
		?string $inNomeUsuario = null,
		?string $inNumCpf = null,
		?string $inLocalPerfilAcesso = null
	) {
		$this->inUsuarioRemoto = $inUsuarioRemoto;
		$this->inNomeUsuario = $inNomeUsuario;
		$this->inNumCpf = $inNumCpf;
		$this->inLocalPerfilAcesso = $inLocalPerfilAcesso;
	}

	/**
	 * @param string|null $inUsuarioRemoto
	 * @return self
	 */
	public function setInUsuarioRemoto(?string $inUsuarioRemoto): self
	{
		$this->inUsuarioRemoto = $inUsuarioRemoto;
		return $this;
	}

	/**
	 * @param string|null $inNomeUsuario
	 * @return self
	 */
	public function setInNomeUsuario(?string $inNomeUsuario): self
	{
		$this->inNomeUsuario = $inNomeUsuario;
		return $this;
	}

	/**
	 * @param string|null $inNumCpf
	 * @return self
	 */
	public function setInNumCpf(?string $inNumCpf): self
	{
		$this->inNumCpf = $inNumCpf;
		return $this;
	}

	/**
	 * @param string|null $inLocalPerfilAcesso
	 * @return self
	 */
	public function setInLocalPerfilAcesso(?string $inLocalPerfilAcesso): self
	{
		$this->inLocalPerfilAcesso = $inLocalPerfilAcesso;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outUsuarioRemoto'] ?? null,
			$data['outNomeUsuario'] ?? null,
			$data['outNumCPF'] ?? null,
			$data['outLocalPerfilAcesso'] ?? null
		);
	}

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}


?>