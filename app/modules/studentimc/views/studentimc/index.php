<?php
/* @var $this StudentIMCController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Student Imcs',
);

$this->menu=array(
	array('label'=>'Create StudentIMC', 'url'=>array('create')),
	array('label'=>'Manage StudentIMC', 'url'=>array('admin')),
);
?>

<h1>Student Imcs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
