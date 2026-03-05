<?php
/* @var $this StudentIMCController */
/* @var $model StudentIMC */

$this->breadcrumbs = [
    'Student Imcs' => ['index'],
    'Create',
];

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cradastrar IMC'));

$this->menu = [
    ['label' => 'List StudentIMC', 'url' => ['index']],
    ['label' => 'Manage StudentIMC', 'url' => ['admin']],
];

$title = 'Cradastrar IMC';
?>

<?php $this->renderPartial('_form', ['model' => $model, 'disorder' => $disorder, 'studentIdentification' => $studentIdentification, 'title' => $title]); ?>
