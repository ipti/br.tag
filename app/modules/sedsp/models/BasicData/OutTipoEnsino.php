<?php

class OutTipoEnsino
{
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	/** @var OutSerieAno[]|null */
	public $outSerieAno;

	/**
	 * @param OutSerieAno[]|null $outSerieAno
	 */
	public function __construct(
		?string $outCodTipoEnsino,
		?string $outDescTipoEnsino,
		?array $outSerieAno
	) {
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outSerieAno = $outSerieAno;
	}

	public function getOutCodTipoEnsino(): ?string
	{
		return $this->outCodTipoEnsino;
	}

	public function getOutDescTipoEnsino(): ?string
	{
		return $this->outDescTipoEnsino;
	}

	/**
	 * @return OutSerieAno[]|null
	 */
	public function getOutSerieAno(): ?array
	{
		return $this->outSerieAno;
	}

	public function setOutCodTipoEnsino(?string $outCodTipoEnsino): self
	{
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		return $this;
	}

	public function setOutDescTipoEnsino(?string $outDescTipoEnsino): self
	{
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		return $this;
	}

	/**
	 * @param OutSerieAno[]|null $outSerieAno
	 */
	public function setOutSerieAno(?array $outSerieAno): self
	{
		$this->outSerieAno = $outSerieAno;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodTipoEnsino'] ?? null,
			$data['outDescTipoEnsino'] ?? null,
			($data['outSerieAno'] ?? null) !== null ? array_map(static function($data) {
				return OutSerieAno::fromJson($data);
			}, $data['outSerieAno']) : null
		);
	}
}