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
        <?php
        $this->renderPartial('head');  
        ?>
        <table class="table table-bordered table-striped" >
            <tr><th colspan="4"style="text-align: center">DEPARTAMENTO DE EDUCAÇÃO INFANTIL</th></tr>
            <tr><th>Núcleo Infantil:</th><td colspan="3"> </td></tr>
            <tr><th>Localidade:</th><td style="width: 25%"> </td><th>Ano Letivo:</th><td style="width: 25%"> </td></tr>
        </tbody>
        </table>
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
        <p class="info-issued">Emitido em <?php echo date('d/m/Y à\s H:i'); ?></p>
    </div>
</div>