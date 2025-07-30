<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */

$this->breadcrumbs=array(
	'Course Plans'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CoursePlan', 'url'=>array('index')),
	array('label'=>'Manage CoursePlan', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">

<h1>Create CoursePlan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

</div>
