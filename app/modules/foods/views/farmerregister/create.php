<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */

$this->breadcrumbs=array(
	'Farmer Registers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FarmerRegister', 'url'=>array('index')),
	array('label'=>'Manage FarmerRegister', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model, 'modelFarmerFoods'=>$modelFarmerFoods)); ?>
</div>
