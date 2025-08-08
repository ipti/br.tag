<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */

$this->breadcrumbs = [
    'Farmer Registers' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List FarmerRegister', 'url' => ['index']],
    ['label' => 'Create FarmerRegister', 'url' => ['create']],
    ['label' => 'View FarmerRegister', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage FarmerRegister', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model, 'modelFarmerFoods' => $modelFarmerFoods]); ?>
</div>
