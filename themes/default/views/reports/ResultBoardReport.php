<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ResultBoardReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
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
        <br>
        <table class="table table-bordered table-striped">
            <tr><th> </th><th>Pré-Escolar</th></tr>
            <tr><th>Matrícula Inicial</th><td></td></tr>
            <tr><th>Promovidos por Idade</th><td></td></tr>
            <tr><th>Permantentes por Idade</th><td></td></tr>
            <tr><th>Desistentes</th><td></td></tr>
            <tr><th>Transferidos</th><td></td></tr>
            <tr><th>Matrícula após Estatística</th><td></td></tr>
            <tr><th>Matrícula Final</th><td></td></tr>
        </tbody>
        </table>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>