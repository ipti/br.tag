<?php
/* @var $this ClassBoardController */
/* @var $model ClassBoard */

$this->breadcrumbs=array(
	'Class Boards'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ClassBoard', 'url'=>array('index')),
	array('label'=>'Create ClassBoard', 'url'=>array('create')),
	array('label'=>'View ClassBoard', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ClassBoard', 'url'=>array('admin')),
);
?>

<h1>Update ClassBoard <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>