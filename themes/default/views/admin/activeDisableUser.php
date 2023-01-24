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
                'enablePagination' => true,
                'filter' => $filter,
                'itemsCssClass' => 'tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                'columns' => array(
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->name,Yii::app()->createUrl("admin/disableUser",array("id"=>$data->id)))',
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->username)',
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'cssClassExpression' => '$data->active? show : hide',
                        'updateButtonUrl' => 'Yii::app()->createUrl("admin/disableUser",array("id"=>$data->id))',
                        'template' => '{impressora}',
                        'buttons' => array(
                            'impressora' => array(
                                'imageUrl' => Yii::app()->theme->baseUrl.'/img/impressora',
                            )
                        )
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'cssClassExpression' => '$data->active? hide : show',
                        'updateButtonUrl' => 'Yii::app()->createUrl("admin/activeUser",array("id"=>$data->id))',
                        'template' => '{downArrow}',
                        'buttons' => array(
                            'downArrow' => array(
                                'imageUrl' => Yii::app()->theme->baseUrl.'/img/downArrow',
                            )
                        )
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>