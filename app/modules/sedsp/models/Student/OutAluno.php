<?php

class OutAluno
{
	private $outSucesso;
    private $outProcessoID;
    private $outDataAlteracaoFicha;
    private $outOperador;
    private $outDadosPessoais;
    private $outDocumentos;
    private $outJustificativaDocumentos;
    private $outCertidaoNova;
    private $outCertidaoAntiga;
    private $outEnderecoResidencial;
    private $outEnderecoIndicativo;
    private $outTelefones;
    private $outDeficiencia;
    private $outRecursoAvaliacao;

	/**
	 * Summary of __construct
	 * @param OutAluno $aluno
	 *
	 */
	public function __construct($aluno) 
	{
		$this->outSucesso = $aluno->outSucesso;
		$this->outProcessoID = $aluno->outProcessoID;
		$this->outDataAlteracaoFicha = $aluno->outDataAlteracaoFicha;
		$this->outOperador = $aluno->outOperador;
		$this->outDadosPessoais = new OutDadosPessoais($aluno->outDadosPessoais);
		$this->outDocumentos = new OutDocumentos($aluno->outDocumentos);
		$this->outCertidaoNova = new OutCertidaoNova($aluno->outCertidaoNova);
		$this->outEnderecoResidencial = new OutEnderecoResidencial($aluno->outEnderecoResidencial);
		$this->outEnderecoIndicativo = new OutEnderecoIndicativo($aluno->outEnderecoIndicativo);
		$this->outDeficiencia = new OutDeficiencia($aluno->outDeficiencia);
		$this->outDocumentos = new OutDocumentos($aluno->outDocumentos);
		$this->outRecursoAvaliacao = new OutRecursoAvaliacao($aluno->outRecursoAvaliacao);
		$this->outJustificativaDocumentos = $aluno->outJustificativaDocumentos;
		$this->outCertidaoAntiga = $aluno->outCertidaoAntiga;
		$this->outTelefones = $aluno->outTelefones;
	}
	
	public function getOutSucesso(): string
	{
		return $this->outSucesso;
	}

	public function getOutProcessoId(): string
	{
		return $this->outProcessoID;
	}

	public function getOutDataAlteracaoFicha(): string
	{
		return $this->outDataAlteracaoFicha;
	}

	public function getOutOperador(): string
	{
		return $this->outOperador;
	}

	public function getOutDadosPessoais(): OutDadosPessoais
	{
		return $this->outDadosPessoais;
	}

	public function getOutDocumentos(): OutDocumentos
	{
		return $this->outDocumentos;
	}

	public function getOutJustificativaDocumentos(): string
	{
		return $this->outJustificativaDocumentos;
	}

	public function getOutCertidaoNova(): OutCertidaoNova
	{
		return $this->outCertidaoNova;
	}

	public function getOutEnderecoResidencial(): OutEnderecoResidencial
	{
		return $this->outEnderecoResidencial;
	}

	public function getOutEnderecoIndicativo(): OutEnderecoIndicativo
	{
		return $this->outEnderecoIndicativo;
	}

	public function getOutTelefones(): array
	{
		return $this->outTelefones;
	}

	public function getOutDeficiencia(): OutDeficiencia
	{
		return $this->outDeficiencia;
	}

	public function getOutRecursoAvaliacao(): OutRecursoAvaliacao
	{
		return $this->outRecursoAvaliacao;
	}
}
