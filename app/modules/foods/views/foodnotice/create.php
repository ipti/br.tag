<?php
/** @var $this FoodNoticeController */
/** @var $model FoodNotice */
$this->breadcrumbs = [
    'Food Notices' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List FoodNotice', 'url' => ['index']],
    ['label' => 'Manage FoodNotice', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model, 'title' => 'Cadastrar Edital']); ?>
</div>
