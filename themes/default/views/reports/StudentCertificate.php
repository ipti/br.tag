<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

if (!isset($school)) {
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}

list($day, $month, $year) = explode('/', $student['birthday']);

$months = array(
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
);
$monthName = $months[$month];



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



?>
<div class="pageA4H">
    
    <div style="text-align: center;">
        <div style="position: relative; display: inline-block;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/brasao.png" alt="Brasão" style="width: 80px; position: absolute; top: -60px; left: 50%; transform: translateX(-50%);" />
        </div>
        <h4>ESTADO DO <?php echo strtoupper($school->edcensoUfFk->name); ?></h4>
        <h5>PREFEITURA MUNICIPAL DE <?php echo $school->edcensoCityFk->name; ?></h5>
        <h5>SECRETARIA MUNICIPAL DE EDUCAÇÃO</h5>
        <h1>CERTIFICADO</h1>
    </div>

    <div class="container-certificate">
        <p>O(A) Diretor(a) da Escola <?php echo $school->name ?>,
        no uso de suas atribuições legais, confere o presente. Certificado do  <?php echo $student['ano']; ?>  do  <?php echo $student['tipo_ensino']; ?> a <b><?php echo $student['name']; ?></b>
       filho(a) de <?php echo $student['filiation_1']; ?>
        e de <?php echo $student['filiation_2']; ?>.</p>
        <p>Nascido(a) em <?php echo $day; ?> de <?php echo $monthName; ?> de <?php echo $year; ?>, no Município de <?php echo $student['city']; ?>
        Estado de <?php echo $student['uf_name']; ?>.</p>

    </div>

    <div class ="content-data">
        <div style="display: inline-block; width: 45%; text-align: center;">
            <p>_______________________________</p>
            <p>Secretário(a)</p>
        </div>
        <div style="display: inline-block; width: 45%; text-align: center;">
            <p>______________ (MA) ______________ de ______________ de _____________</p>
        </div>
    </div>

    <div class="signature-section">
        <p>_______________________________________________</p>
        <p>Aluno(a)</p>
    </div>
    <div class="content-data-signature">
    <div>
        <p>Reconhecida pela Resolução nº 005/2023-CME de 28/09/2023</p>
        <p>Reconhecida pela Resolução do CME Conselho Municipal de Educação</p>
    </div>

    <div style="text-align: center;">
        <p>_______________________________</p>
        <p>Diretor(a) da Unidade de Ensino</p>
    </div>
    </div>
    <div class="row-fluid hidden-print" style="margin-top: 20px;">
        <div class="span12">
            <div class="buttons" style="text-align: center;">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;">
                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i>
                </a>
            </div>
        </div>
    </div>

    <?php $this->renderPartial('footer'); ?>
</div>
<div class="container-school-record" style="page-break-before: always;">

<div id="report">
<table style="margin: 0 0 0 25px; font-size: 8px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <thead>
                    <tr>
                        <th colspan="<?= $diciplinesColumnsCount+4 ?>" style="text-align: center">FICHA DE NOTAS</th>
                    </tr>
                    <tr>
                        <td style="text-align: center; min-width: 90px !important;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
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
                            <?php if(TagUtils::isInstance("BUZIOS")): ?>
                                <div>TRIMESTRES</div>
                            <?php else: ?>
                                <div>UNIDADES</div>
                            <?php endif; ?>
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
                    $conceptUnities = false;
                    for($i=1;$i<=count($unities);$i++) {?>
                        <tr>
                            <td><?= strtoupper($unities[$i-1]->name) ?></td>
                            <?php
                            $gradeResultFaults = 0;
                            if ($unities[$i-1]->type == 'UC') {
                                $conceptUnities = true;

                            }
                            for($j=0; $j < $diciplinesColumnsCount; $j++) {
                                $gradeResultFaults += $result[$j]['grade_result']['grade_faults_'.$i];
                                ?>
                                <?php if ($unities[$i-1]->type == 'RF') { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['rec_final'] ?></td>
                                <?php } else if ($unities[$i-1]->type == 'UC') { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['grade_concept_'.$i] ?></td>
                                <?php } else if ($result[$j]['grade_result']['grade_'.$i] < $result[$j]['grade_result']['rec_bim_'.$i]) { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['rec_bim_'.$i] ?></td>
                                <?php } else { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['grade_'.$i] ?></td>
                                <?php } ?>
                            <?php }?>
                            <?php if ($unities[$i-1]->type != 'RF') { ?>
                                <td style="text-align: center;"><?= $school_days[$i-1]?></td>
                                <td style="text-align: center;"><?= $workload[$i-1]?></td>
                                <td style="text-align: center;"><?= $gradeResultFaults == 0 ? $faults[$i-1] : $gradeResultFaults ?></td>
                            <?php } else { ?>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                            <?php } ?>
                        </tr>
                    <?php }?>
                </tbody>

                <!-- <tr>
                    <td colspan="1">MÉDIA ANUAL</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['final_media']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <!-- <tr>
                    <td colspan="1">NOTA DA PROVA FINAL</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= end($result[$i]['grades'])->grade?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <tr>
                    <td colspan="1">MÉDIA FINAL</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;font-weight:bold;"><?= ($conceptUnities ? '' : $result[$i]['final_media']) ?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE AULAS DADAS</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['total_number_of_classes']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE FALTAS</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['total_faults']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">FREQUÊNCIAS %</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) {?>
                        <td style="text-align: center;"><?= is_nan($result[$i]['frequency_percentage']) || $result[$i]['frequency_percentage'] < 0 ? "" : ceil($result[$i]['frequency_percentage']) . "%" ?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

</div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>

<style>
    .pageA4H {
        border-radius: 10px;
        padding: 10px;
        border: 2px solid #000;
        font-family: 'Arial', sans-serif;
        width: 90%;
        height: 100%;
        position: relative;
        box-sizing: border-box;
        margin: 23px 60px 23px 60px;
    }

    h1, h4, h5 {
        margin-top: 5px;
    }
    h4 {
        font-size: 13.99px;
        font-weight: 700;
        color: #252A31;
    }
    h5 {
        font-size: 13.99px;
        font-weight: 400;
        color: #252A31;
    }
    h1 {
        font-weight: 900;
        font-size: 35.13px;
        color: #16205B;
        margin: 20px;
    }
    .content-data {
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        align-items: center;
    }
    p {
        margin: 5px 0;
        font-size: 14px;
        font-weight: 500;
    }
    .signature-section {
        margin-top: 25px;
        text-align: center;
    }

    .signature-section p {
        margin: 20px 0;
    }
    .container-certificate {
        display: flex;
        justify-content: center;
        flex-direction: column;
        text-align: justify;
        padding: 10px 60px;
    }
    .content-data-signature {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0 60px;
    }
    .content-data-signature p {
        text-align: left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        text-align: center;
        padding: 8px;
        font-size: 10px;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .container-school-record {
        padding: 20px 60px;
    }

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
            display: none !important;
        }
        .pageA4H {
            border: none;
            margin: 0;
            padding: 0;
            width: 100%;
        }
    }
</style>
