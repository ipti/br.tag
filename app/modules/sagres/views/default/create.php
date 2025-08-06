<?php
/** @var $this ProvisionAcountsController */
/** @var $model ProvisionAcounts */

$this->breadcrumbs = [
    'Professionals' => ['index'],
    'Create',
];

?>

<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Add New Management Unit'));
$title = Yii::t('default', 'Add New Management Unit');
?>
    <?php
echo $this->renderPartial('_form', [
    'model' => $model,
    'title' => $title
]);
?> 
</div>