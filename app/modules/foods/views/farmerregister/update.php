<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */

$this->breadcrumbs=array(
	'Farmer Registers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FarmerRegister', 'url'=>array('index')),
	array('label'=>'Create FarmerRegister', 'url'=>array('create')),
	array('label'=>'View FarmerRegister', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FarmerRegister', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model, 'modelFarmerFoods'=>$modelFarmerFoods)); ?>
</div>
