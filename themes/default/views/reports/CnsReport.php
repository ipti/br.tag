<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', $title); ?></h3>
    <h3><?php echo $header ?></h3>
    <table class="table table-bordered table-striped" aria-labelledby="cns students">
        <thead>
            <th>NOME</th>
            <th>DATA DE NASCIMENTO</th>
            <th>CNS</th>
            <th>RESPONSÁVEL</th>
            <th>TEL. RESPONSÁVEL</th>
        </thead>
        <tbody>
            <?php foreach($report as $r) { ?>
                <tr>
                    <td><?= $r['name'] ?></td>
                    <td><?= $r['birthday'] ?></td>
                    <td><?= $r['cns'] ?></td>
                    <td><?= $r['responsable_name'] ?></td>
                    <td><?= $r['responsable_telephone'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>