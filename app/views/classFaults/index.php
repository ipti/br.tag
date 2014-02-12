<?php
/* @var $this ClassFaultsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Class Faults',
);

$this->menu=array(
	array('label'=>'Create ClassFaults', 'url'=>array('create')),
	array('label'=>'Manage ClassFaults', 'url'=>array('admin')),
);
?>

<h1>Class Faults</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
