<?php
/* @var $this EnrollmentOnlineStudentIdentificationController */
/* @var $model EnrollmentOnlineStudentIdentification */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enrollment-online-student-identification-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'school_inep_id_fk'); ?>
		<?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'school_inep_id_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'classroom_inep_id'); ?>
		<?php echo $form->textField($model,'classroom_inep_id',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'classroom_inep_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'classroom_fk'); ?>
		<?php echo $form->textField($model,'classroom_fk'); ?>
		<?php echo $form->error($model,'classroom_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birthday'); ?>
		<?php echo $form->textField($model,'birthday',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'birthday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cpf'); ?>
		<?php echo $form->textField($model,'cpf',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'cpf'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sex'); ?>
		<?php echo $form->textField($model,'sex'); ?>
		<?php echo $form->error($model,'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'color_race'); ?>
		<?php echo $form->textField($model,'color_race'); ?>
		<?php echo $form->error($model,'color_race'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency'); ?>
		<?php echo $form->textField($model,'deficiency'); ?>
		<?php echo $form->error($model,'deficiency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_blindness'); ?>
		<?php echo $form->textField($model,'deficiency_type_blindness'); ?>
		<?php echo $form->error($model,'deficiency_type_blindness'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_low_vision'); ?>
		<?php echo $form->textField($model,'deficiency_type_low_vision'); ?>
		<?php echo $form->error($model,'deficiency_type_low_vision'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_deafness'); ?>
		<?php echo $form->textField($model,'deficiency_type_deafness'); ?>
		<?php echo $form->error($model,'deficiency_type_deafness'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_disability_hearing'); ?>
		<?php echo $form->textField($model,'deficiency_type_disability_hearing'); ?>
		<?php echo $form->error($model,'deficiency_type_disability_hearing'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_deafblindness'); ?>
		<?php echo $form->textField($model,'deficiency_type_deafblindness'); ?>
		<?php echo $form->error($model,'deficiency_type_deafblindness'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_phisical_disability'); ?>
		<?php echo $form->textField($model,'deficiency_type_phisical_disability'); ?>
		<?php echo $form->error($model,'deficiency_type_phisical_disability'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_intelectual_disability'); ?>
		<?php echo $form->textField($model,'deficiency_type_intelectual_disability'); ?>
		<?php echo $form->error($model,'deficiency_type_intelectual_disability'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_multiple_disabilities'); ?>
		<?php echo $form->textField($model,'deficiency_type_multiple_disabilities'); ?>
		<?php echo $form->error($model,'deficiency_type_multiple_disabilities'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_autism'); ?>
		<?php echo $form->textField($model,'deficiency_type_autism'); ?>
		<?php echo $form->error($model,'deficiency_type_autism'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deficiency_type_gifted'); ?>
		<?php echo $form->textField($model,'deficiency_type_gifted'); ?>
		<?php echo $form->error($model,'deficiency_type_gifted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_change'); ?>
		<?php echo $form->textField($model,'last_change'); ?>
		<?php echo $form->error($model,'last_change'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mother_name'); ?>
		<?php echo $form->textField($model,'mother_name',array('size'=>60,'maxlength'=>90)); ?>
		<?php echo $form->error($model,'mother_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'father_name'); ?>
		<?php echo $form->textField($model,'father_name',array('size'=>60,'maxlength'=>90)); ?>
		<?php echo $form->error($model,'father_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'responsable_name'); ?>
		<?php echo $form->textField($model,'responsable_name',array('size'=>60,'maxlength'=>90)); ?>
		<?php echo $form->error($model,'responsable_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'responsable_cpf'); ?>
		<?php echo $form->textField($model,'responsable_cpf',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'responsable_cpf'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'responsable_telephone'); ?>
		<?php echo $form->textField($model,'responsable_telephone',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'responsable_telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cep'); ?>
		<?php echo $form->textField($model,'cep',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'cep'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complement'); ?>
		<?php echo $form->textField($model,'complement',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'complement'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'neighborhood'); ?>
		<?php echo $form->textField($model,'neighborhood',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'neighborhood'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone'); ?>
		<?php echo $form->textField($model,'zone'); ?>
		<?php echo $form->error($model,'zone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'edcenso_city_fk'); ?>
		<?php echo $form->textField($model,'edcenso_city_fk'); ?>
		<?php echo $form->error($model,'edcenso_city_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'edcenso_uf_fk'); ?>
		<?php echo $form->textField($model,'edcenso_uf_fk'); ?>
		<?php echo $form->error($model,'edcenso_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unavailable'); ?>
		<?php echo $form->textField($model,'unavailable'); ?>
		<?php echo $form->error($model,'unavailable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status_fk'); ?>
		<?php echo $form->textField($model,'status_fk'); ?>
		<?php echo $form->error($model,'status_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'student_fk'); ?>
		<?php echo $form->textField($model,'student_fk'); ?>
		<?php echo $form->error($model,'student_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'edcenso_stage_vs_modality_fk'); ?>
		<?php echo $form->textField($model,'edcenso_stage_vs_modality_fk'); ?>
		<?php echo $form->error($model,'edcenso_stage_vs_modality_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'event_pre_registration_fk'); ?>
		<?php echo $form->textField($model,'event_pre_registration_fk'); ?>
		<?php echo $form->error($model,'event_pre_registration_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stages_vacancy_pre_registration_fk'); ?>
		<?php echo $form->textField($model,'stages_vacancy_pre_registration_fk'); ?>
		<?php echo $form->error($model,'stages_vacancy_pre_registration_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->