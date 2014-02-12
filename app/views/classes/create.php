<?php
/* @var $this ClassesController */
/* @var $model Classes */

$this->breadcrumbs=array(
	'Classes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Classes', 'url'=>array('index')),
	array('label'=>'Manage Classes', 'url'=>array('admin')),
);
?>

<h1>Create Classes</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>