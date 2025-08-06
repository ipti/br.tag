<?php
/** @var $this EnrollmentOnlineStudentIdentificationController */
/** @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Enrollment Online Student Identifications',
];

$this->menu = [
    ['label' => 'Create EnrollmentOnlineStudentIdentification', 'url' => ['create']],
    ['label' => 'Manage EnrollmentOnlineStudentIdentification', 'url' => ['admin']],
];
?>

<h1>Enrollment Online Student Identifications</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
