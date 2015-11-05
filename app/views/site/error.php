<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
?>



<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic">Oops - Algo Inesperado aconteceu</h3>
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>
<div class="innerLR">
    <div>
        <div class="alert alert-primary">
            <?php $error = Yii::app()->errorHandler->getError(); ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Atenção!</strong> Entre em contato conosco no telefone: (79) 3255-1664 ou (79) 9944-2915
            e informe o código de erro: <strong><?php echo $error['code'] ?></strong>
            <br/>

            Informações Técnicas:</br>
            <ul>
                <?php foreach ($error as $key => $value) {?>
                    <li><?php echo Yii::t('default', $key)?>: <?php echo $value ?></li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>


