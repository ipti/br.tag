<?php
/* @var $this FoodInventoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Food Inventories',
);

$this->menu=array(
	array('label'=>'Create FoodInventory', 'url'=>array('create')),
	array('label'=>'Manage FoodInventory', 'url'=>array('admin')),
);
?>

<h1>Food Inventories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
