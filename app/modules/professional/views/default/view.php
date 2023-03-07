<?php
/* @var $this ProfessionalController */
/* @var $model Professional */

$this->breadcrumbs=array(
	'Professionals'=>array('index'),
	$model->id_professional,
);

$this->menu=array(
	array('label'=>'List Professional', 'url'=>array('index')),
	array('label'=>'Create Professional', 'url'=>array('create')),
	array('label'=>'Update Professional', 'url'=>array('update', 'id'=>$model->id_professional)),
	array('label'=>'Delete Professional', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_professional),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Professional', 'url'=>array('admin')),
);
?>

<h1>View Professional #<?php echo $model->id_professional; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_professional',
		'cpf_professional',
		'specialty',
		'inep_id_fk',
		'fundeb',
	),
)); ?>
