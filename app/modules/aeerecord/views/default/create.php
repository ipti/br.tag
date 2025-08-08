<?php
/* @var $this StudentAeeRecordController */
/* @var $model StudentAeeRecord */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cadastrar Ficha AEE'));
$this->breadcrumbs = [
    'Student Aee Records' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List StudentAeeRecord', 'url' => ['index']],
    ['label' => 'Manage StudentAeeRecord', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
