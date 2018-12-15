<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentGradesReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

$a = $enrollment->getSheetGrade();

$disciplines = $a['disciplines'];
$exams = $a['evaluations'];
$recovery = $a['recovery'];
$averages = $a['average'];
$frequencyByDiscipline = $a['frequency'];
$schoolDays = $a['work_days'];
$schoolDaysByDiscipline = $a['work_days_discipline'];
$workingHours = $a['work_hours'];
$absences = $a['absences'];

$recoveryIndex = 1;
$averageIndex = 1;

$disciplinesCount = (count($disciplines['base'])+count($disciplines['diversified']));
$disciplineBaseCount = count($disciplines['base']);
$disciplineDiversifiedCount = count($disciplines['diversified']);
$workingHoursTotal = array_sum($workingHours);
$schoolDaysTotal = array_sum($schoolDays);
$absencesTotal = array_sum(array_slice($absences,0, 4));

@$frequencyTotal = (array_sum($frequencyByDiscipline['base']) + array_sum($frequencyByDiscipline['diversified'])) / (count($frequencyByDiscipline['base']) + count($frequencyByDiscipline['diversified']));
$frequencyTotal = number_format($frequencyTotal, 2, ',', '');

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
            <table id="notas" style="margin: 0 0 0 50px; font-size: 8px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <tr>
                    <th colspan="19" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
                </tr>
                <tr>
                    <td rowspan="10" class="vertical-text"><div class="vertical-2">QUADRO APLICÁVEL AO ENSINO FUNDAMENTAL DO 4º AO 5º ANO</div></td>
                    <td></td>
                    <td style="text-align: center; max-width: 90px !important;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                    <td colspan="<?= $disciplineBaseCount ?>" style="text-align: center; font-weight: bold">BASE
                        NACIONAL
                        COMUM
                    </td>
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
                    <td style=" position: relative; padding: 0 0 !important; height: 118px !important;">
                        <canvas id="line" width="100" height="118"></canvas>
                        <span class="right-top">Compomentes Curriculares</span>
                        <span class="left-bottom">Aproveitamento</span>
                    </td>
                    <?php foreach ($disciplines['base'] as $name): ?>
                        <td class="vertical-text">
                            <div><?= $name ?></div>
                        </td>
                    <?php endforeach; ?>
                    <?php foreach ($disciplines['diversified'] as $name): ?>
                        <td class="vertical-text">
                            <div><?= $name ?></div>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <?php for ($i = 1; $i <= 2; $i++): ?>
                    <tr>
                        <td><?= $i ?>º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <?php
                        foreach ($exams[$i]['base'] as $grade):?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <td><?= $schoolDays[$i] ?></td>
                        <td><?= $workingHours[$i] ?></td>
                        <td><?= $absences[$i] ?></td>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">NOTA DA RECUPERAÇÃO</td>
                    <?php
                    foreach ($recovery[$recoveryIndex]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($recovery[$recoveryIndex]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td><? /* $schoolDays[$i] */ ?></td>
                    <td><? /* $workingHours[$i] */ ?></td>
                    <td><? /* $faultsCount['exam'][$i] */ ?></td>
                    <?php $recoveryIndex++; ?>
                </tr>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">MÉDIA DO 1º SEMESTRE</td>
                    <?php
                    foreach ($averages[$averageIndex]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($averages[$averageIndex]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td><? /* $schoolDays[$i] */ ?></td>
                    <td><? /* $workingHours[$i] */ ?></td>
                    <td><? /* $absences[$i] */ ?></td>
                    <?php $averageIndex++; ?>
                </tr>
                <?php for ($i = 3; $i <= 4; $i++): ?>
                    <tr>
                        <td><?= $i ?>º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <?php
                        foreach ($exams[$i]['base'] as $grade):?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <?php foreach ($exams[$i]['diversified'] as $grade): ?>
                            <td><?= $grade ?></td>
                        <?php endforeach; ?>
                        <td><?= $schoolDays[$i] ?></td>
                        <td><?= $workingHours[$i] ?></td>
                        <td><?= $absences[$i] ?></td>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">NOTA DA RECUPERAÇÃO</td>
                    <?php
                    foreach ($recovery[$recoveryIndex]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($recovery[$recoveryIndex]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td><? /* $schoolDays[$i] */ ?></td>
                    <td><? /* $workingHours[$i] */ ?></td>
                    <td><? /* $absences[$i] */ ?></td>
                    <?php $recoveryIndex++; ?>
                </tr>
                <tr>
                    <td COLSPAN="2" style="text-align: center;">MÉDIA DO 2º SEMESTRE</td>
                    <?php
                    foreach ($averages[$averageIndex]['base'] as $grade):?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($averages[$averageIndex]['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td><? /* $schoolDays[$i] */ ?></td>
                    <td><? /* $workingHours[$i] */ ?></td>
                    <td><? /* $faultsCount['exam'][$i] */ ?></td>
                    <?php $averageIndex++; ?>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">MÉDIA ANUAL</td>
                    <?php foreach ($averages['year']['base'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($averages['year']['diversified'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">NOTA DA PROVA FINAL</td>
                    <?php foreach ($recovery['final']['base'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($recovery['final']['diversified'] as $grade): ?>
                        <td><?= $grade ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">MÉDIA FINAL</td>
                    <?php foreach ($averages['final']['base'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($averages['final']['diversified'] as $average): ?>
                        <td><?= $average ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">TOTAL DE AULAS DADAS</td>
                    <?php foreach ($schoolDaysByDiscipline['base'] as $given): ?>
                        <td><?= $given ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($schoolDaysByDiscipline['diversified'] as $given): ?>
                        <td><?= $given ?></td>
                    <?php endforeach; ?>
                    <td><?= $schoolDaysTotal ?></td>
                    <td><?= $workingHoursTotal ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">TOTAL DE FALTAS</td>
                    <?php foreach ($absences['base'] as $faults): ?>
                        <td><?= $faults ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($absences['diversified'] as $faults): ?>
                        <td><?= $faults ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td></td>
                    <td><?=$absencesTotal?></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="3">FREQUÊNCIAS %</td>
                    <?php foreach ($frequencyByDiscipline['base'] as $frequency): ?>
                        <td><?= $frequency  . is_null($frequency) ? '' : "%" ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($frequencyByDiscipline['diversified'] as $frequency): ?>
                        <td><?= $frequency . is_null($frequency) ? '' : "%" ?></td>
                    <?php endforeach; ?>
                    <td></td>
                    <td><?= $frequencyTotal ."%"?></td>
                    <td></td>
                </tr>
            </table>
            <br/>
            <table class="table table-bordered report-table-empty" style="width: 250px; margin-left:50px;float:left;" >
                <thead>
                    <tr><td colspan="1" style="text-align:center;">RESULTADO FINAL</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 10px">Nº</td>
                        <td>CONCEITO</td>
                        <td>RES:</td>
                    </tr>
                    <tr>
                        <td>01</td>
                        <td>APROVADO</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>REPROVADO</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>EVADIDO</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>TRANSFERIDO</td>
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
                            <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
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

    #report table tr td{
        text-align: center;
    }

    

    .right-top{
        max-width: 85px;
        position: absolute;
        right: 5px;
        top: 5px;
        line-height: 12px;
        text-align: right;
    }

    .left-bottom{
        max-width: 85px;
        position: absolute;
        left: 5px;
        bottom: 8px;
        line-height: 12px;
        text-align: left;
    }

    @media print {
        #container-header {
            width: 425px !important;
        }

        table, td, tr, th {
            border-color: black !important;
            border-width: 1px !important;
        }

        td, tr, th {
            border-collapse: collapse !important;
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

        .vertical-text div.vertical-2 {
            transform: translate(25px, 0px) rotate(270deg);
            width: 400px;
            line-height: 13px;
            margin: 0 10px 0 0;
            transform-origin: bottom left;
            
        }

        #notas{
            height: 580px;
        }

        #report{
            margin-left: -30px;
        }

        table {
            
        }
    }
</style>

<script>
    var el =document.getElementById("line");
    var line = el.getContext("2d");
    line.beginPath();
    line.moveTo(0,0);
    line.lineTo(98,118);
    line.stroke();
</script>