<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
/* @var $students StudentEnrollment[]*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/AtaSchoolPerformance/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Classrooms') => array('/classroom/index'),
    $classroom->name => array('/classroom/update&id=' . $classroom->id),
    Yii::t('default', 'Ata School Performance'),
);
$stage = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk)->name;



function valorPorExtenso( $valor = 0)
    {
        $old = $valor;
        $valor = intval($valor);
        $pre = "Ao";
        $texto = array("NaN", "Primeiro", "Segundo", "Terceiro", "Quarto", "Quinto", "Sexto","sétimo", "Oitavo", "Nono",
            "Décimo", "Décimo primeiro", "Décimo segundo", "Décimo terceiro", "Décimo quarto", "Décimo quinto", "Décimo sexto", "Décimo sétimo", "Décimo oitavo", "Décimo nono",
            "Vigésimo", "Vigésimo primeiro", "Vigésimo segundo", "Vigésimo terceiro", "Vigésimo quarto", "Vigésimo quinto", "Vigésimo sexto", "Vigésimo sétimo", "Vigésimo oitavo", "Vigésimo nono",
            "Trigésimo", "Trigésimo primeiro");
        $pos = "dia ($old)";
        return($pre." ".$texto[$valor]." ".$pos);
 
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
    
    .vertical-text {
	//transform: rotate(270deg);
	//transform-origin: left top 0;
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
        <p><?php echo valorPorExtenso($report["day"])?> do mês de <?php echo $report["month"]?> do ano de <?php echo $report["year"]?>. 
        Realizou-se o processo de apuração do rendimento escolar dos alunos da série de <?php echo $report["serie"]?>, turma <?php echo $report["name"]?>,
        turno <?php echo $report["turn"]?> do <?php echo $report["ensino"]?> deste estabelecimento, com a Carga Horária anual de _________ horas 
        e um total de _________ dias letivos, conforme os resultados abaixo.</p>
        
        <?php 
            $disciplines = explode('|',$report['disciplines']); 
            $qtde = count($disciplines);
        ?>
        <table class="table table-bordered table-striped">
            <tr><th rowspan="4" class='vertical-text'>Ordem</th><th rowspan="4">Nome do Aluno</th><th colspan="25">Componentes Curriculares</th></tr>
            <tr><th colspan="25">Rendimento Escolar</th></tr>
            <tr><th colspan="15">Disciplinas</th><th colspan="5">Resultado Final</th><th colspan="5">Dependência</th></tr>
            <tr><?php 
                foreach ($disciplines as $name) {
                    echo "<th class='vertical-text'>$name</th>";
                }
                for($i=$qtde; $i<=14; $i++){
                    echo("<th></th>");
                }
                    
            ?>
                <th></th><th class='vertical-text'>Frequência %</th><th class='vertical-text'>Aprovado</th><th class='vertical-text'>Prom. com Dependência</th><th class='vertical-text'>Reprovado</th>
                <th></th><th class='vertical-text'>Disciplina</th><th class='vertical-text'>Nota</th><th class='vertical-text'>Disciplina</th><th class='vertical-text'>Nota</th>
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
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>