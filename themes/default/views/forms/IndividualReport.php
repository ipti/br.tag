<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/IndividualReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

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
    </div>
    <div class="container-box">
        <div class="header-table-container">
            <div class="header-table">
                <p><b>Ano Letivo:</b></p>
                <p></p>
            </div>
            <div class="header-table">
                <p><b>Ano Escolar:</b></p>
                <p></p>
            </div>
            <div class="header-table">
                <p><b>Turma:</b></p>
                <p></p>
            </div>
            <div class="header-table">
                <p><b>Turno:</b></p>
                <p></p>
            </div>
            <div class="header-table">
                <p><b>Nº:</b></p>
                <p></p>
            </div>
            <div class="header-table">
                <p><b>Dias Letivos:</b></p>
                <p></p>
            </div>
            <div class="header-table">
                <p><b>Carga Horária:</b></p>
                <p></p>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" colspan="2" rowspan="2" style="text-align:center;vertical-align: middle;">Disciplinas</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">1ª Unidade</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">2ª Unidade</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">3ª Unidade</th>
                    <th scope="col" colspan="3" style="text-align:center;vertical-align: middle;">4ª Unidade</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Total<br>de<br>Faltas</th>
                    <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Total<br>Aulas<br>Dadas</th>
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

                <!-- R1 -->
                <tr>
                    <td rowspan="3">R1</td>
                    <td>Lingua Portuguesa</td>
                    <td><?= $portuguese['grade1']?></td>
                    <td rowspan="3"><?= count($rOneFaults[0])?></td>
                    <td rowspan="3"><?= count($rOneClassesGiven[0])?></td>
                    <td><?= $portuguese['grade2']?></td>
                    <td rowspan="3"><?= count($rOneFaults[1])?></td>
                    <td rowspan="3"><?= count($rOneClassesGiven[1])?></td>
                    <td><?= $portuguese['grade3']?></td>
                    <td rowspan="3"><?= count($rOneFaults[2])?></td>
                    <td rowspan="3"><?= count($rOneClassesGiven[2])?></td>
                    <td><?= $portuguese['grade4']?></td>
                    <td rowspan="3"><?= count($rOneFaults[3])?></td>
                    <td rowspan="3"><?= count($rOneClassesGiven[3])?></td>
                    <td rowspan="3"><?= count($rOnefaultsTotal)?></td>
                    <td rowspan="3"><?= count($rOneclassesGuivenTotal)?></td>
                    <td><?= $portuguese['final_media']?></td>
                </tr>
                <tr>
                    <td>História</td>
                    <td><?= $history['grade1']?></td>
                    <td><?= $history['grade2']?></td>
                    <td><?= $history['grade3']?></td>
                    <td><?= $history['grade4']?></td>
                    <td><?= $history['final_media']?></td>
                </tr>
                <tr>
                    <td>Geografia</td>
                    <td><?= $geography['grade1']?></td>
                    <td><?= $geography['grade2']?></td>
                    <td><?= $geography['grade3']?></td>
                    <td><?= $geography['grade4']?></td>
                    <td><?= $geography['final_media']?></td>
                </tr>

                <!-- R2 -->
                <tr>
                    <td rowspan="2">R2</td>
                    <td>Matématica</td>
                    <td><?= $mathematics['grade1']?></td>
                    <td rowspan="2"><?= count($rTwoFaults[0])?></td>
                    <td rowspan="2"><?= count($rTwoClassesGiven[0])?></td>
                    <td><?= $mathematics['grade2']?></td>
                    <td rowspan="2"><?= count($rTwoFaults[1])?></td>
                    <td rowspan="2"><?= count($rTwoClassesGiven[1])?></td>
                    <td><?= $mathematics['grade3']?></td>
                    <td rowspan="2"><?= count($rTwoFaults[2])?></td>
                    <td rowspan="2"><?= count($rTwoClassesGiven[2])?></td>
                    <td><?= $mathematics['grade4']?></td>
                    <td rowspan="2"><?= count($rTwoFaults[3])?></td>
                    <td rowspan="2"><?= count($rTwoClassesGiven[3])?></td>
                    <td rowspan="2"><?= count($rTwofaultsTotal)?></td>
                    <td rowspan="2"><?= count($rTwoclassesGuivenTotal)?></td>
                    <td><?= $mathematics['final_media']?></td>
                </tr>
                <tr>
                    <td>Ciências</td>
                    <td><?= $sciences['grade1']?></td>
                    <td><?= $sciences['grade2']?></td>
                    <td><?= $sciences['grade3']?></td>
                    <td><?= $sciences['grade4']?></td>
                    <td><?= $sciences['final_media']?></td>
                </tr>

                <?php foreach ($disciplines as $d) {?>
                    <tr>
                        <td colspan="2"><?= $d['name']?></td>

                        <td><?= $d['grade1']?></td>
                        <td><?= $d['faults1']?></td>
                        <td><?= $d['classesguiven1']?></td>

                        <td><?= $d['grade2']?></td>
                        <td><?= $d['faults2']?></td>
                        <td><?= $d['classesguiven2']?></td>

                        <td><?= $d['grade3']?></td>
                        <td><?= $d['faults3']?></td>
                        <td><?= $d['classesguiven3']?></td>

                        <td><?= $d['grade4']?></td>
                        <td><?= $d['faults4']?></td>
                        <td><?= $d['classesguiven4']?></td>

                        <td><?= $d['faults1'] + $d['faults2'] + $d['faults3'] + $d['faults4']?></td>
                        <td><?= $d['classesGuivenTotal']?></td>
                        <td><?= $d['final_media']?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .header-table-container {
        display: inline-flex;
        width: 100%;
    }

    .header-table {
        width: 14.28%;
    }

    .container-box {
        margin-top: 20px;
    }

    td {
        text-align: center;
        vertical-align: middle;
    }
</style>