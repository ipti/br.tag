<?php 

class AlunoFicha implements JsonSerializable
{
	private $inDadosPessoais;

	private $inDeficiencia;

	private $inRecursoAvaliacao;

	private $inDocumentos;

	private $inCertidaoAntiga;

	private $inCertidaoNova;

	private $inEnderecoResidencial;

	private $inRastreio;

	/**
	 * @param InDadosPessoais|null $inDadosPessoais
	 * @param InDeficiencia|null $inDeficiencia
	 * @param InRecursoAvaliacao|null $inRecursoAvaliacao
	 * @param InDocumentos|null $inDocumentos
	 * @param InCertidaoAntiga|null $inCertidaoAntiga
	 * @param InCertidaoNova|null $inCertidaoNova
	 * @param InEnderecoResidencial|null $inEnderecoResidencial
	 * @param InRastreio|null $inRastreio
	 */
	public function __construct(
		?InDadosPessoais $inDadosPessoais,
		?InDeficiencia $inDeficiencia,
		?InRecursoAvaliacao $inRecursoAvaliacao,
		?InDocumentos $inDocumentos,
		?InCertidaoAntiga $inCertidaoAntiga,
		?InCertidaoNova $inCertidaoNova,
		?InEnderecoResidencial $inEnderecoResidencial,
		?InRastreio $inRastreio
	) {
		$this->inDadosPessoais = $inDadosPessoais;
		$this->inDeficiencia = $inDeficiencia;
		$this->inRecursoAvaliacao = $inRecursoAvaliacao;
		$this->inDocumentos = $inDocumentos;
		$this->inCertidaoAntiga = $inCertidaoAntiga;
		$this->inCertidaoNova = $inCertidaoNova;
		$this->inEnderecoResidencial = $inEnderecoResidencial;
		$this->inRastreio = $inRastreio;
	}

	/**
	 * @param InDadosPessoais|null $inDadosPessoais
	 * @return self
	 */
	public function setInDadosPessoais(?InDadosPessoais $inDadosPessoais): self
	{
		$this->inDadosPessoais = $inDadosPessoais;
		return $this;
	}

	/**
	 * @param InDeficiencia|null $inDeficiencia
	 * @return self
	 */
	public function setInDeficiencia(?InDeficiencia $inDeficiencia): self
	{
		$this->inDeficiencia = $inDeficiencia;
		return $this;
	}

	/**
	 * @param InRecursoAvaliacao|null $inRecursoAvaliacao
	 * @return self
	 */
	public function setInRecursoAvaliacao(?InRecursoAvaliacao $inRecursoAvaliacao): self
	{
		$this->inRecursoAvaliacao = $inRecursoAvaliacao;
		return $this;
	}

	/**
	 * @param InDocumentos|null $inDocumentos
	 * @return self
	 */
	public function setInDocumentos(?InDocumentos $inDocumentos): self
	{
		$this->inDocumentos = $inDocumentos;
		return $this;
	}

	/**
	 * @param InCertidaoAntiga|null $inCertidaoAntiga
	 * @return self
	 */
	public function setInCertidaoAntiga(?InCertidaoAntiga $inCertidaoAntiga): self
	{
		$this->inCertidaoAntiga = $inCertidaoAntiga;
		return $this;
	}

	/**
	 * @param InCertidaoNova|null $inCertidaoNova
	 * @return self
	 */
	public function setInCertidaoNova(?InCertidaoNova $inCertidaoNova): self
	{
		$this->inCertidaoNova = $inCertidaoNova;
		return $this;
	}

	/**
	 * @param InEnderecoResidencial|null $inEnderecoResidencial
	 * @return self
	 */
	public function setInEnderecoResidencial(?InEnderecoResidencial $inEnderecoResidencial): self
	{
		$this->inEnderecoResidencial = $inEnderecoResidencial;
		return $this;
	}

	/**
	 * @param InRastreio|null $inRastreio
	 * @return self
	 */
	public function setInRastreio(?InRastreio $inRastreio): self
	{
		$this->inRastreio = $inRastreio;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			($data['inDadosPessoais'] ?? null) !== null ? InDadosPessoais::fromJson($data['inDadosPessoais']) : null,
			($data['inDeficiencia'] ?? null) !== null ? InDeficiencia::fromJson($data['inDeficiencia']) : null,
			($data['inRecursoAvaliacao'] ?? null) !== null ? InRecursoAvaliacao::fromJson($data['inRecursoAvaliacao']) : null,
			($data['inDocumentos'] ?? null) !== null ? InDocumentos::fromJson($data['inDocumentos']) : null,
			($data['inCertidaoAntiga'] ?? null) !== null ? InCertidaoAntiga::fromJson($data['inCertidaoAntiga']) : null,
			($data['inCertidaoNova'] ?? null) !== null ? InCertidaoNova::fromJson($data['inCertidaoNova']) : null,
			($data['inEnderecoResidencial'] ?? null) !== null ? InEnderecoResidencial::fromJson($data['inEnderecoResidencial']) : null,
			($data['inRastreio'] ?? null) !== null ? InRastreio::fromJson($data['inRastreio']) : null
		);
	}

	public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}