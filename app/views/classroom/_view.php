<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('register_type')); ?>:</b>
	<?php echo CHtml::encode($data->register_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('school_inep_fk')); ?>:</b>
	<?php echo CHtml::encode($data->school_inep_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inep_id')); ?>:</b>
	<?php echo CHtml::encode($data->inep_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('initial_hour')); ?>:</b>
	<?php echo CHtml::encode($data->initial_hour); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('initial_minute')); ?>:</b>
	<?php echo CHtml::encode($data->initial_minute); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('final_hour')); ?>:</b>
	<?php echo CHtml::encode($data->final_hour); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('final_minute')); ?>:</b>
	<?php echo CHtml::encode($data->final_minute); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_sunday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_sunday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_monday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_monday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_tuesday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_tuesday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_wednesday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_wednesday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_thursday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_thursday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_friday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_friday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_days_saturday')); ?>:</b>
	<?php echo CHtml::encode($data->week_days_saturday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assistance_type')); ?>:</b>
	<?php echo CHtml::encode($data->assistance_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mais_educacao_participator')); ?>:</b>
	<?php echo CHtml::encode($data->mais_educacao_participator); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activity_type_1')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activity_type_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activity_type_2')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activity_type_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activity_type_3')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activity_type_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activity_type_4')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activity_type_4); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activity_type_5')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activity_type_5); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complementary_activity_type_6')); ?>:</b>
	<?php echo CHtml::encode($data->complementary_activity_type_6); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_braille_system_education')); ?>:</b>
	<?php echo CHtml::encode($data->aee_braille_system_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_optical_and_non_optical_resources')); ?>:</b>
	<?php echo CHtml::encode($data->aee_optical_and_non_optical_resources); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_mental_processes_development_strategies')); ?>:</b>
	<?php echo CHtml::encode($data->aee_mental_processes_development_strategies); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_mobility_and_orientation_techniques')); ?>:</b>
	<?php echo CHtml::encode($data->aee_mobility_and_orientation_techniques); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_libras')); ?>:</b>
	<?php echo CHtml::encode($data->aee_libras); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_caa_use_education')); ?>:</b>
	<?php echo CHtml::encode($data->aee_caa_use_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_curriculum_enrichment_strategy')); ?>:</b>
	<?php echo CHtml::encode($data->aee_curriculum_enrichment_strategy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_soroban_use_education')); ?>:</b>
	<?php echo CHtml::encode($data->aee_soroban_use_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_usability_and_functionality_of_computer_accessible_education')); ?>:</b>
	<?php echo CHtml::encode($data->aee_usability_and_functionality_of_computer_accessible_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_teaching_of_Portuguese_language_written_modality')); ?>:</b>
	<?php echo CHtml::encode($data->aee_teaching_of_Portuguese_language_written_modality); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aee_strategy_for_school_environment_autonomy')); ?>:</b>
	<?php echo CHtml::encode($data->aee_strategy_for_school_environment_autonomy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modality')); ?>:</b>
	<?php echo CHtml::encode($data->modality); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_stage_vs_modality_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_stage_vs_modality_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_professional_education_course_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_professional_education_course_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_chemistry')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_chemistry); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_physics')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_physics); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_mathematics')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_mathematics); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_biology')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_biology); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_science')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_science); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_language_portuguese_literature')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_language_portuguese_literature); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_foreign_language_english')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_foreign_language_english); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_foreign_language_spanish')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_foreign_language_spanish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_foreign_language_franch')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_foreign_language_franch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_foreign_language_other')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_foreign_language_other); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_arts')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_arts); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_physical_education')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_physical_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_history')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_history); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_geography')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_geography); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_philosophy')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_philosophy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_social_study')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_social_study); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_sociology')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_sociology); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_informatics')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_informatics); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_professional_disciplines')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_professional_disciplines); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_special_education_and_inclusive_practices')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_special_education_and_inclusive_practices); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_sociocultural_diversity')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_sociocultural_diversity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_libras')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_libras); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_pedagogical')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_pedagogical); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_religious')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_religious); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_native_language')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_native_language); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_others')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_others); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('instructor_situation')); ?>:</b>
	<?php echo CHtml::encode($data->instructor_situation); ?>
	<br />

	*/ ?>

</div>