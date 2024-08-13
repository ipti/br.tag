<?php
/* @var $this StudentAeeRecordController */
/* @var $model StudentAeeRecord */

$this->breadcrumbs=array(
	'Student Aee Records'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StudentAeeRecord', 'url'=>array('index')),
	array('label'=>'Create StudentAeeRecord', 'url'=>array('create')),
	array('label'=>'View StudentAeeRecord', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StudentAeeRecord', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
