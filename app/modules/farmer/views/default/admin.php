<?php
/* @var $this DefaultController */
/* @var $model FarmerRegister */

$this->breadcrumbs = [
    'Farmer Registers' => ['index'],
    'Manage',
];

$this->setPageTitle('TAG - Gerenciar Agricultores');
?>

<div id="mainPage" class="main">
    <div class="row">
        <h1>Gerenciar Agricultores</h1>
    </div>

    <div class="tag-inner">
        <?php $this->widget('zii.widgets.grid.CGridView', [
            'id' => 'farmer-register-admin-grid',
            'dataProvider' => $model->search(true),
            'filter' => $model,
            'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
            'columns' => [
                'id',
                'name',
                'cpf',
                'phone',
                'group_type',
                'status',
                [
                    'class' => 'CButtonColumn',
                    'template' => '{view}{update}{delete}',
                    'viewButtonUrl' => 'Yii::app()->createUrl("farmer/default/view", ["id" => $data->id])',
                    'updateButtonUrl' => 'Yii::app()->createUrl("farmer/default/update", ["id" => $data->id])',
                    'deleteButtonUrl' => 'Yii::app()->createUrl("farmer/default/delete", ["id" => $data->id])',
                ],
            ],
        ]); ?>
    </div>
</div>
