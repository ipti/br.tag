<?php
/** @var $this CourseClassAbilitiesController */
/** @var $model CourseClassAbilities */

$this->breadcrumbs = [
    'Course Class Abilities' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List CourseClassAbilities', 'url' => ['index']],
    ['label' => 'Manage CourseClassAbilities', 'url' => ['admin']],
];
$this->pageTitle = 'TAG - Habilidades';
$title = 'Habilidades';
?>


<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title]); ?>
