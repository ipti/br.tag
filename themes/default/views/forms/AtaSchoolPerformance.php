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

$actual_day = valorPorExtenso(date('d'));
$actual_mounth = date('M');
$actual_year = date('Y');

$turno =  $classroom->turn;
if ($turno == 'M') {
    $turno = "Matutino";
}else if ($turno == 'T') {
    $turno = "Tarde";
}else if ($turno == 'N') {
    $turno = "Noite";
}else if ($turno == '' || $turno == null) {
    $turno = "______________________";
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
        line-height: 12px;
        font-size: 11px;
        margin: 0 auto;
        position: relative;
        left: -6px;
    }
    .text-center{
        text-align: center;
    }
    
    @media print{
        @page {
            size: landscape;
        }
        table { page-break-inside:auto;}
        tr    { page-break-inside:avoid; page-break-after:auto;}
        td    { page-break-inside:avoid; page-break-after:auto;}
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
</style>

<?php ?>


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
        <p style="text-align: justify;"><?php echo $actual_day?> do mês de <b><?php echo Yii::t('default', $actual_mounth) ?></b> do ano de <b><?php echo $actual_year?></b>. 
            Realizou-se o processo de apuração do rendimento escolar dos alunos da série de <b><?php echo $report["serie"]?></b>, turma <b><?php echo $classroom->name?></b>,
            turno <b><?php echo $turno?></b> do <b><?php echo $classroom->edcensoStageVsModalityFk->name?></b> deste estabelecimento, com a Carga Horária anual de _________ horas 
        e um total de _________ dias letivos, conforme os resultados abaixo.</p>
        
        <?php 
            //$disciplines = explode('|',$report['disciplines']); 
            $qtde = count($disciplines);
        ?>
        <br>
        <table class="table table-bordered table-striped">
            <thead>
                <tr><th rowspan="4" class='vertical-text'><div>Ordem</div></th><th rowspan="4" class='vertical-text'><div>ID INEP</div></th><th rowspan="4">Nome do Aluno</th><th colspan="25">Componentes Curriculares</th></tr>
                <tr><th colspan="25">Rendimento Escolar</th></tr>
                <tr><th colspan="<?php echo $qtde?>">Disciplinas</th><th colspan="3">Resultado Final</th><th colspan="2">Dependência</th></tr>
                <tr>
                    <?php foreach($disciplines as $discipline) {
                        
                        // Tratamentos da string de disciplina
                        if($discipline['discipline_id'] == 10) {
                            $discipline = "Artes";
                        }else if($discipline['discipline_id'] == 6) {
                            $discipline = "Português";
                        }else {
                            $discipline = $discipline['discipline_name'];
                        }
                        $comprimento_maximo = 30;
                        if(strpos($discipline, "Língua /Literatura estrangeira - ") !== false) {
                            $discipline = str_replace("Língua /Literatura estrangeira - ", "", $discipline);
                        }
                        if (strlen($discipline) > $comprimento_maximo) {
                          $discipline = substr($discipline, 0, $comprimento_maximo) . "...";
                        }

                        echo "<th class='vertical-text'><div>".$discipline."</div></th>";
                    }
                    ?>
                    <th class='vertical-text'><div>Aprovado</div></th><th class='vertical-text'><div>Prom.&nbsp;com Dependência</div></th><th class='vertical-text'><div>Reprovado</div></th>
                    <th class='vertical-text'><div>Disciplina</div></th><th class='vertical-text'><div>Nota</div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach($students as $s) {
                    if ($count < 10) {
                        $label_id = "0".$count;
                    }else {
                        $label_id = $count;
                    }
                    echo "<tr>"
                    ."<td>".$label_id."</td>"
                    ."<td>".$s->studentFk->inep_id."</td>"
                    ."<td>".$s->studentFk->name."</td>";
                    foreach ($grades as $grade) {
                        if($grade['student_id'] == $s->studentFk->id) {
                            echo "<td>".$grade['final_media']."</td>";
                        }
                    }
                    echo "<td></td>".
                    "<td></td>".
                    "<td></td>".
                    "<td></td>".
                    "<td></td>";
                    echo "</tr>";
                    $count++;
                }
                ?>
            </tbody>
        </table>        
        <br>
        <div class="divlines">
            <div class="hrline" style="width:100%;text-align:left;">
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
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>