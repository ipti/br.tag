<?php
/* @var $this InstanceConfigController */
/* @var $model InstanceConfig */

$this->breadcrumbs=array(
	'Instance Configs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List InstanceConfig', 'url'=>array('index')),
	array('label'=>'Create InstanceConfig', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#instance-config-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Instance Configs</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>


</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'instance-config-grid',
    'enablePagination' => false,
    'enableSorting' => false,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'launch_grades',
		'sedsp_sync',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
