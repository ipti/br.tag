<?php

class OutClasses
{
	public $outNumClasse;
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	public $outCodSerieAno;
	public $outTurma;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outCodHabilitacao;
	public $outNumSala;
	public $outHorarioInicio;
	public $outHorarioFim;
	public $outCodTipoClasse;
	public $outDescTipoClasse;
	public $outSemestre;
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

	public function __construct(
		string $outNumClasse,
		string $outCodTipoEnsino,
		string $outDescTipoEnsino,
		string $outCodSerieAno,
		string $outTurma,
		string $outCodTurno,
		string $outDescricaoTurno,
		string $outCodHabilitacao,
		string $outNumSala,
		string $outHorarioInicio,
		string $outHorarioFim,
		string $outCodTipoClasse,
		string $outDescTipoClasse,
		string $outSemestre,
		string $outQtdAtual,
		string $outQtdDigitados,
		string $outQtdEvadidos,
		string $outQtdNCom,
		string $outQtdOutros,
		string $outQtdTransferidos,
		string $outQtdRemanejados,
		string $outQtdCessados,
		string $outQtdReclassificados,
		string $outCapacidadeFisicaMax
	) {
		$this->outNumClasse = $outNumClasse;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outTurma = $outTurma;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outCodHabilitacao = $outCodHabilitacao;
		$this->outNumSala = $outNumSala;
		$this->outHorarioInicio = $outHorarioInicio;
		$this->outHorarioFim = $outHorarioFim;
		$this->outCodTipoClasse = $outCodTipoClasse;
		$this->outDescTipoClasse = $outDescTipoClasse;
		$this->outSemestre = $outSemestre;
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
	 * Get the value of outDescTipoClasse
	 */
	public function getOutDescTipoClasse()
	{
		return $this->outDescTipoClasse;
	}

	/**
	 * Set the value of outDescTipoClasse
	 */
	public function setOutDescTipoClasse($outDescTipoClasse): self
	{
		$this->outDescTipoClasse = $outDescTipoClasse;

		return $this;
	}

	/**
	 * Get the value of outSemestre
	 */
	public function getOutSemestre()
	{
		return $this->outSemestre;
	}

	/**
	 * Set the value of outSemestre
	 */
	public function setOutSemestre($outSemestre): self
	{
		$this->outSemestre = $outSemestre;

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
}