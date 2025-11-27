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
?>

<h1>Update EnrollmentOnlineStudentIdentification <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>
