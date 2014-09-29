<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ResultBoardReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Reports') => array('/reports'),
    Yii::t('default', 'Result Board'),
);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Result Board'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>
        <?php $this->renderPartial('head'); ?>
        <table class="table table-bordered table-striped">
            <tr><th>Ano Letivo:</th><td></td></tr>
            <tr><th>Responsável:</th><td></td></tr>
        </table>
        <br>
        <table class="table table-bordered table-striped" >
            <tr><th>Idade</th><th>Matriculado</th><th>%</th><th>Idade</th><th>Desistente/Evadido</th><th>%</th><th>Transferido</th><th>%</th></tr>
            <tr><th>4 Anos</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><th>5 Anos</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><th>5 Anos e Meio</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            
        </table>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>