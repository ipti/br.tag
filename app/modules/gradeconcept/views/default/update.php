<?php
/* @var $this GradeConceptController */
/* @var $model GradeConcept */

$this->breadcrumbs = [
    'Grade Concepts' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List GradeConcept', 'url' => ['index']],
    ['label' => 'Create GradeConcept', 'url' => ['create']],
    ['label' => 'View GradeConcept', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage GradeConcept', 'url' => ['admin']],
];
?>
<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
