<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */

$this->breadcrumbs=array(
	'Food Inventories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FoodInventory', 'url'=>array('index')),
	array('label'=>'Create FoodInventory', 'url'=>array('create')),
	array('label'=>'View FoodInventory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FoodInventory', 'url'=>array('admin')),
);
?>

<h1>Update FoodInventory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>