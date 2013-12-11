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
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nis'); ?>
		<?php echo $form->textField($model,'nis',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'birthday_date'); ?>
		<?php echo $form->textField($model,'birthday_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sex'); ?>
		<?php echo $form->textField($model,'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'color_race'); ?>
		<?php echo $form->textField($model,'color_race'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mother_name'); ?>
		<?php echo $form->textField($model,'mother_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nationality'); ?>
		<?php echo $form->textField($model,'nationality'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_nation_fk'); ?>
		<?php echo $form->textField($model,'edcenso_nation_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_uf_fk'); ?>
		<?php echo $form->textField($model,'edcenso_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_city_fk'); ?>
		<?php echo $form->textField($model,'edcenso_city_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency'); ?>
		<?php echo $form->textField($model,'deficiency'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_blindness'); ?>
		<?php echo $form->textField($model,'deficiency_type_blindness'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_low_vision'); ?>
		<?php echo $form->textField($model,'deficiency_type_low_vision'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_deafness'); ?>
		<?php echo $form->textField($model,'deficiency_type_deafness'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_disability_hearing'); ?>
		<?php echo $form->textField($model,'deficiency_type_disability_hearing'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_deafblindness'); ?>
		<?php echo $form->textField($model,'deficiency_type_deafblindness'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_phisical_disability'); ?>
		<?php echo $form->textField($model,'deficiency_type_phisical_disability'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_intelectual_disability'); ?>
		<?php echo $form->textField($model,'deficiency_type_intelectual_disability'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deficiency_type_multiple_disabilities'); ?>
		<?php echo $form->textField($model,'deficiency_type_multiple_disabilities'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->