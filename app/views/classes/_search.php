<?php
/* @var $this ClassesController */
/* @var $model Classes */
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
		<?php echo $form->label($model,'day'); ?>
		<?php echo $form->textField($model,'day'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'month'); ?>
		<?php echo $form->textField($model,'month'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classtype'); ?>
		<?php echo $form->textField($model,'classtype',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'given_class'); ?>
		<?php echo $form->textField($model,'given_class'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'schedule'); ?>
		<?php echo $form->textField($model,'schedule',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->