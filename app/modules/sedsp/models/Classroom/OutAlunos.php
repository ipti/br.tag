<?php 

class OutAlunos
{
	private $outNumRA;
	private $outDigitoRA;
	private $outSiglaUFRA;
	private $outNomeAluno;
	private $outNumAluno;
	private $outDataNascimento;
	private $outGrauNivel;
	private $outSerieNivel;
	private $outCodSitMatricula;
	private $outDescSitMatricula;

    /**
     * Summary of __construct
     * @param OutAlunos $aluno
	 * @return OutAluno[]
	 * 
     */
    public function __construct($aluno) {
        $this->outNumRA = $aluno->outNumRA;
        $this->outDigitoRA = $aluno->outDigitoRA;
        $this->outSiglaUFRA = $aluno->outSiglaUFRA;
        $this->outNomeAluno = $aluno->outNomeAluno;
        $this->outNumAluno = $aluno->outNumAluno;
        $this->outDataNascimento = $aluno->outDataNascimento;
        $this->outGrauNivel = $aluno->outGrauNivel;
        $this->outSerieNivel = $aluno->outSerieNivel;
        $this->outCodSitMatricula = $aluno->outCodSitMatricula;
        $this->outDescSitMatricula = $aluno->outDescSitMatricula;
    }
    
	public function getOutNumRa(): string
	{
		return $this->outNumRA;
	}

	public function getOutDigitoRa(): string
	{
		return $this->outDigitoRA;
	}

	public function getOutSiglaUfra(): string
	{
		return $this->outSiglaUFRA;
	}

	public function getOutNomeAluno(): string
	{
		return $this->outNomeAluno;
	}

	public function getOutNumAluno(): string
	{
		return $this->outNumAluno;
	}

	public function getOutDataNascimento(): string
	{
		return $this->outDataNascimento;
	}

	public function getOutGrauNivel(): string
	{
		return $this->outGrauNivel;
	}

	public function getOutSerieNivel(): string
	{
		return $this->outSerieNivel;
	}

	public function getOutCodSitMatricula(): string
	{
		return $this->outCodSitMatricula;
	}

	public function getOutDescSitMatricula(): string
	{
		return $this->outDescSitMatricula;
	}
}