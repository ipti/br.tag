<?php 

class OutListaMatriculaRA
{
	public $outAluno;
	/** @var OutListaMatriculas[]|null */
	public $outListaMatriculas;
	public $outErro;
	public $outProcessoID;

	/**
	 * @param OutListaMatriculas[]|null $outListaMatriculas
	 */
	public function __construct(
		?OutAluno $outAluno,
		?array $outListaMatriculas,
		?string $outErro,
		?string $outProcessoID
	) {
		$this->outAluno = $outAluno;
		$this->outListaMatriculas = $outListaMatriculas;
		$this->outErro = $outErro;
		$this->outProcessoID = $outProcessoID;
	}

	public function getOutAluno(): ?OutAluno
	{
		return $this->outAluno;
	}

	/**
	 * @return OutListaMatriculas[]|null
	 */
	public function getOutListaMatriculas(): ?array
	{
		return $this->outListaMatriculas;
	}

	public function getOutErro(): ?string
	{
		return $this->outErro;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	public function setOutAluno(?OutAluno $outAluno): self
	{
		$this->outAluno = $outAluno;
		return $this;
	}

	/**
	 * @param OutListaMatriculas[]|null $outListaMatriculas
	 */
	public function setOutListaMatriculas(?array $outListaMatriculas): self
	{
		$this->outListaMatriculas = $outListaMatriculas;
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
			($data['outAluno'] ?? null) !== null ? OutAluno::fromJson($data['outAluno']) : null,
			($data['outListaMatriculas'] ?? null) !== null ? array_map(static function($data) {
				return OutListaMatriculas::fromJson($data);
			}, $data['outListaMatriculas']) : null,
			$data['outErro'] ?? null,
			$data['outProcessoID'] ?? null
		);
	}
}