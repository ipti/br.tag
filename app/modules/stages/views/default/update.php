<?php
/* @var $this DefaultController */
/* @var $model EdcensoStageVsModality */

$this->breadcrumbs=array(
	'Edcenso Stage Vs Modalities'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EdcensoStageVsModality', 'url'=>array('index')),
	array('label'=>'Create EdcensoStageVsModality', 'url'=>array('create')),
	array('label'=>'View EdcensoStageVsModality', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EdcensoStageVsModality', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
