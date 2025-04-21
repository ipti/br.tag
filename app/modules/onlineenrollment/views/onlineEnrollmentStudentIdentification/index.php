<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Online Enrollment Student Identifications',
);

$this->menu=array(
	array('label'=>'Create OnlineEnrollmentStudentIdentification', 'url'=>array('create')),
	array('label'=>'Manage OnlineEnrollmentStudentIdentification', 'url'=>array('admin')),
);
?>

<h1>Online Enrollment Student Identifications</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
