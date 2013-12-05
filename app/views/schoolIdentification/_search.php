<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'register_type'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'register_type',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'inep_id'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'inep_id',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'situation'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'situation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'initial_date'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'initial_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'final_date'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'final_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'name'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'latitude'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'latitude',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'longitude'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'longitude',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'cep'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'cep',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'address'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'address',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'address_number'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'address_number',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'address_complement'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'address_complement',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'address_neighborhood'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'address_neighborhood',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'edcenso_uf_fk'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'edcenso_uf_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'edcenso_city_fk'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'edcenso_city_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'edcenso_district_fk'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'edcenso_district_fk'); ?>
	</div>
    
	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'ddd'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'ddd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'phone_number'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'phone_number',array('size'=>9,'maxlength'=>9)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'public_phone_number'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'public_phone_number',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'other_phone_number'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'other_phone_number',array('size'=>9,'maxlength'=>9)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'fax_number'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'fax_number',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'email'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'email',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'edcenso_regional_education_organ_fk'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'edcenso_regional_education_organ_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'administrative_dependence'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'administrative_dependence'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'location'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'location'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'private_school_category'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'private_school_category'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'public_contract'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'public_contract'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'private_school_maintainer_fk'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'private_school_maintainer_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'private_school_maintainer_cnpj'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'private_school_maintainer_cnpj',array('size'=>14,'maxlength'=>14)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'private_school_cnpj'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'private_school_cnpj',array('size'=>14,'maxlength'=>14)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($modelSchoolIdentification,'regulation'); ?>
		<?php echo $form->textField($modelSchoolIdentification,'regulation'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
