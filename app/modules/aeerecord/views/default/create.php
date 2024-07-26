<?php
/* @var $this StudentAeeRecordController */
/* @var $model StudentAeeRecord */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cadastrar Ficha AEE'));
$this->breadcrumbs=array(
	'Student Aee Records'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StudentAeeRecord', 'url'=>array('index')),
	array('label'=>'Manage StudentAeeRecord', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
