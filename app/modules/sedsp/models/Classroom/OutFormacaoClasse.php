<?php

class OutFormacaoClasse
{
	public $outCodEscola;
	public $outDescNomeAbrevEscola;
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	public $outAnoLetivo;
	public $outNumClasse;
	public $outCodSerieAno;
	public $outTurma;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outCodHabilitacao;
	public $outCodTipoClasse;
	public $outNumSala;
	public $outHorarioInicio;
	public $outHorarioFim;
	public $outQtdAtual;
	public $outQtdDigitados;
	public $outQtdEvadidos;
	public $outQtdNCom;
	public $outQtdOutros;
	public $outQtdTransferidos;
	public $outQtdRemanejados;
	public $outQtdCessados;
	public $outQtdReclassificados;
	public $outCapacidadeFisicaMax;
	public $outDataInicioAula;
	public $outDataFimAula;
	/** @var OutAlunos[] */
	public $outAlunos;

	/**
	 * @param OutAlunos[] $outAlunos
	 */
	public function __construct(
		string $outCodEscola,
		string $outDescNomeAbrevEscola,
		string $outCodTipoEnsino,
		string $outDescTipoEnsino,
		string $outAnoLetivo,
		string $outNumClasse,
		string $outCodSerieAno,
		string $outTurma,
		string $outCodTurno,
		string $outDescricaoTurno,
		string $outCodHabilitacao,
		string $outCodTipoClasse,
		string $outNumSala,
		string $outHorarioInicio,
		string $outHorarioFim,
		string $outQtdAtual,
		string $outQtdDigitados,
		string $outQtdEvadidos,
		string $outQtdNCom,
		string $outQtdOutros,
		string $outQtdTransferidos,
		string $outQtdRemanejados,
		string $outQtdCessados,
		string $outQtdReclassificados,
		string $outCapacidadeFisicaMax,
		string $outDataInicioAula,
		string $outDataFimAula,
		array $outAlunos
	) {
		$this->outCodEscola = $outCodEscola;
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outNumClasse = $outNumClasse;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outTurma = $outTurma;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outCodHabilitacao = $outCodHabilitacao;
		$this->outCodTipoClasse = $outCodTipoClasse;
		$this->outNumSala = $outNumSala;
		$this->outHorarioInicio = $outHorarioInicio;
		$this->outHorarioFim = $outHorarioFim;
		$this->outQtdAtual = $outQtdAtual;
		$this->outQtdDigitados = $outQtdDigitados;
		$this->outQtdEvadidos = $outQtdEvadidos;
		$this->outQtdNCom = $outQtdNCom;
		$this->outQtdOutros = $outQtdOutros;
		$this->outQtdTransferidos = $outQtdTransferidos;
		$this->outQtdRemanejados = $outQtdRemanejados;
		$this->outQtdCessados = $outQtdCessados;
		$this->outQtdReclassificados = $outQtdReclassificados;
		$this->outCapacidadeFisicaMax = $outCapacidadeFisicaMax;
		$this->outDataInicioAula = $outDataInicioAula;
		$this->outDataFimAula = $outDataFimAula;
		$this->outAlunos = $outAlunos;
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
	 * Get the value of outCodTipoClasse
	 */
	public function getOutCodTipoClasse()
	{
		return $this->outCodTipoClasse;
	}

	/**
	 * Set the value of outCodTipoClasse
	 */
	public function setOutCodTipoClasse($outCodTipoClasse): self
	{
		$this->outCodTipoClasse = $outCodTipoClasse;

		return $this;
	}

	/**
	 * Get the value of outNumSala
	 */
	public function getOutNumSala()
	{
		return $this->outNumSala;
	}

	/**
	 * Set the value of outNumSala
	 */
	public function setOutNumSala($outNumSala): self
	{
		$this->outNumSala = $outNumSala;

		return $this;
	}

	/**
	 * Get the value of outHorarioInicio
	 */
	public function getOutHorarioInicio()
	{
		return $this->outHorarioInicio;
	}

	/**
	 * Set the value of outHorarioInicio
	 */
	public function setOutHorarioInicio($outHorarioInicio): self
	{
		$this->outHorarioInicio = $outHorarioInicio;

		return $this;
	}

	/**
	 * Get the value of outHorarioFim
	 */
	public function getOutHorarioFim()
	{
		return $this->outHorarioFim;
	}

	/**
	 * Set the value of outHorarioFim
	 */
	public function setOutHorarioFim($outHorarioFim): self
	{
		$this->outHorarioFim = $outHorarioFim;

		return $this;
	}

	/**
	 * Get the value of outQtdAtual
	 */
	public function getOutQtdAtual()
	{
		return $this->outQtdAtual;
	}

	/**
	 * Set the value of outQtdAtual
	 */
	public function setOutQtdAtual($outQtdAtual): self
	{
		$this->outQtdAtual = $outQtdAtual;

		return $this;
	}

	/**
	 * Get the value of outQtdDigitados
	 */
	public function getOutQtdDigitados()
	{
		return $this->outQtdDigitados;
	}

	/**
	 * Set the value of outQtdDigitados
	 */
	public function setOutQtdDigitados($outQtdDigitados): self
	{
		$this->outQtdDigitados = $outQtdDigitados;

		return $this;
	}

	/**
	 * Get the value of outQtdEvadidos
	 */
	public function getOutQtdEvadidos()
	{
		return $this->outQtdEvadidos;
	}

	/**
	 * Set the value of outQtdEvadidos
	 */
	public function setOutQtdEvadidos($outQtdEvadidos): self
	{
		$this->outQtdEvadidos = $outQtdEvadidos;

		return $this;
	}

	/**
	 * Get the value of outQtdNCom
	 */
	public function getOutQtdNCom()
	{
		return $this->outQtdNCom;
	}

	/**
	 * Set the value of outQtdNCom
	 */
	public function setOutQtdNCom($outQtdNCom): self
	{
		$this->outQtdNCom = $outQtdNCom;

		return $this;
	}

	/**
	 * Get the value of outQtdOutros
	 */
	public function getOutQtdOutros()
	{
		return $this->outQtdOutros;
	}

	/**
	 * Set the value of outQtdOutros
	 */
	public function setOutQtdOutros($outQtdOutros): self
	{
		$this->outQtdOutros = $outQtdOutros;

		return $this;
	}

	/**
	 * Get the value of outQtdTransferidos
	 */
	public function getOutQtdTransferidos()
	{
		return $this->outQtdTransferidos;
	}

	/**
	 * Set the value of outQtdTransferidos
	 */
	public function setOutQtdTransferidos($outQtdTransferidos): self
	{
		$this->outQtdTransferidos = $outQtdTransferidos;

		return $this;
	}

	/**
	 * Get the value of outQtdRemanejados
	 */
	public function getOutQtdRemanejados()
	{
		return $this->outQtdRemanejados;
	}

	/**
	 * Set the value of outQtdRemanejados
	 */
	public function setOutQtdRemanejados($outQtdRemanejados): self
	{
		$this->outQtdRemanejados = $outQtdRemanejados;

		return $this;
	}

	/**
	 * Get the value of outQtdCessados
	 */
	public function getOutQtdCessados()
	{
		return $this->outQtdCessados;
	}

	/**
	 * Set the value of outQtdCessados
	 */
	public function setOutQtdCessados($outQtdCessados): self
	{
		$this->outQtdCessados = $outQtdCessados;

		return $this;
	}

	/**
	 * Get the value of outQtdReclassificados
	 */
	public function getOutQtdReclassificados()
	{
		return $this->outQtdReclassificados;
	}

	/**
	 * Set the value of outQtdReclassificados
	 */
	public function setOutQtdReclassificados($outQtdReclassificados): self
	{
		$this->outQtdReclassificados = $outQtdReclassificados;

		return $this;
	}

	/**
	 * Get the value of outCapacidadeFisicaMax
	 */
	public function getOutCapacidadeFisicaMax()
	{
		return $this->outCapacidadeFisicaMax;
	}

	/**
	 * Set the value of outCapacidadeFisicaMax
	 */
	public function setOutCapacidadeFisicaMax($outCapacidadeFisicaMax): self
	{
		$this->outCapacidadeFisicaMax = $outCapacidadeFisicaMax;

		return $this;
	}

	/**
	 * Get the value of outDataInicioAula
	 */
	public function getOutDataInicioAula()
	{
		return $this->outDataInicioAula;
	}

	/**
	 * Set the value of outDataInicioAula
	 */
	public function setOutDataInicioAula($outDataInicioAula): self
	{
		$this->outDataInicioAula = $outDataInicioAula;

		return $this;
	}

	/**
	 * Get the value of outDataFimAula
	 */
	public function getOutDataFimAula()
	{
		return $this->outDataFimAula;
	}

	/**
	 * Set the value of outDataFimAula
	 */
	public function setOutDataFimAula($outDataFimAula): self
	{
		$this->outDataFimAula = $outDataFimAula;

		return $this;
	}

	/**
	 * Get the value of outAlunos
	 */
	public function getOutAlunos()
	{
		return $this->outAlunos;
	}

	/**
	 * Set the value of outAlunos
	 */
	public function setOutAlunos($outAlunos): self
	{
		$this->outAlunos = $outAlunos;

		return $this;
	}
}