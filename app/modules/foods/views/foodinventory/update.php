<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Estoque'));

$this->breadcrumbs = [
    'Food Inventories' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List FoodInventory', 'url' => ['index']],
    ['label' => 'Create FoodInventory', 'url' => ['create']],
    ['label' => 'View FoodInventory', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage FoodInventory', 'url' => ['admin']],
];
?>

<h1>Update FoodInventory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>
