<?php 

class RootObject
{
	public $outAluno;
	/** @var OutListaMatriculas[]|null */
	public $outListaMatriculas;
	public $outProcessoID;

	
    /**
     * Summary of __construct
     * @param InAluno $inAluno
     */
	public function __construct($inAluno) {
		$this->outAluno = new OutDadosPessoais($inAluno->inNumRa);
		$this->outListaMatriculas = $outListaMatriculas;
		$this->outProcessoID = $outProcessoID;
    }
}