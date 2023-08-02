<?php

class OutMatricula
{
	public $outAnoLetivo;
	public $outCodEscola;
	public $outDescNomeAbrevEscola;
	public $outNumClasse;
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	public $outCodSerieAno;
	public $outDescSerieAno;
	public $outGrauNivel;
	public $outSerieNivel;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outNumAluno;
	public $outCodHabilitacao;
	public $outDescHabilitacao;
	public $outTurma;
	public $outDescTurma;
	public $outDataInclusaoMatricula;
	public $outDataFimMatricula;
	public $outDataInicioMatricula;
	public $outHoraFinal;
	public $outHoraInicial;
	public $outCodSitMatricula;
	public $outDescSitMatricula;
	public $outCodSitTranspEscolar;
	public $outDescSitTranspEscolar ;

	public function __construct(
		string $outAnoLetivo,
		string $outCodEscola,
		string $outDescNomeAbrevEscola,
		string $outNumClasse,
		string $outCodTipoEnsino,
		string $outDescTipoEnsino,
		string $outCodSerieAno,
		string $outDescSerieAno,
		string $outGrauNivel,
		string $outSerieNivel,
		string $outCodTurno,
		string $outDescricaoTurno,
		string $outNumAluno,
		string $outCodHabilitacao,
		string $outDescHabilitacao,
		string $outTurma,
		string $outDescTurma,
		string $outDataInclusaoMatricula,
		string $outDataFimMatricula,
		string $outDataInicioMatricula,
		string $outHoraFinal,
		string $outHoraInicial,
		string $outCodSitMatricula,
		string $outDescSitMatricula,
		string $outCodSitTranspEscolar,
		string $outDescSitTranspEscolar 
	) {
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outCodEscola = $outCodEscola;
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		$this->outNumClasse = $outNumClasse;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outDescSerieAno = $outDescSerieAno;
		$this->outGrauNivel = $outGrauNivel;
		$this->outSerieNivel = $outSerieNivel;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outNumAluno = $outNumAluno;
		$this->outCodHabilitacao = $outCodHabilitacao;
		$this->outDescHabilitacao = $outDescHabilitacao;
		$this->outTurma = $outTurma;
		$this->outDescTurma = $outDescTurma;
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;
		$this->outDataFimMatricula = $outDataFimMatricula;
		$this->outDataInicioMatricula = $outDataInicioMatricula;
		$this->outHoraFinal = $outHoraFinal;
		$this->outHoraInicial = $outHoraInicial;
		$this->outCodSitMatricula = $outCodSitMatricula;
		$this->outDescSitMatricula = $outDescSitMatricula;
		$this->outCodSitTranspEscolar = $outCodSitTranspEscolar;
		$this->outDescSitTranspEscolar  = $outDescSitTranspEscolar ;
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
	 * Get the value of outCodHabilitacao
	 */
	public function getOutCodHabilitacao()
	{
		return $this->outCodHabilitacao;
	}

	/**
	 * Set the value of outCodHabilitacao
	 */
	public function setOutCodHabilitacao($outCodHabilitacao): self
	{
		$this->outCodHabilitacao = $outCodHabilitacao;

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
	 * Get the value of outHoraFinal
	 */
	public function getOutHoraFinal()
	{
		return $this->outHoraFinal;
	}

	/**
	 * Set the value of outHoraFinal
	 */
	public function setOutHoraFinal($outHoraFinal): self
	{
		$this->outHoraFinal = $outHoraFinal;

		return $this;
	}

	/**
	 * Get the value of outHoraInicial
	 */
	public function getOutHoraInicial()
	{
		return $this->outHoraInicial;
	}

	/**
	 * Set the value of outHoraInicial
	 */
	public function setOutHoraInicial($outHoraInicial): self
	{
		$this->outHoraInicial = $outHoraInicial;

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
	 * Get the value of outCodSitTranspEscolar
	 */
	public function getOutCodSitTranspEscolar()
	{
		return $this->outCodSitTranspEscolar;
	}

	/**
	 * Set the value of outCodSitTranspEscolar
	 */
	public function setOutCodSitTranspEscolar($outCodSitTranspEscolar): self
	{
		$this->outCodSitTranspEscolar = $outCodSitTranspEscolar;

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