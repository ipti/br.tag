<?php
/* @var $this GradeConceptController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Grade Concepts',
);

$this->menu=array(
	array('label'=>'Create GradeConcept', 'url'=>array('create')),
	array('label'=>'Manage GradeConcept', 'url'=>array('admin')),
);
?>

<h1>Grade Concepts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
