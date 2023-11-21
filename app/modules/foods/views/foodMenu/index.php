<?php
/* @var $this FoodMenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Food Menus',
);

$this->menu=array(
	array('label'=>'Create FoodMenu', 'url'=>array('create')),
	array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
);
?>

<h1>Food Menus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
