<?php

declare(strict_types=1);

class OutConsultClass
{
    /** @var int */
    private $outAnoLetivo;
    
    /** @var int */
    private $outCodEscola;

    /** @var string */
    private $outNomeEscola;

    /** @var int */
    private $outCodUnidade;

    /** @var int */
    private $outCodTipoClasse;

    /** @var int */
    private $outCodTurno;

    /** @var string */
    private $outDescricaoTurno;

    /** @var int */
    private $outTurma;

    /** @var string */
    private $outDescricaoTurma;

    /** @var int */
    private $outNrCapacidadeFisicaMaxima;

    /** @var int */
    private $outNrAlunosAtivos;

    private $outDataInicioAula;
    private $outDataFimAula;
    private $outHorarioInicioAula;
    private $outHorarioFimAula;

    /** @var int */
    private $outCodDuracao;

    /** @var int */
    private $outCodHabilitacao;

    /** @var int */
    private $outACodtividadesComplementar;

    /** @var string */
    private $outANometividadesComplementar;

    /** @var int */
    private $outCodTipoEnsino;

    /** @var string */
    private $outNomeTipoEnsino;

    /** @var int */
    private $outNumeroSala;

    /** @var int */
    private $outCodSerieAno;

    /** @var string */
    private $outDescricaoSerieAno;
    
    /** @var [] */
    private $outDiasSemana;

    /** @var bool */
    private $outFlagSegunda;
    private $outHoraIniAulaSegunda;
    private $outHoraFimAulaSegunda;

    /** @var bool */
    private $outFlagTerca;
    private $outHoraIniAulaTerca;
    private $outHoraFimAulaTerca;

    /** @var bool */
    private $outFlagQuarta;
    private $outHoraIniAulaQuarta;
    private $outHoraFimAulaQuarta;

    /** @var bool */
    private $outFlagQuinta;
    private $outHoraIniAulaQuinta;
    private $outHoraFimAulaQuinta;

    /** @var bool */
    private $outFlagSexta;
    private $outHoraIniAulaSexta;
    private $outHoraFimAulaSexta;

    /** @var bool */
    private $outFlagSabado;

    /**
     * Summary of __construct
     * @param OutConsultClass $consultClass
     */
    public function __construct($consultClass)
    {
        $this->outAnoLetivo = $consultClass->outAnoLetivo;
        $this->outCodEscola = $consultClass->outCodEscola;
        $this->outNomeEscola = $consultClass->outNomeEscola;
        $this->outCodUnidade = $consultClass->outCodUnidade;
        $this->outCodTipoClasse = $consultClass->outCodTipoClasse;
        $this->outCodTurno = $consultClass->outCodTurno;
        $this->outDescricaoTurno = $consultClass->outDescricaoTurno;
        $this->outTurma = $consultClass->outTurma;
        $this->outDescricaoTurma = $consultClass->outDescricaoTurma;
        $this->outNrCapacidadeFisicaMaxima = $consultClass->outNrCapacidadeFisicaMaxima;
        $this->outNrAlunosAtivos = $consultClass->outNrAlunosAtivos;
        $this->outDataInicioAula = $consultClass->outDataInicioAula;
        $this->outDataFimAula = $consultClass->outDataFimAula;
        $this->outHorarioInicioAula = $consultClass->outHorarioInicioAula;
        $this->outHorarioFimAula = $consultClass->outHorarioFimAula;
        $this->outCodDuracao = $consultClass->outCodDuracao;
        $this->outCodHabilitacao = $consultClass->outCodHabilitacao;
        $this->outACodtividadesComplementar = $consultClass->outACodtividadesComplementar;
        $this->outANometividadesComplementar = $consultClass->outANometividadesComplementar;
        $this->outCodTipoEnsino = $consultClass->outCodTipoEnsino;
        $this->outNomeTipoEnsino = $consultClass->outNomeTipoEnsino;
        $this->outNumeroSala = $consultClass->outNumeroSala;
        $this->outCodSerieAno = $consultClass->outCodSerieAno;
        $this->outDescricaoSerieAno = $consultClass->outDescricaoSerieAno;
        $this->outDiasSemana = $consultClass->outDiasSemana;
        $this->outFlagSegunda = $consultClass->outFlagSegunda;
        $this->outHoraIniAulaSegunda = $consultClass->outHoraIniAulaSegunda;
        $this->outHoraFimAulaSegunda = $consultClass->outHoraFimAulaSegunda;
        $this->outFlagTerca = $consultClass->outFlagTerca;
        $this->outHoraIniAulaTerca = $consultClass->outHoraIniAulaTerca;
        $this->outHoraFimAulaTerca = $consultClass->outHoraFimAulaTerca;
        $this->outFlagQuarta = $consultClass->outFlagQuarta;
        $this->outHoraIniAulaQuarta = $consultClass->outHoraIniAulaQuarta;
        $this->outHoraFimAulaQuarta = $consultClass->outHoraFimAulaQuarta;
        $this->outFlagQuinta = $consultClass->outFlagQuinta;
        $this->outHoraIniAulaQuinta = $consultClass->outHoraIniAulaQuinta;
        $this->outHoraFimAulaQuinta = $consultClass->outHoraFimAulaQuinta;
        $this->outFlagSexta = $consultClass->outFlagSexta;
        $this->outHoraIniAulaSexta = $consultClass->outHoraIniAulaSexta;
        $this->outHoraFimAulaSexta = $consultClass->outHoraFimAulaSexta;
        $this->outFlagSabado = $consultClass->outFlagSabado;
    }
}
