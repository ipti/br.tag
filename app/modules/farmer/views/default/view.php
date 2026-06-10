<?php
/* @var $this DefaultController */
/* @var $model FarmerRegister */

$this->breadcrumbs = [
    'Farmer Registers' => ['index'],
    $model->name,
];

$this->setPageTitle('TAG - Agricultor');
?>

<div id="mainPage" class="main">
    <div class="row">
        <h1><?= CHtml::encode($model->name); ?></h1>
    </div>

    <div class="tag-inner">
        <?php $this->widget('zii.widgets.CDetailView', [
            'data' => $model,
            'htmlOptions' => ['class' => 'table table-striped table-bordered detail-view'],
            'attributes' => [
                'id',
                'name',
                'cpf',
                'phone',
                'group_type',
                'status',
            ],
        ]); ?>
    </div>
</div>
