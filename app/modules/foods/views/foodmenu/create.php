<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */

$this->breadcrumbs=array(
	'Food Menus'=>array('index'),
	'Create',
);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Criar Cardápio'));
$this->menu=array(
	array('label'=>'List FoodMenu', 'url'=>array('index')),
	array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
);
$title = "Criar Cardápio";
?>



<?php $this->renderPartial('_form', array('model'=>$model,'stages'=>$stages, 'title' => $title)); ?>

<script type="text/javascript">
	 var mealTypeList = <?php echo CJSON::encode($mealTypeList); ?>;
	 var tacoFoodList = <?php echo CJSON::encode($tacoFoodsList); ?>;
	 var foodMeasurementList = <?php echo CJSON::encode($foodMeasurementList); ?>;
</script>
