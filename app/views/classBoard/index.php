<?php
/* @var $this ClassBoardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Class Boards',
);

$this->menu=array(
	array('label'=>'Create ClassBoard', 'url'=>array('create')),
	array('label'=>'Manage ClassBoard', 'url'=>array('admin')),
);
?>

<h1>Class Boards</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
