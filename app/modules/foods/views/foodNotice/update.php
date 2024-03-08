<?php
/* @var $this FoodNoticeController */
/* @var $model FoodNotice */

$this->breadcrumbs=array(
	'Food Notices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FoodNotice', 'url'=>array('index')),
	array('label'=>'Create FoodNotice', 'url'=>array('create')),
	array('label'=>'View FoodNotice', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FoodNotice', 'url'=>array('admin')),
);
?>

<h1>Update FoodNotice <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>