<?php
/* @var $this InstanceConfigController */
/* @var $model InstanceConfig */

$this->breadcrumbs=array(
	'Instance Configs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InstanceConfig', 'url'=>array('index')),
	array('label'=>'Manage InstanceConfig', 'url'=>array('admin')),
);
?>

<h1>Create InstanceConfig</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>