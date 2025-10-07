<?php
/* @var $this StudentAeeRecordController */
/* @var $model StudentAeeRecord */

$this->breadcrumbs = [
    'Student Aee Records' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List StudentAeeRecord', 'url' => ['index']],
    ['label' => 'Create StudentAeeRecord', 'url' => ['create']],
    ['label' => 'View StudentAeeRecord', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage StudentAeeRecord', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
