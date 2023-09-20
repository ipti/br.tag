<?php
/* @var $this ReportsController */
/* @var $report mixed */
Yii::app()->clientScript->registerCoreScript('jquery');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentStatisticsByYearReport/functions.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<style>
    th, td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Matrículas Atuais'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <div>
        <table id="enrollment-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th colspan="2">ANO LETIVO DE <?= Yii::app()->user->year ?></th>
                    <?php foreach($stageNumberGroups as $stageNumberGroupName => $stageNumberGroup): ?>
                            <th colspan="<?=$stageNumberGroup["colspan"]?>"><?=$stageNumberGroup["colname"]?></th>
                    <?php endforeach; ?>
                    <th></th>
                </tr>
                <tr>
                    <th>Unidade Escolar</th>
                    <th>Total de Estudantes</th>
                    <?php foreach($schoolStages as $schoolNameIndex => $schoolNameValue): ?>
                        <?php foreach($schoolNameValue as $stageNumberIndex => $stageNumberValue): ?>
                            <?php foreach($stageNumberValue as $stageName => $enrollmentsCount): ?>
                                <?php $key = array_search($stageName, array_column($stages, 'name')) ?>
                                <th><?= $stages[$key]["alias"] ?></th>
                            <?php endforeach; ?>
                        <?php  endforeach; ?>
                    <?php break; endforeach; ?>
                    <th>Total por Unidade</th>
                </tr>
            <tr>

            </tr>
            </thead>
            <tbody>
                <?php foreach($schoolStages as $schoolNameIndex => $schoolNameValue): ?>
                    <tr>
                        <td><?= $schoolNameIndex ?></td>
                        <td class="school-total">0</td>
                        <?php $stageGroupIndex = 0; ?>
                        <?php foreach($schoolNameValue as $stageNumberIndex => $stageNumberValue): ?>
                            <?php foreach($stageNumberValue as $stageName => $enrollmentsCount): ?>
                                <td class="stage-enrollment stage-<?= $stageGroupIndex ?>"><?= ($enrollmentsCount == 0 ? "" : $enrollmentsCount) ?></td>
                            <?php endforeach; ?>
                            <?php $stageGroupIndex++; ?>
                        <?php endforeach; ?>
                        <td class="unity-total">0</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="col-total">
                    <td colspan="2">Total de Alunos na Rede</td>
                    <?php foreach($schoolStages as $schoolNameIndex => $schoolNameValue): ?>
                        <?php foreach($schoolNameValue as $stageNumberIndex => $stageNumberValue): ?>
                            <?php foreach($stageNumberValue as $stageName => $enrollmentsCount): ?>
                                <td class="stage-total">0</td>
                            <?php endforeach; ?>
                        <?php  endforeach; ?>
                    <?php break; endforeach; ?>
                    <td class="rede-total">0</td>
                </tr>

                <tr class="group-stage-total">
                    <td colspan="2"></td>
                    <?php foreach($stageNumberGroups as $stageNumberGroupName => $stageNumberGroup): ?>
                        <td class="group-total" colspan="<?=$stageNumberGroup["colspan"]?>">0</td>
                    <?php endforeach; ?>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="rodape"><?php $this->renderPartial('footer'); ?></div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>