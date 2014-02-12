<?php
/* @var $this ClassBoardController */
/* @var $model ClassBoard */

$this->breadcrumbs=array(
	'Class Boards'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ClassBoard', 'url'=>array('index')),
	array('label'=>'Manage ClassBoard', 'url'=>array('admin')),
);
?>

<h1>Create ClassBoard</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>