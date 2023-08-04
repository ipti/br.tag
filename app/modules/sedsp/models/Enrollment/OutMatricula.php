<?php

class OutMatricula
{
	public $outAnoLetivo;
	public $outCodEscola;
	public $outDescNomeAbrevEscola;
	public $outNumClasse;
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	public $outCodSerieAno;
	public $outDescSerieAno;
	public $outGrauNivel;
	public $outSerieNivel;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outNumAluno;
	public $outCodHabilitacao;
	public $outDescHabilitacao;
	public $outTurma;
	public $outDescTurma;
	public $outDataInclusaoMatricula;
	public $outDataFimMatricula;
	public $outDataInicioMatricula;
	public $outHoraFinal;
	public $outHoraInicial;
	public $outCodSitMatricula;
	public $outDescSitMatricula;
	public $outCodSitTranspEscolar;
	public $outDescSitTranspEscolar;

	public function __construct(
		?string $outAnoLetivo,
		?string $outCodEscola,
		?string $outDescNomeAbrevEscola,
		?string $outNumClasse,
		?string $outCodTipoEnsino,
		?string $outDescTipoEnsino,
		?string $outCodSerieAno,
		?string $outDescSerieAno,
		?string $outGrauNivel,
		?string $outSerieNivel,
		?string $outCodTurno,
		?string $outDescricaoTurno,
		?string $outNumAluno,
		?string $outCodHabilitacao,
		?string $outDescHabilitacao,
		?string $outTurma,
		?string $outDescTurma,
		?string $outDataInclusaoMatricula,
		?string $outDataFimMatricula,
		?string $outDataInicioMatricula,
		?string $outHoraFinal,
		?string $outHoraInicial,
		?string $outCodSitMatricula,
		?string $outDescSitMatricula,
		?string $outCodSitTranspEscolar,
		?string $outDescSitTranspEscolar 
	) {
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outCodEscola = $outCodEscola;
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		$this->outNumClasse = $outNumClasse;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outDescSerieAno = $outDescSerieAno;
		$this->outGrauNivel = $outGrauNivel;
		$this->outSerieNivel = $outSerieNivel;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outNumAluno = $outNumAluno;
		$this->outCodHabilitacao = $outCodHabilitacao;
		$this->outDescHabilitacao = $outDescHabilitacao;
		$this->outTurma = $outTurma;
		$this->outDescTurma = $outDescTurma;
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;
		$this->outDataFimMatricula = $outDataFimMatricula;
		$this->outDataInicioMatricula = $outDataInicioMatricula;
		$this->outHoraFinal = $outHoraFinal;
		$this->outHoraInicial = $outHoraInicial;
		$this->outCodSitMatricula = $outCodSitMatricula;
		$this->outDescSitMatricula = $outDescSitMatricula;
		$this->outCodSitTranspEscolar = $outCodSitTranspEscolar;
		$this->outDescSitTranspEscolar  = $outDescSitTranspEscolar ;
	}

	public function getOutAnoLetivo(): ?string
	{
		return $this->outAnoLetivo;
	}

	public function getOutCodEscola(): ?string
	{
		return $this->outCodEscola;
	}

	public function getOutDescNomeAbrevEscola(): ?string
	{
		return $this->outDescNomeAbrevEscola;
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

	public function getOutDescSerieAno(): ?string
	{
		return $this->outDescSerieAno;
	}

	public function getOutGrauNivel(): ?string
	{
		return $this->outGrauNivel;
	}

	public function getOutSerieNivel(): ?string
	{
		return $this->outSerieNivel;
	}

	public function getOutCodTurno(): ?string
	{
		return $this->outCodTurno;
	}

	public function getOutDescricaoTurno(): ?string
	{
		return $this->outDescricaoTurno;
	}

	public function getOutNumAluno(): ?string
	{
		return $this->outNumAluno;
	}

	public function getOutCodHabilitacao(): ?string
	{
		return $this->outCodHabilitacao;
	}

	public function getOutDescHabilitacao(): ?string
	{
		return $this->outDescHabilitacao;
	}

	public function getOutTurma(): ?string
	{
		return $this->outTurma;
	}

	public function getOutDescTurma(): ?string
	{
		return $this->outDescTurma;
	}

	public function getOutDataInclusaoMatricula(): ?string
	{
		return $this->outDataInclusaoMatricula;
	}

	public function getOutDataFimMatricula(): ?string
	{
		return $this->outDataFimMatricula;
	}

	public function getOutDataInicioMatricula(): ?string
	{
		return $this->outDataInicioMatricula;
	}

	public function getOutHoraFinal(): ?string
	{
		return $this->outHoraFinal;
	}

	public function getOutHoraInicial(): ?string
	{
		return $this->outHoraInicial;
	}

	public function getOutCodSitMatricula(): ?string
	{
		return $this->outCodSitMatricula;
	}

	public function getOutDescSitMatricula(): ?string
	{
		return $this->outDescSitMatricula;
	}

	public function getOutCodSitTranspEscolar(): ?string
	{
		return $this->outCodSitTranspEscolar;
	}

	public function getOutDescSitTranspEscolar(): ?string
	{
		return $this->outDescSitTranspEscolar ;
	}

	public function setOutAnoLetivo(?string $outAnoLetivo): self
	{
		$this->outAnoLetivo = $outAnoLetivo;
		return $this;
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

	public function setOutDescSerieAno(?string $outDescSerieAno): self
	{
		$this->outDescSerieAno = $outDescSerieAno;
		return $this;
	}

	public function setOutGrauNivel(?string $outGrauNivel): self
	{
		$this->outGrauNivel = $outGrauNivel;
		return $this;
	}

	public function setOutSerieNivel(?string $outSerieNivel): self
	{
		$this->outSerieNivel = $outSerieNivel;
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

	public function setOutNumAluno(?string $outNumAluno): self
	{
		$this->outNumAluno = $outNumAluno;
		return $this;
	}

	public function setOutCodHabilitacao(?string $outCodHabilitacao): self
	{
		$this->outCodHabilitacao = $outCodHabilitacao;
		return $this;
	}

	public function setOutDescHabilitacao(?string $outDescHabilitacao): self
	{
		$this->outDescHabilitacao = $outDescHabilitacao;
		return $this;
	}

	public function setOutTurma(?string $outTurma): self
	{
		$this->outTurma = $outTurma;
		return $this;
	}

	public function setOutDescTurma(?string $outDescTurma): self
	{
		$this->outDescTurma = $outDescTurma;
		return $this;
	}

	public function setOutDataInclusaoMatricula(?string $outDataInclusaoMatricula): self
	{
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;
		return $this;
	}

	public function setOutDataFimMatricula(?string $outDataFimMatricula): self
	{
		$this->outDataFimMatricula = $outDataFimMatricula;
		return $this;
	}

	public function setOutDataInicioMatricula(?string $outDataInicioMatricula): self
	{
		$this->outDataInicioMatricula = $outDataInicioMatricula;
		return $this;
	}

	public function setOutHoraFinal(?string $outHoraFinal): self
	{
		$this->outHoraFinal = $outHoraFinal;
		return $this;
	}

	public function setOutHoraInicial(?string $outHoraInicial): self
	{
		$this->outHoraInicial = $outHoraInicial;
		return $this;
	}

	public function setOutCodSitMatricula(?string $outCodSitMatricula): self
	{
		$this->outCodSitMatricula = $outCodSitMatricula;
		return $this;
	}

	public function setOutDescSitMatricula(?string $outDescSitMatricula): self
	{
		$this->outDescSitMatricula = $outDescSitMatricula;
		return $this;
	}

	public function setOutCodSitTranspEscolar(?string $outCodSitTranspEscolar): self
	{
		$this->outCodSitTranspEscolar = $outCodSitTranspEscolar;
		return $this;
	}

	public function setOutDescSitTranspEscolar(?string $outDescSitTranspEscolar ): self
	{
		$this->outDescSitTranspEscolar  = $outDescSitTranspEscolar ;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outAnoLetivo'] ?? null,
			$data['outCodEscola'] ?? null,
			$data['outDescNomeAbrevEscola'] ?? null,
			$data['outNumClasse'] ?? null,
			$data['outCodTipoEnsino'] ?? null,
			$data['outDescTipoEnsino'] ?? null,
			$data['outCodSerieAno'] ?? null,
			$data['outDescSerieAno'] ?? null,
			$data['outGrauNivel'] ?? null,
			$data['outSerieNivel'] ?? null,
			$data['outCodTurno'] ?? null,
			$data['outDescricaoTurno'] ?? null,
			$data['outNumAluno'] ?? null,
			$data['outCodHabilitacao'] ?? null,
			$data['outDescHabilitacao'] ?? null,
			$data['outTurma'] ?? null,
			$data['outDescTurma'] ?? null,
			$data['outDataInclusaoMatricula'] ?? null,
			$data['outDataFimMatricula'] ?? null,
			$data['outDataInicioMatricula'] ?? null,
			$data['outHoraFinal'] ?? null,
			$data['outHoraInicial'] ?? null,
			$data['outCodSitMatricula'] ?? null,
			$data['outDescSitMatricula'] ?? null,
			$data['outCodSitTranspEscolar'] ?? null,
			$data['outDescSitTranspEscolar'] ?? null
		);
	}	
}