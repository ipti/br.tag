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
    <h3 style="text-align:center;">FICHA INDIVIDUAL DE ENSINO FUNDAMENTAL 1º SEGMENTO</h3>
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
            <span style="float:right;margin-right:100px;"><?= "Naturalidade: ".$enrollment->studentFk->edcensoCityFk->name."/".$enrollment->studentFk->edcensoUfFk->acronym?></span>
        </p>
        <p>
            <span><?= "Identidade: ".$enrollment->studentFk->documentsFk->rg_number ?></span>
            <span style="float:right;margin-right:100px;"><?= "Orgão Expedidor: ".$enrollment->studentFk->documentsFk->rgNumberEdcensoOrganIdEmitterFk->name?></span>
        </p>
        <?php
            if($enrollment->studentFk->deficiency) {
                echo "<p>Atendimento Educacional Especializado: <input type='checkbox' checked disabled>Sim <input type='checkbox' disabled>Não</p>";
            }else {
                echo "<p>Atendimento Educacional Especializado: <input type='checkbox' disabled>Sim <input type='checkbox' checked disabled>Não</p>";
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
        <table class="table table-bordered" style="border:none;">
            <thead>
                <tr>
                    <th scope="col" colspan="2" rowspan="2" style="text-align:center;vertical-align: middle;">Disciplinas</th>
                    <th scope="col" colspan="2" style="text-align:center;vertical-align: middle;">1ª Unidade</th>
                    <th scope="col" colspan="2" style="text-align:center;vertical-align: middle;">2ª Unidade</th>
                    <th scope="col" colspan="2" style="text-align:center;vertical-align: middle;">3ª Unidade</th>
                    <th scope="col" colspan="2" style="text-align:center;vertical-align: middle;">4ª Unidade</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Total<br>de<br>Faltas</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Média<br>Final</th>
                </tr>
                <tr>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">N</th>
                    <th scope="col" style="text-align:center;vertical-align: middle;">F</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $rOneFaults = [];
                $rOneFaults[0] = $portuguese[0]['faults1'] + $history[0]['faults1'] + $geography[0]['faults1'];
                $rOneFaults[1] = $portuguese[1]['faults2'] + $history[1]['faults2'] + $geography[1]['faults2'];
                $rOneFaults[2] = $portuguese[2]['faults3'] + $history[2]['faults3'] + $geography[2]['faults3'];
                $rOneFaults[3] = $portuguese[3]['faults4'] + $history[3]['faults4'] + $geography[3]['faults4'];
                $rOnefaultsTotal = $rOneFaults[0] + $rOneFaults[1] + $rOneFaults[2] + $rOneFaults[3];

                $rTwoFaults = [];
                $rTwoFaults[0] = $mathematics[0]['faults1'] + $sciences[0]['faults1'];
                $rTwoFaults[1] = $mathematics[1]['faults2'] + $sciences[1]['faults2'];
                $rTwoFaults[2] = $mathematics[2]['faults3'] + $sciences[2]['faults3'];
                $rTwoFaults[3] = $mathematics[3]['faults4'] + $sciences[3]['faults4'];
                $rTwofaultsTotal = $rTwoFaults[0] + $rTwoFaults[1] + $rTwoFaults[2] + $rTwoFaults[3];

                $reprovado = false;

                ?>
                <!-- R1 -->
                <tr>
                    <td rowspan="3">R1</td>
                    <td class="discipline-name-td">Lingua Portuguesa</td>
                    <td><?= $portuguese[0]['grade1']?></td>
                    <td rowspan="3"><?= $rOneFaults[0]?></td>
                    <td><?= $portuguese[0]['grade2']?></td>
                    <td rowspan="3"><?= $rOneFaults[1]?></td>
                    <td><?= $portuguese[0]['grade3']?></td>
                    <td rowspan="3"><?= $rOneFaults[2]?></td>
                    <td><?= $portuguese[0]['grade4']?></td>
                    <td rowspan="3"><?= $rOneFaults[3]?></td>
                    <td rowspan="3"><?= $rOnefaultsTotal?></td>
                    <td><?= $portuguese[0]['final_media']?></td>
                    <?php 
                    if($portuguese[0]['final_media'] < 5.0) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <tr>
                    <td class="discipline-name-td">História</td>
                    <td><?= $history[0]['grade1']?></td>
                    <td><?= $history[0]['grade2']?></td>
                    <td><?= $history[0]['grade3']?></td>
                    <td><?= $history[0]['grade4']?></td>
                    <td><?= $history[0]['final_media']?></td>
                    <?php 
                    if($history[0]['final_media'] < 5.0) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <tr>
                    <td class="discipline-name-td">Geografia</td>
                    <td><?= $geography[0]['grade1']?></td>
                    <td><?= $geography[0]['grade2']?></td>
                    <td><?= $geography[0]['grade3']?></td>
                    <td><?= $geography[0]['grade4']?></td>
                    <td><?= $geography[0]['final_media']?></td>
                    <?php 
                    if($geography[0]['final_media'] < 5.0) {
                        $reprovado = true;
                    }
                    ?>
                </tr>

                <!-- R2 -->
                <tr>
                    <td rowspan="2">R2</td>
                    <td class="discipline-name-td">Matématica</td>
                    <td><?= $mathematics[0]['grade1']?></td>
                    <td rowspan="2"><?= $rTwoFaults[0]?></td>
                    <td><?= $mathematics[0]['grade2']?></td>
                    <td rowspan="2"><?= $rTwoFaults[1]?></td>
                    <td><?= $mathematics[0]['grade3']?></td>
                    <td rowspan="2"><?= $rTwoFaults[2]?></td>
                    <td><?= $mathematics[0]['grade4']?></td>
                    <td rowspan="2"><?= $rTwoFaults[3]?></td>
                    <td rowspan="2"><?= $rTwofaultsTotal?></td>
                    <td><?= $mathematics[0]['final_media']?></td>
                    <?php 
                    if($mathematics[0]['final_media'] < 5.0) {
                        $reprovado = true;
                    }
                    ?>
                </tr>
                <tr>
                    <td class="discipline-name-td">Ciências</td>
                    <td><?= $sciences[0]['grade1']?></td>
                    <td><?= $sciences[0]['grade2']?></td>
                    <td><?= $sciences[0]['grade3']?></td>
                    <td><?= $sciences[0]['grade4']?></td>
                    <td><?= $sciences[0]['final_media']?></td>
                    <?php 
                    if($sciences[0]['final_media'] < 5.0) {
                        $reprovado = true;
                    }
                    ?>
                </tr>

                <?php foreach ($disciplines as $d) {?>
                    <tr>
                        <td colspan="2"  class="discipline-name-td"><?= $d['name']?></td>

                        <td><?= $d['grade1']?></td>
                        <td><?= $d['faults1']?></td>

                        <td><?= $d['grade2']?></td>
                        <td><?= $d['faults2']?></td>

                        <td><?= $d['grade3']?></td>
                        <td><?= $d['faults3']?></td>

                        <td><?= $d['grade4']?></td>
                        <td><?= $d['faults4']?></td>

                        <td><?= $d['faults1'] + $d['faults2'] + $d['faults3'] + $d['faults4']?></td>
                        <td><?= $d['final_media']?></td>
                        <?php 
                        if($d['final_media'] < 5.0) {
                            $reprovado = true;
                        }
                        ?>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div class="container-box">
        <p class="footer-container">
            <span style="margin-right: 50%;">Frequência: <?= $frequency."%"?></span>
            <span>Resultado: <?= $reprovado ? "Reprovado" : "Aprovado"?></span>
        </p>
        <p class="footer-container">
        Observações:<br>
        * Processo de avaliação das disciplinas de Educação Física e Arte e Cultura do Ensino Fundamental é feito<br>
        através de relatório trimestral, segundo o Regimento Escolar.<br>
        * A nota do 3º Trimestre possui peso 2.<br>
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
    .header-table-container {
        display: inline-flex;
        width: 100%;
    }

    table th, table td{
        border: 0.5px solid #e5e5e5 !important;
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
        border: 0.5px solid #e5e5e5;
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