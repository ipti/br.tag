<?php
/* @var $this ProfessionalController */
/* @var $model Professional */

$this->breadcrumbs=array(
	'Professionals'=>array('index'),
	$model->id_professional=>array('view','id'=>$model->id_professional),
	'Update',
);

$this->menu=array(
	array('label'=>'List Professional', 'url'=>array('index')),
	array('label'=>'Create Professional', 'url'=>array('create')),
	array('label'=>'View Professional', 'url'=>array('view', 'id'=>$model->id_professional)),
	array('label'=>'Manage Professional', 'url'=>array('admin')),
);
?>

<h1>Update Professional <?php echo $model->id_professional; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>