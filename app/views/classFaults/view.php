<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */

$this->breadcrumbs=array(
	'Class Faults'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ClassFaults', 'url'=>array('index')),
	array('label'=>'Create ClassFaults', 'url'=>array('create')),
	array('label'=>'Update ClassFaults', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ClassFaults', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ClassFaults', 'url'=>array('admin')),
);
?>

<h1>View ClassFaults #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'class_fk',
		'student_fk',
	),
)); ?>
