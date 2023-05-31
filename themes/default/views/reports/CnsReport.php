<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H" style="width: 1080px;">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', $title); ?></h3>
    <h3><?php echo $header ?></h3>
    <table class="table table-bordered table-striped" aria-labelledby="cns students">
        <thead>
            <th>NOME</th>
            <th>D.N</th>
            <th>CNS</th>
            <th>RESPONSÁVEL</th>
            <th>TEL. RESPONSÁVEL</th>
            <?php echo $allSchools ? "<th scope='col'>ESCOLA</th>" : ""?>
        </thead>
        <tbody>
            <?php foreach($report as $r) { ?>
                <tr>
                    <td><?= $r['name'] ?></td>
                    <td><?= $r['birthday'] ?></td>
                    <td><?= $r['cns'] ?></td>
                    <td><?= $r['responsable_name'] ?></td>
                    <td><?= $r['responsable_telephone'] ?></td>
                    <?php echo $allSchools ? "<td>".$r['school_name']."</td>" : ""?>
                </tr>
            <?php } 
            if($countTotal) {
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php echo $allSchools ? "<td></td>" : ""?>
                <td><b>TOTAL:<?php echo " ".count($report)?></b></td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>

<style>
    table thead th {
        vertical-align: middle !important;
        text-align: center !important;
    }
</style>