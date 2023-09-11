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
<div class="row main">
	<div class="column">
		<h1>Create FoodMenu</h1>
	</div>
</div>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>