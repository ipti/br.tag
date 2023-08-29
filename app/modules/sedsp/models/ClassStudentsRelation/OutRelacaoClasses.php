<?php

class OutRelacaoClasses
{
    public $outAnoLetivo;
	public $outCodEscola;
	public $outDescNomeAbrevEscola;
	/** @var OutClasses[]|null */
	public $outClasses;
	public $outErro;
	public $outProcessoID;

	/**
	 * @param OutClasses[]|null $outClasses
	 */
	public function __construct(
		?string $outAnoLetivo,
		?string $outCodEscola,
		?string $outDescNomeAbrevEscola,
		?array $outClasses,
		?string $outErro,
		?string $outProcessoID
	) {
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outCodEscola = $outCodEscola;
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		$this->outClasses = $outClasses;
		$this->outErro = $outErro;
		$this->outProcessoID = $outProcessoID;
	}

	public function getOutAnoLetivo(): ?string
	{
		return $this->outAnoLetivo;
	}

	public function getOutCodEscola(): ?string
	{
		return $this->outCodEscola;
	}

	public function getOutDescNomeAbrevEscola(): ?string
	{
		return $this->outDescNomeAbrevEscola;
	}

	/**
	 * @return OutClasses[]|null
	 */
	public function getOutClasses(): ?array
	{
		return $this->outClasses;
	}

	public function getOutErro(): ?string
	{
		return $this->outErro;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	public function setOutAnoLetivo(?string $outAnoLetivo): self
	{
		$this->outAnoLetivo = $outAnoLetivo;
		return $this;
	}

	public function setOutCodEscola(?string $outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;
		return $this;
	}

	public function setOutDescNomeAbrevEscola(?string $outDescNomeAbrevEscola): self
	{
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		return $this;
	}

	/**
	 * @param OutClasses[]|null $outClasses
	 */
	public function setOutClasses(?array $outClasses): self
	{
		$this->outClasses = $outClasses;
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
			$data['outAnoLetivo'] ?? null,
			$data['outCodEscola'] ?? null,
			$data['outDescNomeAbrevEscola'] ?? null,
			($data['outClasses'] ?? null) !== null ? array_map(static function($data) {
				return OutClasses::fromJson($data);
			}, $data['outClasses']) : null,
			$data['outErro'] ?? null,
			$data['outProcessoID'] ?? null
		);
	}
}
