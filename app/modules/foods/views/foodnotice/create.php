<?php
/* @var $this FoodNoticeController */
/* @var $model FoodNotice */
$this->breadcrumbs=array(
	'Food Notices'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FoodNotice', 'url'=>array('index')),
	array('label'=>'Manage FoodNotice', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', array('model'=>$model, 'title' => 'Cadastrar Edital')); ?>
</div>
