
<?php
/* @var $this FoodRequestController */
/* @var $model FoodRequest */

$this->breadcrumbs = [
    'Food Requests' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List FoodRequest', 'url' => ['index']],
    ['label' => 'Create FoodRequest', 'url' => ['create']],
    ['label' => 'Update FoodRequest', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete FoodRequest', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage FoodRequest', 'url' => ['admin']],
];
?>

<h1>View FoodRequest #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'date',
        'status',
        'notice_fk',
    ],
]); ?>
