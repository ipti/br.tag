<?php

class OutListaNecessidadesEspeciais
{
	public $outCodNecesEspecial;
	public $outNomeNecesEspecial;

	public function __construct(?string $outCodNecesEspecial, ?string $outNomeNecesEspecial)
	{
		$this->outCodNecesEspecial = $outCodNecesEspecial;
		$this->outNomeNecesEspecial = $outNomeNecesEspecial;
	}

	public function getOutCodNecesEspecial(): ?string
	{
		return $this->outCodNecesEspecial;
	}

	public function getOutNomeNecesEspecial(): ?string
	{
		return $this->outNomeNecesEspecial;
	}

	public function setOutCodNecesEspecial(?string $outCodNecesEspecial): self
	{
		$this->outCodNecesEspecial = $outCodNecesEspecial;
		return $this;
	}

	public function setOutNomeNecesEspecial(?string $outNomeNecesEspecial): self
	{
		$this->outNomeNecesEspecial = $outNomeNecesEspecial;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodNecesEspecial'] ?? null,
			$data['outNomeNecesEspecial'] ?? null
		);
	}
}