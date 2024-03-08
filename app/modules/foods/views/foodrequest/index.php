<?php
/* @var $this FoodRequestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Food Requests',
);

$this->menu=array(
	array('label'=>'Create FoodRequest', 'url'=>array('create')),
	array('label'=>'Manage FoodRequest', 'url'=>array('admin')),
);
?>

<h1>Food Requests</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
