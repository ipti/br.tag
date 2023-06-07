<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/activeDisableUser/_initialization.js', CClientScript::POS_END);
$themeUrl = Yii::app()->theme->baseUrl;

?>

<div id="mainPage" class="main">
<?php
$this->setPageTitle('TAG - ' . Yii::t('default', 'Users'));
?>
    <div class="row-fluid hide-responsive" style="margin-bottom: 50px;">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Users') ?></h1> 
        </div>
    </div>
    <div class="widget">
        <div class="widget-body">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'enablePagination' => false,
                'enableSorting' => false,
                'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                'columns' => array(
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->name,Yii::app()->createUrl("admin/update",array("id"=>$data->id)))',
                        'htmlOptions' => array('class' => 'link-update-grid-view'),
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
                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/disableUser.svg',
                        'htmlOptions' => array('style' => 'text-align: center', 'title' => 'Desativar Usuário'),
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'cssClassExpression' => '$data->active? hide : show',
                        'urlExpression'=>'Yii::app()->createUrl("admin/activeUser",array("id"=>$data->id))',
                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/activeUser.svg',
                        'htmlOptions' => array('style' => 'text-align: center', 'title' => 'Ativar Usuário'),
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>
