<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentGradesReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

function classroomDisciplineLabelResumeArray($id) {
    $disciplinas = array(
        1 => 'Química',
        2 => 'Física',
        3 => 'Matemática',
        4 => 'Biologia',
        5 => 'Ciências',
        6 => 'Português',
        7 => 'Inglês',
        8 => 'Espanhol',
        9 => 'Outro Idioma',
        10 => 'Artes',
        11 => 'Educação Física',
        12 => 'História',
        13 => 'Geografia',
        14 => 'Filosofia',
        16 => 'Informática',
        17 => 'Disc. Profissionalizante',
        20 => 'Educação Especial',
        21 => 'Sociedade&nbspe Cultura',
        23 => 'Libras',
        25 => 'Disciplinas pedagógicas',
        26 => 'Ensino religioso',
        27 => 'Língua indígena',
        28 => 'Estudos Sociais',
        29 => 'Sociologia',
        30 => 'Francês',
        99 => 'Outras Disciplinas',
        10001 => 'Redação',
        10002 => 'Linguagem oral e escrita',
        10003 => 'Natureza e sociedade',
        10004 => 'Movimento',
        10005 => 'Música',
        10006 => 'Artes visuais',
        10007 => 'Acompanhamento Pedagógico',
        10008 => 'Teatro',
        10009 => 'Canteiro Sustentável',
        10010 => 'Dança',
        10011 => 'Cordel',
        10012 => 'Física'
    );

    if (array_key_exists($id, $disciplinas)) {
        return $disciplinas[$id];
    } else {
        return EdcensoDiscipline::model()->findByPk($id)->name;
    }
}
$rows = count($baseDisciplines)+count($diversifiedDisciplines); // contador com a soma do total de disciplinas da matriz
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" onclick="imprimirPagina()" class='btn btn-icon hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<div class="pageA4H">
    <div>
        <div id="report">
            <?php $this->renderPartial('head'); ?>
            <br>
            <div style="margin: 0 0 0 50px; width: calc(100% - 51px); text-align:center">
                <div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span9"><b>ALUNO: </b><?= $enrollment->studentFk->name ?></div>
                    <div class="span9"><b>TURMA: </b><?= $enrollment->classroomFk->name ?></div>
                    <div class="span9"><b>ANO LETIVO: </b><?= $enrollment->classroomFk->school_year ?></div>
                </div>
            </div>
            <br>
            <table style="margin: 0 0 0 25px; font-size: 8px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <thead>
                    <tr>
                        <th colspan="<?= $rows+4 ?>" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;  -width: 90px !important;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                        <?php if (count($baseDisciplines) > 0) {?>
                        <td colspan="<?= count($baseDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:150px;">BASE
                            NACIONAL
                            COMUM
                        </td>
                        <?php } ?>
                        <?php if (count($diversifiedDisciplines) > 0) {?>
                        <td colspan="<?= count($diversifiedDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:100px;">PARTE
                            DIVERSIFICADA
                        </td>
                        <?php } ?>
                        <td rowspan="2" class="vertical-text">
                            <div>DIAS&nbsp;LETIVOS</div>
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>CARGA&nbsp;HORÁRIA</div>
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>Nº&nbsp;DE&nbsp;FALTAS</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="vertical-text">
                            <?php if (INSTANCE == "BUZIOS") { ?>
                                <div>TRIMESTRES</div>
                            <?php }else {?>
                                <div>UNIDADES</div>
                            <?php }?>
                        </td>
                        <?php foreach ($baseDisciplines as $name): ?>
                            <td class="vertical-text">
                                <div><?= classroomDisciplineLabelResumeArray($name) ?></div>
                            </td>
                        <?php endforeach; ?>
                        <?php foreach ($diversifiedDisciplines as $name): ?>
                            <td class="vertical-text">
                                <div><?= classroomDisciplineLabelResumeArray($name) ?></div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for($i=1;$i<=count($unities);$i++) {?>
                        <tr>
                            <td><?= strtoupper($unities[$i-1]->name) ?></td>
                            <?php
                            $school_days = 0;
                            $workload = 0;
                            $faults = 0;
                            for($j=0; $j < $rows; $j++) { 
                                $school_days += $result[$j]['school_days'];
                                $workload += $result[$j]['workload'];
                                $faults += $result[$j]['faults'];
                                ?>
                                <td style="text-align: center;"><?= $result[$j]['grades'][$i-1]->grade ?></td>
                            <?php }?>
                            <td style="text-align: center;"><?= $school_days?></td>
                            <td style="text-align: center;"><?= $workload?></td>
                            <td style="text-align: center;"><?= $faults?></td>
                        </tr>
                    <?php }?>
                </tbody>

                <!-- <tr>
                    <td colspan="1">MÉDIA ANUAL</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['final_media']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <!-- <tr>
                    <td colspan="1">NOTA DA PROVA FINAL</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= end($result[$i]['grades'])->grade?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <tr>
                    <td colspan="1">MÉDIA FINAL</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;font-weight:bold;"><?= $result[$i]['final_media']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE AULAS DADAS</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['total_number_of_classes']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE FALTAS</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['faults']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">FREQUÊNCIAS %</td>
                    <?php for ($i=0; $i < $rows; $i++) { 
                        $totalDiasAula = $result[$i]['school_days'];
                        $quantidadeFaltas = $result[$i]['faults'];
                        $frequencia = (($totalDiasAula - $quantidadeFaltas) / $totalDiasAula) * 100;
                        $verifyCalc = is_nan($frequencia);
                        ?>
                        <td style="text-align: center;"><?= !$verifyCalc ? strval(number_format($frequencia, 2))."%" : "" ?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            </br>
            <div style="text-align:right; margin-right:20px;">Resultado Final _____________________________</div>
            <div style="text-align:center">APTO PARA CURSAR O _____________ ANO DO ENSINO FUNDAMENTAL
                <p style="margin-top:20px">
                    <span>_________________________________________________________</span>
                    <span style="margin-inline: 16px;">_____________________________,____________________________</span>
                    <span>_________________________________________________________</span>
                </p>
                <p style="margin-top:10px">
                    <span>Assinatura do(a) Secretário(a)</span>
                    <span style="margin-inline: 180px;">Local e data</span>
                    <span>Assinatura do(a) Diretor(a)</span>
                </p>
            </div>
        </div>
    </div>
</div>
<style>
    .vertical-text {
        height: 100px;
        vertical-align: bottom !IMPORTANT;
        max-width: 20px;
        width: 35px;
        padding-bottom: 10px !important;
    }

    .vertical-text div {
        transform: translate(25px, 0px) rotate(270deg);
        width: 100px;
        line-height: 13px;
        margin: 0 10px 0 0;
        transform-origin: bottom left;
    }

    td {
        max-width: 35px;
    }

    @media print {
        #container-header {
            width: 425px !important;
        }

        table, td, tr, th {
            border-color: black !important;
        }

        table tbody tr td {
            padding: 10px !important;
        }

        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%, auto;
        } 

        .hidden-print {
            display: none;
        }
    }

    @page {
      size: landscape;
    }
</style>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>   