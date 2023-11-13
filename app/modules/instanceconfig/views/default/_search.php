<?php
/* @var $this InstanceConfigController */
/* @var $model InstanceConfig */
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
		<?php echo $form->label($model,'launch_grades'); ?>
		<?php echo $form->textField($model,'launch_grades'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sedsp_sync'); ?>
		<?php echo $form->textField($model,'sedsp_sync'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->