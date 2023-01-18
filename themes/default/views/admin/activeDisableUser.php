<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);
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
                'enablePagination' => true,
                'filter' => $filter,
                'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                'columns' => array(
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->name,Yii::app()->createUrl("admin/disableUser",array("id"=>$data->id)))',
                        'htmlOptions' => array('width' => '400px')
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->username)',
                        'htmlOptions' => array('width' => '100px')
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'labelExpression'=>'Desativar',
                        'cssClassExpression' => 'btn btn-default',
                        'urlExpression'=>'Yii::app()->createUrl("admin/disableUser",array("id"=>$data->id))',
                        'htmlOptions' => array('width' => '10px')
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'labelExpression'=>'Ativar',
                        'urlExpression'=>'Yii::app()->createUrl("admin/activeUser",array("id"=>$data->id))',
                        'htmlOptions' => array('width' => '10px')
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>