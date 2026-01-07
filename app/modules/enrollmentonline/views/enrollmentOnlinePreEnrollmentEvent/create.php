<?php
/* @var $this EnrollmentOnlinePreEnrollmentEventController */
/* @var $model EnrollmentOnlinePreEnrollmentEvent */

$this->breadcrumbs = array(
    'Enrollment Online Pre Enrollment Events' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List EnrollmentOnlinePreEnrollmentEvent', 'url' => array('index')),
    array('label' => 'Manage EnrollmentOnlinePreEnrollmentEvent', 'url' => array('admin')),
);

$title = 'Evento de Pre Matrícula';
$this->pageTitle = 'TAG - Evento de Pré-Matrícula';
?>


<?php $this->renderPartial('_form', array('model' => $model, 'title' => $title)); ?>
