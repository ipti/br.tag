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
?>
<div class="row main">
	<div class="column">
		<h1>Criar Cardápio</h1>
	</div>
</div>

<?php $this->renderPartial('_form', array('model'=>$model,'stages'=>$stages)); ?>