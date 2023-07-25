<?php 

class OutListaMatriculaRA
{
	public $outAluno;

	/** @var OutListaMatriculas[]*/
	public $outListaMatriculas;
	public $outProcessoID;

	

	/**
	 * Summary of __construct
	 * @param OutListaMatriculaRA $outListaMatriculaRA
	 * @return OutListaMatriculaRA
	 */
	public function __construct($outListaMatriculaRA) {
		$this->outAluno = new OutDadosPessoais($outListaMatriculaRA->outAluno);
		$this->outListaMatriculas = new OutListaMatriculas($outListaMatriculaRA->outListaMatriculas);
		$this->outProcessoID = $outListaMatriculaRA->outProcessoID;
    }
}