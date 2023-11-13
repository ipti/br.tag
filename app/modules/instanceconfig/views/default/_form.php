<?php
/* @var $this InstanceConfigController */
/* @var $model InstanceConfig */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'instance-config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'launch_grades'); ?>
		<?php echo $form->textField($model,'launch_grades'); ?>
		<?php echo $form->error($model,'launch_grades'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sedsp_sync'); ?>
		<?php echo $form->textField($model,'sedsp_sync'); ?>
		<?php echo $form->error($model,'sedsp_sync'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->