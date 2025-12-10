<?php
/* @var $this EnrollmentOnlineStudentIdentificationController */
/* @var $model EnrollmentOnlineStudentIdentification */

$this->breadcrumbs = [
    'Enrollment Online Student Identifications' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List EnrollmentOnlineStudentIdentification', 'url' => ['index']],
    ['label' => 'Create EnrollmentOnlineStudentIdentification', 'url' => ['create']],
    ['label' => 'View EnrollmentOnlineStudentIdentification', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage EnrollmentOnlineStudentIdentification', 'url' => ['admin']],
];

$title = 'Pre Matrícula';
$this->pageTitle = 'TAG - Pré-Matrícula';
?>


<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title, 'studentSolicitations' => $studentSolicitations, 'schools' => $schools, 'isRejected' => $isRejected  ]); ?>
