<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/IndividualReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$turn = "";
if($enrollment->classroomFk->turn == "M") {
    $turn = "Matutino";
}else if ($enrollment->classroomFk->turn == "T") {
    $turn = "Vespertino";
}else if ($enrollment->classroomFk->turn == "N") {
    $turn = "Noturno";
}

$gradeOneTotalFaults = 0;
$gradeTwoTotalFaults = 0;
$gradeThreeTotalFaults = 0;
$gradeFourTotalFaults = 0;
$gradeTotalFaults = 0;

$portugueseCount = !empty($portuguese) ? 1 : 0;
$historyCount = !empty($history) ? 1 : 0;
$geographyCount = !empty($geography) ? 1 : 0;

$mathematicsCount = !empty($mathematics) ? 1 : 0;
$sciencesCount = !empty($sciences) ? 1 : 0;

$rOneDisiciplinesCount = $portugueseCount + $historyCount + $geographyCount;
$rTwoDisciplinesCount = $mathematicsCount + $sciencesCount;

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" onclick="imprimirPagina()" class='btn btn-icon hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<div class="pageA4V">
    <?php $this->renderPartial('../reports/buzios/headers/headBuziosVI'); ?>

    <hr>
    <h3 style="text-align:center;">FICHA INDIVIDUAL DE ENSINO FUNDAMENTAL <?php echo $minorFundamental ? "1º" : "2º"?> SEGMENTO</h3>
    <hr>
    <div class="container-box">
        <p>
            <span><?= "Nome do aluno(a): <u>".$enrollment->studentFk->name?></u></span>
            <span style="float:right;margin-right:100px;"><?= "Ano Letivo(a): <u>".$enrollment->classroomFk->school_year ?></u></span>
        </p>
        <p>
            <p><?= "Filiação: ".$enrollment->studentFk->filiation_1 ?></p>
            <p style="margin-left:60px;"><?= $enrollment->studentFk->filiation_2 ?></p>
        </p>
        <p>
            <span><?= "Nascimento: ".$enrollment->studentFk->birthday?></span>
            <span style="float:right;margin-right:100px;"><?= "Naturalidade: ".$enrollment->studentFk->edcensoCityFk->name." - ".$enrollment->studentFk->edcensoUfFk->acronym?></span>
        </p>
        <p>
            <span><?= "Identidade: ".$enrollment->studentFk->documentsFk->rg_number ?></span>
            <span style="float:right;margin-right:100px;"><?= "Orgão Expedidor: ".$enrollment->studentFk->documentsFk->rgNumberEdcensoOrganIdEmitterFk->name?></span>
        </p>
        <?php
            if($enrollment->studentFk->deficiency) {
                echo "<p>Atendimento Educacional Especializado: &nbsp(&nbspX&nbsp)&nbspSim &nbsp(&nbsp&nbsp)&nbspNão</p>";
            }else {
                echo "<p>Atendimento Educacional Especializado: &nbsp(&nbsp&nbsp)&nbspSim &nbsp(&nbspX&nbsp)&nbspNão</p>";
            }
        ?>
        <p>Etapa: <?= $enrollment->classroomFk->edcensoStageVsModalityFk->name?></p>
    </div>
    <div class="container-box">
        <div class="header-table-container">
            <div class="header-table">
                <p><b>Ano Letivo:</b></p>
                <p><?= $enrollment->classroomFk->school_year?></p>
            </div>
            <div class="header-table">
                <p><b>Turma:</b></p>
                <p><?= $enrollment->classroomFk->name?></p>
            </div>
            <div class="header-table">
                <p><b>Turno:</b></p>
                <p><?= $turn?></p>
            </div>
            <div class="header-table">
                <p><b>Nº:</b></p>
                <p><?= $enrollment->daily_order?></p>
            </div>
            <div class="header-table">
                <p><b>Dias Letivos:</b></p>
                <p><?= count($schedules)?></p>
            </div>
            <div class="header-table">
                <p><b>Carga Horária:</b></p>
                <p><?= $workload ?></p>
            </div>
        </div>
        <table class="table table-bordered" style="border:none;" aria-describedby="Tabela Notas">
            <thead>
                <tr>
                    <th scope="col" colspan="2" rowspan="2" style="text-align:center;vertical-align: middle;">Disciplinas</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">1ª Unidade</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">2ª Unidade</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">3ª Unidade</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">4ª Unidade</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Total<br>de<br>Faltas</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Total<br>de<br>Aulas Dadas</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Média<br>Final</th>
                </tr>
                <tr>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">AD</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">AD</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">AD</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">AD</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rOneGivenClasses = [];
                $rOneGivenClasses[0] = $portuguese[0]['givenClasses1'] + $history[0]['givenClasses1'] + $geography[0]['givenClasses1'];
                $rOneGivenClasses[1] = $portuguese[0]['givenClasses2'] + $history[0]['givenClasses2'] + $geography[0]['givenClasses2'];
                $rOneGivenClasses[2] = $portuguese[0]['givenClasses3'] + $history[0]['givenClasses3'] + $geography[0]['givenClasses3'];
                $rOneGivenClasses[3] = $portuguese[0]['givenClasses4'] + $history[0]['givenClasses4'] + $geography[0]['givenClasses4'];
                $rOneGivenClassesTotal = $rOneGivenClasses[0] + $rOneGivenClasses[1] + $rOneGivenClasses[2] + $rOneGivenClasses[3];

                $rTwoGivenClasses = [];
                $rTwoGivenClasses[0] = $mathematics[0]['givenClasses1'] + $sciences[0]['givenClasses1'];
                $rTwoGivenClasses[1] = $mathematics[0]['givenClasses2'] + $sciences[0]['givenClasses2'];
                $rTwoGivenClasses[2] = $mathematics[0]['givenClasses3'] + $sciences[0]['givenClasses3'];
                $rTwoGivenClasses[3] = $mathematics[0]['givenClasses4'] + $sciences[0]['givenClasses4'];
                $rTwoGivenClassesTotal = $rTwoGivenClasses[0] + $rTwoGivenClasses[1] + $rTwoGivenClasses[2] + $rTwoGivenClasses[3];

                $totalGivenClasses = $rOneGivenClassesTotal + $rTwoGivenClassesTotal;

                $rOneFaults = [];
                $rOneFaults[0]= $portuguese[0]['faults1'] + $history[0]['faults1'] + $geography[0]['faults1'];
                $rOneFaults[1] = $portuguese[0]['faults2'] + $history[0]['faults2'] + $geography[0]['faults2'];
                $rOneFaults[2] = $portuguese[0]['faults3'] + $history[0]['faults3'] + $geography[0]['faults3'];
                $rOneFaults[3] = $portuguese[0]['faults4'] + $history[0]['faults4'] + $geography[0]['faults4'];
                $rOnefaultsTotal = $rOneFaults[0] + $rOneFaults[1] + $rOneFaults[2] + $rOneFaults[3];

                $rTwoFaults = [];
                $rTwoFaults[0] = $mathematics[0]['faults1'] + $sciences[0]['faults1'];
                $rTwoFaults[1] = $mathematics[0]['faults2'] + $sciences[0]['faults2'];
                $rTwoFaults[2] = $mathematics[0]['faults3'] + $sciences[0]['faults3'];
                $rTwoFaults[3] = $mathematics[0]['faults4'] + $sciences[0]['faults4'];
                $rTwofaultsTotal = $rTwoFaults[0] + $rTwoFaults[1] + $rTwoFaults[2] + $rTwoFaults[3];

                $gradeOneTotalFaults += $rOneFaults[0] + $rTwoFaults[0];
                $gradeTwoTotalFaults += $rOneFaults[1] + $rTwoFaults[1];
                $gradeThreeTotalFaults += $rOneFaults[2] + $rTwoFaults[2];
                $gradeFourTotalFaults += $rOneFaults[3] + $rTwoFaults[3];

                $reprovado = false;

                ?>

                <?php if($minorFundamental) {?>
                <!-- INÍCIO FUNDAMENTAL MENOR -->
                <!-- R1 -->
                <?php if($portuguese || $history || $geography) { ?>
                    <tr>
                        <td rowspan="<?php echo $rOneDisiciplinesCount + 1?>">R1</td>
                    </tr>
                <?php } ?>
                <?php if($portuguese) {?>
                <tr>
                    <td class="discipline-name-td">Lingua Portuguesa</td>
                    <td class="<?php echo $portuguese[0]['grade1'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $portuguese[0]['grade1'] == null ? "-" : $portuguese[0]['grade1']?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[0]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[0]?></td>
                    <td class="<?php echo $portuguese[0]['grade2'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $portuguese[0]['grade2'] == null ? "-" : $portuguese[0]['grade2']?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[1]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[1]?></td>
                    <td class="<?php echo $portuguese[0]['grade3'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $portuguese[0]['grade3'] == null ? "-" : $portuguese[0]['grade3']?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[2]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[2]?></td>
                    <td class="<?php echo $portuguese[0]['grade4'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $portuguese[0]['grade4'] == null ? "-" : $portuguese[0]['grade4']?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[3]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[3]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOnefaultsTotal?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClassesTotal?></td>
                    <td><?= $portuguese[0]['final_media']?></td>
                    <?php
                    if($portuguese[0]['final_media'] < $gradeRules->approvation_media) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <?php }?>

                <?php if($history) {?>
                <tr>
                    <td class="discipline-name-td">História</td>
                    <td class="<?php echo $history[0]['grade1'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $history[0]['grade1'] == null ? "-" : $history[0]['grade1']?></td>
                    <?php if(!$portuguese) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[0]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[0]?></td>
                    <?php } ?>
                    <td class="<?php echo $history[0]['grade2'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $history[0]['grade2'] == null ? "-" : $history[0]['grade2']?></td>
                    <?php if(!$portuguese) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[1]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[1]?></td>
                    <?php } ?>
                    <td class="<?php echo $history[0]['grade3'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $history[0]['grade3'] == null ? "-" : $history[0]['grade3']?></td>
                    <?php if(!$portuguese) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[2]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[2]?></td>
                    <?php } ?>
                    <td class="<?php echo $history[0]['grade4'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $history[0]['grade4'] == null ? "-" : $history[0]['grade4']?></td>
                    <?php if(!$portuguese) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[3]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[3]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOnefaultsTotal?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClassesTotal?></td>
                    <?php } ?>
                    <td><?= $history[0]['final_media']?></td>
                    <?php
                    if($history[0]['final_media'] < $gradeRules->approvation_media) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <?php }?>

                <?php if($geography) {?>
                <tr>
                    <td class="discipline-name-td">Geografia</td>
                    <td class="<?php echo $geography[0]['grade1'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $geography[0]['grade1'] == null ? "-" : $geography[0]['grade1']?></td>
                    <?php if(!$portuguese && !$history) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[0]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[0]?></td>
                    <?php } ?>
                    <td class="<?php echo $geography[0]['grade2'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $geography[0]['grade2'] == null ? "-" : $geography[0]['grade2']?></td>
                    <?php if(!$portuguese && !$history) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[1]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[1]?></td>
                    <?php } ?>
                    <td class="<?php echo $geography[0]['grade3'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $geography[0]['grade3'] == null ? "-" : $geography[0]['grade3']?></td>
                    <?php if(!$portuguese && !$history) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[2]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[2]?></td>
                    <?php } ?>
                    <td class="<?php echo $geography[0]['grade4'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $geography[0]['grade4'] == null ? "-" : $geography[0]['grade4']?></td>
                    <?php if(!$portuguese && !$history) {?>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneFaults[3]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClasses[3]?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOnefaultsTotal?></td>
                    <td rowspan="<?php echo $rOneDisiciplinesCount?>"><?= $rOneGivenClassesTotal?></td>
                    <?php } ?>
                    <td><?= $geography[0]['final_media']?></td>
                    <?php
                    if($geography[0]['final_media'] < $gradeRules->approvation_media) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <?php }?>

                <!-- R2 -->
                <?php if($mathematics || $sciences) { ?>
                    <td rowspan="<?php echo $rTwoDisciplinesCount + 1?>">R2</td>
                <?php } ?>
                <?php if($mathematics) {?>
                <tr>
                    <td class="discipline-name-td">Matématica</td>
                    <td class="<?php echo $mathematics[0]['grade1'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $mathematics[0]['grade1'] == null ? "-" : $mathematics[0]['grade1']?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[0]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[0]?></td>
                    <td class="<?php echo $mathematics[0]['grade2'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $mathematics[0]['grade2'] == null ? "-" : $mathematics[0]['grade2']?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[1]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[1]?></td>
                    <td class="<?php echo $mathematics[0]['grade3'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $mathematics[0]['grade3'] == null ? "-" : $mathematics[0]['grade3']?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[2]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[2]?></td>
                    <td class="<?php echo $mathematics[0]['grade4'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $mathematics[0]['grade4'] == null ? "-" : $mathematics[0]['grade4']?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[3]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[3]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwofaultsTotal?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClassesTotal?></td>
                    <td><?= $mathematics[0]['final_media']?></td>
                    <?php
                    if($mathematics[0]['final_media'] < $gradeRules->approvation_media) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <?php }?>

                <?php if($sciences) {?>
                <tr>
                    <td class="discipline-name-td">Ciências</td>
                    <td class="<?php echo $sciences[0]['grade1'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $sciences[0]['grade1'] == null ? "-" : $sciences[0]['grade1']?></td>
                    <?php if(!$sciences) {?>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[0]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[0]?></td>
                    <?php } ?>
                    <td class="<?php echo $sciences[0]['grade2'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $sciences[0]['grade2'] == null ? "-" : $sciences[0]['grade2']?></td>
                    <?php if(!$sciences) {?>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[1]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[1]?></td>
                    <?php } ?>
                    <td class="<?php echo $sciences[0]['grade3'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $sciences[0]['grade3'] == null ? "-" : $sciences[0]['grade3']?></td>
                    <?php if(!$sciences) {?>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[2]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[2]?></td>
                    <?php } ?>
                    <td class="<?php echo $sciences[0]['grade4'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                    <?= $sciences[0]['grade4'] == null ? "-" : $sciences[0]['grade4']?></td>
                    <?php if(!$sciences) {?>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoFaults[3]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClasses[3]?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwofaultsTotal?></td>
                    <td rowspan="<?php echo $rTwoDisciplinesCount?>"><?= $rTwoGivenClassesTotal?></td>
                    <?php } ?>
                    <td><?= $sciences[0]['final_media']?></td>
                    <?php
                    if($sciences[0]['final_media'] < $gradeRules->approvation_media) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <?php }?>
                <?php }?>
                <!-- FIM FUNDAMENTAL MENOR -->

                <?php foreach ($disciplines as $d) {?>
                    <tr>
                        <td colspan="2"  class="discipline-name-td"><?= $d['name']?></td>

                        <td class="<?php echo $d['grade1'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                        <?= $d['grade1'] == null ? "-" : $d['grade1']?></td>
                        <td><?= $d['faults1']?></td>
                        <td><?= $d['givenClasses1'] ?></td>

                        <?php $gradeOneTotalFaults += $d['faults1']?>

                        <td class="<?php echo $d['grade2'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                        <?= $d['grade2'] == null ? "-" : $d['grade2']?></td>
                        <td><?= $d['faults2']?></td>
                        <td><?= $d['givenClasses2'] ?></td>

                        <?php $gradeTwoTotalFaults += $d['faults2']?>

                        <td class="<?php echo $d['grade3'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                        <?= $d['grade3'] == null ? "-" : $d['grade3']?></td>
                        <td><?= $d['faults3']?></td>
                        <?php $gradeThreeTotalFaults += $d['faults3']?>
                        <td><?= $d['givenClasses3'] ?></td>

                        <td class="<?php echo $d['grade4'] < $gradeRules->approvation_media ? "under-media-td" : ""?>">
                        <?= $d['grade4'] == null ? "-" : $d['grade4']?></td>
                        <td><?= $d['faults4']?></td>
                        <?php $gradeFourTotalFaults += $d['faults4']?>
                        <td><?= $d['givenClasses4'] ?></td>

                        <td><?= $d['faults1'] + $d['faults2'] + $d['faults3'] + $d['faults4']?></td>
                        <td><?= $d['givenClasses1'] + $d['givenClasses2'] + $d['givenClasses3'] + $d['givenClasses4'] ?></td>
                        <?php $totalGivenClasses += $d['givenClasses1'] + $d['givenClasses2'] + $d['givenClasses3'] + $d['givenClasses4'] ?>
                        <td><?= $d['final_media']?></td>
                        <?php
                        if($d['final_media'] < $gradeRules->approvation_media) {
                            $reprovado = true;
                        }
                        ?>
                    </tr>
                <?php }?>
                <tr>
                    <td colspan="2">Total Faltas</td>
                    <td colspan="3"><?= $gradeOneTotalFaults?></td>
                    <td colspan="3"><?= $gradeTwoTotalFaults?></td>
                    <td colspan="3"><?= $gradeThreeTotalFaults?></td>
                    <td colspan="3"><?= $gradeFourTotalFaults?></td>
                    <td colspan="2">Faltas Geral</td>
                    <td><?= $gradeOneTotalFaults + $gradeTwoTotalFaults + $gradeThreeTotalFaults + $gradeFourTotalFaults?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container-box">
        <div class="footer-container" style="display: flex; justify-content:space-between; margin-bottom: 10px">
            <p>Total de aulas dadas: <?= $totalGivenClasses ?></p>
            <p>Frequência: <?= $frequency."%"?></p>
            <p>Resultado: <?= $reprovado ? "Reprovado" : "Aprovado"?></p>
        </div>
        <p class="footer-container">
        Observações:<br>
        <?php if ($segment) {?>
        * Processo de avaliação das disciplinas de Educação Física e Arte e Cultura do Ensino Fundamental é feito<br>
        através de relatório trimestral, segundo o Regimento Escolar.<br>
        * A nota do 3º Trimestre possui peso 2.<br>
        <?php }else {?>
            <br><br><br><br><br><br><br><br><br><br><br>
        <?php }?>
        </p>
    </div>
    <div class="container-box signatures-container">
        <p>
            <span>_______________________________________</span>
            <span>_______________________________________</span>
        </p>
        <p>
            <span>Inspetor(a) Escolar</span>
            <span>Diretor(a)</span>
        </p>
        <p class="date">Data de Emissão: <?= date("d/m/Y") ?></span>
    </div>
</div>

<style>
    .under-media-td {
        color: red;
        text-decoration: underline;
        text-decoration-color: red;
    }
    .header-table-container {
        display: inline-flex;
        padding: 10px;
        width: 97.4%;
        border: 1px solid black !important;
    }

    table th, table td{
        border: 1px solid black !important;
    }

    .header-table {
        width: 16.66%;
    }

    .container-box {
        margin-top: 20px;
    }

    td:not(.discipline-name-td) {
        text-align: center !important;
        vertical-align: middle !important;
    }
    .footer-container {
        border: 1px solid black;
        padding: 10px;
    }
    .signatures-container {
        margin-top: 80px !important;
        width: 96%;
    }

    .signatures-container p {
        margin: 10px 0px;
        width: 100%;
        align-items: center;
        display: flex;
    }

    .signatures-container p span {
        margin: 0px 50px;
        width: 100%;
        align-items: center;
        display: flex;
        justify-content: center;
    }
    .date {
        margin-top: 20px !important;
        margin-left: 70% !important;
        font-size: 12px !important;
    }
</style>

<script>
    function imprimirPagina() {
        window.print()
    }
</script>
