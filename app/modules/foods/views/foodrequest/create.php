<?php
/** @var $this FoodRequestController */
/** @var $model FoodRequest */

$this->breadcrumbs = [
    'Food Requests' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List FoodRequest', 'url' => ['index']],
    ['label' => 'Manage FoodRequest', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial(
    '_form',
    ['model' => $model,
        'requestFarmerModel' => $requestFarmerModel,
        'requestSchoolModel' => $requestSchoolModel,
        'requestItemModel' => $requestItemModel]
); ?>
</div>
