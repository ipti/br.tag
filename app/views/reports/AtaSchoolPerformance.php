<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
/* @var $students StudentEnrollment[]*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/AtaSchoolPerformance/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$stage = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk)->name;

function valorPorExtenso( $valor = 0){
    $old = $valor;
    $valor = intval($valor);
    $pre = "Ao";
    $texto = array("NaN", "primeiro", "segundo", "terceiro", "quarto", "quinto", "sexto","sétimo", "oitavo", "nono",
        "décimo", "décimo primeiro", "décimo segundo", "décimo terceiro", "décimo quarto", "décimo quinto", "décimo sexto", "décimo sétimo", "décimo oitavo", "décimo nono",
        "vigésimo", "vigésimo primeiro", "vigésimo segundo", "vigésimo terceiro", "vigésimo quarto", "vigésimo quinto", "vigésimo sexto", "vigésimo sétimo", "vigésimo oitavo", "vigésimo nono",
        "trigésimo", "trigésimo primeiro");
    $pos = "dia ($old)";
    return($pre." <b>".$texto[$valor]." ".$pos."</b>");

}
?>

<style>
    hr.hrline{
        margin: 1px;
        border-top-color: black;
    }
    div.hrline{
        margin-right:20px; 
        float:left; 
        display:block; 
        width:30%;
    }
    div.hrline, div.hrlastline{
        text-align: center; 
    }
    div.divlines{
        width:100%; margin:0 auto;
    }
    div.hrline span{
        font-size: 10px;
    }    
    th{
        vertical-align: middle !IMPORTANT;
        text-align: center !IMPORTANT;
    }
    th.vertical-text {
        height: 100px;
        vertical-align: bottom !IMPORTANT;
      }

    th.vertical-text > div {
        transform: translate(5px, 0px) rotate(270deg);
        width: 10px;
        line-height: 10px;
    }
</style>


<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Ata School Performance of ').$this->year; ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>
        <div id="report-logo" class="visible-print">
            <img src="../../../images/sntaluzia.png">
        </div>
        
        <div class="divlines">
            <div class="hrline" style="width:60%;">
                <?php echo $report['school']?>
                <hr class="hrline">
                <span>DENOMINAÇÃO DO ESTABELECIMENTO</span>
            </div>
            <div class="hrlastline">
                <?php echo $report['city']?>
                <hr class="hrline">
                <span>CIDADE-UF</span>
            </div>
        </div>
        <br><br>
        <div class="divlines">
            <div class="hrline">
                <hr class="hrline">
                <span>ATO DE CRIAÇÃO</span>
            </div>
            <div class="hrline">
                <hr class="hrline">
                <span>ATO DE AUTORIZAÇÃO DE FUNCIONAMENTO</span>
            </div>
            <div class="hrlastline">
                <hr class="hrline">
                <span>ATO DE RECONHECIMENTO</span>
            </div>
        </div>
        <br>
        <br>
        <p style="text-align: justify;"><?php echo valorPorExtenso($report["day"])?> do mês de <b><?php echo $report["month"]?></b> do ano de <b><?php echo $report["year"]?></b>. 
            Realizou-se o processo de apuração do rendimento escolar dos alunos da série de <b><?php echo $report["serie"]?></b>, turma <b><?php echo $report["name"]?></b>,
            turno <b><?php echo $report["turn"]?></b> do <b><?php echo $report["ensino"]?></b> deste estabelecimento, com a Carga Horária anual de _________ horas 
        e um total de _________ dias letivos, conforme os resultados abaixo.</p>
        
        <?php 
            $disciplines = explode('|',$report['disciplines']); 
            $qtde = count($disciplines);
        ?>
        <br>
        <table class="table table-bordered table-striped">
            <tr><th rowspan="4" class='vertical-text'><div>Ordem</div></th><th rowspan="4">Nome do Aluno</th><th colspan="25">Componentes Curriculares</th></tr>
            <tr><th colspan="25">Rendimento Escolar</th></tr>
            <tr><th colspan="15">Disciplinas</th><th colspan="5">Resultado Final</th><th colspan="5">Dependência</th></tr>
            <tr><?php 
                foreach ($disciplines as $name) {
                    echo "<th class='vertical-text'><div>$name</div></th>";
                }
                for($i=$qtde; $i<=14; $i++){
                    echo("<th class='vertical-text'><div></div></th>");
                }
                    
            ?>
                <th class='vertical-text'><div></div></th><th class='vertical-text'><div>Frequência&nbsp;</div></th><th class='vertical-text'><div>Aprovado</div></th><th class='vertical-text'><div>Prom.&nbsp;com Dependência</div></th><th class='vertical-text'><div>Reprovado</div></th>
                <th class='vertical-text'><div></div></th><th class='vertical-text'><div>Disciplina</div></th><th class='vertical-text'><div>Nota</div></th><th class='vertical-text'><div>Disciplina</div></th><th class='vertical-text'><div>Nota</div></th>
            </tr>
            <?php
            $html = "";
            $td = "";
            for($i=1; $i<=25;$i++){$td .= "<td></td>";}
            $i = 0;
            foreach ($students as $s) {
                $i++;
                $sName = $s->studentFk->name;
                $html .= "<tr>"
                        . "<td>$i</td>"
                        . "<td>$sName</td>"
                        .$td;
            }
            echo $html;
            ?>
        </table>        
        <br>
        <div class="divlines">
            <div class="hrline" style="width:100%; text-align: left;">
                Obs.:
                <hr class="hrline">
                <br>
                <hr class="hrline">
                <br>
            </div>
        </div>
        <p style="text-align: justify;">
            E, para contar, eu ___________________________________________________________________ lavrei a presente ata que vai por mim 
            assinada e pelo(a) do estabelecimento.
        </p>
        <p style="text-align: right;">
            __________________,____ de ______________________ de ________
        </p>
        <br>
        <div class="divlines">
            <div class="hrline" style="width:45%;">
                <hr class="hrline">
                <span>SECRETÁRIO(A) Nº ATO AUTORIZAÇÃO</span>
            </div>
            <div class="hrlastline">
                <hr class="hrline">
                <span>DIRETOR(A) Nº ATO AUTORIZAÇÃO</span>
            </div>
        </div>
    </div>
</div>