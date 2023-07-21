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
    public $inTurma;
    public $inNrCapacidadeFisicaMaxima;
    public $inNumeroSala;
    public $inDataInicioAula;
    public $inDataFimAula;
    public $inHorarioInicioAula;
    public $inHorarioFimAula;
    public $inCodDuracao;
    public $inCodHabilitacao;

    /** @var [] */
    public $inCodigoAtividadeComplementar;
    public $inDiasDaSemana;
    public $inFlagSegunda;
    public $inHoraIniAulaSegunda;
    public $inHoraFimAulaSegunda;
    public $inFlagTerca;
    public $inHoraIniAulaTerca;
    public $inHoraFimAulaTerca;
    public $inFlagQuarta;
    public $inHoraIniAulaQuarta;
    public $inHoraFimAulaQuarta;
    public $inFlagQuinta;
    public $inHoraIniAulaQuinta;
    public $inHoraFimAulaQuinta;
    public $inFlagSexta;
    public $inHoraIniAulaSexta;
    public $inHoraFimAulaSexta;
    public $inFlagSabado;
    public $inHoraIniAulaSabado;
    public $inHoraFimAulaSabado;

    /**
     * Summary of __construct
     * @param mixed $inAnoLetivo
     * @param mixed $inCodEscola
     * @param mixed $inCodUnidade
     * @param mixed $inCodTipoEnsino
     * @param mixed $inCodSerieAno
     * @param mixed $inCodTipoClasse
     * @param mixed $inCodTurno
     * @param mixed $inTurma
     * @param mixed $inNrCapacidadeFisicaMaxima
     * @param mixed $inNumeroSala
     * @param mixed $inDataInicioAula
     * @param mixed $inDataFimAula
     * @param mixed $inHorarioInicioAula
     * @param mixed $inHorarioFimAula
     * @param mixed $inCodDuracao
     * @param mixed $inCodHabilitacao
     * @param [] $inCodigoAtividadeComplementar
     * @param string $inDiasDaSemana
     * @param mixed $inFlagSegunda
     * @param mixed $inHoraIniAulaSegunda
     * @param mixed $inHoraFimAulaSegunda
     * @param mixed $inFlagTerca
     * @param mixed $inHoraIniAulaTerca
     * @param mixed $inHoraFimAulaTerca
     * @param mixed $inFlagQuarta
     * @param mixed $inHoraIniAulaQuarta
     * @param mixed $inHoraFimAulaQuarta
     * @param mixed $inFlagQuinta
     * @param mixed $inHoraIniAulaQuinta
     * @param mixed $inHoraFimAulaQuinta
     * @param mixed $inFlagSexta
     * @param mixed $inHoraIniAulaSexta
     * @param mixed $inHoraFimAulaSexta
     * @param mixed $inFlagSabado
     * @param mixed $inHoraIniAulaSabado
     * @param mixed $inHoraFimAulaSabado
     */
    public function __construct(
        ?string $inAnoLetivo,
        ?string $inCodEscola,
        ?string $inCodUnidade,
        ?string $inCodTipoEnsino,
        ?string $inCodSerieAno,
        ?string $inCodTipoClasse,
        ?string $inCodTurno,
        ?string $inTurma,
        ?string $inNrCapacidadeFisicaMaxima,
        ?string $inNumeroSala,
        ?string $inDataInicioAula,
        ?string $inDataFimAula,
        ?string $inHorarioInicioAula,
        ?string $inHorarioFimAula,
        ?string $inCodDuracao,
        ?string $inCodHabilitacao,
        ?string $inCodigoAtividadeComplementar,
        string $inDiasDaSemana,
        ?string $inFlagSegunda,
        ?string $inHoraIniAulaSegunda,
        ?string $inHoraFimAulaSegunda,
        ?string $inFlagTerca,
        ?string $inHoraIniAulaTerca,
        ?string $inHoraFimAulaTerca,
        ?string $inFlagQuarta,
        ?string $inHoraIniAulaQuarta,
        ?string $inHoraFimAulaQuarta,
        ?string $inFlagQuinta,
        ?string $inHoraIniAulaQuinta,
        ?string $inHoraFimAulaQuinta,
        ?string $inFlagSexta,
        ?string $inHoraIniAulaSexta,
        ?string $inHoraFimAulaSexta,
        ?string $inFlagSabado,
        ?string $inHoraIniAulaSabado,
        ?string $inHoraFimAulaSabado
    ) {
        $this->inAnoLetivo = $inAnoLetivo;
        $this->inCodEscola = $inCodEscola;
        $this->inCodUnidade = $inCodUnidade;
        $this->inCodTipoEnsino = $inCodTipoEnsino;
        $this->inCodSerieAno = $inCodSerieAno;
        $this->inCodTipoClasse = $inCodTipoClasse;
        $this->inCodTurno = $inCodTurno;
        $this->inTurma = $inTurma;
        $this->inNrCapacidadeFisicaMaxima = $inNrCapacidadeFisicaMaxima;
        $this->inNumeroSala = $inNumeroSala;
        $this->inDataInicioAula = $inDataInicioAula;
        $this->inDataFimAula = $inDataFimAula;
        $this->inHorarioInicioAula = $inHorarioInicioAula;
        $this->inHorarioFimAula = $inHorarioFimAula;
        $this->inCodDuracao = $inCodDuracao;
        $this->inCodHabilitacao = $inCodHabilitacao;
        $this->inCodigoAtividadeComplementar = $inCodigoAtividadeComplementar;
        $this->inDiasDaSemana = $inDiasDaSemana;
        $this->inFlagSegunda = $inFlagSegunda;
        $this->inHoraIniAulaSegunda = $inHoraIniAulaSegunda;
        $this->inHoraFimAulaSegunda = $inHoraFimAulaSegunda;
        $this->inFlagTerca = $inFlagTerca;
        $this->inHoraIniAulaTerca = $inHoraIniAulaTerca;
        $this->inHoraFimAulaTerca = $inHoraFimAulaTerca;
        $this->inFlagQuarta = $inFlagQuarta;
        $this->inHoraIniAulaQuarta = $inHoraIniAulaQuarta;
        $this->inHoraFimAulaQuarta = $inHoraFimAulaQuarta;
        $this->inFlagQuinta = $inFlagQuinta;
        $this->inHoraIniAulaQuinta = $inHoraIniAulaQuinta;
        $this->inHoraFimAulaQuinta = $inHoraFimAulaQuinta;
        $this->inFlagSexta = $inFlagSexta;
        $this->inHoraIniAulaSexta = $inHoraIniAulaSexta;
        $this->inHoraFimAulaSexta = $inHoraFimAulaSexta;
        $this->inFlagSabado = $inFlagSabado;
        $this->inHoraIniAulaSabado = $inHoraIniAulaSabado;
        $this->inHoraFimAulaSabado = $inHoraFimAulaSabado;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}