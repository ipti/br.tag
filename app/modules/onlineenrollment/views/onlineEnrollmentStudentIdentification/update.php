<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $model OnlineEnrollmentStudentIdentification */

$this->breadcrumbs=array(
	'Online Enrollment Student Identifications'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OnlineEnrollmentStudentIdentification', 'url'=>array('index')),
	array('label'=>'Create OnlineEnrollmentStudentIdentification', 'url'=>array('create')),
	array('label'=>'View OnlineEnrollmentStudentIdentification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OnlineEnrollmentStudentIdentification', 'url'=>array('admin')),
);
?>

<h1>Update OnlineEnrollmentStudentIdentification <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>