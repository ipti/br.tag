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
		<?php echo $form->label($model,'student_fk'); ?>
		<?php echo $form->textField($model,'student_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rg_number'); ?>
		<?php echo $form->textField($model,'rg_number',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rg_number_complement'); ?>
		<?php echo $form->textField($model,'rg_number_complement',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rg_number_edcenso_organ_id_emitter_fk'); ?>
		<?php echo $form->textField($model,'rg_number_edcenso_organ_id_emitter_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rg_number_edcenso_uf_fk'); ?>
		<?php echo $form->textField($model,'rg_number_edcenso_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rg_number_expediction_date'); ?>
		<?php echo $form->textField($model,'rg_number_expediction_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_certification'); ?>
		<?php echo $form->textField($model,'civil_certification'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_certification_type'); ?>
		<?php echo $form->textField($model,'civil_certification_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_certification_term_number'); ?>
		<?php echo $form->textField($model,'civil_certification_term_number',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_certification_sheet'); ?>
		<?php echo $form->textField($model,'civil_certification_sheet',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_certification_book'); ?>
		<?php echo $form->textField($model,'civil_certification_book',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_certification_date'); ?>
		<?php echo $form->textField($model,'civil_certification_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notary_office_uf_fk'); ?>
		<?php echo $form->textField($model,'notary_office_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notary_office_city_fk'); ?>
		<?php echo $form->textField($model,'notary_office_city_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_notary_office_fk'); ?>
		<?php echo $form->textField($model,'edcenso_notary_office_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'civil_register_enrollment_number'); ?>
		<?php echo $form->textField($model,'civil_register_enrollment_number',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cpf'); ?>
		<?php echo $form->textField($model,'cpf',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'foreign_document_or_passport'); ?>
		<?php echo $form->textField($model,'foreign_document_or_passport',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nis'); ?>
		<?php echo $form->textField($model,'nis',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'document_failure_lack'); ?>
		<?php echo $form->textField($model,'document_failure_lack'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'residence_zone'); ?>
		<?php echo $form->textField($model,'residence_zone'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cep'); ?>
		<?php echo $form->textField($model,'cep',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'complement'); ?>
		<?php echo $form->textField($model,'complement',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'neighborhood'); ?>
		<?php echo $form->textField($model,'neighborhood',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_uf_fk'); ?>
		<?php echo $form->textField($model,'edcenso_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_city_fk'); ?>
		<?php echo $form->textField($model,'edcenso_city_fk'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->