<?php

class InRelacaoClasses
{
    public $inAnoLetivo;
	public $inCodEscola;
	public $inCodTipoEnsino;
	public $inCodSerieAno;
	public $inCodTurno;
	public $inSemestre;

	public function __construct(
		?string $inAnoLetivo,
		?string $inCodEscola,
		?string $inCodTipoEnsino,
		?string $inCodSerieAno,
		?string $inCodTurno,
		?string $inSemestre
	) {
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inCodEscola = $inCodEscola;
		$this->inCodTipoEnsino = $inCodTipoEnsino;
		$this->inCodSerieAno = $inCodSerieAno;
		$this->inCodTurno = $inCodTurno;
		$this->inSemestre = $inSemestre;
	}

	public function getInAnoLetivo(): ?string
	{
		return $this->inAnoLetivo;
	}

	public function getInCodEscola(): ?string
	{
		return $this->inCodEscola;
	}

	public function getInCodTipoEnsino(): ?string
	{
		return $this->inCodTipoEnsino;
	}

	public function getInCodSerieAno(): ?string
	{
		return $this->inCodSerieAno;
	}

	public function getInCodTurno(): ?string
	{
		return $this->inCodTurno;
	}

	public function getInSemestre(): ?string
	{
		return $this->inSemestre;
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

	public function setInCodTurno(?string $inCodTurno): self
	{
		$this->inCodTurno = $inCodTurno;
		return $this;
	}

	public function setInSemestre(?string $inSemestre): self
	{
		$this->inSemestre = $inSemestre;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inAnoLetivo'] ?? null,
			$data['inCodEscola'] ?? null,
			$data['inCodTipoEnsino'] ?? null,
			$data['inCodSerieAno'] ?? null,
			$data['inCodTurno'] ?? null,
			$data['inSemestre'] ?? null
		);
	}
}
