<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $model OnlineEnrollmentStudentIdentification */

$this->breadcrumbs=array(
	'Online Enrollment Student Identifications'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OnlineEnrollmentStudentIdentification', 'url'=>array('index')),
	array('label'=>'Manage OnlineEnrollmentStudentIdentification', 'url'=>array('admin')),
);

$title = "Pre Matrícula";
$this->pageTitle = 'TAG - Pré-Matrícula';
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'title' => $title)); ?>
