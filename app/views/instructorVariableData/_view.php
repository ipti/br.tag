<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('register_type')); ?>:</b>
	<?php echo CHtml::encode($data->register_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('school_inep_id_fk')); ?>:</b>
	<?php echo CHtml::encode($data->school_inep_id_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inep_id')); ?>:</b>
	<?php echo CHtml::encode($data->inep_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('scholarity')); ?>:</b>
	<?php echo CHtml::encode($data->scholarity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_situation_1')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_situation_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_formation_1')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_formation_1); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_course_code_1_fk')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_course_code_1_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_initial_year_1')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_initial_year_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_final_year_1')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_final_year_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_institution_type_1')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_institution_type_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_institution_code_1_fk')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_institution_code_1_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_situation_2')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_situation_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_formation_2')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_formation_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_course_code_2_fk')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_course_code_2_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_initial_year_2')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_initial_year_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_final_year_2')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_final_year_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_institution_type_2')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_institution_type_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_institution_code_2_fk')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_institution_code_2_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_situation_3')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_situation_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_formation_3')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_formation_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_course_code_3_fk')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_course_code_3_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_initial_year_3')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_initial_year_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_final_year_3')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_final_year_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_institution_type_3')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_institution_type_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('high_education_institution_code_3_fk')); ?>:</b>
	<?php echo CHtml::encode($data->high_education_institution_code_3_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_graduation_specialization')); ?>:</b>
	<?php echo CHtml::encode($data->post_graduation_specialization); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_graduation_master')); ?>:</b>
	<?php echo CHtml::encode($data->post_graduation_master); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_graduation_doctorate')); ?>:</b>
	<?php echo CHtml::encode($data->post_graduation_doctorate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_graduation_none')); ?>:</b>
	<?php echo CHtml::encode($data->post_graduation_none); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_nursery')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_nursery); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_pre_school')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_pre_school); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_basic_education_initial_years')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_basic_education_initial_years); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_basic_education_final_years')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_basic_education_final_years); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_high_school')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_high_school); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_education_of_youth_and_adults')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_education_of_youth_and_adults); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_special_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_special_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_native_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_native_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_field_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_field_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_environment_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_environment_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_human_rights_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_human_rights_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_sexual_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_sexual_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_child_and_teenage_rights')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_child_and_teenage_rights); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_ethnic_education')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_ethnic_education); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_other')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_other); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_courses_none')); ?>:</b>
	<?php echo CHtml::encode($data->other_courses_none); ?>
	<br />

	*/ ?>

</div>