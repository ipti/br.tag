<?php
$this->breadcrumbs=array(
	'Instructor Variable Datas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List InstructorVariableData', 'url'=>array('index')),
	array('label'=>'Create InstructorVariableData', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('instructor-variable-data-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Instructor Variable Datas</h1>

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
	'id'=>'instructor-variable-data-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'scholarity',
		'high_education_situation_1',
		/*
		'high_education_formation_1',
		'high_education_course_code_1_fk',
		'high_education_initial_year_1',
		'high_education_final_year_1',
		'high_education_institution_type_1',
		'high_education_institution_code_1_fk',
		'high_education_situation_2',
		'high_education_formation_2',
		'high_education_course_code_2_fk',
		'high_education_initial_year_2',
		'high_education_final_year_2',
		'high_education_institution_type_2',
		'high_education_institution_code_2_fk',
		'high_education_situation_3',
		'high_education_formation_3',
		'high_education_course_code_3_fk',
		'high_education_initial_year_3',
		'high_education_final_year_3',
		'high_education_institution_type_3',
		'high_education_institution_code_3_fk',
		'post_graduation_specialization',
		'post_graduation_master',
		'post_graduation_doctorate',
		'post_graduation_none',
		'other_courses_nursery',
		'other_courses_pre_school',
		'other_courses_basic_education_initial_years',
		'other_courses_basic_education_final_years',
		'other_courses_high_school',
		'other_courses_education_of_youth_and_adults',
		'other_courses_special_education',
		'other_courses_native_education',
		'other_courses_field_education',
		'other_courses_environment_education',
		'other_courses_human_rights_education',
		'other_courses_sexual_education',
		'other_courses_child_and_teenage_rights',
		'other_courses_ethnic_education',
		'other_courses_other',
		'other_courses_none',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
