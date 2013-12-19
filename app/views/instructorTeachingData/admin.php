<?php
$this->breadcrumbs=array(
	'Instructor Teaching Datas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List InstructorTeachingData', 'url'=>array('index')),
	array('label'=>'Create InstructorTeachingData', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('instructor-teaching-data-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Instructor Teaching Datas</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'instructor-teaching-data-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'classroom_inep_id',
		'classroom_id_fk',
		/*
		'role',
		'contract_type',
		'discipline_1_fk',
		'discipline_2_fk',
		'discipline_3_fk',
		'discipline_4_fk',
		'discipline_5_fk',
		'discipline_6_fk',
		'discipline_7_fk',
		'discipline_8_fk',
		'discipline_9_fk',
		'discipline_10_fk',
		'discipline_11_fk',
		'discipline_12_fk',
		'discipline_13_fk',
		'instructor_fk',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
