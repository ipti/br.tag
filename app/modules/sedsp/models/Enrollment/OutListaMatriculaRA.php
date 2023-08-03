<?php 

class OutListaMatriculaRA
{
	public $outAluno;

	/** @var OutListaMatriculas[]|null */
	public $outListaMatriculas;
	public $outProcessoID;

	/**
	 * @param OutListaMatriculas[]|null $outListaMatriculas
	 */
	public function __construct(
		?OutAluno $outAluno,
		?array $outListaMatriculas,
		?string $outProcessoID
	) {
		$this->outAluno = $outAluno;
		$this->outListaMatriculas = $outListaMatriculas;
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

	public function setOutProcessoId(?string $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

	/**
	 * Summary of fromJson
	 * @param array $data
	 * @return OutListaMatriculaRA
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outAluno'] ?? null,
			($data['outListaMatriculas'] ?? null) !== null ? array_map(static function($data) {
				return OutListaMatriculas::fromJson($data);
			}, 
			$data['outListaMatriculas']) : null,
			$data['outProcessoID'] ?? null
		);
	}	
}