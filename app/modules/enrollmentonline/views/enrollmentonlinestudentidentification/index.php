<?php
/* @var $this EnrollmentOnlineStudentIdentificationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Enrollment Online Student Identifications',
);

$this->menu=array(
	array('label'=>'Create EnrollmentOnlineStudentIdentification', 'url'=>array('create')),
	array('label'=>'Manage EnrollmentOnlineStudentIdentification', 'url'=>array('admin')),
);
?>

<h1>Enrollment Online Student Identifications</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
