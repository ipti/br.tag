<?php

class InIncluirTurmaClasse
{
    public $inAnoLetivo;
	public $inCodEscola;
	public $inCodUnidade;
	public $inCodTipoEnsino;
	public $inCodSerieAno;
	public $inCodTipoClasse;
	public $inCodTurno;
	public $inCodDuracao;
	public $inTurma;
	public $inNumeroSala;
	public $inNrCapacidadeFisicaMaxima;
	public $inDataInicioAula;
	public $inDataFimAula;
	public $inHorarioInicioAula;
	public $inHorarioFimAula;
	public $inCodHabilitacao;
	/** @var string[] */
	public $inCodigoAtividadeComplementar;
	public $inDiasDaSemana;

	/**
	 * @param string[] $inCodigoAtividadeComplementar
	 */
	public function __construct(
		string $inAnoLetivo,
		string $inCodEscola,
		string $inCodUnidade,
		string $inCodTipoEnsino,
		string $inCodSerieAno,
		string $inCodTipoClasse,
		string $inCodTurno,
		string $inCodDuracao,
		string $inTurma,
		string $inNumeroSala,
		string $inNrCapacidadeFisicaMaxima,
		string $inDataInicioAula,
		string $inDataFimAula,
		string $inHorarioInicioAula,
		string $inHorarioFimAula,
		string $inCodHabilitacao,
		array $inCodigoAtividadeComplementar,
		InDiasDaSemana $inDiasDaSemana
	) {
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inCodEscola = $inCodEscola;
		$this->inCodUnidade = $inCodUnidade;
		$this->inCodTipoEnsino = $inCodTipoEnsino;
		$this->inCodSerieAno = $inCodSerieAno;
		$this->inCodTipoClasse = $inCodTipoClasse;
		$this->inCodTurno = $inCodTurno;
		$this->inCodDuracao = $inCodDuracao;
		$this->inTurma = $inTurma;
		$this->inNumeroSala = $inNumeroSala;
		$this->inNrCapacidadeFisicaMaxima = $inNrCapacidadeFisicaMaxima;
		$this->inDataInicioAula = $inDataInicioAula;
		$this->inDataFimAula = $inDataFimAula;
		$this->inHorarioInicioAula = $inHorarioInicioAula;
		$this->inHorarioFimAula = $inHorarioFimAula;
		$this->inCodHabilitacao = $inCodHabilitacao;
		$this->inCodigoAtividadeComplementar = $inCodigoAtividadeComplementar;
		$this->inDiasDaSemana = $inDiasDaSemana;
	}

    /**
     * Get the value of inAnoLetivo
     */
    public function getInAnoLetivo()
    {
        return $this->inAnoLetivo;
    }

    /**
     * Set the value of inAnoLetivo
     */
    public function setInAnoLetivo($inAnoLetivo): self
    {
        $this->inAnoLetivo = $inAnoLetivo;

        return $this;
    }

	/**
	 * Get the value of inCodEscola
	 */
	public function getInCodEscola()
	{
		return $this->inCodEscola;
	}

	/**
	 * Set the value of inCodEscola
	 */
	public function setInCodEscola($inCodEscola): self
	{
		$this->inCodEscola = $inCodEscola;

		return $this;
	}

	/**
	 * Get the value of inCodUnidade
	 */
	public function getInCodUnidade()
	{
		return $this->inCodUnidade;
	}

	/**
	 * Set the value of inCodUnidade
	 */
	public function setInCodUnidade($inCodUnidade): self
	{
		$this->inCodUnidade = $inCodUnidade;

		return $this;
	}

	/**
	 * Get the value of inCodTipoEnsino
	 */
	public function getInCodTipoEnsino()
	{
		return $this->inCodTipoEnsino;
	}

	/**
	 * Set the value of inCodTipoEnsino
	 */
	public function setInCodTipoEnsino($inCodTipoEnsino): self
	{
		$this->inCodTipoEnsino = $inCodTipoEnsino;

		return $this;
	}

	/**
	 * Get the value of inCodSerieAno
	 */
	public function getInCodSerieAno()
	{
		return $this->inCodSerieAno;
	}

	/**
	 * Set the value of inCodSerieAno
	 */
	public function setInCodSerieAno($inCodSerieAno): self
	{
		$this->inCodSerieAno = $inCodSerieAno;

		return $this;
	}

	/**
	 * Get the value of inCodTipoClasse
	 */
	public function getInCodTipoClasse()
	{
		return $this->inCodTipoClasse;
	}

	/**
	 * Set the value of inCodTipoClasse
	 */
	public function setInCodTipoClasse($inCodTipoClasse): self
	{
		$this->inCodTipoClasse = $inCodTipoClasse;

		return $this;
	}

	/**
	 * Get the value of inCodTurno
	 */
	public function getInCodTurno()
	{
		return $this->inCodTurno;
	}

	/**
	 * Set the value of inCodTurno
	 */
	public function setInCodTurno($inCodTurno): self
	{
		$this->inCodTurno = $inCodTurno;

		return $this;
	}

	/**
	 * Get the value of inCodDuracao
	 */
	public function getInCodDuracao()
	{
		return $this->inCodDuracao;
	}

	/**
	 * Set the value of inCodDuracao
	 */
	public function setInCodDuracao($inCodDuracao): self
	{
		$this->inCodDuracao = $inCodDuracao;

		return $this;
	}

	/**
	 * Get the value of inTurma
	 */
	public function getInTurma()
	{
		return $this->inTurma;
	}

	/**
	 * Set the value of inTurma
	 */
	public function setInTurma($inTurma): self
	{
		$this->inTurma = $inTurma;

		return $this;
	}

	/**
	 * Get the value of inNumeroSala
	 */
	public function getInNumeroSala()
	{
		return $this->inNumeroSala;
	}

	/**
	 * Set the value of inNumeroSala
	 */
	public function setInNumeroSala($inNumeroSala): self
	{
		$this->inNumeroSala = $inNumeroSala;

		return $this;
	}

	/**
	 * Get the value of inNrCapacidadeFisicaMaxima
	 */
	public function getInNrCapacidadeFisicaMaxima()
	{
		return $this->inNrCapacidadeFisicaMaxima;
	}

	/**
	 * Set the value of inNrCapacidadeFisicaMaxima
	 */
	public function setInNrCapacidadeFisicaMaxima($inNrCapacidadeFisicaMaxima): self
	{
		$this->inNrCapacidadeFisicaMaxima = $inNrCapacidadeFisicaMaxima;

		return $this;
	}

	/**
	 * Get the value of inDataInicioAula
	 */
	public function getInDataInicioAula()
	{
		return $this->inDataInicioAula;
	}

	/**
	 * Set the value of inDataInicioAula
	 */
	public function setInDataInicioAula($inDataInicioAula): self
	{
		$this->inDataInicioAula = $inDataInicioAula;

		return $this;
	}

	/**
	 * Get the value of inDataFimAula
	 */
	public function getInDataFimAula()
	{
		return $this->inDataFimAula;
	}

	/**
	 * Set the value of inDataFimAula
	 */
	public function setInDataFimAula($inDataFimAula): self
	{
		$this->inDataFimAula = $inDataFimAula;

		return $this;
	}

	/**
	 * Get the value of inHorarioInicioAula
	 */
	public function getInHorarioInicioAula()
	{
		return $this->inHorarioInicioAula;
	}

	/**
	 * Set the value of inHorarioInicioAula
	 */
	public function setInHorarioInicioAula($inHorarioInicioAula): self
	{
		$this->inHorarioInicioAula = $inHorarioInicioAula;

		return $this;
	}

	/**
	 * Get the value of inHorarioFimAula
	 */
	public function getInHorarioFimAula()
	{
		return $this->inHorarioFimAula;
	}

	/**
	 * Set the value of inHorarioFimAula
	 */
	public function setInHorarioFimAula($inHorarioFimAula): self
	{
		$this->inHorarioFimAula = $inHorarioFimAula;

		return $this;
	}

	/**
	 * Get the value of inCodHabilitacao
	 */
	public function getInCodHabilitacao()
	{
		return $this->inCodHabilitacao;
	}

	/**
	 * Set the value of inCodHabilitacao
	 */
	public function setInCodHabilitacao($inCodHabilitacao): self
	{
		$this->inCodHabilitacao = $inCodHabilitacao;

		return $this;
	}

	/**
	 * Get the value of inCodigoAtividadeComplementar
	 */
	public function getInCodigoAtividadeComplementar()
	{
		return $this->inCodigoAtividadeComplementar;
	}

	/**
	 * Set the value of inCodigoAtividadeComplementar
	 */
	public function setInCodigoAtividadeComplementar($inCodigoAtividadeComplementar): self
	{
		$this->inCodigoAtividadeComplementar = $inCodigoAtividadeComplementar;

		return $this;
	}

	/**
	 * Get the value of inDiasDaSemana
	 */
	public function getInDiasDaSemana()
	{
		return $this->inDiasDaSemana;
	}

	/**
	 * Set the value of inDiasDaSemana
	 */
	public function setInDiasDaSemana($inDiasDaSemana): self
	{
		$this->inDiasDaSemana = $inDiasDaSemana;

		return $this;
	}
}
