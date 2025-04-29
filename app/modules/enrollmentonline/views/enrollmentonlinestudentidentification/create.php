<?php
/* @var $this EnrollmentOnlineStudentIdentificationController */
/* @var $model EnrollmentOnlineStudentIdentification */

$this->breadcrumbs=array(
	'Enrollment Online Student Identifications'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EnrollmentOnlineStudentIdentification', 'url'=>array('index')),
	array('label'=>'Manage EnrollmentOnlineStudentIdentification', 'url'=>array('admin')),
);
?>

<h1>Create EnrollmentOnlineStudentIdentification</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>