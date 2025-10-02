<?php
/* @var $this FoodNoticeController */
/* @var $model FoodNotice */

$this->breadcrumbs = [
    'Food Notices' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List FoodNotice', 'url' => ['index']],
    ['label' => 'Create FoodNotice', 'url' => ['create']],
    ['label' => 'View FoodNotice', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage FoodNotice', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model,  'title' => $model->name]); ?>
</div>
