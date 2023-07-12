<?php
/** @var FormsController $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));


foreach ($enrollments as $enrollment) {
    $this->renderPartial('StudentFileForm', array('enrollment' => $enrollment, 'school' => $school));
?>
<div style="page-break-after: always;"></div>
<?php
}








?>