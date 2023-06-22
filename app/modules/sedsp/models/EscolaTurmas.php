<?php


class EscolaTurmas
{
    public $outAnoLetivo;
    public $outCodEscola;
    public $outDescNomeAbrevEscola;
    public $classrooms;

    public function __construct($data)
    {
        $json = json_decode($data, true, 512,  JSON_OBJECT_AS_ARRAY);
        $this->outAnoLetivo = $json["outAnoLetivo"];
        $this->outCodEscola = $json["outCodEscola"];
        $this->outDescNomeAbrevEscola = $json["outDescNomeAbrevEscola"];
        
        $classrooms = array();
        foreach ($json["outClasses"] as $classJson) {
            
            $classroom = new OutClassroom($classJson);
            array_push($classrooms, $classroom);
        }
        $this->classrooms = $classrooms;
    }
}

class OutClassroom
{
    public $outNumClasse;
    public $outCodUnidade;
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

    public function __construct($json)
    {
        $this->outNumClasse = $json["outNumClasse"];
        $this->outCodUnidade = $json["outCodUnidade"];
        $this->outCodTipoEnsino = $json["outCodTipoEnsino"];
        $this->outDescTipoEnsino = $json["outDescTipoEnsino"];
        $this->outCodSerieAno = $json["outCodSerieAno"];
        $this->outTurma = $json["outTurma"];
        $this->outCodTurno = $json["outCodTurno"];
        $this->outDescricaoTurno = $json["outDescricaoTurno"];
        $this->outCodHabilitacao = $json["outCodHabilitacao"];
        $this->outNumSala = $json["outNumSala"];
        $this->outHorarioInicio = $json["outHorarioInicio"];
        $this->outHorarioFim = $json["outHorarioFim"];
        $this->outCodTipoClasse = $json["outCodTipoClasse"];
        $this->outDescTipoClasse = $json["outDescTipoClasse"];
        $this->outSemestre = $json["outSemestre"];
        $this->outQtdAtual = $json["outQtdAtual"];
        $this->outQtdDigitados = $json["outQtdDigitados"];
        $this->outQtdEvadidos = $json["outQtdEvadidos"];
        $this->outQtdNCom = $json["outQtdNCom"];
        $this->outQtdOutros= $json["outQtdOutros"];
        $this->outQtdTransferidos= $json["outQtdTransferidos"];
        $this->outQtdRemanejados= $json["outQtdRemanejados"];
        $this->outQtdCessados= $json["outQtdCessados"];
        $this->outQtdReclassificados= $json["outQtdReclassificados"];
        $this->outCapacidadeFisicaMax= $json["outCapacidadeFisicaMax"];

    }
}
