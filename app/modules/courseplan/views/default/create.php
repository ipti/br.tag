<?php
/* @var $this DefaultController */
/* @var $model CoursePlan */

$this->breadcrumbs=array(
	'Courseplan' => array('index'),
    'Create',
);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Criar Plano de Aula'));
$this->menu=array(
	array('label'=>'Create CoursePlan', 'url'=>array('index')),
	array('label'=>'List ClassPlan', 'url'=>array('admin')),
    // array('label'=>'List FoodMenu', 'url'=>array('index')),
	// array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
);
?>
<div class="row main">
	<div class="column">
		<h1>Criar Plano de Aula</h1>
	</div>
</div>

<?php $this->renderPartial('_form', array('coursePlan'=>$coursePlan,'stages'=>$stages)); ?>
