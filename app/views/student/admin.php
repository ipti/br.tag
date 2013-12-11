<?php
$this->breadcrumbs=array(
	'Student Identifications'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List StudentIdentification', 'url'=>array('index')),
	array('label'=>'Create StudentIdentification', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('student-identification-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Student Identifications</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'modelStudentIdentification'=>$modelStudentIdentification,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'student-identification-grid',
	'dataProvider'=>$modelStudentIdentification->search(),
	'filter'=>$modelStudentIdentification,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'name',
		'nis',
		/*
		'birthday',
		'sex',
		'color_race',
		'filiation',
		'mother_name',
		'father_name',
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
		'deficiency_type_autism',
		'deficiency_type_aspenger_syndrome',
		'deficiency_type_rett_syndrome',
		'deficiency_type_childhood_disintegrative_disorder',
		'deficiency_type_gifted',
		'resource_aid_lector',
		'resource_aid_transcription',
		'resource_interpreter_guide',
		'resource_interpreter_libras',
		'resource_lip_reading',
		'resource_zoomed_test_16',
		'resource_zoomed_test_20',
		'resource_zoomed_test_24',
		'resource_braille_test',
		'resource_none',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
