<?php
/* @var $this EnrollmentOnlineStudentIdentificationController */
/* @var $model EnrollmentOnlineStudentIdentification */

$this->breadcrumbs=array(
	'Enrollment Online Student Identifications'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EnrollmentOnlineStudentIdentification', 'url'=>array('index')),
	array('label'=>'Create EnrollmentOnlineStudentIdentification', 'url'=>array('create')),
	array('label'=>'View EnrollmentOnlineStudentIdentification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EnrollmentOnlineStudentIdentification', 'url'=>array('admin')),
);
?>

<h1>Update EnrollmentOnlineStudentIdentification <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
