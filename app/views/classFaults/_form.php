<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'class-faults-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'class_fk'); ?>
		<?php echo $form->textField($model,'class_fk'); ?>
		<?php echo $form->error($model,'class_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'student_fk'); ?>
		<?php echo $form->textField($model,'student_fk'); ?>
		<?php echo $form->error($model,'student_fk'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->