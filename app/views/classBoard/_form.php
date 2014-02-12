<?php
/* @var $this ClassBoardController */
/* @var $model ClassBoard */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'class-board-form',
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
		<?php echo $form->labelEx($model,'week_day_monday'); ?>
		<?php echo $form->textField($model,'week_day_monday'); ?>
		<?php echo $form->error($model,'week_day_monday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'week_day_tuesday'); ?>
		<?php echo $form->textField($model,'week_day_tuesday'); ?>
		<?php echo $form->error($model,'week_day_tuesday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'week_day_wednesday'); ?>
		<?php echo $form->textField($model,'week_day_wednesday'); ?>
		<?php echo $form->error($model,'week_day_wednesday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'week_day_thursday'); ?>
		<?php echo $form->textField($model,'week_day_thursday'); ?>
		<?php echo $form->error($model,'week_day_thursday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'week_day_friday'); ?>
		<?php echo $form->textField($model,'week_day_friday'); ?>
		<?php echo $form->error($model,'week_day_friday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'week_day_saturday'); ?>
		<?php echo $form->textField($model,'week_day_saturday'); ?>
		<?php echo $form->error($model,'week_day_saturday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'week_day_sunday'); ?>
		<?php echo $form->textField($model,'week_day_sunday'); ?>
		<?php echo $form->error($model,'week_day_sunday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estimated_classes'); ?>
		<?php echo $form->textField($model,'estimated_classes'); ?>
		<?php echo $form->error($model,'estimated_classes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'given_classes'); ?>
		<?php echo $form->textField($model,'given_classes'); ?>
		<?php echo $form->error($model,'given_classes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'replaced_classes'); ?>
		<?php echo $form->textField($model,'replaced_classes'); ?>
		<?php echo $form->error($model,'replaced_classes'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->