<?php
class OutClasse
{
	/**
	 * @var string|null
	 */
	public $outCodEscola;

	/**
	 * @var string|null
	 */
	public $outDescNomeAbrevEscola;

	/**
	 * @var string|null
	 */
	public $outCodTipoEnsino;

	/**
	 * @var string|null
	 */
	public $outDescTipoEnsino;

	/**
	 * @var string|null
	 */
	public $outAnoLetivo;

	/**
	 * @var string|null
	 */
	public $outNumClasse;

	/**
	 * @var string|null
	 */
	public $outCodSerieAno;

	/**
	 * @var string|null
	 */
	public $outTurma;

	/**
	 * @var string|null
	 */
	public $outCodTurno;

	/**
	 * @var string|null
	 */
	public $outDescricaoTurno;

	/**
	 * @var string|null
	 */
	public $outCodHabilitacao;

	/**
	 * @var string|null
	 */
	public $outCodTipoClasse;

	/**
	 * @var string|null
	 */
	public $outNumSala;

	/**
	 * @var string|null
	 */
	public $outHorarioInicio;

	/**
	 * @var string|null
	 */
	public $outHorarioFim;

	/**
	 * @var string|null
	 */
	public $outDataInicioAula;

	/**
	 * @var string|null
	 */
	public $outDataFimAula;

	/**
	 * @var string|null
	 */
	public $outQtdAtual;

	/**
	 * @var string|null
	 */
	public $outQtdDigitados;

	/**
	 * @var string|null
	 */
	public $outQtdEvadidos;

	/**
	 * @var string|null
	 */
	public $outQtdNCom;

	/**
	 * @var string|null
	 */
	public $outQtdOutros;

	/**
	 * @var string|null
	 */
	public $outQtdTransferidos;

	/**
	 * @var string|null
	 */
	public $outQtdRemanejados;

	/**
	 * @var string|null
	 */
	public $outQtdCessados;

	/**
	 * @var string|null
	 */
	public $outQtdReclassificados;

	/**
	 * @var string|null
	 */
	public $outCapacidadeFisicaMax;

	/**
	 * @var OutAluno[]|null
	 */
	public $outAlunos;

	/**
	 * @var string|null
	 */
	public $outProcessoID;

	/**
	 * @param string|null $outCodEscola
	 * @param string|null $outDescNomeAbrevEscola
	 * @param string|null $outCodTipoEnsino
	 * @param string|null $outDescTipoEnsino
	 * @param string|null $outAnoLetivo
	 * @param string|null $outNumClasse
	 * @param string|null $outCodSerieAno
	 * @param string|null $outTurma
	 * @param string|null $outCodTurno
	 * @param string|null $outDescricaoTurno
	 * @param string|null $outCodHabilitacao
	 * @param string|null $outCodTipoClasse
	 * @param string|null $outNumSala
	 * @param string|null $outHorarioInicio
	 * @param string|null $outHorarioFim
	 * @param string|null $outDataInicioAula
	 * @param string|null $outDataFimAula
	 * @param string|null $outQtdAtual
	 * @param string|null $outQtdDigitados
	 * @param string|null $outQtdEvadidos
	 * @param string|null $outQtdNCom
	 * @param string|null $outQtdOutros
	 * @param string|null $outQtdTransferidos
	 * @param string|null $outQtdRemanejados
	 * @param string|null $outQtdCessados
	 * @param string|null $outQtdReclassificados
	 * @param string|null $outCapacidadeFisicaMax
	 * @param OutAluno[]|null $outAlunos
	 * @param string|null $outProcessoID
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
		?string $outDataInicioAula,
		?string $outDataFimAula,
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
		$this->outDataInicioAula = $outDataInicioAula;
		$this->outDataFimAula = $outDataFimAula;
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
		$this->outAlunos = $outAlunos;
		$this->outProcessoID = $outProcessoID;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodEscola(): ?string
	{
		return $this->outCodEscola;
	}

	/**
	 * @return string|null
	 */
	public function getOutDescNomeAbrevEscola(): ?string
	{
		return $this->outDescNomeAbrevEscola;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodTipoEnsino(): ?string
	{
		return $this->outCodTipoEnsino;
	}

	/**
	 * @return string|null
	 */
	public function getOutDescTipoEnsino(): ?string
	{
		return $this->outDescTipoEnsino;
	}

	/**
	 * @return string|null
	 */
	public function getOutAnoLetivo(): ?string
	{
		return $this->outAnoLetivo;
	}

	/**
	 * @return string|null
	 */
	public function getOutNumClasse(): ?string
	{
		return $this->outNumClasse;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodSerieAno(): ?string
	{
		return $this->outCodSerieAno;
	}

	/**
	 * @return string|null
	 */
	public function getOutTurma(): ?string
	{
		return $this->outTurma;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodTurno(): ?string
	{
		return $this->outCodTurno;
	}

	/**
	 * @return string|null
	 */
	public function getOutDescricaoTurno(): ?string
	{
		return $this->outDescricaoTurno;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodHabilitacao(): ?string
	{
		return $this->outCodHabilitacao;
	}

	/**
	 * @return string|null
	 */
	public function getOutCodTipoClasse(): ?string
	{
		return $this->outCodTipoClasse;
	}

	/**
	 * @return string|null
	 */
	public function getOutNumSala(): ?string
	{
		return $this->outNumSala;
	}

	/**
	 * @return string|null
	 */
	public function getOutHorarioInicio(): ?string
	{
		return $this->outHorarioInicio;
	}

	/**
	 * @return string|null
	 */
	public function getOutHorarioFim(): ?string
	{
		return $this->outHorarioFim;
	}

	/**
	 * @return string|null
	 */
	public function getOutDataInicioAula(): ?string
	{
		return $this->outDataInicioAula;
	}

	/**
	 * @return string|null
	 */
	public function getOutDataFimAula(): ?string
	{
		return $this->outDataFimAula;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdAtual(): ?string
	{
		return $this->outQtdAtual;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdDigitados(): ?string
	{
		return $this->outQtdDigitados;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdEvadidos(): ?string
	{
		return $this->outQtdEvadidos;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdNCom(): ?string
	{
		return $this->outQtdNCom;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdOutros(): ?string
	{
		return $this->outQtdOutros;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdTransferidos(): ?string
	{
		return $this->outQtdTransferidos;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdRemanejados(): ?string
	{
		return $this->outQtdRemanejados;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdCessados(): ?string
	{
		return $this->outQtdCessados;
	}

	/**
	 * @return string|null
	 */
	public function getOutQtdReclassificados(): ?string
	{
		return $this->outQtdReclassificados;
	}

	/**
	 * @return string|null
	 */
	public function getOutCapacidadeFisicaMax(): ?string
	{
		return $this->outCapacidadeFisicaMax;
	}

	/**
	 * @return OutAluno[]|null
	 */
	public function getOutAlunos(): ?array
	{
		return $this->outAlunos;
	}

	/**
	 * @return string|null
	 */
	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	/**
	 * @param string|null $outCodEscola
	 * @return self
	 */
	public function setOutCodEscola(?string $outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;
		return $this;
	}

	/**
	 * @param string|null $outDescNomeAbrevEscola
	 * @return self
	 */
	public function setOutDescNomeAbrevEscola(?string $outDescNomeAbrevEscola): self
	{
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		return $this;
	}

	/**
	 * @param string|null $outCodTipoEnsino
	 * @return self
	 */
	public function setOutCodTipoEnsino(?string $outCodTipoEnsino): self
	{
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		return $this;
	}

	/**
	 * @param string|null $outDescTipoEnsino
	 * @return self
	 */
	public function setOutDescTipoEnsino(?string $outDescTipoEnsino): self
	{
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		return $this;
	}

	/**
	 * @param string|null $outAnoLetivo
	 * @return self
	 */
	public function setOutAnoLetivo(?string $outAnoLetivo): self
	{
		$this->outAnoLetivo = $outAnoLetivo;
		return $this;
	}

	/**
	 * @param string|null $outNumClasse
	 * @return self
	 */
	public function setOutNumClasse(?string $outNumClasse): self
	{
		$this->outNumClasse = $outNumClasse;
		return $this;
	}

	/**
	 * @param string|null $outCodSerieAno
	 * @return self
	 */
	public function setOutCodSerieAno(?string $outCodSerieAno): self
	{
		$this->outCodSerieAno = $outCodSerieAno;
		return $this;
	}

	/**
	 * @param string|null $outTurma
	 * @return self
	 */
	public function setOutTurma(?string $outTurma): self
	{
		$this->outTurma = $outTurma;
		return $this;
	}

	/**
	 * @param string|null $outCodTurno
	 * @return self
	 */
	public function setOutCodTurno(?string $outCodTurno): self
	{
		$this->outCodTurno = $outCodTurno;
		return $this;
	}

	/**
	 * @param string|null $outDescricaoTurno
	 * @return self
	 */
	public function setOutDescricaoTurno(?string $outDescricaoTurno): self
	{
		$this->outDescricaoTurno = $outDescricaoTurno;
		return $this;
	}

	/**
	 * @param string|null $outCodHabilitacao
	 * @return self
	 */
	public function setOutCodHabilitacao(?string $outCodHabilitacao): self
	{
		$this->outCodHabilitacao = $outCodHabilitacao;
		return $this;
	}

	/**
	 * @param string|null $outCodTipoClasse
	 * @return self
	 */
	public function setOutCodTipoClasse(?string $outCodTipoClasse): self
	{
		$this->outCodTipoClasse = $outCodTipoClasse;
		return $this;
	}

	/**
	 * @param string|null $outNumSala
	 * @return self
	 */
	public function setOutNumSala(?string $outNumSala): self
	{
		$this->outNumSala = $outNumSala;
		return $this;
	}

	/**
	 * @param string|null $outHorarioInicio
	 * @return self
	 */
	public function setOutHorarioInicio(?string $outHorarioInicio): self
	{
		$this->outHorarioInicio = $outHorarioInicio;
		return $this;
	}

	/**
	 * @param string|null $outHorarioFim
	 * @return self
	 */
	public function setOutHorarioFim(?string $outHorarioFim): self
	{
		$this->outHorarioFim = $outHorarioFim;
		return $this;
	}

	/**
	 * @param string|null $outDataInicioAula
	 * @return self
	 */
	public function setOutDataInicioAula(?string $outDataInicioAula): self
	{
		$this->outDataInicioAula = $outDataInicioAula;
		return $this;
	}

	/**
	 * @param string|null $outDataFimAula
	 * @return self
	 */
	public function setOutDataFimAula(?string $outDataFimAula): self
	{
		$this->outDataFimAula = $outDataFimAula;
		return $this;
	}

	/**
	 * @param string|null $outQtdAtual
	 * @return self
	 */
	public function setOutQtdAtual(?string $outQtdAtual): self
	{
		$this->outQtdAtual = $outQtdAtual;
		return $this;
	}

	/**
	 * @param string|null $outQtdDigitados
	 * @return self
	 */
	public function setOutQtdDigitados(?string $outQtdDigitados): self
	{
		$this->outQtdDigitados = $outQtdDigitados;
		return $this;
	}

	/**
	 * @param string|null $outQtdEvadidos
	 * @return self
	 */
	public function setOutQtdEvadidos(?string $outQtdEvadidos): self
	{
		$this->outQtdEvadidos = $outQtdEvadidos;
		return $this;
	}

	/**
	 * @param string|null $outQtdNCom
	 * @return self
	 */
	public function setOutQtdNCom(?string $outQtdNCom): self
	{
		$this->outQtdNCom = $outQtdNCom;
		return $this;
	}

	/**
	 * @param string|null $outQtdOutros
	 * @return self
	 */
	public function setOutQtdOutros(?string $outQtdOutros): self
	{
		$this->outQtdOutros = $outQtdOutros;
		return $this;
	}

	/**
	 * @param string|null $outQtdTransferidos
	 * @return self
	 */
	public function setOutQtdTransferidos(?string $outQtdTransferidos): self
	{
		$this->outQtdTransferidos = $outQtdTransferidos;
		return $this;
	}

	/**
	 * @param string|null $outQtdRemanejados
	 * @return self
	 */
	public function setOutQtdRemanejados(?string $outQtdRemanejados): self
	{
		$this->outQtdRemanejados = $outQtdRemanejados;
		return $this;
	}

	/**
	 * @param string|null $outQtdCessados
	 * @return self
	 */
	public function setOutQtdCessados(?string $outQtdCessados): self
	{
		$this->outQtdCessados = $outQtdCessados;
		return $this;
	}

	/**
	 * @param string|null $outQtdReclassificados
	 * @return self
	 */
	public function setOutQtdReclassificados(?string $outQtdReclassificados): self
	{
		$this->outQtdReclassificados = $outQtdReclassificados;
		return $this;
	}

	/**
	 * @param string|null $outCapacidadeFisicaMax
	 * @return self
	 */
	public function setOutCapacidadeFisicaMax(?string $outCapacidadeFisicaMax): self
	{
		$this->outCapacidadeFisicaMax = $outCapacidadeFisicaMax;
		return $this;
	}

	/**
	 * @param OutAluno[]|null $outAlunos
	 * @return self
	 */
	public function setOutAlunos(?array $outAlunos): self
	{
		$this->outAlunos = $outAlunos;
		return $this;
	}

	/**
	 * @param string|null $outProcessoID
	 * @return self
	 */
	public function setOutProcessoId(?string $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
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
			$data['outDataInicioAula'] ?? null,
			$data['outDataFimAula'] ?? null,
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
			($data['outAlunos'] ?? null) !== null ? array_map(static function($data) {
				return OutAluno::fromJson($data);
			}, $data['outAlunos']) : null,
			$data['outProcessoID'] ?? null
		);
	}
}

?>