<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/** @var FoodMenu $model */

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


<?php $this->renderPartial('_form', array('model'=>$model, 'title' => $title)); ?>
<script type="text/javascript">
	 var menuUpdate = <?php echo CJSON::encode($model); ?>
</script>
