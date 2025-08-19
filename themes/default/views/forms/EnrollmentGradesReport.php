<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentGradesReport/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

function classroomDisciplineLabelResumeArray($id)
{
    $discipline = EdcensoDiscipline::model()->findByPk($id);
    return  substr(  empty($discipline->abbreviation) ? $discipline->name : $discipline->abbreviation, 0, 50);
}

$diciplinesColumnsCount = count($baseDisciplines) + count($diversifiedDisciplines); // contador com a soma do total de disciplinas da matriz

function calculateFrequence($numClasses, $numFalts): int {
    if(empty($numClasses) || is_nan($numClasses)){
        return 100;
    }

    $aulasdadas = (int) $numClasses;
    $faltas = (int) $numFalts;

    return round((($aulasdadas - $faltas) * 100) / $aulasdadas);
}

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" onclick="imprimirPagina()" class='btn btn-icon hidden-print' style="padding: 10px;"><img
                        alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg"
                        class="img_cards"/> <?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<div class="pageA4H">
    <div>
        <div id="report">
            <?php $this->renderPartial('head'); ?>
            <br>
            <div style="margin: 0 0 0 50px; width: calc(100% - 51px); text-align:center">
                <div style="float: left; text-align: justify;margin: 5px 0 20px -20px;line-height: 14px;">
                    <div class="span6"><b>ALUNO: </b><?= $enrollment->studentFk->name ?></div>
                    <div class="span6"><b>DATA DE NASCIMENTO: </b><?= $enrollment->studentFk->birthday ?></div>
                    <div class="span6">
                        <b>GÊNERO: </b><?= strtoupper($enrollment->studentFk->sex == 1 ? "Masculino" : "Feminino") ?>
                    </div>
                    <div class="span6">
                        <b>NATURALIDADE: </b><?= strtoupper($enrollment->studentFk->edcensoCityFk->name) . "/" . $enrollment->studentFk->edcensoUfFk->acronym ?>
                    </div>
                    <div class="span6">
                        <b>IDENTIDADE: </b><?= $enrollment->studentFk->documentsFk->rg_number ?>
                    </div>


                    <div class="span6"><b>FILIAÇÃO PRINCIPAL: </b><?= $enrollment->studentFk->filiation_1 ?></div>
                    <div class="span6">
                        <b>ÓRGÃO EXPEDIDOR: </b><?= $enrollment->studentFk->documentsFk->rgNumberEdcensoOrganIdEmitterFk->name ?>
                    </div>
                    <div class="span6"><b>FILIAÇÃO SECUNDÁRIA: </b><?= $enrollment->studentFk->filiation_2 ?></div>
                    <div class="span6"><b>TURMA: </b><?= $enrollment->classroomFk->name ?></div>
                    <div class="span6"><b>ANO LETIVO: </b><?= $enrollment->classroomFk->school_year ?></div>
                    <div class="span6"><b>ETAPA: </b><?= strtoupper($enrollment->edcensoStageVsModalityFk->name) ?></div>

                    <div class="span6">
                        <?php
                            if($enrollment->studentFk->deficiency) {
                                echo "<b>ATENDIMENTO EDUCACIONAL ESPECIALIZADO:/<b> &nbsp(&nbspX&nbsp)&nbspSIM &nbsp(&nbsp&nbsp)&nbspNÃO";
                            }else {
                                echo "<b>ATENDIMENTO EDUCACIONAL ESPECIALIZADO:</b> &nbsp(&nbsp&nbsp)&nbspSIM &nbsp(&nbspX&nbsp)&nbspNÃO";
                            }
                        ?>
                    </div>

                </div>
            </div>
            <br>
            <table style="margin: 20px 0 0 25px; font-size: 10px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <thead>
                <tr>
                    <th colspan="<?= $diciplinesColumnsCount + 4 ?>" style="text-align: center">FICHA DE NOTAS</th>
                </tr>
                <tr>
                    <td style="text-align: center; min-width: 90px !important;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                    <?php if (count($baseDisciplines) > 0) { ?>
                        <td colspan="<?= count($baseDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:150px;"><?= $isMinorEducation ?  "Eixos/Componentes" : "BASE NACIONAL COMUM" ?></td>
                    <?php } ?>
                    <?php if (count($diversifiedDisciplines) > 0) { ?>
                        <td colspan="<?= count($diversifiedDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:100px;">
                            PARTE DIVERSIFICADA
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
                        <?php if (TagUtils::isInstance("BUZIOS")): ?>
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
                for ($i = 1; $i <= count($unities); $i++) { ?>
                    <tr>
                        <td><?= strtoupper($unities[$i - 1]->name) ?></td>
                        <?php
                        $gradeResultFaults = 0;
                        if ($unities[$i - 1]->type == 'UC') {
                            $conceptUnities = true;

                        }
                        for ($j = 0; $j < $diciplinesColumnsCount; $j++) {
                            $gradeResultFaults += $result[$j]['grade_result']['grade_faults_' . $i];
                            $gradeResult = $result[$j]['grade_result'];
                            ?>
                            <?php if ($unities[$i - 1]->type == 'RF') { ?>
                                <td style="text-align: center;"><?= $gradeResult['rec_final'] ?></td>
                            <?php } elseif ($unities[$i - 1]->type == 'UC') { ?>
                                <td style="text-align: center;"><?= $gradeResult['grade_concept_' . $i] ?></td>
                                <?php
                                  } elseif ($gradeResult['grade_' . $i] < $gradeResult['rec_partial_' . $i] &&
                                    isset($result[$j]["partial_recoveries"]["rec_partial_" . $i]) &&
                                    is_array($result[$j]["partial_recoveries"]["rec_partial_" . $i]) &&
                                    in_array('grade_' . $i, $result[$j]["partial_recoveries"]["rec_partial_" . $i])) {
                                ?>
                                <td style="text-align: center;"><?= $gradeResult['rec_partial_' . $i] ?></td>
                            <?php } else { ?>
                                <td style="text-align: center;"><?= $gradeResult['grade_' . $i] ?></td>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($unities[$i - 1]->type != 'RF') { ?>
                            <td style="text-align: center;"><?= $school_days[$i - 1] ?></td>
                            <td style="text-align: center;"><?= $workload[$i - 1] ?></td>
                            <td style="text-align: center;"><?= $gradeResultFaults == 0 ? $faults[$i - 1] : $gradeResultFaults ?></td>
                        <?php } else { ?>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
                <tr>
                    <td colspan="1">MÉDIA FINAL</td>
                    <?php for ($i = 0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;font-weight:bold;"><?= ($conceptUnities ? '' : $result[$i]['final_media']) ?></td>
                    <?php } ?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE AULAS DADAS</td>
                    <?php if ($isMinorEducation): ?>
                        <td style="text-align:right;" colspan="<?= $diciplinesColumnsCount ?>"><?= $result[0]['total_number_of_classes']?></td>
                    <?php else: ?>
                        <?php for ($i = 0; $i < $diciplinesColumnsCount; $i++) : ?>
                            <td style="text-align: center;"><?= $result[$i]['total_number_of_classes'] ?></td>
                        <?php endfor; ?>
                    <?php endif;?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE FALTAS</td>
                    <?php if ($isMinorEducation): ?>
                        <td style="text-align:right;" colspan="<?= $diciplinesColumnsCount ?>"><?= $result[0]['total_faults']?></td>
                    <?php else: ?>
                        <?php for ($i = 0; $i < $diciplinesColumnsCount; $i++) : ?>
                            <td style="text-align: center;"><?= $result[$i]['total_faults'] ?></td>
                        <?php endfor; ?>
                    <?php endif;?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">FREQUÊNCIAS %</td>
                    <?php if ($isMinorEducation): ?>
                        <td style="text-align: right;" colspan="<?= $diciplinesColumnsCount ?>"><?= $result[0]['frequency_percentage'] . "%" ?></td>
                    <?php else: ?>
                        <?php for ($i = 0; $i < $diciplinesColumnsCount; $i++) : ?>
                            <td style="text-align: center;"><?= is_nan($result[$i]['frequency_percentage'] ?? NAN) || $result[$i]['frequency_percentage'] < 0 ? "" : ceil($result[$i]['frequency_percentage']) . "%" ?></td>
                        <?php endfor; ?>
                    <?php endif;?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            </br>
            <div></div>
            <p>* As aulas dadas na educação infantil são calculadas por dias registrados e não pelos horários</p>
            <div style="text-align:right; margin-right:25px;">RESULTADO FINAL: _____________________________</div>
            <div class="pull-right hidden-print" style="margin: 30px 25px 50px 0">
                <input type="checkbox" class="bring-date" checked> Local e Data
            </div>
            <div class="local-and-date pull-right" style="margin: 30px 25px 50px 0">
                <span class="written-date" style=""><?=$enrollment->classroomFk->schoolInepFk->edcensoCityFk->name?>/<?=$enrollment->classroomFk->schoolInepFk->edcensoUfFk->acronym?>, <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?></span>
                <span class="line-date" style="display:none">__________________________,______________________________________________<p style="text-align: center;">Local e Data</p></span>
            </div>
            <div>
                <table style="width: 100%;text-align: center">
                    <thead><tr><th></th><th></th></tr></thead>
                    <tbody>
                    <tr>
                        <td><span>_________________________________________________________</span><br><span>Assinatura do(a) Secretário(a)</span></td>
                        <td><span>_________________________________________________________</span><br><span>Assinatura do(a) Diretor(a)</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .span6{
        margin: 5px 0;
    }
    .vertical-text {
        height: 100px;
        vertical-align: bottom !IMPORTANT;
        max-width: 20px;
        width: 35px;
        padding-bottom: 10px !important;
    }

    .vertical-text div {
        height: 100px;
        line-height: 13px;
        margin: 0 10px 0 0;
        writing-mode: sideways-lr;
        text-orientation: mixed;
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
    $(".bring-date").change(function() {
        if ($(this).is(":checked")) {
            $(".written-date").show();
            $(".line-date").hide();
        } else {
            $(".written-date").hide();
            $(".line-date").show();
        }
    })
</script>
