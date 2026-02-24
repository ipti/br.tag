<?php
/* @var $this ProfessionalController */
/* @var $model Professional */

$this->breadcrumbs = [
    'Professionals' => ['index'],
    'Create',
];

?>

<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Professional'));
$title = Yii::t('default', 'Update Professional');
$moduleUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.professional.resources'));
Yii::app()->clientScript->registerScriptFile($moduleUrl . '/common/js/allocation.js', CClientScript::POS_END);
?>
    <?php
echo $this->renderPartial('_form', [
    'modelProfessional'     => $modelProfessional,
    'modelAttendance'       => $modelAttendance,
    'attendanceProvider'    => $attendanceProvider,
    'allocationProvider'    => $allocationProvider,
    'allocationModel'       => $allocationModel,
    'schools'               => $schools,
    'totalAttendancesMonth' => $totalAttendancesMonth,
    'totalAllocations'      => $totalAllocations,
    'title'                 => $title
]);
?> 
</div>