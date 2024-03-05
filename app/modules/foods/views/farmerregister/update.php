<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */

$this->breadcrumbs=array(
	'Farmer Registers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FarmerRegister', 'url'=>array('index')),
	array('label'=>'Create FarmerRegister', 'url'=>array('create')),
	array('label'=>'View FarmerRegister', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FarmerRegister', 'url'=>array('admin')),
);
?>

<h1>Update FarmerRegister <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>