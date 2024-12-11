<?php
/* @var $this GradeConceptController */
/* @var $model GradeConcept */

$this->breadcrumbs=array(
	'Grade Concepts'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GradeConcept', 'url'=>array('index')),
	array('label'=>'Create GradeConcept', 'url'=>array('create')),
	array('label'=>'View GradeConcept', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GradeConcept', 'url'=>array('admin')),
);
?>

<h1>Update GradeConcept <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>