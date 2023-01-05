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
        <p style="text-align: justify;"><?php echo valorPorExtenso($report["day"])?> do mês de <b><?php echo Yii::t('default', $report["month"]) ?></b> do ano de <b><?php echo $report["year"]?></b>. 
            Realizou-se o processo de apuração do rendimento escolar dos alunos da série de <b><?php echo $report["serie"]?></b>, turma <b><?php echo $report["name"]?></b>,
            turno <b><?php echo $report["turn"]?></b> do <b><?php echo $report["ensino"]?></b> deste estabelecimento, com a Carga Horária anual de _________ horas 
        e um total de _________ dias letivos, conforme os resultados abaixo.</p>
        
        <?php 
            //$disciplines = explode('|',$report['disciplines']); 
            $qtde = count($disciplines);
        ?>
        <br>
        <table class="table table-bordered table-striped">
            <tr><th rowspan="4" class='vertical-text'><div>Ordem</div></th><th rowspan="4" class='vertical-text'><div>ID INEP</div></th><th rowspan="4">Nome do Aluno</th><th colspan="25">Componentes Curriculares</th></tr>
            <tr><th colspan="25">Rendimento Escolar</th></tr>
            <tr><th colspan="15">Disciplinas</th><th colspan="5">Resultado Final</th><th colspan="5">Dependência</th></tr>
            <tr><?php 
                $idDisciplines = array_column($disciplines, 'discipline_id', 'discipline_id');
                $resumeDisciplines = ClassroomController::classroomDisciplineLabelResumeArray();
                $nameDisciplines = array_intersect_key ($resumeDisciplines, $idDisciplines);

                foreach ($nameDisciplines as $name) {
                    echo "<th class='vertical-text'><div>$name</div></th>";
                }


                $templateRow = "
                    <td class=\"center\">{{index}}</td>
                    <td class=\"center\">{{inep_id}}</td>
                    <td>{{student}}</td>
                    <td class=\"center\">{{discipline_1}}</td>
                    <td class=\"center\">{{discipline_2}}</td>
                    <td class=\"center\">{{discipline_3}}</td>
                    <td class=\"center\">{{discipline_4}}</td>
                    <td class=\"center\">{{discipline_5}}</td>
                    <td class=\"center\">{{discipline_6}}</td>
                    <td class=\"center\">{{discipline_7}}</td>
                    <td class=\"center\">{{discipline_8}}</td>
                    <td class=\"center\">{{discipline_9}}</td>
                    <td class=\"center\">{{discipline_10}}</td>
                    <td class=\"center\">{{discipline_11}}</td>
                    <td class=\"center\">{{discipline_12}}</td>
                    <td class=\"center\">{{discipline_13}}</td>
                    <td class=\"center\">{{discipline_14}}</td>
                    <td class=\"center\">{{discipline_15}}</td>
                    <td class=\"center\"></td>
                    <td class=\"center\">{{frequency}}</td>
                    <td class=\"center\">{{approvedAllDisciplines}}</td>
                    <td class=\"center\">{{dependency}}</td>
                    <td class=\"center\">{{disapprovedAllDisciplines}}</td>
                    <td class=\"center\"></td>
                    <td class=\"center\">{{dependency_discipline}}</td>
                    <td class=\"center\">{{dependency_note}}</td>
                    <td class=\"center\">{{dependency_discipline_2}}</td>
                    <td class=\"center\">{{dependency_note_2}}</td>
                ";
                
                for($i=$qtde; $i<=14; $i++){
                    echo("<th class='vertical-text'><div></div></th>");
                }
                    
            ?>
                <th class='vertical-text'><div></div></th><th class='vertical-text'><div>Frequência&nbsp;</div></th><th class='vertical-text'><div>Aprovado</div></th><th class='vertical-text'><div>Prom.&nbsp;com Dependência</div></th><th class='vertical-text'><div>Reprovado</div></th>
                <th class='vertical-text'><div></div></th><th class='vertical-text'><div>Disciplina</div></th><th class='vertical-text'><div>Nota</div></th><th class='vertical-text'><div>Disciplina</div></th><th class='vertical-text'><div>Nota</div></th>
            </tr>
            <?php

            $html = "";
            $i = 0;

            foreach ($students as $s) {
                $i++;
                $finalFrequency = 0;
                $finalAverage = 0;
                $approvedAllDisciplines = true;
                $sName = $s->studentFk->name;
                $sIDInep = $s->studentFk->inep_id;

                $resultGrade = $s->getResultGrade($idDisciplines);
                $average = array();
                $frequency = array();
                $result = array();
                foreach ($resultGrade as $value) {
                    $average[] = floatval($value['final_average']);
                    $frequency[] = floatval($value['frequency']);
                    if($value['final_average'] < 5 || $value['frequency'] < 75){
                        $approvedAllDisciplines = false;
                    }
                }

                if(count($frequency) > 0){
                    $finalFrequency = array_sum($frequency) / count($frequency);
                    $finalAverage = array_sum($average) / count($average);
                }

                for ($j = count($resultGrade); $j < 15; $j++) {
                    $average[] = '';
                    
                }

                $average = array_map(function($v){ return number_format(floatval($v), 2, ',',''); }, $average);

                $result[] = $i;
                $result[] = $sIDInep;
                $result[] = $sName;
                $result[] = $average[0];
                $result[] = $average[1];
                $result[] = $average[2];
                $result[] = $average[3];
                $result[] = $average[4];
                $result[] = $average[5];
                $result[] = $average[6];
                $result[] = $average[7];
                $result[] = $average[8];
                $result[] = $average[9];
                $result[] = $average[10];
                $result[] = $average[11];
                $result[] = $average[12];
                $result[] = $average[13];
                $result[] = $average[14];
                $result[] = number_format($finalFrequency, 2, ',', '');
                $result[] = ($approvedAllDisciplines) ? 'S' : 'N' ;
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = '';

                $td = str_replace(
                    [
                        '{{index}}',
                        '{{inep_id}}',
                        '{{student}}',
                        '{{discipline_1}}',
                        '{{discipline_2}}',
                        '{{discipline_3}}',
                        '{{discipline_4}}',
                        '{{discipline_5}}',
                        '{{discipline_6}}',
                        '{{discipline_7}}',
                        '{{discipline_8}}',
                        '{{discipline_9}}',
                        '{{discipline_10}}',
                        '{{discipline_11}}',
                        '{{discipline_12}}',
                        '{{discipline_13}}',
                        '{{discipline_14}}',
                        '{{discipline_15}}',
                        '{{frequency}}',
                        '{{approvedAllDisciplines}}',
                        '{{dependency}}',
                        '{{disapprovedAllDisciplines}}',
                        '{{dependency_discipline}}',
                        '{{dependency_note}}',
                        '{{dependency_discipline_2}}',
                        '{{dependency_note_2}}',
                    ],
                    $result,
                    $templateRow
                );

                $html .= "<tr>"
                        .$td
                        ."</tr>";
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
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>