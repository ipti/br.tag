<?php

class InInscricao implements JsonSerializable
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
    public $inCodigoEscolaNovotecOpcaoUm;
    public $inCodigoUnidadeNovotecOpcaoUm;
    public $inFl_InteresseCentroIdiomas;
    public $inFl_AceiteEscolaPEIDistante;
    public $inCodigoEixoCELOpcaoUm;
    public $inCodigoEscolaCELOpcaoUm;
    public $inCodigoUnidadeCELOpcaoUm;
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
        ?string $inCodigoEscolaNovotecOpcaoUm,
        ?string $inCodigoUnidadeNovotecOpcaoUm,
        ?string $inFl_InteresseCentroIdiomas,
        ?string $inFl_AceiteEscolaPEIDistante,
        ?string $inCodigoEixoCELOpcaoUm,
        ?string $inCodigoEscolaCELOpcaoUm,
        ?string $inCodigoUnidadeCELOpcaoUm
        ,
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
        $this->inCodigoEscolaNovotecOpcaoUm = $inCodigoEscolaNovotecOpcaoUm;
        $this->inCodigoUnidadeNovotecOpcaoUm = $inCodigoUnidadeNovotecOpcaoUm;
        $this->inFl_InteresseCentroIdiomas = $inFl_InteresseCentroIdiomas;
        $this->inFl_AceiteEscolaPEIDistante = $inFl_AceiteEscolaPEIDistante;
        $this->inCodigoEixoCELOpcaoUm = $inCodigoEixoCELOpcaoUm;
        $this->inCodigoEscolaCELOpcaoUm = $inCodigoEscolaCELOpcaoUm;
        $this->inCodigoUnidadeCELOpcaoUm = $inCodigoUnidadeCELOpcaoUm;
        $this->inCodigoEixoNovotecOpcaoDois = $inCodigoEixoNovotecOpcaoDois;
        $this->inCodigoEixoNovotecOpcaoTres = $inCodigoEixoNovotecOpcaoTres;
    }

    public function get_inAnoLetivo(): ?string
    {
        return $this->inAnoLetivo;
    }

    public function get_inCodEscola(): ?string
    {
        return $this->inCodEscola;
    }

    public function get_inCodUnidade(): ?string
    {
        return $this->inCodUnidade;
    }

    public function get_inFase(): ?string
    {
        return $this->inFase;
    }

    public function get_inInteresseIntegral(): ?string
    {
        return $this->inInteresseIntegral;
    }

    public function get_inInteresseEspanhol(): ?string
    {
        return $this->inInteresseEspanhol;
    }

    public function get_inNecesAtendNoturno(): ?string
    {
        return $this->inNecesAtendNoturno;
    }

    public function get_inInteresseNovoTec(): ?string
    {
        return $this->inInteresseNovoTec;
    }

    public function get_inCodigoObservacaoOpcaoNoturno(): ?string
    {
        return $this->inCodigoObservacaoOpcaoNoturno;
    }

    public function get_inCodigoEixoNovotecOpcaoUm(): ?string
    {
        return $this->inCodigoEixoNovotecOpcaoUm;
    }

    public function get_inCodigoEscolaNovotecOpcaoUm(): ?string
    {
        return $this->inCodigoEscolaNovotecOpcaoUm;
    }

    public function get_inCodigoUnidadeNovotecOpcaoUm(): ?string
    {
        return $this->inCodigoUnidadeNovotecOpcaoUm;
    }

    public function get_inFl_InteresseCentroIdiomas(): ?string
    {
        return $this->inFl_InteresseCentroIdiomas;
    }

    public function get_inFl_AceiteEscolaPEIDistante(): ?string
    {
        return $this->inFl_AceiteEscolaPEIDistante;
    }

    public function get_inCodigoEixoCELOpcaoUm(): ?string
    {
        return $this->inCodigoEixoCELOpcaoUm;
    }

    public function get_inCodigoEscolaCELOpcaoUm(): ?string
    {
        return $this->inCodigoEscolaCELOpcaoUm;
    }

    public function get_inCodigoUnidadeCELOpcaoUm(): ?string
    {
        return $this->inCodigoUnidadeCELOpcaoUm;
    }

    public function get_inCodigoEixoNovotecOpcaoDois(): ?string
    {
        return $this->inCodigoEixoNovotecOpcaoDois;
    }

    public function get_inCodigoEixoNovotecOpcaoTres(): ?string
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

    public function setInCodigoEscolaNovotecOpcaoUm(?string $inCodigoEscolaNovotecOpcaoUm): self
    {
        $this->inCodigoEscolaNovotecOpcaoUm = $inCodigoEscolaNovotecOpcaoUm;
        return $this;
    }

    public function setInCodigoUnidadeNovotecOpcaoUm(?string $inCodigoUnidadeNovotecOpcaoUm): self
    {
        $this->inCodigoUnidadeNovotecOpcaoUm = $inCodigoUnidadeNovotecOpcaoUm;
        return $this;
    }

    public function setInFlInteresseCentroIdiomas(?string $inFl_InteresseCentroIdiomas): self
    {
        $this->inFl_InteresseCentroIdiomas = $inFl_InteresseCentroIdiomas;
        return $this;
    }

    public function setInFlAceiteEscolaPeiDistante(?string $inFl_AceiteEscolaPEIDistante): self
    {
        $this->inFl_AceiteEscolaPEIDistante = $inFl_AceiteEscolaPEIDistante;
        return $this;
    }

    public function setInCodigoEixoCelOpcaoUm(?string $inCodigoEixoCELOpcaoUm): self
    {
        $this->inCodigoEixoCELOpcaoUm = $inCodigoEixoCELOpcaoUm;
        return $this;
    }

    public function setInCodigoEscolaCelOpcaoUm(?string $inCodigoEscolaCELOpcaoUm): self
    {
        $this->inCodigoEscolaCELOpcaoUm = $inCodigoEscolaCELOpcaoUm;
        return $this;
    }

    public function setInCodigoUnidadeCelOpcaoUm(?string $inCodigoUnidadeCELOpcaoUm): self
    {
        $this->inCodigoUnidadeCELOpcaoUm = $inCodigoUnidadeCELOpcaoUm;
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
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}