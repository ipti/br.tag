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
?>
<div class="row main">
	<div class="column">
		<h1> <?= $model->description ?></h1>
	</div>
</div>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<script type="text/javascript">
	 var menuUpdate = <?php echo CJSON::encode($model); ?>
</script>
