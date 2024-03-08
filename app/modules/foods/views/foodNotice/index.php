<?php
/* @var $this FoodNoticeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Food Notices',
);

$this->menu=array(
	array('label'=>'Create FoodNotice', 'url'=>array('create')),
	array('label'=>'Manage FoodNotice', 'url'=>array('admin')),
);
?>

<h1>Food Notices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
