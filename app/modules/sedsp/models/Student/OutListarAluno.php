<?php

class OutListarAluno
{
    /** @var OutListaAlunos[]|null */
	public $outListaAlunos;
	public $outErro;
	public $outProcessoID;

	/**
	 * @param OutListaAlunos[]|null $outListaAlunos
	 */
	public function __construct(
		?array $outListaAlunos,
		?string $outErro,
		?string $outProcessoID
	) {
		$this->outListaAlunos = $outListaAlunos;
		$this->outErro = $outErro;
		$this->outProcessoID = $outProcessoID;
	}

	/**
	 * @return OutListaAlunos[]|null
	 */
	public function getOutListaAlunos(): ?array
	{
		return $this->outListaAlunos;
	}

	public function getOutErro(): ?string
	{
		return $this->outErro;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	/**
	 * @param OutListaAlunos[]|null $outListaAlunos
	 */
	public function setOutListaAlunos(?array $outListaAlunos): self
	{
		$this->outListaAlunos = $outListaAlunos;
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
			($data['outListaAlunos'] ?? null) !== null ? array_map(static function($data) {
				return OutListaAlunos::fromJson($data);
			}, $data['outListaAlunos']) : null,
			$data['outErro'] ?? null,
			$data['outProcessoID'] ?? null
		);
	}
}
