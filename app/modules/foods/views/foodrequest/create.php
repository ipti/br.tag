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

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form',
    array('model'=>$model,
    'requestFarmerModel'=>$requestFarmerModel,
    'requestSchoolModel'=>$requestSchoolModel,
    'requestItemModel'=>$requestItemModel)); ?>
</div>
