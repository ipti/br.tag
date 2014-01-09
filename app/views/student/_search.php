<div class="wide form">

<?php 
//@done S1 - 07 - 12 - Implementar a busca de alunos na tela de listagem
//@done S1 - 08 - 13 - Na listagem de alunos o link está no nome da escola????, o link deveria estar no nome do aluno
$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'register_type'); ?>
		<?php echo $form->textField($modelStudentIdentification,'register_type',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'school_inep_id_fk'); ?>
		<?php echo $form->textField($modelStudentIdentification,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'inep_id'); ?>
		<?php echo $form->textField($modelStudentIdentification,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'id'); ?>
		<?php echo $form->textField($modelStudentIdentification,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'name'); ?>
		<?php echo $form->textField($modelStudentIdentification,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'nis'); ?>
		<?php echo $form->textField($modelStudentIdentification,'nis',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'birthday'); ?>
		<?php echo $form->textField($modelStudentIdentification,'birthday',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'sex'); ?>
		<?php echo $form->textField($modelStudentIdentification,'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'color_race'); ?>
		<?php echo $form->textField($modelStudentIdentification,'color_race'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'filiation'); ?>
		<?php echo $form->textField($modelStudentIdentification,'filiation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'mother_name'); ?>
		<?php echo $form->textField($modelStudentIdentification,'mother_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'father_name'); ?>
		<?php echo $form->textField($modelStudentIdentification,'father_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'nationality'); ?>
		<?php echo $form->textField($modelStudentIdentification,'nationality'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'edcenso_nation_fk'); ?>
		<?php echo $form->textField($modelStudentIdentification,'edcenso_nation_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'edcenso_uf_fk'); ?>
		<?php echo $form->textField($modelStudentIdentification,'edcenso_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'edcenso_city_fk'); ?>
		<?php echo $form->textField($modelStudentIdentification,'edcenso_city_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_blindness'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_blindness'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_low_vision'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_low_vision'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_deafness'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_deafness'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_disability_hearing'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_disability_hearing'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_deafblindness'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_deafblindness'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_phisical_disability'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_phisical_disability'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_intelectual_disability'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_intelectual_disability'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_multiple_disabilities'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_multiple_disabilities'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_autism'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_autism'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_aspenger_syndrome'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_aspenger_syndrome'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_rett_syndrome'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_rett_syndrome'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_childhood_disintegrative_disorder'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_childhood_disintegrative_disorder'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'deficiency_type_gifted'); ?>
		<?php echo $form->textField($modelStudentIdentification,'deficiency_type_gifted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_aid_lector'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_aid_lector'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_aid_transcription'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_aid_transcription'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_interpreter_guide'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_interpreter_guide'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_interpreter_libras'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_interpreter_libras'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_lip_reading'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_lip_reading'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_zoomed_test_16'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_zoomed_test_16'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_zoomed_test_20'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_zoomed_test_20'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_zoomed_test_24'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_zoomed_test_24'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_braille_test'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_braille_test'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelStudentIdentification,'resource_none'); ?>
		<?php echo $form->textField($modelStudentIdentification,'resource_none'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->