<?php
/* @var $this ClassesController */
/* @var $model Classes */

$this->breadcrumbs=array(
	'Classes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Classes', 'url'=>array('index')),
	array('label'=>'Create Classes', 'url'=>array('create')),
	array('label'=>'Update Classes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Classes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Classes', 'url'=>array('admin')),
);
?>

<h1>View Classes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'discipline_fk',
		'classroom_fk',
		'day',
		'month',
		'classtype',
		'given_class',
		'schedule',
	),
)); ?>
