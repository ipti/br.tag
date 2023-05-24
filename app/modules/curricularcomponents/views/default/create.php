<?php
/* @var $this DefaultController */
/* @var $model EdcensoDiscipline */

$this->breadcrumbs=array(
	'Edcenso Disciplines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EdcensoDiscipline', 'url'=>array('index')),
	array('label'=>'Manage EdcensoDiscipline', 'url'=>array('admin')),
);
?>

<h1>Create EdcensoDiscipline</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>