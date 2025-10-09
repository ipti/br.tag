<?php
/* @var $this StudentIMCController */
/* @var $model StudentIMC */

$this->breadcrumbs=array(
	'Student Imcs'=>array('index'),
	'Create',
);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cradastrar IMC'));

$this->menu=array(
	array('label'=>'List StudentIMC', 'url'=>array('index')),
	array('label'=>'Manage StudentIMC', 'url'=>array('admin')),
);

$title = 'Cradastrar IMC';
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'disorder' => $disorder, 'title'=>$title)); ?>
