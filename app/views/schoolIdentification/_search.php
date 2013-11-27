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
		<?php echo $form->label($model,'inep_id'); ?>
		<?php echo $form->textField($model,'inep_id',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'situation'); ?>
		<?php echo $form->textField($model,'situation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'initial_date'); ?>
		<?php echo $form->textField($model,'initial_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'final_date'); ?>
		<?php echo $form->textField($model,'final_date',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude',array('size'=>20,'maxlength'=>20)); ?>
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
		<?php echo $form->label($model,'address_number'); ?>
		<?php echo $form->textField($model,'address_number',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address_complement'); ?>
		<?php echo $form->textField($model,'address_complement',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address_neighborhood'); ?>
		<?php echo $form->textField($model,'address_neighborhood',array('size'=>50,'maxlength'=>50)); ?>
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
		<?php echo $form->label($model,'edcenso_district_fk'); ?>
		<?php echo $form->textField($model,'edcenso_district_fk'); ?>
	</div>
    
	<div class="row">
		<?php echo $form->label($model,'ddd'); ?>
		<?php echo $form->textField($model,'ddd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone_number'); ?>
		<?php echo $form->textField($model,'phone_number',array('size'=>9,'maxlength'=>9)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'public_phone_number'); ?>
		<?php echo $form->textField($model,'public_phone_number',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_phone_number'); ?>
		<?php echo $form->textField($model,'other_phone_number',array('size'=>9,'maxlength'=>9)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fax_number'); ?>
		<?php echo $form->textField($model,'fax_number',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_regional_education_organ_fk'); ?>
		<?php echo $form->textField($model,'edcenso_regional_education_organ_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'administrative_dependence'); ?>
		<?php echo $form->textField($model,'administrative_dependence'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'location'); ?>
		<?php echo $form->textField($model,'location'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'private_school_category'); ?>
		<?php echo $form->textField($model,'private_school_category'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'public_contract'); ?>
		<?php echo $form->textField($model,'public_contract'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'private_school_maintainer_fk'); ?>
		<?php echo $form->textField($model,'private_school_maintainer_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'private_school_maintainer_cnpj'); ?>
		<?php echo $form->textField($model,'private_school_maintainer_cnpj',array('size'=>14,'maxlength'=>14)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'private_school_cnpj'); ?>
		<?php echo $form->textField($model,'private_school_cnpj',array('size'=>14,'maxlength'=>14)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'regulation'); ?>
		<?php echo $form->textField($model,'regulation'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->