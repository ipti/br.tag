<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
$classroom = Classroom::model()->findByPk($classroom_id);
$enrollments = $classroom->studentEnrollments;



foreach ($enrollments as $enrollment) {
    $this->renderPartial('StudentFileForm', array('enrollment_id'=>$enrollment->id));
?>
<div style="page-break-after: always;"></div>
<?php
}








?>