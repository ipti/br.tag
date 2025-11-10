<?php
/* @var $this DefaultController */
/* @var $model CoursePlan */

$this->breadcrumbs = [
    'Courseplan' => ['index'],
    'Create',
];

$this->setPageTitle('TAG - ' . Yii::t('default', 'Criar Plano de Aula'));
$this->menu = [
    ['label' => 'Create CoursePlan', 'url' => ['index']],
    ['label' => 'List ClassPlan', 'url' => ['admin']],
];
?>
<div class="row main">
	<div class="column">
		<h1>Criar Plano de Aula</h1>
	</div>
</div>

<?php $this->renderPartial('_form', ['coursePlan' => $coursePlan, 'stages' => $stages, 'resources' => $resources, ]); ?>
