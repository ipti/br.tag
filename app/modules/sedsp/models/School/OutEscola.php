<?php

class OutEscola
{
    /** @var OutEscolas[]|null */
	public $outEscolas;
	public $outProcessoID;

	/**
	 * @param OutEscolas[]|null $outEscolas
	 */
	public function __construct(?array $outEscolas, ?string $outProcessoID)
	{
		$this->outEscolas = $outEscolas;
		$this->outProcessoID = $outProcessoID;
	}

	/**
	 * @return OutEscolas[]|null
	 */
	public function getOutEscolas(): ?array
	{
		return $this->outEscolas;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	/**
	 * @param OutEscolas[]|null $outEscolas
	 */
	public function setOutEscolas(?array $outEscolas): self
	{
		$this->outEscolas = $outEscolas;
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
			($data['outEscolas'] ?? null) !== null ? array_map(static function($data) {
				return OutEscolas::fromJson($data);
			}, $data['outEscolas']) : null,
			$data['outProcessoID'] ?? null
		);
	}
}
