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

	public function __construct(
		?string $outAnoLetivo,
		?string $outMunicipio,
		?string $outRedeEnsino,
		?string $outCodEscola,
		?string $outCodUnidade,
		?string $outDescNomeAbrevEscola,
		?string $outNumClasse,
		?string $outNumAluno,
		?string $outCodTurno,
		?string $outDescricaoTurno,
		?string $outCodTipoEnsino,
		?string $outDescTipoEnsino,
		?string $outCodSerieAno,
		?string $outDescSerieAno,
		?string $outGrauNivel,
		?string $outSerieNivel,
		?string $outTurma,
		?string $outDescTurma,
		?string $outDataInicioMatricula,
		?string $outDataFimMatricula,
		?string $outDataInclusaoMatricula,
		?string $outCodSitMatricula,
		?string $outDescSitMatricula,
		?string $outDescHabilitacao,
		?string $outDescSitTranspEscolar
	) {
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outMunicipio = $outMunicipio;
		$this->outRedeEnsino = $outRedeEnsino;
		$this->outCodEscola = $outCodEscola;
		$this->outCodUnidade = $outCodUnidade;
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		$this->outNumClasse = $outNumClasse;
		$this->outNumAluno = $outNumAluno;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outDescSerieAno = $outDescSerieAno;
		$this->outGrauNivel = $outGrauNivel;
		$this->outSerieNivel = $outSerieNivel;
		$this->outTurma = $outTurma;
		$this->outDescTurma = $outDescTurma;
		$this->outDataInicioMatricula = $outDataInicioMatricula;
		$this->outDataFimMatricula = $outDataFimMatricula;
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;
		$this->outCodSitMatricula = $outCodSitMatricula;
		$this->outDescSitMatricula = $outDescSitMatricula;
		$this->outDescHabilitacao = $outDescHabilitacao;
		$this->outDescSitTranspEscolar = $outDescSitTranspEscolar;
	}
}