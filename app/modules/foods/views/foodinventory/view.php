<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */

$this->breadcrumbs = [
    'Food Inventories' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List FoodInventory', 'url' => ['index']],
    ['label' => 'Create FoodInventory', 'url' => ['create']],
    ['label' => 'Update FoodInventory', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete FoodInventory', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage FoodInventory', 'url' => ['admin']],
];
?>

<h1>View FoodInventory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'school_fk',
        'food_fk',
        'amount',
        'measurementUnit',
    ],
]); ?>
