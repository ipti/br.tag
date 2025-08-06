<?php
/** @var $this FoodMenuController */
/** @var $model FoodMenu */
/** @var FoodMenu $model */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Atualizar Cardápio'));

$this->breadcrumbs = [
    'Food Menus' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List FoodMenu', 'url' => ['index']],
    ['label' => 'Create FoodMenu', 'url' => ['create']],
    ['label' => 'View FoodMenu', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage FoodMenu', 'url' => ['admin']],
];
$title = $model->description
?>


<?php $this->renderPartial('_form', ['model' => $model, 'title' => $title, 'stages' => $stages]); ?>
<script type="text/javascript">
	 var menuUpdate = <?php echo CJSON::encode($model); ?>;
	 var mealTypeList = <?php echo CJSON::encode($mealTypeList); ?>;
	 var tacoFoodList = <?php echo CJSON::encode($tacoFoodsList); ?>;
	 var foodMeasurementList = <?php echo CJSON::encode($foodMeasurementList); ?>;
</script>
