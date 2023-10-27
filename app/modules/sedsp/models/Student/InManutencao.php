<?php

class InManutencao implements JsonSerializable
{
	public ?InAluno $inAluno;
	public ?InDadosPessoais $inDadosPessoais;
	public ?InDeficiencia $inDeficiencia;
	public ?InRecursoAvaliacao $inRecursoAvaliacao;
	public ?InDocumentos $inDocumentos;
	public ?InCertidaoAntiga $inCertidaoAntiga;
	public ?InCertidaoNova $inCertidaoNova;
	public ?InEnderecoResidencial $inEnderecoResidencial;
	/** @var InTelefone[]|null */
	public ?array $inTelefone;
	public ?InRastreio $inRastreio;

	/**
	 * @param InTelefone[]|null $inTelefone
	 */
	public function __construct(
		?InAluno $inAluno,
		?InDadosPessoais $inDadosPessoais,
		?InDeficiencia $inDeficiencia,
		?InRecursoAvaliacao $inRecursoAvaliacao,
		?InDocumentos $inDocumentos,
		?InCertidaoAntiga $inCertidaoAntiga,
		?InCertidaoNova $inCertidaoNova,
		?InEnderecoResidencial $inEnderecoResidencial,
		?array $inTelefone,
		?InRastreio $inRastreio
	) {
		$this->inAluno = $inAluno;
		$this->inDadosPessoais = $inDadosPessoais;
		$this->inDeficiencia = $inDeficiencia;
		$this->inRecursoAvaliacao = $inRecursoAvaliacao;
		$this->inDocumentos = $inDocumentos;
		$this->inCertidaoAntiga = $inCertidaoAntiga;
		$this->inCertidaoNova = $inCertidaoNova;
		$this->inEnderecoResidencial = $inEnderecoResidencial;
		$this->inTelefone = $inTelefone;
		$this->inRastreio = $inRastreio;
	}

	public function getInAluno(): ?InAluno
	{
		return $this->inAluno;
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

	/**
	 * @return InTelefone[]|null
	 */
	public function getInTelefone(): ?array
	{
		return $this->inTelefone;
	}

	public function getInRastreio(): ?InRastreio
	{
		return $this->inRastreio;
	}

	public function setInAluno(?InAluno $inAluno): self
	{
		$this->inAluno = $inAluno;
		return $this;
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

	/**
	 * @param InTelefone[]|null $inTelefone
	 */
	public function setInTelefone(?array $inTelefone): self
	{
		$this->inTelefone = $inTelefone;
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
			($data['inAluno'] ?? null) !== null ? InAluno::fromJson($data['inAluno']) : null,
			($data['inDadosPessoais'] ?? null) !== null ? InDadosPessoais::fromJson($data['inDadosPessoais']) : null,
			($data['inDeficiencia'] ?? null) !== null ? InDeficiencia::fromJson($data['inDeficiencia']) : null,
			($data['inRecursoAvaliacao'] ?? null) !== null ? InRecursoAvaliacao::fromJson($data['inRecursoAvaliacao']) : null,
			($data['inDocumentos'] ?? null) !== null ? InDocumentos::fromJson($data['inDocumentos']) : null,
			($data['inCertidaoAntiga'] ?? null) !== null ? InCertidaoAntiga::fromJson($data['inCertidaoAntiga']) : null,
			($data['inCertidaoNova'] ?? null) !== null ? InCertidaoNova::fromJson($data['inCertidaoNova']) : null,
			($data['inEnderecoResidencial'] ?? null) !== null ? InEnderecoResidencial::fromJson($data['inEnderecoResidencial']) : null,
			($data['inTelefone'] ?? null) !== null ? array_map(static function ($data) {
				return InTelefone::fromJson($data);
			}, $data['inTelefone']) : null,
			($data['inRastreio'] ?? null) !== null ? InRastreio::fromJson($data['inRastreio']) : null
		);
	}

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
