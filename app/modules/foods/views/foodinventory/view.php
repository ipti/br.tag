<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */

$this->breadcrumbs=array(
	'Food Inventories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List FoodInventory', 'url'=>array('index')),
	array('label'=>'Create FoodInventory', 'url'=>array('create')),
	array('label'=>'Update FoodInventory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete FoodInventory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FoodInventory', 'url'=>array('admin')),
);
?>

<h1>View FoodInventory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'school_fk',
		'food_fk',
		'amount',
		'measurementUnit',
	),
)); ?>
