<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('school_inep_id_fk')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->school_inep_id_fk), array('view', 'id'=>$data->school_inep_id_fk)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('register_type')); ?>:</b>
	<?php echo CHtml::encode($data->register_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manager_cpf')); ?>:</b>
	<?php echo CHtml::encode($data->manager_cpf); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manager_name')); ?>:</b>
	<?php echo CHtml::encode($data->manager_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manager_role')); ?>:</b>
	<?php echo CHtml::encode($data->manager_role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manager_email')); ?>:</b>
	<?php echo CHtml::encode($data->manager_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_building')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_building); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_temple')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_temple); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_businness_room')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_businness_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_instructor_house')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_instructor_house); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_other_school_room')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_other_school_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_barracks')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_barracks); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_socioeducative_unity')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_socioeducative_unity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_prison_unity')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_prison_unity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operation_location_other')); ?>:</b>
	<?php echo CHtml::encode($data->operation_location_other); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('building_occupation_situation')); ?>:</b>
	<?php echo CHtml::encode($data->building_occupation_situation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_building_with_school')); ?>:</b>
	<?php echo CHtml::encode($data->shared_building_with_school); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_school_inep_id_1')); ?>:</b>
	<?php echo CHtml::encode($data->shared_school_inep_id_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_school_inep_id_2')); ?>:</b>
	<?php echo CHtml::encode($data->shared_school_inep_id_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_school_inep_id_3')); ?>:</b>
	<?php echo CHtml::encode($data->shared_school_inep_id_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_school_inep_id_4')); ?>:</b>
	<?php echo CHtml::encode($data->shared_school_inep_id_4); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_school_inep_id_5')); ?>:</b>
	<?php echo CHtml::encode($data->shared_school_inep_id_5); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shared_school_inep_id_6')); ?>:</b>
	<?php echo CHtml::encode($data->shared_school_inep_id_6); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('consumed_water_type')); ?>:</b>
	<?php echo CHtml::encode($data->consumed_water_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('water_supply_public')); ?>:</b>
	<?php echo CHtml::encode($data->water_supply_public); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('water_supply_artesian_well')); ?>:</b>
	<?php echo CHtml::encode($data->water_supply_artesian_well); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('water_supply_well')); ?>:</b>
	<?php echo CHtml::encode($data->water_supply_well); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('water_supply_river')); ?>:</b>
	<?php echo CHtml::encode($data->water_supply_river); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('water_supply_inexistent')); ?>:</b>
	<?php echo CHtml::encode($data->water_supply_inexistent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('energy_supply_public')); ?>:</b>
	<?php echo CHtml::encode($data->energy_supply_public); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('energy_supply_generator')); ?>:</b>
	<?php echo CHtml::encode($data->energy_supply_generator); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('energy_supply_other')); ?>:</b>
	<?php echo CHtml::encode($data->energy_supply_other); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('energy_supply_inexistent')); ?>:</b>
	<?php echo CHtml::encode($data->energy_supply_inexistent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sewage_public')); ?>:</b>
	<?php echo CHtml::encode($data->sewage_public); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sewage_fossa')); ?>:</b>
	<?php echo CHtml::encode($data->sewage_fossa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sewage_inexistent')); ?>:</b>
	<?php echo CHtml::encode($data->sewage_inexistent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garbage_destination_collect')); ?>:</b>
	<?php echo CHtml::encode($data->garbage_destination_collect); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garbage_destination_burn')); ?>:</b>
	<?php echo CHtml::encode($data->garbage_destination_burn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garbage_destination_throw_away')); ?>:</b>
	<?php echo CHtml::encode($data->garbage_destination_throw_away); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garbage_destination_recycle')); ?>:</b>
	<?php echo CHtml::encode($data->garbage_destination_recycle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garbage_destination_bury')); ?>:</b>
	<?php echo CHtml::encode($data->garbage_destination_bury); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garbage_destination_other')); ?>:</b>
	<?php echo CHtml::encode($data->garbage_destination_other); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_principal_room')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_principal_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_instructors_room')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_instructors_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_secretary_room')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_secretary_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_info_lab')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_info_lab); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_science_lab')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_science_lab); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_aee_room')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_aee_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_indoor_sports_court')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_indoor_sports_court); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_outdoor_sports_court')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_outdoor_sports_court); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_kitchen')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_kitchen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_library')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_library); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_reading_room')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_reading_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_playground')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_playground); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_nursery')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_nursery); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_outside_bathroom')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_outside_bathroom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_inside_bathroom')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_inside_bathroom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_child_bathroom')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_child_bathroom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_prysical_disability_bathroom')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_prysical_disability_bathroom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_physical_disability_support')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_physical_disability_support); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_bathroom_with_shower')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_bathroom_with_shower); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_refectory')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_refectory); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_storeroom')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_storeroom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_warehouse')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_warehouse); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_auditorium')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_auditorium); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_covered_patio')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_covered_patio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_uncovered_patio')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_uncovered_patio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_student_accomodation')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_student_accomodation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_instructor_accomodation')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_instructor_accomodation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_green_area')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_green_area); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_laundry')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_laundry); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dependencies_none')); ?>:</b>
	<?php echo CHtml::encode($data->dependencies_none); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('classroom_count')); ?>:</b>
	<?php echo CHtml::encode($data->classroom_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('used_classroom_count')); ?>:</b>
	<?php echo CHtml::encode($data->used_classroom_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_tv')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_tv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_vcr')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_vcr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_dvd')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_dvd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_satellite_dish')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_satellite_dish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_copier')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_copier); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_overhead_projector')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_overhead_projector); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_printer')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_printer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_stereo_system')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_stereo_system); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_data_show')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_data_show); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_fax')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_fax); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_camera')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_camera); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipments_computer')); ?>:</b>
	<?php echo CHtml::encode($data->equipments_computer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('administrative_computers_count')); ?>:</b>
	<?php echo CHtml::encode($data->administrative_computers_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('student_computers_count')); ?>:</b>
	<?php echo CHtml::encode($data->student_computers_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('internet_access')); ?>:</b>
	<?php echo CHtml::encode($data->internet_access); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bandwidth')); ?>:</b>
	<?php echo CHtml::encode($data->bandwidth); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employees_count')); ?>:</b>
	<?php echo CHtml::encode($data->employees_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('feeding')); ?>:</b>
	<?php echo CHtml::encode($data->feeding); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee')); ?>:</b>
	<?php echo CHtml::encode($data->aee); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activities')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activities); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modalities_regular')); ?>:</b>
	<?php echo CHtml::encode($data->modalities_regular); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modalities_especial')); ?>:</b>
	<?php echo CHtml::encode($data->modalities_especial); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modalities_eja')); ?>:</b>
	<?php echo CHtml::encode($data->modalities_eja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_creche')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_creche); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_preschool')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_preschool); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_fundamental_eigth_years')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_fundamental_eigth_years); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_fundamental_nine_years')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_fundamental_nine_years); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_high_school')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_high_school); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_high_school_integrated')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_high_school_integrated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_high_school_normal_mastership')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_high_school_normal_mastership); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_regular_education_high_school_preofessional_education')); ?>:</b>
	<?php echo CHtml::encode($data->stage_regular_education_high_school_preofessional_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_creche')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_creche); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_preschool')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_preschool); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_fundamental_eigth_years')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_fundamental_eigth_years); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_fundamental_nine_years')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_fundamental_nine_years); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_high_school')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_high_school); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_high_school_integrated')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_high_school_integrated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_high_school_normal_mastership')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_high_school_normal_mastership); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_high_school_professional_education')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_high_school_professional_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_eja_fundamental_education')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_eja_fundamental_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_special_education_eja_high_school_education')); ?>:</b>
	<?php echo CHtml::encode($data->stage_special_education_eja_high_school_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_education_eja_fundamental_education')); ?>:</b>
	<?php echo CHtml::encode($data->stage_education_eja_fundamental_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_education_eja_fundamental_education_projovem')); ?>:</b>
	<?php echo CHtml::encode($data->stage_education_eja_fundamental_education_projovem); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stage_education_eja_high_school_education')); ?>:</b>
	<?php echo CHtml::encode($data->stage_education_eja_high_school_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('basic_education_cycle_organized')); ?>:</b>
	<?php echo CHtml::encode($data->basic_education_cycle_organized); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('different_location')); ?>:</b>
	<?php echo CHtml::encode($data->different_location); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sociocultural_didactic_material_none')); ?>:</b>
	<?php echo CHtml::encode($data->sociocultural_didactic_material_none); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sociocultural_didactic_material_quilombola')); ?>:</b>
	<?php echo CHtml::encode($data->sociocultural_didactic_material_quilombola); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sociocultural_didactic_material_native')); ?>:</b>
	<?php echo CHtml::encode($data->sociocultural_didactic_material_native); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('native_education')); ?>:</b>
	<?php echo CHtml::encode($data->native_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('native_education_language_native')); ?>:</b>
	<?php echo CHtml::encode($data->native_education_language_native); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('native_education_language_portuguese')); ?>:</b>
	<?php echo CHtml::encode($data->native_education_language_portuguese); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_native_languages_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_native_languages_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brazil_literate')); ?>:</b>
	<?php echo CHtml::encode($data->brazil_literate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_weekend')); ?>:</b>
	<?php echo CHtml::encode($data->open_weekend); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pedagogical_formation_by_alternance')); ?>:</b>
	<?php echo CHtml::encode($data->pedagogical_formation_by_alternance); ?>
	<br />

	*/ ?>

</div>