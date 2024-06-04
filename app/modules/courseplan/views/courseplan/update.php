<?php
/* @var $this DefaultController */
/* @var $model CoursePlan */

$this->breadcrumbs=array(
	'Courseplan' => array('index'),
    'Update',
);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Atualizar Plano de Aula'));
$this->menu=array(
	array('label'=>'Create CoursePlan', 'url'=>array('index')),
	array('label'=>'List ClassPlan', 'url'=>array('admin')),
);

$title = $model->description
?>
<div class="row main">
	<div class="column">
		<h1>Atualizar Plano de Aula</h1>
	</div>
</div>

<?php $this->renderPartial('_form', array('coursePlan'=>$coursePlan, 'title' => $title,'stages'=>$stages,'resources' => $resources,)); ?>
