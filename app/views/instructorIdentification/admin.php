<?php
$this->breadcrumbs=array(
	'Instructor Identifications'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List InstructorIdentification', 'url'=>array('index')),
	array('label'=>'Create InstructorIdentification', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('instructor-identification-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Instructor Identifications</h1>

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
	'id'=>'instructor-identification-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'name',
		'email',
		/*
		'nis',
		'birthday_date',
		'sex',
		'color_race',
		'mother_name',
		'nationality',
		'edcenso_nation_fk',
		'edcenso_uf_fk',
		'edcenso_city_fk',
		'deficiency',
		'deficiency_type_blindness',
		'deficiency_type_low_vision',
		'deficiency_type_deafness',
		'deficiency_type_disability_hearing',
		'deficiency_type_deafblindness',
		'deficiency_type_phisical_disability',
		'deficiency_type_intelectual_disability',
		'deficiency_type_multiple_disabilities',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
