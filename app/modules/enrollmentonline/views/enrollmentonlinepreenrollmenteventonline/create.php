<?php
/* @var $this EnrollmentOnlinePreEnrollmentEventOnlineController */
/* @var $model EnrollmentOnlinePreEnrollmentEventOnline */

$this->breadcrumbs = array(
    'Enrollment Online Pre Enrollment Events' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List EnrollmentOnlinePreEnrollmentEventOnline', 'url' => array('index')),
    array('label' => 'Manage EnrollmentOnlinePreEnrollmentEventOnline', 'url' => array('admin')),
);

$title = 'Evento de Pre Matrícula';
$this->pageTitle = 'TAG - Evento de Pré-Matrícula';
?>


<?php $this->renderPartial('_form', array('model' => $model, 'title' => $title)); ?>
