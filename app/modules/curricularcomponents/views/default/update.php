<?php
/* @var $this DefaultController */
/* @var $model EdcensoDiscipline */

$this->breadcrumbs=array(
	'Edcenso Disciplines'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EdcensoDiscipline', 'url'=>array('index')),
	array('label'=>'Create EdcensoDiscipline', 'url'=>array('create')),
	array('label'=>'View EdcensoDiscipline', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EdcensoDiscipline', 'url'=>array('admin')),
);
?>

<h1>Update EdcensoDiscipline <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>