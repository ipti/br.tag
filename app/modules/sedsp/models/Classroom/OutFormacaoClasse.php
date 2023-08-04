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
	/** @var OutAlunos[]|null */
	public $outAlunos;
	public $outProcessoID;

	/**
	 * @param OutAlunos[]|null $outAlunos
	 */
	public function __construct(
		?string $outCodEscola,
		?string $outDescNomeAbrevEscola,
		?string $outCodTipoEnsino,
		?string $outDescTipoEnsino,
		?string $outAnoLetivo,
		?string $outNumClasse,
		?string $outCodSerieAno,
		?string $outTurma,
		?string $outCodTurno,
		?string $outDescricaoTurno,
		?string $outCodHabilitacao,
		?string $outCodTipoClasse,
		?string $outNumSala,
		?string $outHorarioInicio,
		?string $outHorarioFim,
		?string $outQtdAtual,
		?string $outQtdDigitados,
		?string $outQtdEvadidos,
		?string $outQtdNCom,
		?string $outQtdOutros,
		?string $outQtdTransferidos,
		?string $outQtdRemanejados,
		?string $outQtdCessados,
		?string $outQtdReclassificados,
		?string $outCapacidadeFisicaMax,
		?string $outDataInicioAula,
		?string $outDataFimAula,
		?array $outAlunos,
		?string $outProcessoID
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
		$this->outProcessoID = $outProcessoID;
	}

	public function getOutCodEscola(): ?string
	{
		return $this->outCodEscola;
	}

	public function getOutDescNomeAbrevEscola(): ?string
	{
		return $this->outDescNomeAbrevEscola;
	}

	public function getOutCodTipoEnsino(): ?string
	{
		return $this->outCodTipoEnsino;
	}

	public function getOutDescTipoEnsino(): ?string
	{
		return $this->outDescTipoEnsino;
	}

	public function getOutAnoLetivo(): ?string
	{
		return $this->outAnoLetivo;
	}

	public function getOutNumClasse(): ?string
	{
		return $this->outNumClasse;
	}

	public function getOutCodSerieAno(): ?string
	{
		return $this->outCodSerieAno;
	}

	public function getOutTurma(): ?string
	{
		return $this->outTurma;
	}

	public function getOutCodTurno(): ?string
	{
		return $this->outCodTurno;
	}

	public function getOutDescricaoTurno(): ?string
	{
		return $this->outDescricaoTurno;
	}

	public function getOutCodHabilitacao(): ?string
	{
		return $this->outCodHabilitacao;
	}

	public function getOutCodTipoClasse(): ?string
	{
		return $this->outCodTipoClasse;
	}

	public function getOutNumSala(): ?string
	{
		return $this->outNumSala;
	}

	public function getOutHorarioInicio(): ?string
	{
		return $this->outHorarioInicio;
	}

	public function getOutHorarioFim(): ?string
	{
		return $this->outHorarioFim;
	}

	public function getOutQtdAtual(): ?string
	{
		return $this->outQtdAtual;
	}

	public function getOutQtdDigitados(): ?string
	{
		return $this->outQtdDigitados;
	}

	public function getOutQtdEvadidos(): ?string
	{
		return $this->outQtdEvadidos;
	}

	public function getOutQtdNCom(): ?string
	{
		return $this->outQtdNCom;
	}

	public function getOutQtdOutros(): ?string
	{
		return $this->outQtdOutros;
	}

	public function getOutQtdTransferidos(): ?string
	{
		return $this->outQtdTransferidos;
	}

	public function getOutQtdRemanejados(): ?string
	{
		return $this->outQtdRemanejados;
	}

	public function getOutQtdCessados(): ?string
	{
		return $this->outQtdCessados;
	}

	public function getOutQtdReclassificados(): ?string
	{
		return $this->outQtdReclassificados;
	}

	public function getOutCapacidadeFisicaMax(): ?string
	{
		return $this->outCapacidadeFisicaMax;
	}

	public function getOutDataInicioAula(): ?string
	{
		return $this->outDataInicioAula;
	}

	public function getOutDataFimAula(): ?string
	{
		return $this->outDataFimAula;
	}

	/**
	 * @return OutAlunos[]|null
	 */
	public function getOutAlunos(): ?array
	{
		return $this->outAlunos;
	}


	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	public function setOutCodEscola(?string $outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;
		return $this;
	}

	public function setOutDescNomeAbrevEscola(?string $outDescNomeAbrevEscola): self
	{
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		return $this;
	}

	public function setOutCodTipoEnsino(?string $outCodTipoEnsino): self
	{
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		return $this;
	}

	public function setOutDescTipoEnsino(?string $outDescTipoEnsino): self
	{
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		return $this;
	}

	public function setOutAnoLetivo(?string $outAnoLetivo): self
	{
		$this->outAnoLetivo = $outAnoLetivo;
		return $this;
	}

	public function setOutNumClasse(?string $outNumClasse): self
	{
		$this->outNumClasse = $outNumClasse;
		return $this;
	}

	public function setOutCodSerieAno(?string $outCodSerieAno): self
	{
		$this->outCodSerieAno = $outCodSerieAno;
		return $this;
	}

	public function setOutTurma(?string $outTurma): self
	{
		$this->outTurma = $outTurma;
		return $this;
	}

	public function setOutCodTurno(?string $outCodTurno): self
	{
		$this->outCodTurno = $outCodTurno;
		return $this;
	}

	public function setOutDescricaoTurno(?string $outDescricaoTurno): self
	{
		$this->outDescricaoTurno = $outDescricaoTurno;
		return $this;
	}

	public function setOutCodHabilitacao(?string $outCodHabilitacao): self
	{
		$this->outCodHabilitacao = $outCodHabilitacao;
		return $this;
	}

	public function setOutCodTipoClasse(?string $outCodTipoClasse): self
	{
		$this->outCodTipoClasse = $outCodTipoClasse;
		return $this;
	}

	public function setOutNumSala(?string $outNumSala): self
	{
		$this->outNumSala = $outNumSala;
		return $this;
	}

	public function setOutHorarioInicio(?string $outHorarioInicio): self
	{
		$this->outHorarioInicio = $outHorarioInicio;
		return $this;
	}

	public function setOutHorarioFim(?string $outHorarioFim): self
	{
		$this->outHorarioFim = $outHorarioFim;
		return $this;
	}

	public function setOutQtdAtual(?string $outQtdAtual): self
	{
		$this->outQtdAtual = $outQtdAtual;
		return $this;
	}

	public function setOutQtdDigitados(?string $outQtdDigitados): self
	{
		$this->outQtdDigitados = $outQtdDigitados;
		return $this;
	}

	public function setOutQtdEvadidos(?string $outQtdEvadidos): self
	{
		$this->outQtdEvadidos = $outQtdEvadidos;
		return $this;
	}

	public function setOutQtdNCom(?string $outQtdNCom): self
	{
		$this->outQtdNCom = $outQtdNCom;
		return $this;
	}

	public function setOutQtdOutros(?string $outQtdOutros): self
	{
		$this->outQtdOutros = $outQtdOutros;
		return $this;
	}

	public function setOutQtdTransferidos(?string $outQtdTransferidos): self
	{
		$this->outQtdTransferidos = $outQtdTransferidos;
		return $this;
	}

	public function setOutQtdRemanejados(?string $outQtdRemanejados): self
	{
		$this->outQtdRemanejados = $outQtdRemanejados;
		return $this;
	}

	public function setOutQtdCessados(?string $outQtdCessados): self
	{
		$this->outQtdCessados = $outQtdCessados;
		return $this;
	}

	public function setOutQtdReclassificados(?string $outQtdReclassificados): self
	{
		$this->outQtdReclassificados = $outQtdReclassificados;
		return $this;
	}

	public function setOutCapacidadeFisicaMax(?string $outCapacidadeFisicaMax): self
	{
		$this->outCapacidadeFisicaMax = $outCapacidadeFisicaMax;
		return $this;
	}

	public function setOutDataInicioAula(?string $outDataInicioAula): self
	{
		$this->outDataInicioAula = $outDataInicioAula;
		return $this;
	}

	public function setOutDataFimAula(?string $outDataFimAula): self
	{
		$this->outDataFimAula = $outDataFimAula;
		return $this;
	}

	/**
	 * @param OutAlunos[]|null $outAlunos
	 */
	public function setOutAlunos(?array $outAlunos): self
	{
		$this->outAlunos = $outAlunos;
		return $this;
	}

	public function setOutProcessoId(?string $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodEscola'] ?? null,
			$data['outDescNomeAbrevEscola'] ?? null,
			$data['outCodTipoEnsino'] ?? null,
			$data['outDescTipoEnsino'] ?? null,
			$data['outAnoLetivo'] ?? null,
			$data['outNumClasse'] ?? null,
			$data['outCodSerieAno'] ?? null,
			$data['outTurma'] ?? null,
			$data['outCodTurno'] ?? null,
			$data['outDescricaoTurno'] ?? null,
			$data['outCodHabilitacao'] ?? null,
			$data['outCodTipoClasse'] ?? null,
			$data['outNumSala'] ?? null,
			$data['outHorarioInicio'] ?? null,
			$data['outHorarioFim'] ?? null,
			$data['outQtdAtual'] ?? null,
			$data['outQtdDigitados'] ?? null,
			$data['outQtdEvadidos'] ?? null,
			$data['outQtdNCom'] ?? null,
			$data['outQtdOutros'] ?? null,
			$data['outQtdTransferidos'] ?? null,
			$data['outQtdRemanejados'] ?? null,
			$data['outQtdCessados'] ?? null,
			$data['outQtdReclassificados'] ?? null,
			$data['outCapacidadeFisicaMax'] ?? null,
			$data['outDataInicioAula'] ?? null,
			$data['outDataFimAula'] ?? null,
			$data['outProcessoID'] ?? null,
			($data['outAlunos'] ?? null) !== null ? array_map(static function($data) {
				return OutAlunos::fromJson($data);
			}, $data['outAlunos']) : null
		);
	}
}