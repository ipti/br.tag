<?php
/* @var $this GradeConceptController */
/* @var $model GradeConcept */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cadastrar Conceito'));
$this->breadcrumbs = [
    'Grade Concepts' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List GradeConcept', 'url' => ['index']],
    ['label' => 'Manage GradeConcept', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
	<?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>