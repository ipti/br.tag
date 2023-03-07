<?php
/* @var $this ProfessionalController */
/* @var $model Professional */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_professional'); ?>
		<?php echo $form->textField($model,'id_professional'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cpf_professional'); ?>
		<?php echo $form->textField($model,'cpf_professional',array('size'=>14,'maxlength'=>14)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'specialty'); ?>
		<?php echo $form->textField($model,'specialty',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inep_id_fk'); ?>
		<?php echo $form->textField($model,'inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fundeb'); ?>
		<?php echo $form->textField($model,'fundeb'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->