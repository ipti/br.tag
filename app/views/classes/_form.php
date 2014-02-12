<?php
/* @var $this ClassesController */
/* @var $model Classes */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'classes-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'discipline_fk'); ?>
		<?php echo $form->textField($model,'discipline_fk'); ?>
		<?php echo $form->error($model,'discipline_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'classroom_fk'); ?>
		<?php echo $form->textField($model,'classroom_fk'); ?>
		<?php echo $form->error($model,'classroom_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'day'); ?>
		<?php echo $form->textField($model,'day'); ?>
		<?php echo $form->error($model,'day'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'month'); ?>
		<?php echo $form->textField($model,'month'); ?>
		<?php echo $form->error($model,'month'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'classtype'); ?>
		<?php echo $form->textField($model,'classtype',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'classtype'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'given_class'); ?>
		<?php echo $form->textField($model,'given_class'); ?>
		<?php echo $form->error($model,'given_class'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'schedule'); ?>
		<?php echo $form->textField($model,'schedule',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'schedule'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->