<?php
/** @var $this DefaultController */
/** @var $model CoursePlan */

$this->breadcrumbs = [
    'Courseplan' => ['index'],
    'Update',
];

$this->setPageTitle('TAG - ' . Yii::t('default', 'Atualizar Plano de Aula'));
$this->menu = [
    ['label' => 'Create CoursePlan', 'url' => ['index']],
    ['label' => 'List ClassPlan', 'url' => ['admin']],
];

$title = $model->description
?>
<div class="row main">
	<div class="column">
		<h1>Atualizar Plano de Aula</h1>
	</div>
</div>

<?php $this->renderPartial('_form', ['coursePlan' => $coursePlan, 'title' => $title, 'stages' => $stages, 'resources' => $resources, ]); ?>
