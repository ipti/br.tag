<?php
/* @var $this ReportsController */
/* @var $infos mixed*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default','Reports'));

$this->breadcrumbs=array(
	Yii::t('default', 'Reports')=>array('/reports'),
	Yii::t('default', 'Bolsa Família'),
);

?>


<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Relatório do Bolsa Família'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">

    <div class="filter-bar margin-bottom-none">
    </div>
    <div class="widget" id="widget-frequency" style="display:none; margin-top: 8px;">
        <div class="widget-head">
            <h4 class="heading"><span id="month_text"></span> - <span id="discipline_text"></span></h4>
        </div>
            <table id="frequency" class="table table-bordered table-striped">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>

                    </tr>
                </tbody>
            </table>
    </div>
</div>
<script>
    var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";
</script>