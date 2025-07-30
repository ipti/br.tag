<?php
/* @var $this CoursePlanController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Course Plans',
);

$this->menu=array(
	array('label'=>'Create CoursePlan', 'url'=>array('create')),
	array('label'=>'Manage CoursePlan', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
<h1>Course Plans</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</div>
