<?php

class InFichaAluno implements JsonSerializable
{
    public $inDadosPessoais;
	public $inDeficiencia;
	public $inRecursoAvaliacao;
	public $inDocumentos;
	public $inCertidaoAntiga;
	public $inCertidaoNova;
	public $inEnderecoResidencial;
	public $inRastreio;

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

	public function getInDadosPessoais(): ?InDadosPessoais
	{
		return $this->inDadosPessoais;
	}

	public function getInDeficiencia(): ?InDeficiencia
	{
		return $this->inDeficiencia;
	}

	public function getInRecursoAvaliacao(): ?InRecursoAvaliacao
	{
		return $this->inRecursoAvaliacao;
	}

	public function getInDocumentos(): ?InDocumentos
	{
		return $this->inDocumentos;
	}

	public function getInCertidaoAntiga(): ?InCertidaoAntiga
	{
		return $this->inCertidaoAntiga;
	}

	public function getInCertidaoNova(): ?InCertidaoNova
	{
		return $this->inCertidaoNova;
	}

	public function getInEnderecoResidencial(): ?InEnderecoResidencial
	{
		return $this->inEnderecoResidencial;
	}

	public function getInRastreio(): ?InRastreio
	{
		return $this->inRastreio;
	}

	public function setInDadosPessoais(?InDadosPessoais $inDadosPessoais): self
	{
		$this->inDadosPessoais = $inDadosPessoais;
		return $this;
	}

	public function setInDeficiencia(?InDeficiencia $inDeficiencia): self
	{
		$this->inDeficiencia = $inDeficiencia;
		return $this;
	}

	public function setInRecursoAvaliacao(?InRecursoAvaliacao $inRecursoAvaliacao): self
	{
		$this->inRecursoAvaliacao = $inRecursoAvaliacao;
		return $this;
	}

	public function setInDocumentos(?InDocumentos $inDocumentos): self
	{
		$this->inDocumentos = $inDocumentos;
		return $this;
	}

	public function setInCertidaoAntiga(?InCertidaoAntiga $inCertidaoAntiga): self
	{
		$this->inCertidaoAntiga = $inCertidaoAntiga;
		return $this;
	}

	public function setInCertidaoNova(?InCertidaoNova $inCertidaoNova): self
	{
		$this->inCertidaoNova = $inCertidaoNova;
		return $this;
	}

	public function setInEnderecoResidencial(?InEnderecoResidencial $inEnderecoResidencial): self
	{
		$this->inEnderecoResidencial = $inEnderecoResidencial;
		return $this;
	}

	public function setInRastreio(?InRastreio $inRastreio): self
	{
		$this->inRastreio = $inRastreio;
		return $this;
	}

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
    function jsonSerialize()
	{
		return get_object_vars($this);
	}
}