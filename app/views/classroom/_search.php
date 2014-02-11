<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="controls">
		<?php echo $form->label($model,'register_type'); ?>
		<?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'school_inep_fk'); ?>
		<?php echo $form->textField($model,'school_inep_fk',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'inep_id'); ?>
		<?php echo $form->textField($model,'inep_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'initial_hour'); ?>
		<?php echo $form->textField($model,'initial_hour',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'initial_minute'); ?>
		<?php echo $form->textField($model,'initial_minute',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'final_hour'); ?>
		<?php echo $form->textField($model,'final_hour',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'final_minute'); ?>
		<?php echo $form->textField($model,'final_minute',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_sunday'); ?>
		<?php echo $form->textField($model,'week_days_sunday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_monday'); ?>
		<?php echo $form->textField($model,'week_days_monday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_tuesday'); ?>
		<?php echo $form->textField($model,'week_days_tuesday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_wednesday'); ?>
		<?php echo $form->textField($model,'week_days_wednesday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_thursday'); ?>
		<?php echo $form->textField($model,'week_days_thursday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_friday'); ?>
		<?php echo $form->textField($model,'week_days_friday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'week_days_saturday'); ?>
		<?php echo $form->textField($model,'week_days_saturday'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'assistance_type'); ?>
		<?php echo $form->textField($model,'assistance_type'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'mais_educacao_participator'); ?>
		<?php echo $form->textField($model,'mais_educacao_participator'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'complementary_activity_type_1'); ?>
		<?php echo $form->textField($model,'complementary_activity_type_1'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'complementary_activity_type_2'); ?>
		<?php echo $form->textField($model,'complementary_activity_type_2'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'complementary_activity_type_3'); ?>
		<?php echo $form->textField($model,'complementary_activity_type_3'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'complementary_activity_type_4'); ?>
		<?php echo $form->textField($model,'complementary_activity_type_4'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'complementary_activity_type_5'); ?>
		<?php echo $form->textField($model,'complementary_activity_type_5'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'complementary_activity_type_6'); ?>
		<?php echo $form->textField($model,'complementary_activity_type_6'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_braille_system_education'); ?>
		<?php echo $form->textField($model,'aee_braille_system_education'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_optical_and_non_optical_resources'); ?>
		<?php echo $form->textField($model,'aee_optical_and_non_optical_resources'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_mental_processes_development_strategies'); ?>
		<?php echo $form->textField($model,'aee_mental_processes_development_strategies'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_mobility_and_orientation_techniques'); ?>
		<?php echo $form->textField($model,'aee_mobility_and_orientation_techniques'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_libras'); ?>
		<?php echo $form->textField($model,'aee_libras'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_caa_use_education'); ?>
		<?php echo $form->textField($model,'aee_caa_use_education'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_curriculum_enrichment_strategy'); ?>
		<?php echo $form->textField($model,'aee_curriculum_enrichment_strategy'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_soroban_use_education'); ?>
		<?php echo $form->textField($model,'aee_soroban_use_education'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_usability_and_functionality_of_computer_accessible_education'); ?>
		<?php echo $form->textField($model,'aee_usability_and_functionality_of_computer_accessible_education'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_teaching_of_Portuguese_language_written_modality'); ?>
		<?php echo $form->textField($model,'aee_teaching_of_Portuguese_language_written_modality'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'aee_strategy_for_school_environment_autonomy'); ?>
		<?php echo $form->textField($model,'aee_strategy_for_school_environment_autonomy'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'modality'); ?>
		<?php echo $form->textField($model,'modality'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'edcenso_stage_vs_modality_fk'); ?>
		<?php echo $form->textField($model,'edcenso_stage_vs_modality_fk',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'edcenso_professional_education_course_fk'); ?>
		<?php echo $form->textField($model,'edcenso_professional_education_course_fk'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_chemistry'); ?>
		<?php echo $form->textField($model,'discipline_chemistry'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_physics'); ?>
		<?php echo $form->textField($model,'discipline_physics'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_mathematics'); ?>
		<?php echo $form->textField($model,'discipline_mathematics'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_biology'); ?>
		<?php echo $form->textField($model,'discipline_biology'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_science'); ?>
		<?php echo $form->textField($model,'discipline_science'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_language_portuguese_literature'); ?>
		<?php echo $form->textField($model,'discipline_language_portuguese_literature'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_foreign_language_english'); ?>
		<?php echo $form->textField($model,'discipline_foreign_language_english'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_foreign_language_spanish'); ?>
		<?php echo $form->textField($model,'discipline_foreign_language_spanish'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_foreign_language_franch'); ?>
		<?php echo $form->textField($model,'discipline_foreign_language_franch'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_foreign_language_other'); ?>
		<?php echo $form->textField($model,'discipline_foreign_language_other'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_arts'); ?>
		<?php echo $form->textField($model,'discipline_arts'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_physical_education'); ?>
		<?php echo $form->textField($model,'discipline_physical_education'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_history'); ?>
		<?php echo $form->textField($model,'discipline_history'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_geography'); ?>
		<?php echo $form->textField($model,'discipline_geography'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_philosophy'); ?>
		<?php echo $form->textField($model,'discipline_philosophy'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_social_study'); ?>
		<?php echo $form->textField($model,'discipline_social_study'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_sociology'); ?>
		<?php echo $form->textField($model,'discipline_sociology'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_informatics'); ?>
		<?php echo $form->textField($model,'discipline_informatics'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_professional_disciplines'); ?>
		<?php echo $form->textField($model,'discipline_professional_disciplines'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_special_education_and_inclusive_practices'); ?>
		<?php echo $form->textField($model,'discipline_special_education_and_inclusive_practices'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_sociocultural_diversity'); ?>
		<?php echo $form->textField($model,'discipline_sociocultural_diversity'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_libras'); ?>
		<?php echo $form->textField($model,'discipline_libras'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_pedagogical'); ?>
		<?php echo $form->textField($model,'discipline_pedagogical'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_religious'); ?>
		<?php echo $form->textField($model,'discipline_religious'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_native_language'); ?>
		<?php echo $form->textField($model,'discipline_native_language'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'discipline_others'); ?>
		<?php echo $form->textField($model,'discipline_others'); ?>
	</div>

	<div class="controls">
		<?php echo $form->label($model,'instructor_situation'); ?>
		<?php echo $form->textField($model,'instructor_situation'); ?>
	</div>

	<div class="controls buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->