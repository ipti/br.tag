<?php

class OutListaMatriculas
{
	public $outAnoLetivo;
	public $outMunicipio;
	public $outRedeEnsino;
	public $outCodEscola;
	public $outCodUnidade;
	public $outDescNomeAbrevEscola;
	public $outNumClasse;
	public $outNumAluno;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	public $outCodSerieAno;
	public $outDescSerieAno;
	public $outGrauNivel;
	public $outSerieNivel;
	public $outTurma;
	public $outDescTurma;
	public $outDataInicioMatricula;
	public $outDataFimMatricula;
	public $outDataInclusaoMatricula;
	public $outCodSitMatricula;
	public $outDescSitMatricula;
	public $outDescHabilitacao;
	public $outDescSitTranspEscolar;

	/**
	 * Summary of __construct
	 * @return OutListaMatriculas[]
	 */
	public function __construct($outListaMatricula) {
		$this->outAnoLetivo = $outListaMatricula[0]->outAnoLetivo;
		$this->outMunicipio = $outListaMatricula[0]->outMunicipio;
		$this->outRedeEnsino = $outListaMatricula[0]->outRedeEnsino;
		$this->outCodEscola = $outListaMatricula[0]->outCodEscola;
		$this->outCodUnidade = $outListaMatricula[0]->outCodUnidade;
		$this->outDescNomeAbrevEscola = $outListaMatricula[0]->outDescNomeAbrevEscola;
		$this->outNumClasse = $outListaMatricula[0]->outNumClasse;
		$this->outNumAluno = $outListaMatricula[0]->outNumAluno;
		$this->outCodTurno = $outListaMatricula[0]->outCodTurno;
		$this->outDescricaoTurno = $outListaMatricula[0]->outDescricaoTurno;
		$this->outCodTipoEnsino = $outListaMatricula[0]->outCodTipoEnsino;
		$this->outDescTipoEnsino = $outListaMatricula[0]->outDescTipoEnsino;
		$this->outCodSerieAno = $outListaMatricula[0]->outCodSerieAno;
		$this->outDescSerieAno = $outListaMatricula[0]->outDescSerieAno;
		$this->outGrauNivel = $outListaMatricula[0]->outGrauNivel;
		$this->outSerieNivel = $outListaMatricula[0]->outSerieNivel;
		$this->outTurma = $outListaMatricula[0]->outTurma;
		$this->outDescTurma = $outListaMatricula[0]->outDescTurma;
		$this->outDataInicioMatricula = $outListaMatricula[0]->outDataInicioMatricula;
		$this->outDataFimMatricula = $outListaMatricula[0]->outDataFimMatricula;
		$this->outDataInclusaoMatricula = $outListaMatricula[0]->outDataInclusaoMatricula;
		$this->outCodSitMatricula = $outListaMatricula[0]->outCodSitMatricula;
		$this->outDescSitMatricula = $outListaMatricula[0]->outDescSitMatricula;
		$this->outDescHabilitacao = $outListaMatricula[0]->outDescHabilitacao;
		$this->outDescSitTranspEscolar = $outListaMatricula[0]->outDescSitTranspEscolar;
	}	
}