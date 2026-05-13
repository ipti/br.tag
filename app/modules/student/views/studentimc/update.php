<?php
/* @var $this StudentIMCController */
/* @var $model StudentIMC */

$this->breadcrumbs = [
    'Student Imcs' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];
$this->setPageTitle('TAG - ' . Yii::t('default', 'Atualizar IMC'));

$this->menu = [
    ['label' => 'List StudentIMC', 'url' => ['index']],
    ['label' => 'Create StudentIMC', 'url' => ['create']],
    ['label' => 'View StudentIMC', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage StudentIMC', 'url' => ['admin']],
];

$title = 'Atualizar IMC';
?>

<?php $this->renderPartial('_form', ['model' => $model, 'disorder' => $disorder, 'studentIdentification' => $studentIdentification,  'title' => $title]); ?>
