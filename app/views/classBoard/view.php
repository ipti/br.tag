<?php
/* @var $this ClassBoardController */
/* @var $model ClassBoard */

$this->breadcrumbs=array(
	'Class Boards'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ClassBoard', 'url'=>array('index')),
	array('label'=>'Create ClassBoard', 'url'=>array('create')),
	array('label'=>'Update ClassBoard', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ClassBoard', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ClassBoard', 'url'=>array('admin')),
);
?>

<h1>View ClassBoard #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'discipline_fk',
		'classroom_fk',
		'week_day_monday',
		'week_day_tuesday',
		'week_day_wednesday',
		'week_day_thursday',
		'week_day_friday',
		'week_day_saturday',
		'week_day_sunday',
		'estimated_classes',
		'given_classes',
		'replaced_classes',
	),
)); ?>
