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

	/**
	 * Get the value of outAnoLetivo
	 */
	public function getOutAnoLetivo()
	{
		return $this->outAnoLetivo;
	}

	/**
	 * Set the value of outAnoLetivo
	 */
	public function setOutAnoLetivo($outAnoLetivo): self
	{
		$this->outAnoLetivo = $outAnoLetivo;

		return $this;
	}

	/**
	 * Get the value of outMunicipio
	 */
	public function getOutMunicipio()
	{
		return $this->outMunicipio;
	}

	/**
	 * Set the value of outMunicipio
	 */
	public function setOutMunicipio($outMunicipio): self
	{
		$this->outMunicipio = $outMunicipio;

		return $this;
	}

	/**
	 * Get the value of outRedeEnsino
	 */
	public function getOutRedeEnsino()
	{
		return $this->outRedeEnsino;
	}

	/**
	 * Set the value of outRedeEnsino
	 */
	public function setOutRedeEnsino($outRedeEnsino): self
	{
		$this->outRedeEnsino = $outRedeEnsino;

		return $this;
	}

	/**
	 * Get the value of outCodEscola
	 */
	public function getOutCodEscola()
	{
		return $this->outCodEscola;
	}

	/**
	 * Set the value of outCodEscola
	 */
	public function setOutCodEscola($outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;

		return $this;
	}

	/**
	 * Get the value of outCodUnidade
	 */
	public function getOutCodUnidade()
	{
		return $this->outCodUnidade;
	}

	/**
	 * Set the value of outCodUnidade
	 */
	public function setOutCodUnidade($outCodUnidade): self
	{
		$this->outCodUnidade = $outCodUnidade;

		return $this;
	}

	/**
	 * Get the value of outDescNomeAbrevEscola
	 */
	public function getOutDescNomeAbrevEscola()
	{
		return $this->outDescNomeAbrevEscola;
	}

	/**
	 * Set the value of outDescNomeAbrevEscola
	 */
	public function setOutDescNomeAbrevEscola($outDescNomeAbrevEscola): self
	{
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;

		return $this;
	}

	/**
	 * Get the value of outNumClasse
	 */
	public function getOutNumClasse()
	{
		return $this->outNumClasse;
	}

	/**
	 * Set the value of outNumClasse
	 */
	public function setOutNumClasse($outNumClasse): self
	{
		$this->outNumClasse = $outNumClasse;

		return $this;
	}

	/**
	 * Get the value of outNumAluno
	 */
	public function getOutNumAluno()
	{
		return $this->outNumAluno;
	}

	/**
	 * Set the value of outNumAluno
	 */
	public function setOutNumAluno($outNumAluno): self
	{
		$this->outNumAluno = $outNumAluno;

		return $this;
	}

	/**
	 * Get the value of outCodTurno
	 */
	public function getOutCodTurno()
	{
		return $this->outCodTurno;
	}

	/**
	 * Set the value of outCodTurno
	 */
	public function setOutCodTurno($outCodTurno): self
	{
		$this->outCodTurno = $outCodTurno;

		return $this;
	}

	/**
	 * Get the value of outDescricaoTurno
	 */
	public function getOutDescricaoTurno()
	{
		return $this->outDescricaoTurno;
	}

	/**
	 * Set the value of outDescricaoTurno
	 */
	public function setOutDescricaoTurno($outDescricaoTurno): self
	{
		$this->outDescricaoTurno = $outDescricaoTurno;

		return $this;
	}

	/**
	 * Get the value of outCodTipoEnsino
	 */
	public function getOutCodTipoEnsino()
	{
		return $this->outCodTipoEnsino;
	}

	/**
	 * Set the value of outCodTipoEnsino
	 */
	public function setOutCodTipoEnsino($outCodTipoEnsino): self
	{
		$this->outCodTipoEnsino = $outCodTipoEnsino;

		return $this;
	}

	/**
	 * Get the value of outDescTipoEnsino
	 */
	public function getOutDescTipoEnsino()
	{
		return $this->outDescTipoEnsino;
	}

	/**
	 * Set the value of outDescTipoEnsino
	 */
	public function setOutDescTipoEnsino($outDescTipoEnsino): self
	{
		$this->outDescTipoEnsino = $outDescTipoEnsino;

		return $this;
	}

	/**
	 * Get the value of outCodSerieAno
	 */
	public function getOutCodSerieAno()
	{
		return $this->outCodSerieAno;
	}

	/**
	 * Set the value of outCodSerieAno
	 */
	public function setOutCodSerieAno($outCodSerieAno): self
	{
		$this->outCodSerieAno = $outCodSerieAno;

		return $this;
	}

	/**
	 * Get the value of outDescSerieAno
	 */
	public function getOutDescSerieAno()
	{
		return $this->outDescSerieAno;
	}

	/**
	 * Set the value of outDescSerieAno
	 */
	public function setOutDescSerieAno($outDescSerieAno): self
	{
		$this->outDescSerieAno = $outDescSerieAno;

		return $this;
	}

	/**
	 * Get the value of outGrauNivel
	 */
	public function getOutGrauNivel()
	{
		return $this->outGrauNivel;
	}

	/**
	 * Set the value of outGrauNivel
	 */
	public function setOutGrauNivel($outGrauNivel): self
	{
		$this->outGrauNivel = $outGrauNivel;

		return $this;
	}

	/**
	 * Get the value of outSerieNivel
	 */
	public function getOutSerieNivel()
	{
		return $this->outSerieNivel;
	}

	/**
	 * Set the value of outSerieNivel
	 */
	public function setOutSerieNivel($outSerieNivel): self
	{
		$this->outSerieNivel = $outSerieNivel;

		return $this;
	}

	/**
	 * Get the value of outTurma
	 */
	public function getOutTurma()
	{
		return $this->outTurma;
	}

	/**
	 * Set the value of outTurma
	 */
	public function setOutTurma($outTurma): self
	{
		$this->outTurma = $outTurma;

		return $this;
	}

	/**
	 * Get the value of outDescTurma
	 */
	public function getOutDescTurma()
	{
		return $this->outDescTurma;
	}

	/**
	 * Set the value of outDescTurma
	 */
	public function setOutDescTurma($outDescTurma): self
	{
		$this->outDescTurma = $outDescTurma;

		return $this;
	}

	/**
	 * Get the value of outDataInicioMatricula
	 */
	public function getOutDataInicioMatricula()
	{
		return $this->outDataInicioMatricula;
	}

	/**
	 * Set the value of outDataInicioMatricula
	 */
	public function setOutDataInicioMatricula($outDataInicioMatricula): self
	{
		$this->outDataInicioMatricula = $outDataInicioMatricula;

		return $this;
	}

	/**
	 * Get the value of outDataFimMatricula
	 */
	public function getOutDataFimMatricula()
	{
		return $this->outDataFimMatricula;
	}

	/**
	 * Set the value of outDataFimMatricula
	 */
	public function setOutDataFimMatricula($outDataFimMatricula): self
	{
		$this->outDataFimMatricula = $outDataFimMatricula;

		return $this;
	}

	/**
	 * Get the value of outDataInclusaoMatricula
	 */
	public function getOutDataInclusaoMatricula()
	{
		return $this->outDataInclusaoMatricula;
	}

	/**
	 * Set the value of outDataInclusaoMatricula
	 */
	public function setOutDataInclusaoMatricula($outDataInclusaoMatricula): self
	{
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;

		return $this;
	}

	/**
	 * Get the value of outCodSitMatricula
	 */
	public function getOutCodSitMatricula()
	{
		return $this->outCodSitMatricula;
	}

	/**
	 * Set the value of outCodSitMatricula
	 */
	public function setOutCodSitMatricula($outCodSitMatricula): self
	{
		$this->outCodSitMatricula = $outCodSitMatricula;

		return $this;
	}

	/**
	 * Get the value of outDescSitMatricula
	 */
	public function getOutDescSitMatricula()
	{
		return $this->outDescSitMatricula;
	}

	/**
	 * Set the value of outDescSitMatricula
	 */
	public function setOutDescSitMatricula($outDescSitMatricula): self
	{
		$this->outDescSitMatricula = $outDescSitMatricula;

		return $this;
	}

	/**
	 * Get the value of outDescHabilitacao
	 */
	public function getOutDescHabilitacao()
	{
		return $this->outDescHabilitacao;
	}

	/**
	 * Set the value of outDescHabilitacao
	 */
	public function setOutDescHabilitacao($outDescHabilitacao): self
	{
		$this->outDescHabilitacao = $outDescHabilitacao;

		return $this;
	}

	/**
	 * Get the value of outDescSitTranspEscolar
	 */
	public function getOutDescSitTranspEscolar()
	{
		return $this->outDescSitTranspEscolar;
	}

	/**
	 * Set the value of outDescSitTranspEscolar
	 */
	public function setOutDescSitTranspEscolar($outDescSitTranspEscolar): self
	{
		$this->outDescSitTranspEscolar = $outDescSitTranspEscolar;

		return $this;
	}
}