<?php
/* @var $this GradeConceptController */
/* @var $model GradeConcept */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cadastrar Conceito'));
$this->breadcrumbs=array(
	'Grade Concepts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GradeConcept', 'url'=>array('index')),
	array('label'=>'Manage GradeConcept', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>