<?php
/* @var $this EnrollmentOnlinePreEnrollmentEventOnlineController */
/* @var $model EnrollmentOnlinePreEnrollmentEventOnline */

$this->breadcrumbs = [
    'Enrollment Online Pre Enrollment Events' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List EnrollmentOnlinePreEnrollmentEventOnline', 'url' => ['index']],
    ['label' => 'Manage EnrollmentOnlinePreEnrollmentEventOnline', 'url' => ['admin']],
];

$title = 'Evento de Pre Matrícula';
$this->pageTitle = 'TAG - Evento de Pré-Matrícula';
?>


<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title]); ?>
