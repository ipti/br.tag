<?php
/* @var $this ProfessionalController */
/* @var $model Professional */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'professional-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_professional'); ?>
		<?php echo $form->textField($model,'id_professional'); ?>
		<?php echo $form->error($model,'id_professional'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cpf_professional'); ?>
		<?php echo $form->textField($model,'cpf_professional',array('size'=>14,'maxlength'=>14)); ?>
		<?php echo $form->error($model,'cpf_professional'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'specialty'); ?>
		<?php echo $form->textField($model,'specialty',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'specialty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'inep_id_fk'); ?>
		<?php echo $form->textField($model,'inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'inep_id_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fundeb'); ?>
		<?php echo $form->textField($model,'fundeb'); ?>
		<?php echo $form->error($model,'fundeb'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->