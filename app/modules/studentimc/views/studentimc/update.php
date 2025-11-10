<?php
/* @var $this StudentIMCController */
/* @var $model StudentIMC */

$this->breadcrumbs=array(
	'Student Imcs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Atualizar IMC'));

$this->menu=array(
	array('label'=>'List StudentIMC', 'url'=>array('index')),
	array('label'=>'Create StudentIMC', 'url'=>array('create')),
	array('label'=>'View StudentIMC', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StudentIMC', 'url'=>array('admin')),
);

$title = 'Atualizar IMC';
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'disorder' => $disorder, 'studentIdentification' => $studentIdentification,  'title'=>$title)); ?>
