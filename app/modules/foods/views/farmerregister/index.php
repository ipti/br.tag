<?php
/* @var $this FarmerRegisterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Farmer Registers',
);

$this->menu=array(
	array('label'=>'Create FarmerRegister', 'url'=>array('create')),
	array('label'=>'Manage FarmerRegister', 'url'=>array('admin')),
);
?>

<h1>Farmer Registers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
