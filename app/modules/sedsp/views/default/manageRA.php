<?php
/* @var $this DefaultController */

$this->setPageTitle('TAG - ' . Yii::t('default', 'SEDSP'));

$this->breadcrumbs = [
    $this->module->id,
];
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile($baseScriptUrl . '/common/js/functions.js?v=1.1', CClientScript::POS_END);
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
            <?php $this->widget(
    'zii.widgets.grid.CGridView',
    [
        'dataProvider' => $dataProvider,
        'enablePagination' => false,
        'enableSorting' => false,
        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed
            table-striped table-hover table-primary table-vertical-center checkboxs',
        'columns' => [
            [
                'name' => 'name'
            ],
            [
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{generate}',
                'buttons' => [
                    'generate' => [
                        'label' => 'Gerar RA',
                        'options' => ['id' => 'data->primaryKey'],
                        'click' => 'function(e){
                                        e.preventDefault();
                                        return false;
                                    }',
                        'url' => 'Yii::app()->controller->createUrl("GenerateRA",array("id"=>$data->primaryKey))',
                    ],
                ],
                'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
            ]
        ],
    ]
); ?>
        </div>
    </div>
</div>