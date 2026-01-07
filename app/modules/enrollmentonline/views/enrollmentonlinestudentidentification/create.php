<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $model OnlineEnrollmentStudentIdentification */

$this->breadcrumbs = [
    'Online Enrollment Student Identifications' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List OnlineEnrollmentStudentIdentification', 'url' => ['index']],
    ['label' => 'Manage OnlineEnrollmentStudentIdentification', 'url' => ['admin']],
];

$title = 'Pre Matrícula';
$this->pageTitle = 'TAG - Pré-Matrícula';
?>

<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title, 'isRejected' => null]); ?>
