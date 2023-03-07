<?php
/* @var $this ProfessionalController */
/* @var $model Professional */

$this->breadcrumbs=array(
	'Professionals'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Professional', 'url'=>array('index')),
	array('label'=>'Manage Professional', 'url'=>array('admin')),
);
?>

<h1>Create Professional</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>