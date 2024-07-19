
<?php
/* @var $this FoodRequestController */
/* @var $model FoodRequest */

$this->breadcrumbs=array(
    'Food Requests'=>array('index'),
    $model->id,
);

$this->menu=array(
    array('label'=>'List FoodRequest', 'url'=>array('index')),
    array('label'=>'Create FoodRequest', 'url'=>array('create')),
    array('label'=>'Update FoodRequest', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete FoodRequest', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage FoodRequest', 'url'=>array('admin')),
);
?>

<h1>View FoodRequest #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'date',
        'status',
        'notice_fk',
    ),
)); ?>
