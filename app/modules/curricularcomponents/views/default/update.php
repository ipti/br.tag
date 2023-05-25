<?php
/** @var DefaultController $this DefaultController */
/** @var EdcensoDiscipline $model EdcensoDiscipline */

$this->breadcrumbs=array(
	'Edcenso Disciplines'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->setPageTitle('TAG - Atualizar Componente Curricular');
$title = Yii::t('default', 'Atualizar Componente: '. $model->name);

?>

<div id="mainPage" class="main">
	<?php $this->renderPartial('_form', array(
		'model'=>$model, 
		'edcenso_base_disciplines' => $edcenso_base_disciplines,  
		'title' => $title)); ?>
</div>