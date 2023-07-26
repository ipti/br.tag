<?php

/**
 * Summary of OutListarAlunos
 */
class OutListarAlunos 
{
    /** @var string */
    public $outNumRA;

    /** @var string */
	public $outDigitoRA;

    /** @var string */
	public $outSiglaUFRA;

    /** @var string */
	public $outNomeAluno;

    /** @var string */
	public $outNomeMae;

    /** @var string */
	public $outDataNascimento;

    /** @var string */
	public $outNomePai;


    /**
     * 
     * Summary of __construct
     * @param object $listaAlunos
     * @return OutListarAlunos
     * 
     */
    public function __construct(object $listaAlunos) 
    {
        $this->outNumRA = $listaAlunos->outNumRA;
        $this->outDigitoRA = $listaAlunos->outDigitoRA;
        $this->outSiglaUFRA = $listaAlunos->outSiglaUFRA;
        $this->outNomeAluno = $listaAlunos->outNomeAluno;
        $this->outNomeMae = $listaAlunos->outNomeMae;
        $this->outDataNascimento = $listaAlunos->outDataNascimento;
        $this->outNomePai = $listaAlunos->outNomePai;
    }
}
