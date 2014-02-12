<?php
/* @var $this ClassBoardController */
/* @var $model ClassBoard */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_fk'); ?>
		<?php echo $form->textField($model,'discipline_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classroom_fk'); ?>
		<?php echo $form->textField($model,'classroom_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_monday'); ?>
		<?php echo $form->textField($model,'week_day_monday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_tuesday'); ?>
		<?php echo $form->textField($model,'week_day_tuesday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_wednesday'); ?>
		<?php echo $form->textField($model,'week_day_wednesday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_thursday'); ?>
		<?php echo $form->textField($model,'week_day_thursday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_friday'); ?>
		<?php echo $form->textField($model,'week_day_friday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_saturday'); ?>
		<?php echo $form->textField($model,'week_day_saturday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_day_sunday'); ?>
		<?php echo $form->textField($model,'week_day_sunday'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estimated_classes'); ?>
		<?php echo $form->textField($model,'estimated_classes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'given_classes'); ?>
		<?php echo $form->textField($model,'given_classes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'replaced_classes'); ?>
		<?php echo $form->textField($model,'replaced_classes'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->