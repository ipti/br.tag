<?php
/* @var $this CourseClassAbilitiesController */
/* @var $model CourseClassAbilities */

$this->breadcrumbs=array(
	'Course Class Abilities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CourseClassAbilities', 'url'=>array('index')),
	array('label'=>'Create CourseClassAbilities', 'url'=>array('create')),
	array('label'=>'View CourseClassAbilities', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CourseClassAbilities', 'url'=>array('admin')),
);
$title = 'Habilidades';
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'title'=>$title)); ?>
