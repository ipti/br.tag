<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */

$this->breadcrumbs = [
    'Farmer Registers' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List FarmerRegister', 'url' => ['index']],
    ['label' => 'Manage FarmerRegister', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model, 'modelFarmerFoods' => $modelFarmerFoods]); ?>
</div>
