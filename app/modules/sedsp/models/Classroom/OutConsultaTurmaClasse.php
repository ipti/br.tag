<?php

class OutConsultaTurmaClasse
{
    public $outAnoLetivo;
	public $outCodEscola;
	public $outNomeEscola;
	public $outCodUnidade;
	public $outCodTipoClasse;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outTurma;
	public $outDescricaoTurma;
	public $outNrCapacidadeFisicaMaxima;
	public $outNrAlunosAtivos;
	public $outDataInicioAula;
	public $outDataFimAula;
	public $outHorarioInicioAula;
	public $outHorarioFimAula;
	public $outCodDuracao;
	public $outCodHabilitacao;
	public $outAtividadesComplementar;
	public $outCodTipoEnsino;
	public $outNomeTipoEnsino;
	public $outNumeroSala;
	public $outCodSerieAno;
	public $outDescricaoSerieAno;
	public $outDiasSemana;
	public $outProcessoID;

	public function __construct(
		int $outAnoLetivo,
		int $outCodEscola,
		string $outNomeEscola,
		int $outCodUnidade,
		int $outCodTipoClasse,
		int $outCodTurno,
		string $outDescricaoTurno,
		int $outTurma,
		string $outDescricaoTurma,
		int $outNrCapacidadeFisicaMaxima,
		int $outNrAlunosAtivos,
		string $outDataInicioAula,
		string $outDataFimAula,
		string $outHorarioInicioAula,
		string $outHorarioFimAula,
		int $outCodDuracao,
		int $outCodHabilitacao,
		array $outAtividadesComplementar,
		int $outCodTipoEnsino,
		string $outNomeTipoEnsino,
		string $outNumeroSala,
		int $outCodSerieAno,
		string $outDescricaoSerieAno,
		OutDiasSemana $outDiasSemana,
		string $outProcessoID
	) {
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outCodEscola = $outCodEscola;
		$this->outNomeEscola = $outNomeEscola;
		$this->outCodUnidade = $outCodUnidade;
		$this->outCodTipoClasse = $outCodTipoClasse;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outTurma = $outTurma;
		$this->outDescricaoTurma = $outDescricaoTurma;
		$this->outNrCapacidadeFisicaMaxima = $outNrCapacidadeFisicaMaxima;
		$this->outNrAlunosAtivos = $outNrAlunosAtivos;
		$this->outDataInicioAula = $outDataInicioAula;
		$this->outDataFimAula = $outDataFimAula;
		$this->outHorarioInicioAula = $outHorarioInicioAula;
		$this->outHorarioFimAula = $outHorarioFimAula;
		$this->outCodDuracao = $outCodDuracao;
		$this->outCodHabilitacao = $outCodHabilitacao;
		$this->outAtividadesComplementar = $outAtividadesComplementar;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outNomeTipoEnsino = $outNomeTipoEnsino;
		$this->outNumeroSala = $outNumeroSala;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outDescricaoSerieAno = $outDescricaoSerieAno;
		$this->outDiasSemana = $outDiasSemana;
		$this->outProcessoID = $outProcessoID;
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
	 * Get the value of outNomeEscola
	 */
	public function getOutNomeEscola()
	{
		return $this->outNomeEscola;
	}

	/**
	 * Set the value of outNomeEscola
	 */
	public function setOutNomeEscola($outNomeEscola): self
	{
		$this->outNomeEscola = $outNomeEscola;

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
	 * Get the value of outDescricaoTurma
	 */
	public function getOutDescricaoTurma()
	{
		return $this->outDescricaoTurma;
	}

	/**
	 * Set the value of outDescricaoTurma
	 */
	public function setOutDescricaoTurma($outDescricaoTurma): self
	{
		$this->outDescricaoTurma = $outDescricaoTurma;

		return $this;
	}

	/**
	 * Get the value of outNrCapacidadeFisicaMaxima
	 */
	public function getOutNrCapacidadeFisicaMaxima()
	{
		return $this->outNrCapacidadeFisicaMaxima;
	}

	/**
	 * Set the value of outNrCapacidadeFisicaMaxima
	 */
	public function setOutNrCapacidadeFisicaMaxima($outNrCapacidadeFisicaMaxima): self
	{
		$this->outNrCapacidadeFisicaMaxima = $outNrCapacidadeFisicaMaxima;

		return $this;
	}

	/**
	 * Get the value of outNrAlunosAtivos
	 */
	public function getOutNrAlunosAtivos()
	{
		return $this->outNrAlunosAtivos;
	}

	/**
	 * Set the value of outNrAlunosAtivos
	 */
	public function setOutNrAlunosAtivos($outNrAlunosAtivos): self
	{
		$this->outNrAlunosAtivos = $outNrAlunosAtivos;

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
	 * Get the value of outHorarioInicioAula
	 */
	public function getOutHorarioInicioAula()
	{
		return $this->outHorarioInicioAula;
	}

	/**
	 * Set the value of outHorarioInicioAula
	 */
	public function setOutHorarioInicioAula($outHorarioInicioAula): self
	{
		$this->outHorarioInicioAula = $outHorarioInicioAula;

		return $this;
	}

	/**
	 * Get the value of outHorarioFimAula
	 */
	public function getOutHorarioFimAula()
	{
		return $this->outHorarioFimAula;
	}

	/**
	 * Set the value of outHorarioFimAula
	 */
	public function setOutHorarioFimAula($outHorarioFimAula): self
	{
		$this->outHorarioFimAula = $outHorarioFimAula;

		return $this;
	}

	/**
	 * Get the value of outCodDuracao
	 */
	public function getOutCodDuracao()
	{
		return $this->outCodDuracao;
	}

	/**
	 * Set the value of outCodDuracao
	 */
	public function setOutCodDuracao($outCodDuracao): self
	{
		$this->outCodDuracao = $outCodDuracao;

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
	 * Get the value of outAtividadesComplementar
	 */
	public function getOutAtividadesComplementar()
	{
		return $this->outAtividadesComplementar;
	}

	/**
	 * Set the value of outAtividadesComplementar
	 */
	public function setOutAtividadesComplementar($outAtividadesComplementar): self
	{
		$this->outAtividadesComplementar = $outAtividadesComplementar;

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
	 * Get the value of outNomeTipoEnsino
	 */
	public function getOutNomeTipoEnsino()
	{
		return $this->outNomeTipoEnsino;
	}

	/**
	 * Set the value of outNomeTipoEnsino
	 */
	public function setOutNomeTipoEnsino($outNomeTipoEnsino): self
	{
		$this->outNomeTipoEnsino = $outNomeTipoEnsino;

		return $this;
	}

	/**
	 * Get the value of outNumeroSala
	 */
	public function getOutNumeroSala()
	{
		return $this->outNumeroSala;
	}

	/**
	 * Set the value of outNumeroSala
	 */
	public function setOutNumeroSala($outNumeroSala): self
	{
		$this->outNumeroSala = $outNumeroSala;

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
	 * Get the value of outDescricaoSerieAno
	 */
	public function getOutDescricaoSerieAno()
	{
		return $this->outDescricaoSerieAno;
	}

	/**
	 * Set the value of outDescricaoSerieAno
	 */
	public function setOutDescricaoSerieAno($outDescricaoSerieAno): self
	{
		$this->outDescricaoSerieAno = $outDescricaoSerieAno;

		return $this;
	}

	/**
	 * Get the value of outDiasSemana
	 */
	public function getOutDiasSemana()
	{
		return $this->outDiasSemana;
	}

	/**
	 * Set the value of outDiasSemana
	 */
	public function setOutDiasSemana($outDiasSemana): self
	{
		$this->outDiasSemana = $outDiasSemana;

		return $this;
	}

	/**
	 * Get the value of outProcessoID
	 */
	public function getOutProcessoID()
	{
		return $this->outProcessoID;
	}

	/**
	 * Set the value of outProcessoID
	 */
	public function setOutProcessoID($outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;

		return $this;
	}
}
