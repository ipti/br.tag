<?php
$this->breadcrumbs=array(
	'Classrooms'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Classroom', 'url'=>array('index')),
	array('label'=>'Create Classroom', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('classroom-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Classrooms</h1>

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
	'id'=>'classroom-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_fk',
		'inep_id',
		'id',
		'name',
		'initial_hour',
		/*
		'initial_minute',
		'final_hour',
		'final_minute',
		'week_days_sunday',
		'week_days_monday',
		'week_days_tuesday',
		'week_days_wednesday',
		'week_days_thursday',
		'week_days_friday',
		'week_days_saturday',
		'assistance_type',
		'mais_educacao_participator',
		'complementary_activity_type_1',
		'complementary_activity_type_2',
		'complementary_activity_type_3',
		'complementary_activity_type_4',
		'complementary_activity_type_5',
		'complementary_activity_type_6',
		'aee_braille_system_education',
		'aee_optical_and_non_optical_resources',
		'aee_mental_processes_development_strategies',
		'aee_mobility_and_orientation_techniques',
		'aee_libras',
		'aee_caa_use_education',
		'aee_curriculum_enrichment_strategy',
		'aee_soroban_use_education',
		'aee_usability_and_functionality_of_computer_accessible_education',
		'aee_teaching_of_Portuguese_language_written_modality',
		'aee_strategy_for_school_environment_autonomy',
		'modality',
		'edcenso_stage_vs_modality_fk',
		'edcenso_professional_education_course_fk',
		'discipline_chemistry',
		'discipline_physics',
		'discipline_mathematics',
		'discipline_biology',
		'discipline_science',
		'discipline_language_portuguese_literature',
		'discipline_foreign_language_english',
		'discipline_foreign_language_spanish',
		'discipline_foreign_language_franch',
		'discipline_foreign_language_other',
		'discipline_arts',
		'discipline_physical_education',
		'discipline_history',
		'discipline_geography',
		'discipline_philosophy',
		'discipline_social_study',
		'discipline_sociology',
		'discipline_informatics',
		'discipline_professional_disciplines',
		'discipline_special_education_and_inclusive_practices',
		'discipline_sociocultural_diversity',
		'discipline_libras',
		'discipline_pedagogical',
		'discipline_religious',
		'discipline_native_language',
		'discipline_others',
		'instructor_situation',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
