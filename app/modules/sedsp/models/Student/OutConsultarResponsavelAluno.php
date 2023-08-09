<?php

class OutConsultarResponsavelAluno
{
    public $outSucesso;
	public $outErro;
	public $outRequestID;
	/** @var OutResponsaveis[]|null */
	public $outResponsaveis;

	/**
	 * @param OutResponsaveis[]|null $outResponsaveis
	 */
	public function __construct(
		?string $outSucesso,
		?string $outErro,
		?string $outRequestID,
		?array $outResponsaveis
	) {
		$this->outSucesso = $outSucesso;
		$this->outErro = $outErro;
		$this->outRequestID = $outRequestID;
		$this->outResponsaveis = $outResponsaveis;
	}

	public function getOutSucesso(): ?string
	{
		return $this->outSucesso;
	}

	public function getOutErro(): ?string
	{
		return $this->outErro;
	}

	public function getOutRequestId(): ?string
	{
		return $this->outRequestID;
	}

	/**
	 * @return OutResponsaveis[]|null
	 */
	public function getOutResponsaveis(): ?array
	{
		return $this->outResponsaveis;
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

	public function setOutRequestId(?string $outRequestID): self
	{
		$this->outRequestID = $outRequestID;
		return $this;
	}

	/**
	 * @param OutResponsaveis[]|null $outResponsaveis
	 */
	public function setOutResponsaveis(?array $outResponsaveis): self
	{
		$this->outResponsaveis = $outResponsaveis;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outSucesso'] ?? null,
			$data['outErro'] ?? null,
			$data['outRequestID'] ?? null,
			($data['outResponsaveis'] ?? null) !== null ? array_map(static function($data) {
				return OutResponsaveis::fromJson($data);
			}, $data['outResponsaveis']) : null
		);
	}
}
