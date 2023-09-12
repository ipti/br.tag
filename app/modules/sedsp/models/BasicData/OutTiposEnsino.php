<?php

class OutTiposEnsino 
{
    	/** @var OutTipoEnsino[]|null */
	public $outTipoEnsino;
	public $outProcessoID;

	/**
	 * @param OutTipoEnsino[]|null $outTipoEnsino
	 */
	public function __construct(?array $outTipoEnsino, ?string $outProcessoID)
	{
		$this->outTipoEnsino = $outTipoEnsino;
		$this->outProcessoID = $outProcessoID;
	}

	/**
	 * @return OutTipoEnsino[]|null
	 */
	public function getOutTipoEnsino(): ?array
	{
		return $this->outTipoEnsino;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	/**
	 * @param OutTipoEnsino[]|null $outTipoEnsino
	 */
	public function setOutTipoEnsino(?array $outTipoEnsino): self
	{
		$this->outTipoEnsino = $outTipoEnsino;
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
			($data['outTipoEnsino'] ?? null) !== null ? array_map(static function($data) {
				return OutTipoEnsino::fromJson($data);
			}, $data['outTipoEnsino']) : null,
			$data['outProcessoID'] ?? null
		);
	}
}
