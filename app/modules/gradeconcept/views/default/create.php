<?php
/* @var $this GradeConceptController */
/* @var $model GradeConcept */

$this->breadcrumbs=array(
	'Grade Concepts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GradeConcept', 'url'=>array('index')),
	array('label'=>'Manage GradeConcept', 'url'=>array('admin')),
);
?>

<h1>Create GradeConcept</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>