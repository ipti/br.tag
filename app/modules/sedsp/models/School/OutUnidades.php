<?php

class OutUnidades
{
	public $outCodUnidade;
	public $outDescNomeUnidade;
	public $outDDD;
	public $outTelefone;
	/** @var string[]|null */
	public $outNumSalas;

	/**
	 * @param string[]|null $outNumSalas
	 */
	public function __construct(
		?string $outCodUnidade,
		?string $outDescNomeUnidade,
		?string $outDDD,
		?string $outTelefone,
		?array $outNumSalas
	) {
		$this->outCodUnidade = $outCodUnidade;
		$this->outDescNomeUnidade = $outDescNomeUnidade;
		$this->outDDD = $outDDD;
		$this->outTelefone = $outTelefone;
		$this->outNumSalas = $outNumSalas;
	}

	public function getOutCodUnidade(): ?string
	{
		return $this->outCodUnidade;
	}

	public function getOutDescNomeUnidade(): ?string
	{
		return $this->outDescNomeUnidade;
	}

	public function getOutDdd(): ?string
	{
		return $this->outDDD;
	}

	public function getOutTelefone(): ?string
	{
		return $this->outTelefone;
	}

	/**
	 * @return string[]|null
	 */
	public function getOutNumSalas(): ?array
	{
		return $this->outNumSalas;
	}

	public function setOutCodUnidade(?string $outCodUnidade): self
	{
		$this->outCodUnidade = $outCodUnidade;
		return $this;
	}

	public function setOutDescNomeUnidade(?string $outDescNomeUnidade): self
	{
		$this->outDescNomeUnidade = $outDescNomeUnidade;
		return $this;
	}

	public function setOutDdd(?string $outDDD): self
	{
		$this->outDDD = $outDDD;
		return $this;
	}

	public function setOutTelefone(?string $outTelefone): self
	{
		$this->outTelefone = $outTelefone;
		return $this;
	}

	/**
	 * @param string[]|null $outNumSalas
	 */
	public function setOutNumSalas(?array $outNumSalas): self
	{
		$this->outNumSalas = $outNumSalas;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodUnidade'] ?? null,
			$data['outDescNomeUnidade'] ?? null,
			$data['outDDD'] ?? null,
			$data['outTelefone'] ?? null,
			$data['outNumSalas'] ?? null
		);
	}
}