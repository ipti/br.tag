<?php

class OutClassroom
{
	private $outCodEscola;
	private $outDescNomeAbrevEscola;
	private $outCodTipoEnsino;
	private $outDescTipoEnsino;
	private $outAnoLetivo;
	private $outNumClasse;
	private $outCodSerieAno;
	private $outTurma;
	private $outCodTurno;
	private $outDescricaoTurno;
	private $outCodHabilitacao;
	private $outCodTipoClasse;
	private $outNumSala;
	private $outHorarioInicio;
	private $outHorarioFim;
	private $outDataInicioAula;
	private $outDataFimAula;
	private $outQtdAtual;
	private $outQtdDigitados;
	private $outQtdEvadidos;
	private $outQtdNCom;
	private $outQtdOutros;
	private $outQtdTransferidos;
	private $outQtdRemanejados;
	private $outQtdCessados;
	private $outQtdReclassificados;
	private $outCapacidadeFisicaMax;
	
    /** @var OutAlunos[] */
	private $outAlunos;
	private $outProcessoID;

	/**
	 * @param OutClassroom $classroom
	 */
	public function __construct($classroom) { 
		$this->outCodEscola = $classroom->outCodEscola;
		$this->outDescNomeAbrevEscola = $classroom->outDescNomeAbrevEscola;
		$this->outCodTipoEnsino = $classroom->outCodTipoEnsino;
		$this->outDescTipoEnsino = $classroom->outDescTipoEnsino;
		$this->outAnoLetivo = $classroom->outAnoLetivo;
		$this->outNumClasse = $classroom->outNumClasse;
		$this->outCodSerieAno = $classroom->outCodSerieAno;
		$this->outTurma = $classroom->outTurma;
		$this->outCodTurno = $classroom->outCodTurno;
		$this->outDescricaoTurno = $classroom->outDescricaoTurno;
		$this->outCodHabilitacao = $classroom->outCodHabilitacao;
		$this->outCodTipoClasse = $classroom->outCodTipoClasse;
		$this->outNumSala = $classroom->outNumSala;
		$this->outHorarioInicio = $classroom->outHorarioInicio;
		$this->outHorarioFim = $classroom->outHorarioFim;
		$this->outDataInicioAula = $classroom->outDataInicioAula;
		$this->outDataFimAula = $classroom->outDataFimAula;
		$this->outQtdAtual = $classroom->outQtdAtual;
		$this->outQtdDigitados = $classroom->outQtdDigitados;
		$this->outQtdEvadidos = $classroom->outQtdEvadidos;
		$this->outQtdNCom = $classroom->outQtdNCom;
		$this->outQtdOutros = $classroom->outQtdOutros;
		$this->outQtdTransferidos = $classroom->outQtdTransferidos;
		$this->outQtdRemanejados = $classroom->outQtdRemanejados;
		$this->outQtdCessados = $classroom->outQtdCessados;
		$this->outQtdReclassificados = $classroom->outQtdReclassificados;
		$this->outCapacidadeFisicaMax = $classroom->outCapacidadeFisicaMax;
		$this->outAlunos = $classroom->outAlunos;
		$this->outProcessoID = $classroom->outProcessoID;
	}

	public function getOutCodEscola(): string
	{
		return $this->outCodEscola;
	}

	public function getOutDescNomeAbrevEscola(): string
	{
		return $this->outDescNomeAbrevEscola;
	}

	public function getOutCodTipoEnsino(): string
	{
		return $this->outCodTipoEnsino;
	}

	public function getOutDescTipoEnsino(): string
	{
		return $this->outDescTipoEnsino;
	}

	public function getOutAnoLetivo(): string
	{
		return $this->outAnoLetivo;
	}

	public function getOutNumClasse(): string
	{
		return $this->outNumClasse;
	}

	public function getOutCodSerieAno(): string
	{
		return $this->outCodSerieAno;
	}

	public function getOutTurma(): string
	{
		return $this->outTurma;
	}

	public function getOutCodTurno(): string
	{
		return $this->outCodTurno;
	}

	public function getOutDescricaoTurno(): string
	{
		return $this->outDescricaoTurno;
	}

	public function getOutCodHabilitacao(): string
	{
		return $this->outCodHabilitacao;
	}

	public function getOutCodTipoClasse(): string
	{
		return $this->outCodTipoClasse;
	}

	public function getOutNumSala(): string
	{
		return $this->outNumSala;
	}

	public function getOutHorarioInicio(): string
	{
		return $this->outHorarioInicio;
	}

	public function getOutHorarioFim(): string
	{
		return $this->outHorarioFim;
	}

	public function getOutDataInicioAula(): string
	{
		return $this->outDataInicioAula;
	}

	public function getOutDataFimAula(): string
	{
		return $this->outDataFimAula;
	}

	public function getOutQtdAtual(): string
	{
		return $this->outQtdAtual;
	}

	public function getOutQtdDigitados(): string
	{
		return $this->outQtdDigitados;
	}

	public function getOutQtdEvadidos(): string
	{
		return $this->outQtdEvadidos;
	}

	public function getOutQtdNCom(): string
	{
		return $this->outQtdNCom;
	}

	public function getOutQtdOutros(): string
	{
		return $this->outQtdOutros;
	}

	public function getOutQtdTransferidos(): string
	{
		return $this->outQtdTransferidos;
	}

	public function getOutQtdRemanejados(): string
	{
		return $this->outQtdRemanejados;
	}

	public function getOutQtdCessados(): string
	{
		return $this->outQtdCessados;
	}

	public function getOutQtdReclassificados(): string
	{
		return $this->outQtdReclassificados;
	}

	public function getOutCapacidadeFisicaMax(): string
	{
		return $this->outCapacidadeFisicaMax;
	}

	/**
	 * @return OutAlunos[]
	 */
	public function getOutAlunos(): array
	{
		return $this->outAlunos;
	}

	public function getOutProcessoId(): string
	{
		return $this->outProcessoID;
	}
}