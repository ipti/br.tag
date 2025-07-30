<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */

$this->breadcrumbs=array(
	'Course Classes'=>array('index'),
	$model->content,
);

$this->menu=array(
	array('label'=>'List CourseClasses', 'url'=>array('index')),
	array('label'=>'Create CourseClasses', 'url'=>array('create')),
	array('label'=>'Update CourseClasses', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CourseClasses', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CourseClasses', 'url'=>array('admin')),
);
?>


<div id="mainPage" class="main">

<h1>View CoursePlan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'order',
        'content',
        'course_plan_fk',
        'fkid',
        'methodology',
        'created_at',
        'updated_at',
    ),
)); ?>

</div>
