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
		?string $outNumClasse,
		?string $outCodTipoEnsino,
		?string $outDescTipoEnsino,
		?string $outCodSerieAno,
		?string $outTurma,
		?string $outCodTurno,
		?string $outDescricaoTurno,
		?string $outCodHabilitacao,
		?string $outNumSala,
		?string $outHorarioInicio,
		?string $outHorarioFim,
		?string $outCodTipoClasse,
		?string $outDescTipoClasse,
		?string $outSemestre,
		?string $outQtdAtual,
		?string $outQtdDigitados,
		?string $outQtdEvadidos,
		?string $outQtdNCom,
		?string $outQtdOutros,
		?string $outQtdTransferidos,
		?string $outQtdRemanejados,
		?string $outQtdCessados,
		?string $outQtdReclassificados,
		?string $outCapacidadeFisicaMax
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

	public function getOutNumClasse(): ?string
	{
		return $this->outNumClasse;
	}

	public function getOutCodTipoEnsino(): ?string
	{
		return $this->outCodTipoEnsino;
	}

	public function getOutDescTipoEnsino(): ?string
	{
		return $this->outDescTipoEnsino;
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

	public function getOutCodTipoClasse(): ?string
	{
		return $this->outCodTipoClasse;
	}

	public function getOutDescTipoClasse(): ?string
	{
		return $this->outDescTipoClasse;
	}

	public function getOutSemestre(): ?string
	{
		return $this->outSemestre;
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

	public function setOutNumClasse(?string $outNumClasse): self
	{
		$this->outNumClasse = $outNumClasse;
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

	public function setOutCodTipoClasse(?string $outCodTipoClasse): self
	{
		$this->outCodTipoClasse = $outCodTipoClasse;
		return $this;
	}

	public function setOutDescTipoClasse(?string $outDescTipoClasse): self
	{
		$this->outDescTipoClasse = $outDescTipoClasse;
		return $this;
	}

	public function setOutSemestre(?string $outSemestre): self
	{
		$this->outSemestre = $outSemestre;
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

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNumClasse'] ?? null,
			$data['outCodTipoEnsino'] ?? null,
			$data['outDescTipoEnsino'] ?? null,
			$data['outCodSerieAno'] ?? null,
			$data['outTurma'] ?? null,
			$data['outCodTurno'] ?? null,
			$data['outDescricaoTurno'] ?? null,
			$data['outCodHabilitacao'] ?? null,
			$data['outNumSala'] ?? null,
			$data['outHorarioInicio'] ?? null,
			$data['outHorarioFim'] ?? null,
			$data['outCodTipoClasse'] ?? null,
			$data['outDescTipoClasse'] ?? null,
			$data['outSemestre'] ?? null,
			$data['outQtdAtual'] ?? null,
			$data['outQtdDigitados'] ?? null,
			$data['outQtdEvadidos'] ?? null,
			$data['outQtdNCom'] ?? null,
			$data['outQtdOutros'] ?? null,
			$data['outQtdTransferidos'] ?? null,
			$data['outQtdRemanejados'] ?? null,
			$data['outQtdCessados'] ?? null,
			$data['outQtdReclassificados'] ?? null,
			$data['outCapacidadeFisicaMax'] ?? null
		);
	}	
}