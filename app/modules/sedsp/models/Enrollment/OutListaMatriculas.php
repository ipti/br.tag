<?php

class OutListaMatriculas
{
	public $outAnoLetivo;
	public $outMunicipio;
	public $outNomeRedeEnsino;
	public $outCodEscola;
	public $outCodUnidade;
	public $outDescNomeAbrevEscola;
	public $outNumClasse;
	public $outNumAluno;
	public $outCodTurno;
	public $outDescricaoTurno;
	public $outCodTipoEnsino;
	public $outDescTipoEnsino;
	public $outCodSerieAno;
	public $outDescSerieAno;
	public $outGrauNivel;
	public $outSerieNivel;
	public $outTurma;
	public $outDescTurma;
	public $outCodHabilitacao;
	public $outDescHabilitacao;
	public $outDataInicioMatricula;
	public $outDataFimMatricula;
	public $outDataInclusaoMatricula;
	public $outCodSitMatricula;
	public $outDescSitMatricula;
	public $outCodSitTranspEscolar;
	public $outDescSitTranspEscolar ;

	public function __construct(
		?string $outAnoLetivo,
		?string $outMunicipio,
		?string $outNomeRedeEnsino,
		?string $outCodEscola,
		?string $outCodUnidade,
		?string $outDescNomeAbrevEscola,
		?string $outNumClasse,
		?string $outNumAluno,
		?string $outCodTurno,
		?string $outDescricaoTurno,
		?string $outCodTipoEnsino,
		?string $outDescTipoEnsino,
		?string $outCodSerieAno,
		?string $outDescSerieAno,
		?string $outGrauNivel,
		?string $outSerieNivel,
		?string $outTurma,
		?string $outDescTurma,
		?string $outCodHabilitacao,
		?string $outDescHabilitacao,
		?string $outDataInicioMatricula,
		?string $outDataFimMatricula,
		?string $outDataInclusaoMatricula,
		?string $outCodSitMatricula,
		?string $outDescSitMatricula,
		?string $outCodSitTranspEscolar,
		?string $outDescSitTranspEscolar 
	) {
		$this->outAnoLetivo = $outAnoLetivo;
		$this->outMunicipio = $outMunicipio;
		$this->outNomeRedeEnsino = $outNomeRedeEnsino;
		$this->outCodEscola = $outCodEscola;
		$this->outCodUnidade = $outCodUnidade;
		$this->outDescNomeAbrevEscola = $outDescNomeAbrevEscola;
		$this->outNumClasse = $outNumClasse;
		$this->outNumAluno = $outNumAluno;
		$this->outCodTurno = $outCodTurno;
		$this->outDescricaoTurno = $outDescricaoTurno;
		$this->outCodTipoEnsino = $outCodTipoEnsino;
		$this->outDescTipoEnsino = $outDescTipoEnsino;
		$this->outCodSerieAno = $outCodSerieAno;
		$this->outDescSerieAno = $outDescSerieAno;
		$this->outGrauNivel = $outGrauNivel;
		$this->outSerieNivel = $outSerieNivel;
		$this->outTurma = $outTurma;
		$this->outDescTurma = $outDescTurma;
		$this->outCodHabilitacao = $outCodHabilitacao;
		$this->outDescHabilitacao = $outDescHabilitacao;
		$this->outDataInicioMatricula = $outDataInicioMatricula;
		$this->outDataFimMatricula = $outDataFimMatricula;
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;
		$this->outCodSitMatricula = $outCodSitMatricula;
		$this->outDescSitMatricula = $outDescSitMatricula;
		$this->outCodSitTranspEscolar = $outCodSitTranspEscolar;
		$this->outDescSitTranspEscolar  = $outDescSitTranspEscolar ;
	}

	public function getOutAnoLetivo(): ?string
	{
		return $this->outAnoLetivo;
	}

	public function getOutMunicipio(): ?string
	{
		return $this->outMunicipio;
	}

	public function getOutNomeRedeEnsino(): ?string
	{
		return $this->outNomeRedeEnsino;
	}

	public function getOutCodEscola(): ?string
	{
		return $this->outCodEscola;
	}

	public function getOutCodUnidade(): ?string
	{
		return $this->outCodUnidade;
	}

	public function getOutDescNomeAbrevEscola(): ?string
	{
		return $this->outDescNomeAbrevEscola;
	}

	public function getOutNumClasse(): ?string
	{
		return $this->outNumClasse;
	}

	public function getOutNumAluno(): ?string
	{
		return $this->outNumAluno;
	}

	public function getOutCodTurno(): ?string
	{
		return $this->outCodTurno;
	}

	public function getOutDescricaoTurno(): ?string
	{
		return $this->outDescricaoTurno;
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

	public function getOutTurma(): ?string
	{
		return $this->outTurma;
	}

	public function getOutDescTurma(): ?string
	{
		return $this->outDescTurma;
	}

	public function getOutCodHabilitacao(): ?string
	{
		return $this->outCodHabilitacao;
	}

	public function getOutDescHabilitacao(): ?string
	{
		return $this->outDescHabilitacao;
	}

	public function getOutDataInicioMatricula(): ?string
	{
		return $this->outDataInicioMatricula;
	}

	public function getOutDataFimMatricula(): ?string
	{
		return $this->outDataFimMatricula;
	}

	public function getOutDataInclusaoMatricula(): ?string
	{
		return $this->outDataInclusaoMatricula;
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

	public function setOutMunicipio(?string $outMunicipio): self
	{
		$this->outMunicipio = $outMunicipio;
		return $this;
	}

	public function setOutNomeRedeEnsino(?string $outNomeRedeEnsino): self
	{
		$this->outNomeRedeEnsino = $outNomeRedeEnsino;
		return $this;
	}

	public function setOutCodEscola(?string $outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;
		return $this;
	}

	public function setOutCodUnidade(?string $outCodUnidade): self
	{
		$this->outCodUnidade = $outCodUnidade;
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

	public function setOutNumAluno(?string $outNumAluno): self
	{
		$this->outNumAluno = $outNumAluno;
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

	public function setOutDataInicioMatricula(?string $outDataInicioMatricula): self
	{
		$this->outDataInicioMatricula = $outDataInicioMatricula;
		return $this;
	}

	public function setOutDataFimMatricula(?string $outDataFimMatricula): self
	{
		$this->outDataFimMatricula = $outDataFimMatricula;
		return $this;
	}

	public function setOutDataInclusaoMatricula(?string $outDataInclusaoMatricula): self
	{
		$this->outDataInclusaoMatricula = $outDataInclusaoMatricula;
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
			$data['outMunicipio'] ?? null,
			$data['outNomeRedeEnsino'] ?? null,
			$data['outCodEscola'] ?? null,
			$data['outCodUnidade'] ?? null,
			$data['outDescNomeAbrevEscola'] ?? null,
			$data['outNumClasse'] ?? null,
			$data['outNumAluno'] ?? null,
			$data['outCodTurno'] ?? null,
			$data['outDescricaoTurno'] ?? null,
			$data['outCodTipoEnsino'] ?? null,
			$data['outDescTipoEnsino'] ?? null,
			$data['outCodSerieAno'] ?? null,
			$data['outDescSerieAno'] ?? null,
			$data['outGrauNivel'] ?? null,
			$data['outSerieNivel'] ?? null,
			$data['outTurma'] ?? null,
			$data['outDescTurma'] ?? null,
			$data['outCodHabilitacao'] ?? null,
			$data['outDescHabilitacao'] ?? null,
			$data['outDataInicioMatricula'] ?? null,
			$data['outDataFimMatricula'] ?? null,
			$data['outDataInclusaoMatricula'] ?? null,
			$data['outCodSitMatricula'] ?? null,
			$data['outDescSitMatricula'] ?? null,
			$data['outCodSitTranspEscolar'] ?? null,
			$data['outDescSitTranspEscolar'] ?? null
		);
	}	
}