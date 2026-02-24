<?php
/* @var $this EnrollmentOnlinePreEnrollmentEventOnlineController */
/* @var $model EnrollmentOnlinePreEnrollmentEventOnline */

$this->breadcrumbs = array(
    'Enrollment Online Pre Enrollment Events' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List EnrollmentOnlinePreEnrollmentEventOnline', 'url' => array('index')),
    array('label' => 'Create EnrollmentOnlinePreEnrollmentEventOnline', 'url' => array('create')),
    array('label' => 'View EnrollmentOnlinePreEnrollmentEventOnline', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage EnrollmentOnlinePreEnrollmentEventOnline', 'url' => array('admin')),
);

$title = 'Evento de Pre Matrícula';
$this->pageTitle = 'TAG - Evento de Pré-Matrícula';
?>


<?php $this->renderPartial('_form', array('model' => $model, 'title' => $title)); ?>
