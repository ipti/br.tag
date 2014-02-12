<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */

$this->breadcrumbs=array(
	'Class Faults'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ClassFaults', 'url'=>array('index')),
	array('label'=>'Manage ClassFaults', 'url'=>array('admin')),
);
?>

<h1>Create ClassFaults</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>