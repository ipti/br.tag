<?php
/* @var $this CourseClassAbilitiesController */
/* @var $model CourseClassAbilities */

$this->breadcrumbs = [
    'Course Class Abilities' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List CourseClassAbilities', 'url' => ['index']],
    ['label' => 'Create CourseClassAbilities', 'url' => ['create']],
    ['label' => 'View CourseClassAbilities', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage CourseClassAbilities', 'url' => ['admin']],
];
$title = 'Habilidades';
?>

<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title]); ?>
