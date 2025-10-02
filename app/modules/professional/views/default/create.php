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
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Add New Professional'));
$title = Yii::t('default', 'Add New Professional');
?>
    <?php
echo $this->renderPartial('_form', [
    'modelProfessional' => $modelProfessional,
    'title' => $title
]);
?> 
</div>