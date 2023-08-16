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
		?int $outAnoLetivo,
		?int $outCodEscola,
		?string $outNomeEscola,
		?int $outCodUnidade,
		?int $outCodTipoClasse,
		?int $outCodTurno,
		?string $outDescricaoTurno,
		?int $outTurma,
		?string $outDescricaoTurma,
		?int $outNrCapacidadeFisicaMaxima,
		?int $outNrAlunosAtivos,
		?string $outDataInicioAula,
		?string $outDataFimAula,
		?string $outHorarioInicioAula,
		?string $outHorarioFimAula,
		?int $outCodDuracao,
		?int $outCodHabilitacao,
		?array $outAtividadesComplementar,
		?int $outCodTipoEnsino,
		?string $outNomeTipoEnsino,
		?string $outNumeroSala,
		?int $outCodSerieAno,
		?string $outDescricaoSerieAno,
		?OutDiasSemana $outDiasSemana,
		?int $outProcessoID
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

	public function getOutAnoLetivo(): ?int
	{
		return $this->outAnoLetivo;
	}

	public function getOutCodEscola(): ?int
	{
		return $this->outCodEscola;
	}

	public function getOutNomeEscola(): ?string
	{
		return $this->outNomeEscola;
	}

	public function getOutCodUnidade(): ?int
	{
		return $this->outCodUnidade;
	}

	public function getOutCodTipoClasse(): ?int
	{
		return $this->outCodTipoClasse;
	}

	public function getOutCodTurno(): ?int
	{
		return $this->outCodTurno;
	}

	public function getOutDescricaoTurno(): ?string
	{
		return $this->outDescricaoTurno;
	}

	public function getOutTurma(): ?int
	{
		return $this->outTurma;
	}

	public function getOutDescricaoTurma(): ?string
	{
		return $this->outDescricaoTurma;
	}

	public function getOutNrCapacidadeFisicaMaxima(): ?int
	{
		return $this->outNrCapacidadeFisicaMaxima;
	}

	public function getOutNrAlunosAtivos(): ?int
	{
		return $this->outNrAlunosAtivos;
	}

	public function getOutDataInicioAula(): ?string
	{
		return $this->outDataInicioAula;
	}

	public function getOutDataFimAula(): ?string
	{
		return $this->outDataFimAula;
	}

	public function getOutHorarioInicioAula(): ?string
	{
		return $this->outHorarioInicioAula;
	}

	public function getOutHorarioFimAula(): ?string
	{
		return $this->outHorarioFimAula;
	}

	public function getOutCodDuracao(): ?int
	{
		return $this->outCodDuracao;
	}

	public function getOutCodHabilitacao(): ?int
	{
		return $this->outCodHabilitacao;
	}

	public function getOutAtividadesComplementar(): ?array
	{
		return $this->outAtividadesComplementar;
	}

	public function getOutCodTipoEnsino(): ?int
	{
		return $this->outCodTipoEnsino;
	}

	public function getOutNomeTipoEnsino(): ?string
	{
		return $this->outNomeTipoEnsino;
	}

	public function getOutNumeroSala(): ?string
	{
		return $this->outNumeroSala;
	}

	public function getOutCodSerieAno(): ?int
	{
		return $this->outCodSerieAno;
	}

	public function getOutDescricaoSerieAno(): ?string
	{
		return $this->outDescricaoSerieAno;
	}

	public function getOutDiasSemana(): ?OutDiasSemana
	{
		return $this->outDiasSemana;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	public function setOutAnoLetivo(?int $outAnoLetivo): self
	{
		$this->outAnoLetivo = $outAnoLetivo;
		return $this;
	}

	public function setOutCodEscola(?int $outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;
		return $this;
	}

	public function setOutNomeEscola(?string $outNomeEscola): self
	{
		$this->outNomeEscola = $outNomeEscola;
		return $this;
	}

	public function setOutCodUnidade(?int $outCodUnidade): self
	{
		$this->outCodUnidade = $outCodUnidade;
		return $this;
	}

	public function setOutCodTipoClasse(?int $outCodTipoClasse): self
	{
		$this->outCodTipoClasse = $outCodTipoClasse;
		return $this;
	}

	public function setOutCodTurno(?int $outCodTurno): self
	{
		$this->outCodTurno = $outCodTurno;
		return $this;
	}

	public function setOutDescricaoTurno(?string $outDescricaoTurno): self
	{
		$this->outDescricaoTurno = $outDescricaoTurno;
		return $this;
	}

	public function setOutTurma(?int $outTurma): self
	{
		$this->outTurma = $outTurma;
		return $this;
	}

	public function setOutDescricaoTurma(?string $outDescricaoTurma): self
	{
		$this->outDescricaoTurma = $outDescricaoTurma;
		return $this;
	}

	public function setOutNrCapacidadeFisicaMaxima(?int $outNrCapacidadeFisicaMaxima): self
	{
		$this->outNrCapacidadeFisicaMaxima = $outNrCapacidadeFisicaMaxima;
		return $this;
	}

	public function setOutNrAlunosAtivos(?int $outNrAlunosAtivos): self
	{
		$this->outNrAlunosAtivos = $outNrAlunosAtivos;
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

	public function setOutHorarioInicioAula(?string $outHorarioInicioAula): self
	{
		$this->outHorarioInicioAula = $outHorarioInicioAula;
		return $this;
	}

	public function setOutHorarioFimAula(?string $outHorarioFimAula): self
	{
		$this->outHorarioFimAula = $outHorarioFimAula;
		return $this;
	}

	public function setOutCodDuracao(?int $outCodDuracao): self
	{
		$this->outCodDuracao = $outCodDuracao;
		return $this;
	}

	public function setOutCodHabilitacao(?int $outCodHabilitacao): self
	{
		$this->outCodHabilitacao = $outCodHabilitacao;
		return $this;
	}

	public function setOutAtividadesComplementar(?array $outAtividadesComplementar): self
	{
		$this->outAtividadesComplementar = $outAtividadesComplementar;
		return $this;
	}

	public function setOutCodTipoEnsino(?int $outCodTipoEnsino): self
	{
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		return $this;
	}

	public function setOutNomeTipoEnsino(?string $outNomeTipoEnsino): self
	{
		$this->outNomeTipoEnsino = $outNomeTipoEnsino;
		return $this;
	}

	public function setOutNumeroSala(?string $outNumeroSala): self
	{
		$this->outNumeroSala = $outNumeroSala;
		return $this;
	}

	public function setOutCodSerieAno(?int $outCodSerieAno): self
	{
		$this->outCodSerieAno = $outCodSerieAno;
		return $this;
	}

	public function setOutDescricaoSerieAno(?string $outDescricaoSerieAno): self
	{
		$this->outDescricaoSerieAno = $outDescricaoSerieAno;
		return $this;
	}

	public function setOutDiasSemana(?OutDiasSemana $outDiasSemana): self
	{
		$this->outDiasSemana = $outDiasSemana;
		return $this;
	}

	public function setOutProcessoId(?int $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outAnoLetivo'] ?? null,
			$data['outCodEscola'] ?? null,
			$data['outNomeEscola'] ?? null,
			$data['outCodUnidade'] ?? null,
			$data['outCodTipoClasse'] ?? null,
			$data['outCodTurno'] ?? null,
			$data['outDescricaoTurno'] ?? null,
			intval($data['outTurma']) ?? null,
			$data['outDescricaoTurma'] ?? null,
			$data['outNrCapacidadeFisicaMaxima'] ?? null,
			$data['outNrAlunosAtivos'] ?? null,
			$data['outDataInicioAula'] ?? null,
			$data['outDataFimAula'] ?? null,
			$data['outHorarioInicioAula'] ?? null,
			$data['outHorarioFimAula'] ?? null,
			$data['outCodDuracao'] ?? null,
			$data['outCodHabilitacao'] ?? null,
			$data['outAtividadesComplementar'] ?? null,
			$data['outCodTipoEnsino'] ?? null,
			$data['outNomeTipoEnsino'] ?? null,
			$data['outNumeroSala'] ?? null,
			$data['outCodSerieAno'] ?? null,
			$data['outDescricaoSerieAno'] ?? null,
			($data['outDiasSemana'] ?? null) !== null ? OutDiasSemana::fromJson($data['outDiasSemana']) : null,
			intval($data['outProcessoID']) ?? null
		);
	}
}
