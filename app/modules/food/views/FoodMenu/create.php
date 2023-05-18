<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
$this->breadcrumbs=array(
	'Food Menus'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FoodMenu', 'url'=>array('index')),
	array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
);
?>

<h1><?php  echo Yii::t('default', 'create_menu');?></h1>

<?php $this->renderPartial('_form', array('modelFoodMenu'=>$modelFoodMenu)); ?>