<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Classes',
);

$this->menu=array(
	array('label'=>'Create Classes', 'url'=>array('create')),
	array('label'=>'Manage Classes', 'url'=>array('admin')),
);
?>

<h1>Classes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
