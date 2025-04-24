<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/** @var FoodMenu $model */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Atualizar Cardápio'));

$this->breadcrumbs=array(
	'Food Menus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FoodMenu', 'url'=>array('index')),
	array('label'=>'Create FoodMenu', 'url'=>array('create')),
	array('label'=>'View FoodMenu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
);
$title = $model->description
?>


<?php $this->renderPartial('_form', array('model'=>$model, 'title' => $title, 'stages' => $stages)); ?>
<script type="text/javascript">
	 var menuUpdate = <?php echo CJSON::encode($model); ?>;
	 var mealTypeList = <?php echo CJSON::encode($mealTypeList); ?>;
	 var tacoFoodList = <?php echo CJSON::encode($tacoFoodsList); ?>;
	 var foodMeasurementList = <?php echo CJSON::encode($foodMeasurementList); ?>;
</script>
