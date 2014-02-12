<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */

$this->breadcrumbs=array(
	'Class Faults'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ClassFaults', 'url'=>array('index')),
	array('label'=>'Create ClassFaults', 'url'=>array('create')),
	array('label'=>'View ClassFaults', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ClassFaults', 'url'=>array('admin')),
);
?>

<h1>Update ClassFaults <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>