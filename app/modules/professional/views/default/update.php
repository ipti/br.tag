<?php
/** @var $this ProfessionalController */
/** @var $model Professional */

$this->breadcrumbs = [
    'Professionals' => ['index'],
    'Create',
];

?>

<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Professional'));
$title = Yii::t('default', 'Update Professional');
?>
    <?php
echo $this->renderPartial('_form', [
    'modelProfessional' => $modelProfessional,
    'modelAttendance' => $modelAttendance,
    'modelAttendances' => $modelAttendances,
    'title' => $title
]);
?> 
</div>