<?php
/** @var $this FoodMenuController */
/** @var $model FoodMenu */

$this->breadcrumbs = [
    'Food Menus' => ['index'],
    'Create',
];
$this->setPageTitle('TAG - ' . Yii::t('default', 'Criar Cardápio'));
$this->menu = [
    ['label' => 'List FoodMenu', 'url' => ['index']],
    ['label' => 'Manage FoodMenu', 'url' => ['admin']],
];
?>
<div class="row main">
	<div class="column">
		<h1>Criar Cardápio</h1>
	</div>
</div>

<?php $this->renderPartial('_form', ['model' => $model, 'stages' => $stages]); ?>