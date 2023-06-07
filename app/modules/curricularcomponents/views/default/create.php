<?php
/** @var DefaultController $this DefaultController */
/** @var EdcensoDiscipline $model EdcensoDiscipline */

$this->breadcrumbs=array(
	'Edcenso Disciplines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EdcensoDiscipline', 'url'=>array('index')),
	array('label'=>'Manage EdcensoDiscipline', 'url'=>array('admin')),
);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Adicionar Componente Curricular'));
$title = Yii::t('default', 'Adicionar Componente Curricular');

?>



<div id="mainPage" class="main">
	<?php $this->renderPartial('_form', array('model'=>$model, 'edcenso_base_disciplines' => $edcenso_base_disciplines,  'title' => $title)); ?>
</div>