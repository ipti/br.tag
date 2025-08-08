<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Estoque'));

$this->breadcrumbs = [
    'Food Inventories' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List FoodInventory', 'url' => ['index']],
    ['label' => 'Manage FoodInventory', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
