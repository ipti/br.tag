<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'register_type'); ?>
		<?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'school_inep_id_fk'); ?>
		<?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inep_id'); ?>
		<?php echo $form->textField($model,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classroom_inep_id'); ?>
		<?php echo $form->textField($model,'classroom_inep_id',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classroom_id_fk'); ?>
		<?php echo $form->textField($model,'classroom_id_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'role'); ?>
		<?php echo $form->textField($model,'role'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contract_type'); ?>
		<?php echo $form->textField($model,'contract_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_1_fk'); ?>
		<?php echo $form->textField($model,'discipline_1_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_2_fk'); ?>
		<?php echo $form->textField($model,'discipline_2_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_3_fk'); ?>
		<?php echo $form->textField($model,'discipline_3_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_4_fk'); ?>
		<?php echo $form->textField($model,'discipline_4_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_5_fk'); ?>
		<?php echo $form->textField($model,'discipline_5_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_6_fk'); ?>
		<?php echo $form->textField($model,'discipline_6_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_7_fk'); ?>
		<?php echo $form->textField($model,'discipline_7_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_8_fk'); ?>
		<?php echo $form->textField($model,'discipline_8_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_9_fk'); ?>
		<?php echo $form->textField($model,'discipline_9_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_10_fk'); ?>
		<?php echo $form->textField($model,'discipline_10_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_11_fk'); ?>
		<?php echo $form->textField($model,'discipline_11_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_12_fk'); ?>
		<?php echo $form->textField($model,'discipline_12_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discipline_13_fk'); ?>
		<?php echo $form->textField($model,'discipline_13_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'instructor_fk'); ?>
		<?php echo $form->textField($model,'instructor_fk'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->