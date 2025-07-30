<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */

$this->breadcrumbs=array(
	'Course Plans'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List CoursePlan', 'url'=>array('index')),
	array('label'=>'Create CoursePlan', 'url'=>array('create')),
	array('label'=>'Update CoursePlan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CoursePlan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CoursePlan', 'url'=>array('admin')),
);
?>


<div id="mainPage" class="main">

<h1>View CoursePlan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'school_inep_fk',
		'modality_fk',
		'discipline_fk',
		'users_fk',
		'creation_date',
		'fkid',
		'situation',
		'start_date',
		'observation',
		'created_at',
		'updated_at',
	),
)); ?>

</div>
