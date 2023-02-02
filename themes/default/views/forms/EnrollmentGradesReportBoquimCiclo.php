<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentGradesReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

$disciplines = [];
$disciplines['base'] = [];
$disciplines['diversified'] = [];
$exams = [];
$finals = [];
$finals['base'] = [];
$finals['diversified'] = [];
$averages = [];
$averages['base'] = [];
$averages['diversified'] = [];
$finalAverage = [];
$finalAverage['base'] = [];
$finalAverage['diversified'] = [];
$schoolDays = [];
$workingHours = [];
$faultsCount = [];
$faultsCount['discipline'] = [];
$faultsCount['discipline']['base'] = [];
$faultsCount['discipline']['diversified'] = [];
$faultsCount['exam'] = [];
$faultsCount['total'] = 0; ;
$givenClassesByDiscipline = [];
$givenClassesByDiscipline['base'] = [];
$givenClassesByDiscipline['diversified'] = [];
$faultsByDiscipline = [];
$faultsByDiscipline['base'] = [];
$faultsByDiscipline['diversified'] = [];
$frequencyByDiscipline = [];
$frequencyByDiscipline['base'] = [];
$frequencyByDiscipline['diversified'] = [];
$schoolDaysTotal = 0;
$workingHoursTotal = 0;
$frequencyTotal = 0;

for ($i = 1; $i <= 4; $i++) {
    $exams[$i] = [];
    $exams[$i]['base'] = [];
    $exams[$i]['diversified'] = [];
    $schoolDays[$i] = $enrollment->classroomFk->getSchoolDaysByExam($i);
    $schoolDaysTotal += $schoolDays[$i];
    $workingHours[$i] = $enrollment->classroomFk->getWorkingHoursByExam($i);
    $workingHoursTotal += $workingHours[$i];
}

foreach ($enrollment->grades as $grades) {
    /* @var $grades Grade */
    /* @var $discipline EdcensoDiscipline */
    $discipline = $grades->disciplineFk;
    $disciplineId = $discipline->id;
    $type = '';
    if ($disciplineId < 99) {
        $type = 'base';
    } else {
        $type = 'diversified';
    }

    $disciplines[$type][$disciplineId] = $discipline->name;
    $faults = $enrollment->classFaults;

    $exams[1][$type][$disciplineId] = $grades->grade1 != null ? $grades->grade1 : '';
    $exams[2][$type][$disciplineId] = $grades->grade2 != null ? $grades->grade2 : '';
    $exams[3][$type][$disciplineId] = $grades->grade3 != null ? $grades->grade3 : '';
    $exams[4][$type][$disciplineId] = $grades->grade4 != null ? $grades->grade4 : '';
    $finals[$type][$disciplineId] = $grades->recovery_final_grade != null ? $grades->recovery_final_grade : '';
    $averages[$type][$disciplineId] = $grades->getAverage();
    $finalAverage[$type][$disciplineId] = $grades->getFinalAverage();

    $givenClassesByDiscipline[$type][$disciplineId] = count($enrollment->classroomFk->getGivenClassesByDiscipline($disciplineId));
    $faultsCount['discipline'][$type][$disciplineId] = count($enrollment->getFaultsByDiscipline($disciplineId));
    $frequencyByDiscipline[$type][$disciplineId] = $givenClassesByDiscipline[$type][$disciplineId] == 0 ? 1 : (($givenClassesByDiscipline[$type][$disciplineId] - $faultsCount['discipline'][$type][$disciplineId]) / $givenClassesByDiscipline[$type][$disciplineId]);
    $frequencyTotal += $frequencyByDiscipline[$type][$disciplineId];
}

$faultsCount['exam'][1] = count($enrollment->getFaultsByExam(1));
$faultsCount['exam'][2] = count($enrollment->getFaultsByExam(2));
$faultsCount['exam'][3] = count($enrollment->getFaultsByExam(3));
$faultsCount['exam'][4] = count($enrollment->getFaultsByExam(4));
$faultsCount['total'] = $faultsCount['exam'][1] + $faultsCount['exam'][2] + $faultsCount['exam'][3] + $faultsCount['exam'][4];

$disciplinesCount = (count($disciplines['base']) + count($disciplines['diversified']));
$disciplineBaseCount = count($disciplines['base']) + 1;
$disciplineDiversifiedCount = count($disciplines['diversified']) + 1;

@$frequencyTotal = $frequencyTotal / $disciplinesCount;

?>
<div class="pageA4V">
    <div>
        <br>

        <div id="report">
            <div style="margin: 0 0 0 50px; width: calc(100% - 51px); text-align:center">
                <div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span9"><b>ALUNO: </b><?= $enrollment->studentFk->name ?></div>
                    <div class="span9"><b>TURMA: </b><?= $enrollment->classroomFk->name ?></div>
                    <div class="span9"><b>ANO LETIVO: </b><?= $enrollment->classroomFk->school_year ?></div>
                </div>
            </div>
            <br>
            <table style="margin: 0 0 0 50px; font-size: 8px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <tr>
                    <td colspan="19" style="text-align: center">RENDIMENTO DE ACOMPANHAMENTO - RENDIMENTO</td>
                </tr>
                <tr>
                    <td rowspan="2" style="text-align: center; max-width: 90px !important;">DISCIPLINAS</td>
                    <td>1º</td>
                    <td>2º</td>
                    <td>3º</td>
                    <td>4º</td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
                    <td colspan="<?= $disciplineDiversifiedCount ?>" style="text-align: center; font-weight: bold">PARTE
                        DIVERSIFICADA
                    </td>
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
                        <div>BIMESTRES</div>
                    </td>
                    <td class="vertical-text">
                        <canvas width="100%" height="100%"></canvas>
                    </td>
                    <?php foreach ($disciplines['base'] as $name): ?>
                        <td class="vertical-text">
                            <div><?= $name ?></div>
                        </td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($disciplines['diversified'] as $name): ?>
                        <td class="vertical-text">
                            <div><?= $name ?></div>
                        </td>
                    <?php endforeach; ?>
                    <td></td>
                </tr>

                <?php for ($i = 1; $i <= 2; $i++): ?>
                    <tr>
                        <td><?= $i ?>º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <?php
                        foreach ($exams[$i]['base'] as $grade):?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <td></td>
                        <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <td></td>
                        <td><?= $schoolDays[$i] ?></td>
                        <td><?= $workingHours[$i] ?></td>
                        <td><?= $faultsCount['exam'][$i] ?></td>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">NOTA DA RECUPERAÇÃO</td>
                    <?php
                    foreach ($exams[$i]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td><?= $schoolDays[$i] ?></td>
                    <td><?= $workingHours[$i] ?></td>
                    <td><?= $faultsCount['exam'][$i] ?></td>
                </tr>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">MÉDIA DO 1º SEMESTRE</td>
                    <?php
                    foreach ($exams[$i]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td><?= $schoolDays[$i] ?></td>
                    <td><?= $workingHours[$i] ?></td>
                    <td><?= $faultsCount['exam'][$i] ?></td>
                </tr>
                <?php for ($i = 3; $i <= 4; $i++): ?>
                    <tr>
                        <td><?= $i ?>º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <?php
                        foreach ($exams[$i]['base'] as $grade):?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <td></td>
                        <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <td></td>
                        <td><?= $schoolDays[$i] ?></td>
                        <td><?= $workingHours[$i] ?></td>
                        <td><?= $faultsCount['exam'][$i] ?></td>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">NOTA DA RECUPERAÇÃO</td>
                    <?php
                    foreach ($exams[$i]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td><?= $schoolDays[$i] ?></td>
                    <td><?= $workingHours[$i] ?></td>
                    <td><?= $faultsCount['exam'][$i] ?></td>
                </tr>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">MÉDIA DO 2º SEMESTRE</td>
                    <?php
                    foreach ($exams[$i]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td><?= $schoolDays[$i] ?></td>
                    <td><?= $workingHours[$i] ?></td>
                    <td><?= $faultsCount['exam'][$i] ?></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">MÉDIA ANUAL</td>
                    <?php foreach ($averages['base'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($averages['diversified'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">NOTA DA PROVA FINAL</td>
                    <?php foreach ($finals['base'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($finals['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">MÉDIA FINAL</td>
                    <?php foreach ($finalAverage['base'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($finalAverage['diversified'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">TOTAL DE AULAS DADAS</td>
                    <?php foreach ($givenClassesByDiscipline['base'] as $given): ?>
                        <td><?= $given ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($givenClassesByDiscipline['diversified'] as $given): ?>
                        <td><?= $given ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td><?= $schoolDaysTotal ?></td>
                    <td><?= $workingHoursTotal ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">TOTAL DE FALTAS</td>
                    <?php foreach ($faultsCount['discipline']['base'] as $faults): ?>
                        <td><?= $faults ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($faultsCount['discipline']['diversified'] as $faults): ?>
                        <td><?= $faults ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?=$faultsCount['total']?></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">FREQUÊNCIAS %</td>
                    <?php foreach ($frequencyByDiscipline['base'] as $frequency): ?>
                        <td><?= number_format($frequency * 100, 2, ',', '') . '%'?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <?php foreach ($frequencyByDiscipline['diversified'] as $frequency): ?>
                        <td><?= number_format($frequency * 100, 2, ',', '') . '%'?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= number_format($frequencyTotal * 100, 2, ',', '') . '%'?></td>
                </tr>
            </table>
            <br/>
            <table class="table table-bordered report-table-empty" style="width: 250px; margin-left:50px;float:left;" >
                <thead>
                    <tr><td colspan="3" style="text-align:center;">RESULTADO FINAL</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 10px">Nº</td>
                        <td>CONCEITO</td>
                        <td>RES:</td>
                    </tr>
                    <tr>
                        <td>01</td>
                        <td>PROMOVIDO(A)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>RETIDO (A)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>EVADIDO(A)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>TRANSFERIDO(A)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>MAT. CANCELADA</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered report-table-empty" style="width: 450px; margin-left:50px;float:right" >
                <thead>
                 <tr><td>OBSERVAÇÕES</td></tr>
                </thead>
                <tbody>
                <tr>
                    <td><br><br><br><br><br><br></td>
                </tr>
                </tbody>
            </table>
            <span style="display: block;clear:both;"></span>
            <br/><br><br>
            <div style="text-align:center">APTO PARA CURSAR O _____________ ANO DO ENSINO FUNDAMENTAL
                <br/><br/><br/>
                <div>
                    <div style="text-align: center;line-height: 15px;">
                        <span class="pull-right">
                            <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . ' de ' . yii::t('default', date('F')) . ' de ' . date('Y') . '.' ?>
                        </span>
                        <br><br><br><br>
                        <div>
                            <div style="float: left;line-height: 15px; width:50%">
                                _________________________________________________________<br>Assinatura do(a) Secretário
                                (a)
                            </div>
                            <div style="float: right;line-height: 15px;width:50%">
                                _________________________________________________________<br>Assinatura do(a) Diretor(a)
                            </div>
                        </div>
                    </div>
                </div>
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
    }

    .vertical-text div {
        transform: translate(25px, 0px) rotate(270deg);
        width: 100px;
        line-height: 13px;
        margin: 0 10px 0 0;
        transform-origin: bottom left;
    }
    .vertical-text div.vertical-2 {
        transform: translate(25px, 0px) rotate(270deg);
        width: 400px;
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

        .report-table-empty td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%, auto;
        }
    }
</style>