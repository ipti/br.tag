<?php

class InIncluirTurmaClasse implements JsonSerializable
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
	/** @var string[]|null */
	public $inCodigoAtividadeComplementar;
	public $inDiasDaSemana;

	/**
	 * @param string[]|null $inCodigoAtividadeComplementar
	 */
	public function __construct(
		?string $inAnoLetivo,
		?string $inCodEscola,
		?string $inCodUnidade,
		?string $inCodTipoEnsino,
		?string $inCodSerieAno,
		?string $inCodTipoClasse,
		?string $inCodTurno,
		?string $inCodDuracao,
		?string $inTurma,
		?string $inNumeroSala,
		?string $inNrCapacidadeFisicaMaxima,
		?string $inDataInicioAula,
		?string $inDataFimAula,
		?string $inHorarioInicioAula,
		?string $inHorarioFimAula,
		?string $inCodHabilitacao,
		?array $inCodigoAtividadeComplementar,
		?InDiasDaSemana $inDiasDaSemana
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

	public function getInAnoLetivo(): ?string
	{
		return $this->inAnoLetivo;
	}

	public function getInCodEscola(): ?string
	{
		return $this->inCodEscola;
	}

	public function getInCodUnidade(): ?string
	{
		return $this->inCodUnidade;
	}

	public function getInCodTipoEnsino(): ?string
	{
		return $this->inCodTipoEnsino;
	}

	public function getInCodSerieAno(): ?string
	{
		return $this->inCodSerieAno;
	}

	public function getInCodTipoClasse(): ?string
	{
		return $this->inCodTipoClasse;
	}

	public function getInCodTurno(): ?string
	{
		return $this->inCodTurno;
	}

	public function getInCodDuracao(): ?string
	{
		return $this->inCodDuracao;
	}

	public function getInTurma(): ?string
	{
		return $this->inTurma;
	}

	public function getInNumeroSala(): ?string
	{
		return $this->inNumeroSala;
	}

	public function getInNrCapacidadeFisicaMaxima(): ?string
	{
		return $this->inNrCapacidadeFisicaMaxima;
	}

	public function getInDataInicioAula(): ?string
	{
		return $this->inDataInicioAula;
	}

	public function getInDataFimAula(): ?string
	{
		return $this->inDataFimAula;
	}

	public function getInHorarioInicioAula(): ?string
	{
		return $this->inHorarioInicioAula;
	}

	public function getInHorarioFimAula(): ?string
	{
		return $this->inHorarioFimAula;
	}

	public function getInCodHabilitacao(): ?string
	{
		return $this->inCodHabilitacao;
	}

	/**
	 * @return string[]|null
	 */
	public function getInCodigoAtividadeComplementar(): ?array
	{
		return $this->inCodigoAtividadeComplementar;
	}

	public function getInDiasDaSemana(): ?InDiasDaSemana
	{
		return $this->inDiasDaSemana;
	}

	public function setInAnoLetivo(?string $inAnoLetivo): self
	{
		$this->inAnoLetivo = $inAnoLetivo;
		return $this;
	}

	public function setInCodEscola(?string $inCodEscola): self
	{
		$this->inCodEscola = $inCodEscola;
		return $this;
	}

	public function setInCodUnidade(?string $inCodUnidade): self
	{
		$this->inCodUnidade = $inCodUnidade;
		return $this;
	}

	public function setInCodTipoEnsino(?string $inCodTipoEnsino): self
	{
		$this->inCodTipoEnsino = $inCodTipoEnsino;
		return $this;
	}

	public function setInCodSerieAno(?string $inCodSerieAno): self
	{
		$this->inCodSerieAno = $inCodSerieAno;
		return $this;
	}

	public function setInCodTipoClasse(?string $inCodTipoClasse): self
	{
		$this->inCodTipoClasse = $inCodTipoClasse;
		return $this;
	}

	public function setInCodTurno(?string $inCodTurno): self
	{
		$this->inCodTurno = $inCodTurno;
		return $this;
	}

	public function setInCodDuracao(?string $inCodDuracao): self
	{
		$this->inCodDuracao = $inCodDuracao;
		return $this;
	}

	public function setInTurma(?string $inTurma): self
	{
		$this->inTurma = $inTurma;
		return $this;
	}

	public function setInNumeroSala(?string $inNumeroSala): self
	{
		$this->inNumeroSala = $inNumeroSala;
		return $this;
	}

	public function setInNrCapacidadeFisicaMaxima(?string $inNrCapacidadeFisicaMaxima): self
	{
		$this->inNrCapacidadeFisicaMaxima = $inNrCapacidadeFisicaMaxima;
		return $this;
	}

	public function setInDataInicioAula(?string $inDataInicioAula): self
	{
		$this->inDataInicioAula = $inDataInicioAula;
		return $this;
	}

	public function setInDataFimAula(?string $inDataFimAula): self
	{
		$this->inDataFimAula = $inDataFimAula;
		return $this;
	}

	public function setInHorarioInicioAula(?string $inHorarioInicioAula): self
	{
		$this->inHorarioInicioAula = $inHorarioInicioAula;
		return $this;
	}

	public function setInHorarioFimAula(?string $inHorarioFimAula): self
	{
		$this->inHorarioFimAula = $inHorarioFimAula;
		return $this;
	}

	public function setInCodHabilitacao(?string $inCodHabilitacao): self
	{
		$this->inCodHabilitacao = $inCodHabilitacao;
		return $this;
	}

	/**
	 * @param string[]|null $inCodigoAtividadeComplementar
	 */
	public function setInCodigoAtividadeComplementar(?array $inCodigoAtividadeComplementar): self
	{
		$this->inCodigoAtividadeComplementar = $inCodigoAtividadeComplementar;
		return $this;
	}

	public function setInDiasDaSemana(?InDiasDaSemana $inDiasDaSemana): self
	{
		$this->inDiasDaSemana = $inDiasDaSemana;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inAnoLetivo'] ?? null,
			$data['inCodEscola'] ?? null,
			$data['inCodUnidade'] ?? null,
			$data['inCodTipoEnsino'] ?? null,
			$data['inCodSerieAno'] ?? null,
			$data['inCodTipoClasse'] ?? null,
			$data['inCodTurno'] ?? null,
			$data['inCodDuracao'] ?? null,
			$data['inTurma'] ?? null,
			$data['inNumeroSala'] ?? null,
			$data['inNrCapacidadeFisicaMaxima'] ?? null,
			$data['inDataInicioAula'] ?? null,
			$data['inDataFimAula'] ?? null,
			$data['inHorarioInicioAula'] ?? null,
			$data['inHorarioFimAula'] ?? null,
			$data['inCodHabilitacao'] ?? null,
			$data['inCodigoAtividadeComplementar'] ?? null,
			($data['inDiasDaSemana'] ?? null) !== null ? InDiasDaSemana::fromJson($data['inDiasDaSemana']) : null
		);
	}
	
	public function jsonSerialize() {
        return get_object_vars($this);
    }
}
