<?php
/* @var $this EnrollmentOnlinePreEnrollmentEventOnlineController */
/* @var $model EnrollmentOnlinePreEnrollmentEventOnline */

$this->breadcrumbs = [
    'Enrollment Online Pre Enrollment Events' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List EnrollmentOnlinePreEnrollmentEventOnline', 'url' => ['index']],
    ['label' => 'Create EnrollmentOnlinePreEnrollmentEventOnline', 'url' => ['create']],
    ['label' => 'View EnrollmentOnlinePreEnrollmentEventOnline', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage EnrollmentOnlinePreEnrollmentEventOnline', 'url' => ['admin']],
];

$title = 'Evento de Pre Matrícula';
$this->pageTitle = 'TAG - Evento de Pré-Matrícula';
?>


<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title]); ?>
