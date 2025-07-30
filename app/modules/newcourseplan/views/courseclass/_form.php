<?php
/* @var $this CourseclassController */
/* @var $model CourseClass */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course-class-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'order'); ?>
		<?php echo $form->textField($model,'order'); ?>
		<?php echo $form->error($model,'order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'course_plan_fk'); ?>
		<?php echo $form->textField($model,'course_plan_fk'); ?>
		<?php echo $form->error($model,'course_plan_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fkid'); ?>
		<?php echo $form->textField($model,'fkid',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'fkid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'methodology'); ?>
		<?php echo $form->textArea($model,'methodology',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'methodology'); ?>
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