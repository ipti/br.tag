<?php
/* @var $this CourseClassAbilitiesController */
/* @var $model CourseClassAbilities */

$this->breadcrumbs=array(
	'Course Class Abilities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CourseClassAbilities', 'url'=>array('index')),
	array('label'=>'Manage CourseClassAbilities', 'url'=>array('admin')),
);
$this->pageTitle = 'TAG - Habilidades';
$title = 'Habilidades';
?>


<?php $this->renderPartial('_form', array('model'=>$model, 'title'=>$title)); ?>
