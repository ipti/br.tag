<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'register_type'); ?>
		<?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'school_inep_id_fk'); ?>
		<?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'manager_cpf'); ?>
		<?php echo $form->textField($model,'manager_cpf',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'manager_name'); ?>
		<?php echo $form->textField($model,'manager_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'manager_role'); ?>
		<?php echo $form->textField($model,'manager_role'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'manager_email'); ?>
		<?php echo $form->textField($model,'manager_email',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_building'); ?>
		<?php echo $form->textField($model,'operation_location_building'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_temple'); ?>
		<?php echo $form->textField($model,'operation_location_temple'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_businness_room'); ?>
		<?php echo $form->textField($model,'operation_location_businness_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_instructor_house'); ?>
		<?php echo $form->textField($model,'operation_location_instructor_house'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_other_school_room'); ?>
		<?php echo $form->textField($model,'operation_location_other_school_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_barracks'); ?>
		<?php echo $form->textField($model,'operation_location_barracks'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_socioeducative_unity'); ?>
		<?php echo $form->textField($model,'operation_location_socioeducative_unity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_prison_unity'); ?>
		<?php echo $form->textField($model,'operation_location_prison_unity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operation_location_other'); ?>
		<?php echo $form->textField($model,'operation_location_other'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'building_occupation_situation'); ?>
		<?php echo $form->textField($model,'building_occupation_situation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_building_with_school'); ?>
		<?php echo $form->textField($model,'shared_building_with_school'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_school_inep_id_1'); ?>
		<?php echo $form->textField($model,'shared_school_inep_id_1',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_school_inep_id_2'); ?>
		<?php echo $form->textField($model,'shared_school_inep_id_2',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_school_inep_id_3'); ?>
		<?php echo $form->textField($model,'shared_school_inep_id_3',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_school_inep_id_4'); ?>
		<?php echo $form->textField($model,'shared_school_inep_id_4',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_school_inep_id_5'); ?>
		<?php echo $form->textField($model,'shared_school_inep_id_5',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shared_school_inep_id_6'); ?>
		<?php echo $form->textField($model,'shared_school_inep_id_6',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'consumed_water_type'); ?>
		<?php echo $form->textField($model,'consumed_water_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'water_supply_public'); ?>
		<?php echo $form->textField($model,'water_supply_public'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'water_supply_artesian_well'); ?>
		<?php echo $form->textField($model,'water_supply_artesian_well'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'water_supply_well'); ?>
		<?php echo $form->textField($model,'water_supply_well'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'water_supply_river'); ?>
		<?php echo $form->textField($model,'water_supply_river'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'water_supply_inexistent'); ?>
		<?php echo $form->textField($model,'water_supply_inexistent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'energy_supply_public'); ?>
		<?php echo $form->textField($model,'energy_supply_public'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'energy_supply_generator'); ?>
		<?php echo $form->textField($model,'energy_supply_generator'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'energy_supply_other'); ?>
		<?php echo $form->textField($model,'energy_supply_other'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'energy_supply_inexistent'); ?>
		<?php echo $form->textField($model,'energy_supply_inexistent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sewage_public'); ?>
		<?php echo $form->textField($model,'sewage_public'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sewage_fossa'); ?>
		<?php echo $form->textField($model,'sewage_fossa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sewage_inexistent'); ?>
		<?php echo $form->textField($model,'sewage_inexistent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'garbage_destination_collect'); ?>
		<?php echo $form->textField($model,'garbage_destination_collect'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'garbage_destination_burn'); ?>
		<?php echo $form->textField($model,'garbage_destination_burn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'garbage_destination_throw_away'); ?>
		<?php echo $form->textField($model,'garbage_destination_throw_away'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'garbage_destination_recycle'); ?>
		<?php echo $form->textField($model,'garbage_destination_recycle'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'garbage_destination_bury'); ?>
		<?php echo $form->textField($model,'garbage_destination_bury'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'garbage_destination_other'); ?>
		<?php echo $form->textField($model,'garbage_destination_other'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_principal_room'); ?>
		<?php echo $form->textField($model,'dependencies_principal_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_instructors_room'); ?>
		<?php echo $form->textField($model,'dependencies_instructors_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_secretary_room'); ?>
		<?php echo $form->textField($model,'dependencies_secretary_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_info_lab'); ?>
		<?php echo $form->textField($model,'dependencies_info_lab'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_science_lab'); ?>
		<?php echo $form->textField($model,'dependencies_science_lab'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_aee_room'); ?>
		<?php echo $form->textField($model,'dependencies_aee_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_indoor_sports_court'); ?>
		<?php echo $form->textField($model,'dependencies_indoor_sports_court'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_outdoor_sports_court'); ?>
		<?php echo $form->textField($model,'dependencies_outdoor_sports_court'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_kitchen'); ?>
		<?php echo $form->textField($model,'dependencies_kitchen'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_library'); ?>
		<?php echo $form->textField($model,'dependencies_library'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_reading_room'); ?>
		<?php echo $form->textField($model,'dependencies_reading_room'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_playground'); ?>
		<?php echo $form->textField($model,'dependencies_playground'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_nursery'); ?>
		<?php echo $form->textField($model,'dependencies_nursery'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_outside_bathroom'); ?>
		<?php echo $form->textField($model,'dependencies_outside_bathroom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_inside_bathroom'); ?>
		<?php echo $form->textField($model,'dependencies_inside_bathroom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_child_bathroom'); ?>
		<?php echo $form->textField($model,'dependencies_child_bathroom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_prysical_disability_bathroom'); ?>
		<?php echo $form->textField($model,'dependencies_prysical_disability_bathroom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_physical_disability_support'); ?>
		<?php echo $form->textField($model,'dependencies_physical_disability_support'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_bathroom_with_shower'); ?>
		<?php echo $form->textField($model,'dependencies_bathroom_with_shower'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_refectory'); ?>
		<?php echo $form->textField($model,'dependencies_refectory'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_storeroom'); ?>
		<?php echo $form->textField($model,'dependencies_storeroom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_warehouse'); ?>
		<?php echo $form->textField($model,'dependencies_warehouse'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_auditorium'); ?>
		<?php echo $form->textField($model,'dependencies_auditorium'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_covered_patio'); ?>
		<?php echo $form->textField($model,'dependencies_covered_patio'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_uncovered_patio'); ?>
		<?php echo $form->textField($model,'dependencies_uncovered_patio'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_student_accomodation'); ?>
		<?php echo $form->textField($model,'dependencies_student_accomodation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_instructor_accomodation'); ?>
		<?php echo $form->textField($model,'dependencies_instructor_accomodation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_green_area'); ?>
		<?php echo $form->textField($model,'dependencies_green_area'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_laundry'); ?>
		<?php echo $form->textField($model,'dependencies_laundry'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dependencies_none'); ?>
		<?php echo $form->textField($model,'dependencies_none'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classroom_count'); ?>
		<?php echo $form->textField($model,'classroom_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'used_classroom_count'); ?>
		<?php echo $form->textField($model,'used_classroom_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_tv'); ?>
		<?php echo $form->textField($model,'equipments_tv'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_vcr'); ?>
		<?php echo $form->textField($model,'equipments_vcr'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_dvd'); ?>
		<?php echo $form->textField($model,'equipments_dvd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_satellite_dish'); ?>
		<?php echo $form->textField($model,'equipments_satellite_dish'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_copier'); ?>
		<?php echo $form->textField($model,'equipments_copier'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_overhead_projector'); ?>
		<?php echo $form->textField($model,'equipments_overhead_projector'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_printer'); ?>
		<?php echo $form->textField($model,'equipments_printer'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_stereo_system'); ?>
		<?php echo $form->textField($model,'equipments_stereo_system'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_data_show'); ?>
		<?php echo $form->textField($model,'equipments_data_show'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_fax'); ?>
		<?php echo $form->textField($model,'equipments_fax'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_camera'); ?>
		<?php echo $form->textField($model,'equipments_camera'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipments_computer'); ?>
		<?php echo $form->textField($model,'equipments_computer'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'administrative_computers_count'); ?>
		<?php echo $form->textField($model,'administrative_computers_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'student_computers_count'); ?>
		<?php echo $form->textField($model,'student_computers_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'internet_access'); ?>
		<?php echo $form->textField($model,'internet_access'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bandwidth'); ?>
		<?php echo $form->textField($model,'bandwidth'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employees_count'); ?>
		<?php echo $form->textField($model,'employees_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'feeding'); ?>
		<?php echo $form->textField($model,'feeding'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aee'); ?>
		<?php echo $form->textField($model,'aee'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'complementary_activities'); ?>
		<?php echo $form->textField($model,'complementary_activities'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modalities_regular'); ?>
		<?php echo $form->textField($model,'modalities_regular'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modalities_especial'); ?>
		<?php echo $form->textField($model,'modalities_especial'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modalities_eja'); ?>
		<?php echo $form->textField($model,'modalities_eja'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_creche'); ?>
		<?php echo $form->textField($model,'stage_regular_education_creche'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_preschool'); ?>
		<?php echo $form->textField($model,'stage_regular_education_preschool'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_fundamental_eigth_years'); ?>
		<?php echo $form->textField($model,'stage_regular_education_fundamental_eigth_years'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_fundamental_nine_years'); ?>
		<?php echo $form->textField($model,'stage_regular_education_fundamental_nine_years'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_high_school'); ?>
		<?php echo $form->textField($model,'stage_regular_education_high_school'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_high_school_integrated'); ?>
		<?php echo $form->textField($model,'stage_regular_education_high_school_integrated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_high_school_normal_mastership'); ?>
		<?php echo $form->textField($model,'stage_regular_education_high_school_normal_mastership'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_regular_education_high_school_preofessional_education'); ?>
		<?php echo $form->textField($model,'stage_regular_education_high_school_preofessional_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_creche'); ?>
		<?php echo $form->textField($model,'stage_special_education_creche'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_preschool'); ?>
		<?php echo $form->textField($model,'stage_special_education_preschool'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_fundamental_eigth_years'); ?>
		<?php echo $form->textField($model,'stage_special_education_fundamental_eigth_years'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_fundamental_nine_years'); ?>
		<?php echo $form->textField($model,'stage_special_education_fundamental_nine_years'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_high_school'); ?>
		<?php echo $form->textField($model,'stage_special_education_high_school'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_high_school_integrated'); ?>
		<?php echo $form->textField($model,'stage_special_education_high_school_integrated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_high_school_normal_mastership'); ?>
		<?php echo $form->textField($model,'stage_special_education_high_school_normal_mastership'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_high_school_professional_education'); ?>
		<?php echo $form->textField($model,'stage_special_education_high_school_professional_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_eja_fundamental_education'); ?>
		<?php echo $form->textField($model,'stage_special_education_eja_fundamental_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_special_education_eja_high_school_education'); ?>
		<?php echo $form->textField($model,'stage_special_education_eja_high_school_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_education_eja_fundamental_education'); ?>
		<?php echo $form->textField($model,'stage_education_eja_fundamental_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_education_eja_fundamental_education_projovem'); ?>
		<?php echo $form->textField($model,'stage_education_eja_fundamental_education_projovem'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stage_education_eja_high_school_education'); ?>
		<?php echo $form->textField($model,'stage_education_eja_high_school_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'basic_education_cycle_organized'); ?>
		<?php echo $form->textField($model,'basic_education_cycle_organized'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'different_location'); ?>
		<?php echo $form->textField($model,'different_location'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sociocultural_didactic_material_none'); ?>
		<?php echo $form->textField($model,'sociocultural_didactic_material_none'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sociocultural_didactic_material_quilombola'); ?>
		<?php echo $form->textField($model,'sociocultural_didactic_material_quilombola'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sociocultural_didactic_material_native'); ?>
		<?php echo $form->textField($model,'sociocultural_didactic_material_native'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'native_education'); ?>
		<?php echo $form->textField($model,'native_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'native_education_language_native'); ?>
		<?php echo $form->textField($model,'native_education_language_native'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'native_education_language_portuguese'); ?>
		<?php echo $form->textField($model,'native_education_language_portuguese'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_native_languages_fk'); ?>
		<?php echo $form->textField($model,'edcenso_native_languages_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'brazil_literate'); ?>
		<?php echo $form->textField($model,'brazil_literate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'open_weekend'); ?>
		<?php echo $form->textField($model,'open_weekend'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pedagogical_formation_by_alternance'); ?>
		<?php echo $form->textField($model,'pedagogical_formation_by_alternance'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->