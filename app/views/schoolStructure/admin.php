<?php
$this->breadcrumbs=array(
	'School Structures'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SchoolStructure', 'url'=>array('index')),
	array('label'=>'Create SchoolStructure', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('school-structure-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage School Structures</h1>

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
	'id'=>'school-structure-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'manager_cpf',
		'manager_name',
		'manager_role',
		'manager_email',
		/*
		'operation_location_building',
		'operation_location_temple',
		'operation_location_businness_room',
		'operation_location_instructor_house',
		'operation_location_other_school_room',
		'operation_location_barracks',
		'operation_location_socioeducative_unity',
		'operation_location_prison_unity',
		'operation_location_other',
		'building_occupation_situation',
		'shared_building_with_school',
		'shared_school_inep_id_1',
		'shared_school_inep_id_2',
		'shared_school_inep_id_3',
		'shared_school_inep_id_4',
		'shared_school_inep_id_5',
		'shared_school_inep_id_6',
		'consumed_water_type',
		'water_supply_public',
		'water_supply_artesian_well',
		'water_supply_well',
		'water_supply_river',
		'water_supply_inexistent',
		'energy_supply_public',
		'energy_supply_generator',
		'energy_supply_other',
		'energy_supply_inexistent',
		'sewage_public',
		'sewage_fossa',
		'sewage_inexistent',
		'garbage_destination_collect',
		'garbage_destination_burn',
		'garbage_destination_throw_away',
		'garbage_destination_recycle',
		'garbage_destination_bury',
		'garbage_destination_other',
		'dependencies_principal_room',
		'dependencies_instructors_room',
		'dependencies_secretary_room',
		'dependencies_info_lab',
		'dependencies_science_lab',
		'dependencies_aee_room',
		'dependencies_indoor_sports_court',
		'dependencies_outdoor_sports_court',
		'dependencies_kitchen',
		'dependencies_library',
		'dependencies_reading_room',
		'dependencies_playground',
		'dependencies_nursery',
		'dependencies_outside_bathroom',
		'dependencies_inside_bathroom',
		'dependencies_child_bathroom',
		'dependencies_prysical_disability_bathroom',
		'dependencies_physical_disability_support',
		'dependencies_bathroom_with_shower',
		'dependencies_refectory',
		'dependencies_storeroom',
		'dependencies_warehouse',
		'dependencies_auditorium',
		'dependencies_covered_patio',
		'dependencies_uncovered_patio',
		'dependencies_student_accomodation',
		'dependencies_instructor_accomodation',
		'dependencies_green_area',
		'dependencies_laundry',
		'dependencies_none',
		'classroom_count',
		'used_classroom_count',
		'equipments_tv',
		'equipments_vcr',
		'equipments_dvd',
		'equipments_satellite_dish',
		'equipments_copier',
		'equipments_overhead_projector',
		'equipments_printer',
		'equipments_stereo_system',
		'equipments_data_show',
		'equipments_fax',
		'equipments_camera',
		'equipments_computer',
		'administrative_computers_count',
		'student_computers_count',
		'internet_access',
		'bandwidth',
		'employees_count',
		'feeding',
		'aee',
		'complementary_activities',
		'modalities_regular',
		'modalities_especial',
		'modalities_eja',
		'stage_regular_education_creche',
		'stage_regular_education_preschool',
		'stage_regular_education_fundamental_eigth_years',
		'stage_regular_education_fundamental_nine_years',
		'stage_regular_education_high_school',
		'stage_regular_education_high_school_integrated',
		'stage_regular_education_high_school_normal_mastership',
		'stage_regular_education_high_school_preofessional_education',
		'stage_special_education_creche',
		'stage_special_education_preschool',
		'stage_special_education_fundamental_eigth_years',
		'stage_special_education_fundamental_nine_years',
		'stage_special_education_high_school',
		'stage_special_education_high_school_integrated',
		'stage_special_education_high_school_normal_mastership',
		'stage_special_education_high_school_professional_education',
		'stage_special_education_eja_fundamental_education',
		'stage_special_education_eja_high_school_education',
		'stage_education_eja_fundamental_education',
		'stage_education_eja_fundamental_education_projovem',
		'stage_education_eja_high_school_education',
		'basic_education_cycle_organized',
		'different_location',
		'sociocultural_didactic_material_none',
		'sociocultural_didactic_material_quilombola',
		'sociocultural_didactic_material_native',
		'native_education',
		'native_education_language_native',
		'native_education_language_portuguese',
		'edcenso_native_languages_fk',
		'brazil_literate',
		'open_weekend',
		'pedagogical_formation_by_alternance',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
