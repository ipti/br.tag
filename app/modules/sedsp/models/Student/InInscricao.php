<?php

class InInscricao
{
	public $inAnoLetivo;
	public $inCodEscola;
	public $inCodUnidade;
	public $inFase;
	public $inInteresseIntegral;
	public $inInteresseEspanhol;
	public $inNecesAtendNoturno;
	public $inInteresseNovoTec;
	public $inCodigoObservacaoOpcaoNoturno;
	public $inCodigoEixoNovotecOpcaoUm;
	public $inCodigoEscolaNovotecOpcaoUm ;
	public $inCodigoUnidadeNovotecOpcaoUm ;
	public $inFl_InteresseCentroIdiomas ;
	public $inFl_AceiteEscolaPEIDistante ;
	public $inCodigoEixoCELOpcaoUm ;
	public $inCodigoEscolaCELOpcaoUm ;
	public $inCodigoUnidadeCELOpcaoUm ;
	public $inCodigoEixoNovotecOpcaoDois;
	public $inCodigoEixoNovotecOpcaoTres;

	public function __construct(
		?string $inAnoLetivo,
		?string $inCodEscola,
		?string $inCodUnidade,
		?string $inFase,
		?string $inInteresseIntegral,
		?string $inInteresseEspanhol,
		?string $inNecesAtendNoturno,
		?string $inInteresseNovoTec,
		?string $inCodigoObservacaoOpcaoNoturno,
		?string $inCodigoEixoNovotecOpcaoUm,
		?string $inCodigoEscolaNovotecOpcaoUm ,
		?string $inCodigoUnidadeNovotecOpcaoUm ,
		?string $inFl_InteresseCentroIdiomas ,
		?string $inFl_AceiteEscolaPEIDistante ,
		?string $inCodigoEixoCELOpcaoUm ,
		?string $inCodigoEscolaCELOpcaoUm ,
		?string $inCodigoUnidadeCELOpcaoUm ,
		?string $inCodigoEixoNovotecOpcaoDois,
		?string $inCodigoEixoNovotecOpcaoTres
	) {
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inCodEscola = $inCodEscola;
		$this->inCodUnidade = $inCodUnidade;
		$this->inFase = $inFase;
		$this->inInteresseIntegral = $inInteresseIntegral;
		$this->inInteresseEspanhol = $inInteresseEspanhol;
		$this->inNecesAtendNoturno = $inNecesAtendNoturno;
		$this->inInteresseNovoTec = $inInteresseNovoTec;
		$this->inCodigoObservacaoOpcaoNoturno = $inCodigoObservacaoOpcaoNoturno;
		$this->inCodigoEixoNovotecOpcaoUm = $inCodigoEixoNovotecOpcaoUm;
		$this-> inCodigoEscolaNovotecOpcaoUm  = $inCodigoEscolaNovotecOpcaoUm ;
		$this-> inCodigoUnidadeNovotecOpcaoUm  = $inCodigoUnidadeNovotecOpcaoUm ;
		$this-> inFl_InteresseCentroIdiomas  = $inFl_InteresseCentroIdiomas ;
		$this-> inFl_AceiteEscolaPEIDistante  = $inFl_AceiteEscolaPEIDistante ;
		$this-> inCodigoEixoCELOpcaoUm  = $inCodigoEixoCELOpcaoUm ;
		$this-> inCodigoEscolaCELOpcaoUm  = $inCodigoEscolaCELOpcaoUm ;
		$this-> inCodigoUnidadeCELOpcaoUm  = $inCodigoUnidadeCELOpcaoUm ;
		$this->inCodigoEixoNovotecOpcaoDois = $inCodigoEixoNovotecOpcaoDois;
		$this->inCodigoEixoNovotecOpcaoTres = $inCodigoEixoNovotecOpcaoTres;
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

	public function getInFase(): ?string
	{
		return $this->inFase;
	}

	public function getInInteresseIntegral(): ?string
	{
		return $this->inInteresseIntegral;
	}

	public function getInInteresseEspanhol(): ?string
	{
		return $this->inInteresseEspanhol;
	}

	public function getInNecesAtendNoturno(): ?string
	{
		return $this->inNecesAtendNoturno;
	}

	public function getInInteresseNovoTec(): ?string
	{
		return $this->inInteresseNovoTec;
	}

	public function getInCodigoObservacaoOpcaoNoturno(): ?string
	{
		return $this->inCodigoObservacaoOpcaoNoturno;
	}

	public function getInCodigoEixoNovotecOpcaoUm(): ?string
	{
		return $this->inCodigoEixoNovotecOpcaoUm;
	}

	public function getInCodigoEscolaNovotecOpcaoUm(): ?string
	{
		return $this-> inCodigoEscolaNovotecOpcaoUm ;
	}

	public function getInCodigoUnidadeNovotecOpcaoUm(): ?string
	{
		return $this-> inCodigoUnidadeNovotecOpcaoUm ;
	}

	public function getInFlInteresseCentroIdiomas(): ?string
	{
		return $this-> inFl_InteresseCentroIdiomas ;
	}

	public function getInFlAceiteEscolaPeiDistante(): ?string
	{
		return $this-> inFl_AceiteEscolaPEIDistante ;
	}

	public function getInCodigoEixoCelOpcaoUm(): ?string
	{
		return $this-> inCodigoEixoCELOpcaoUm ;
	}

	public function getInCodigoEscolaCelOpcaoUm(): ?string
	{
		return $this-> inCodigoEscolaCELOpcaoUm ;
	}

	public function getInCodigoUnidadeCelOpcaoUm(): ?string
	{
		return $this-> inCodigoUnidadeCELOpcaoUm ;
	}

	public function getInCodigoEixoNovotecOpcaoDois(): ?string
	{
		return $this->inCodigoEixoNovotecOpcaoDois;
	}

	public function getInCodigoEixoNovotecOpcaoTres(): ?string
	{
		return $this->inCodigoEixoNovotecOpcaoTres;
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

	public function setInFase(?string $inFase): self
	{
		$this->inFase = $inFase;
		return $this;
	}

	public function setInInteresseIntegral(?string $inInteresseIntegral): self
	{
		$this->inInteresseIntegral = $inInteresseIntegral;
		return $this;
	}

	public function setInInteresseEspanhol(?string $inInteresseEspanhol): self
	{
		$this->inInteresseEspanhol = $inInteresseEspanhol;
		return $this;
	}

	public function setInNecesAtendNoturno(?string $inNecesAtendNoturno): self
	{
		$this->inNecesAtendNoturno = $inNecesAtendNoturno;
		return $this;
	}

	public function setInInteresseNovoTec(?string $inInteresseNovoTec): self
	{
		$this->inInteresseNovoTec = $inInteresseNovoTec;
		return $this;
	}

	public function setInCodigoObservacaoOpcaoNoturno(?string $inCodigoObservacaoOpcaoNoturno): self
	{
		$this->inCodigoObservacaoOpcaoNoturno = $inCodigoObservacaoOpcaoNoturno;
		return $this;
	}

	public function setInCodigoEixoNovotecOpcaoUm(?string $inCodigoEixoNovotecOpcaoUm): self
	{
		$this->inCodigoEixoNovotecOpcaoUm = $inCodigoEixoNovotecOpcaoUm;
		return $this;
	}

	public function setInCodigoEscolaNovotecOpcaoUm(?string $inCodigoEscolaNovotecOpcaoUm ): self
	{
		$this-> inCodigoEscolaNovotecOpcaoUm  = $inCodigoEscolaNovotecOpcaoUm ;
		return $this;
	}

	public function setInCodigoUnidadeNovotecOpcaoUm(?string $inCodigoUnidadeNovotecOpcaoUm ): self
	{
		$this-> inCodigoUnidadeNovotecOpcaoUm  = $inCodigoUnidadeNovotecOpcaoUm ;
		return $this;
	}

	public function setInFlInteresseCentroIdiomas(?string $inFl_InteresseCentroIdiomas ): self
	{
		$this-> inFl_InteresseCentroIdiomas  = $inFl_InteresseCentroIdiomas ;
		return $this;
	}

	public function setInFlAceiteEscolaPeiDistante(?string $inFl_AceiteEscolaPEIDistante ): self
	{
		$this-> inFl_AceiteEscolaPEIDistante  = $inFl_AceiteEscolaPEIDistante ;
		return $this;
	}

	public function setInCodigoEixoCelOpcaoUm(?string $inCodigoEixoCELOpcaoUm ): self
	{
		$this-> inCodigoEixoCELOpcaoUm  = $inCodigoEixoCELOpcaoUm ;
		return $this;
	}

	public function setInCodigoEscolaCelOpcaoUm(?string $inCodigoEscolaCELOpcaoUm ): self
	{
		$this-> inCodigoEscolaCELOpcaoUm  = $inCodigoEscolaCELOpcaoUm ;
		return $this;
	}

	public function setInCodigoUnidadeCelOpcaoUm(?string $inCodigoUnidadeCELOpcaoUm ): self
	{
		$this-> inCodigoUnidadeCELOpcaoUm  = $inCodigoUnidadeCELOpcaoUm ;
		return $this;
	}

	public function setInCodigoEixoNovotecOpcaoDois(?string $inCodigoEixoNovotecOpcaoDois): self
	{
		$this->inCodigoEixoNovotecOpcaoDois = $inCodigoEixoNovotecOpcaoDois;
		return $this;
	}

	public function setInCodigoEixoNovotecOpcaoTres(?string $inCodigoEixoNovotecOpcaoTres): self
	{
		$this->inCodigoEixoNovotecOpcaoTres = $inCodigoEixoNovotecOpcaoTres;
		return $this;
	}

    /**
     * Summary of fromJson
     * @param array $data
     * @return InInscricao
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['inAnoLetivo'] ?? null,
            $data['inCodEscola'] ?? null,
            $data['inCodUnidade'] ?? null,
            $data['inFase'] ?? null,
            $data['inInteresseIntegral'] ?? null,
            $data['inInteresseEspanhol'] ?? null,
            $data['inNecesAtendNoturno'] ?? null,
            $data['inInteresseNovoTec'] ?? null,
            $data['inCodigoObservacaoOpcaoNoturno'] ?? null,
            $data['inCodigoEixoNovotecOpcaoUm'] ?? null,
            $data['inCodigoEscolaNovotecOpcaoUm'] ?? null,
            $data['inCodigoUnidadeNovotecOpcaoUm'] ?? null,
            $data['inFl_InteresseCentroIdiomas'] ?? null,
            $data['inFl_AceiteEscolaPEIDistante'] ?? null,
            $data['inCodigoEixoCELOpcaoUm'] ?? null,
            $data['inCodigoEscolaCELOpcaoUm'] ?? null,
            $data['inCodigoUnidadeCELOpcaoUm'] ?? null,
            $data['inCodigoEixoNovotecOpcaoDois'] ?? null,
            $data['inCodigoEixoNovotecOpcaoTres'] ?? null
        );
    }
}