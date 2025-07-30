<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */

$this->breadcrumbs=array(
	'Course Plans'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CoursePlan', 'url'=>array('index')),
	array('label'=>'Create CoursePlan', 'url'=>array('create')),
	array('label'=>'View CoursePlan', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CoursePlan', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">

<h1>Update CoursePlan <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

</div>
