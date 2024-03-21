<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */

$this->breadcrumbs=array(
	'Food Inventories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FoodInventory', 'url'=>array('index')),
	array('label'=>'Manage FoodInventory', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
