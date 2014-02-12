<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */
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
		<?php echo $form->label($model,'class_fk'); ?>
		<?php echo $form->textField($model,'class_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'student_fk'); ?>
		<?php echo $form->textField($model,'student_fk'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->