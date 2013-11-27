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
		<?php echo $form->label($model,'inep_id'); ?>
		<?php echo $form->textField($model,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholarity'); ?>
		<?php echo $form->textField($model,'scholarity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_situation_1'); ?>
		<?php echo $form->textField($model,'high_education_situation_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_formation_1'); ?>
		<?php echo $form->textField($model,'high_education_formation_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_course_code_1_fk'); ?>
		<?php echo $form->textField($model,'high_education_course_code_1_fk',array('size'=>6,'maxlength'=>6)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_initial_year_1'); ?>
		<?php echo $form->textField($model,'high_education_initial_year_1',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_final_year_1'); ?>
		<?php echo $form->textField($model,'high_education_final_year_1',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_institution_type_1'); ?>
		<?php echo $form->textField($model,'high_education_institution_type_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_institution_code_1_fk'); ?>
		<?php echo $form->textField($model,'high_education_institution_code_1_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_situation_2'); ?>
		<?php echo $form->textField($model,'high_education_situation_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_formation_2'); ?>
		<?php echo $form->textField($model,'high_education_formation_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_course_code_2_fk'); ?>
		<?php echo $form->textField($model,'high_education_course_code_2_fk',array('size'=>6,'maxlength'=>6)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_initial_year_2'); ?>
		<?php echo $form->textField($model,'high_education_initial_year_2',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_final_year_2'); ?>
		<?php echo $form->textField($model,'high_education_final_year_2',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_institution_type_2'); ?>
		<?php echo $form->textField($model,'high_education_institution_type_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_institution_code_2_fk'); ?>
		<?php echo $form->textField($model,'high_education_institution_code_2_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_situation_3'); ?>
		<?php echo $form->textField($model,'high_education_situation_3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_formation_3'); ?>
		<?php echo $form->textField($model,'high_education_formation_3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_course_code_3_fk'); ?>
		<?php echo $form->textField($model,'high_education_course_code_3_fk',array('size'=>6,'maxlength'=>6)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_initial_year_3'); ?>
		<?php echo $form->textField($model,'high_education_initial_year_3',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_final_year_3'); ?>
		<?php echo $form->textField($model,'high_education_final_year_3',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_institution_type_3'); ?>
		<?php echo $form->textField($model,'high_education_institution_type_3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'high_education_institution_code_3_fk'); ?>
		<?php echo $form->textField($model,'high_education_institution_code_3_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'post_graduation_specialization'); ?>
		<?php echo $form->textField($model,'post_graduation_specialization'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'post_graduation_master'); ?>
		<?php echo $form->textField($model,'post_graduation_master'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'post_graduation_doctorate'); ?>
		<?php echo $form->textField($model,'post_graduation_doctorate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'post_graduation_none'); ?>
		<?php echo $form->textField($model,'post_graduation_none'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_nursery'); ?>
		<?php echo $form->textField($model,'other_courses_nursery'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_pre_school'); ?>
		<?php echo $form->textField($model,'other_courses_pre_school'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_basic_education_initial_years'); ?>
		<?php echo $form->textField($model,'other_courses_basic_education_initial_years'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_basic_education_final_years'); ?>
		<?php echo $form->textField($model,'other_courses_basic_education_final_years'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_high_school'); ?>
		<?php echo $form->textField($model,'other_courses_high_school'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_education_of_youth_and_adults'); ?>
		<?php echo $form->textField($model,'other_courses_education_of_youth_and_adults'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_special_education'); ?>
		<?php echo $form->textField($model,'other_courses_special_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_native_education'); ?>
		<?php echo $form->textField($model,'other_courses_native_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_field_education'); ?>
		<?php echo $form->textField($model,'other_courses_field_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_environment_education'); ?>
		<?php echo $form->textField($model,'other_courses_environment_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_human_rights_education'); ?>
		<?php echo $form->textField($model,'other_courses_human_rights_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_sexual_education'); ?>
		<?php echo $form->textField($model,'other_courses_sexual_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_child_and_teenage_rights'); ?>
		<?php echo $form->textField($model,'other_courses_child_and_teenage_rights'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_ethnic_education'); ?>
		<?php echo $form->textField($model,'other_courses_ethnic_education'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_other'); ?>
		<?php echo $form->textField($model,'other_courses_other'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_courses_none'); ?>
		<?php echo $form->textField($model,'other_courses_none'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->