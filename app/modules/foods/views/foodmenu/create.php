<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */

$this->breadcrumbs = [
    'Food Menus' => ['index'],
    'Create',
];
$this->setPageTitle('TAG - ' . Yii::t('default', 'Criar Cardápio'));
$this->menu = [
    ['label' => 'List FoodMenu', 'url' => ['index']],
    ['label' => 'Manage FoodMenu', 'url' => ['admin']],
];
$title = 'Criar Cardápio';
?>



<?php $this->renderPartial('_form', ['model' => $model, 'stages' => $stages, 'title' => $title]); ?>

<script type="text/javascript">
	 var mealTypeList = <?php echo CJSON::encode($mealTypeList); ?>;
	 var tacoFoodList = <?php echo CJSON::encode($tacoFoodsList); ?>;
	 var foodMeasurementList = <?php echo CJSON::encode($foodMeasurementList); ?>;
</script>
