<?php
/* @var $this InstanceConfigController */
/* @var $model InstanceConfig */

$this->breadcrumbs=array(
	'Instance Configs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InstanceConfig', 'url'=>array('index')),
	array('label'=>'Create InstanceConfig', 'url'=>array('create')),
	array('label'=>'View InstanceConfig', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InstanceConfig', 'url'=>array('admin')),
);
?>

<h1>Update InstanceConfig <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>