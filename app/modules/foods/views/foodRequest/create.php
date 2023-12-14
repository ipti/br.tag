<?php
/* @var $this FoodRequestController */
/* @var $model FoodRequest */

$this->breadcrumbs=array(
	'Food Requests'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FoodRequest', 'url'=>array('index')),
	array('label'=>'Manage FoodRequest', 'url'=>array('admin')),
);
?>

<h1>Create FoodRequest</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>