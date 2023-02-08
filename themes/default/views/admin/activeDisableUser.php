<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.2/r-2.4.0/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.2/r-2.4.0/datatables.min.js"></script>
<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);
$themeUrl = Yii::app()->theme->baseUrl;
$cs->registerCssFile($themeUrl . '/css/template2.css');
?>

<div id="mainPage" class="main">
<?php
$this->setPageTitle('TAG - ' . Yii::t('default', 'Users'));
?>
    <div class="row-fluid hide-responsive" style="margin-bottom: 50px;">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Users') ?></h3> 
        </div>
    </div>
    <div class="widget">
        <div class="widget-body">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'enablePagination' => false,
                'enableSorting' => false,
                'itemsCssClass' => 'js-tag-table tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                'columns' => array(
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => '$data->name',
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'raw',
                        'value' => '$data->username',
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'cssClassExpression' => '$data->active? show : hide',
                        'urlExpression'=>'Yii::app()->createUrl("admin/disableUser",array("id"=>$data->id))',
                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/unpublished_FILL0_wght600_GRAD200_opsz48.svg'
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'cssClassExpression' => '$data->active? hide : show',
                        'urlExpression'=>'Yii::app()->createUrl("admin/activeUser",array("id"=>$data->id))',
                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/check_circle_FILL0_wght600_GRAD200_opsz48.svg'
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>

<style>
    #yw0_c3 {
        display: none;
    }
</style>