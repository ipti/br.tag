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
		<?php echo $form->label($model,'student_inep_id'); ?>
		<?php echo $form->textField($model,'student_inep_id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'student_fk'); ?>
		<?php echo $form->textField($model,'student_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classroom_inep_id'); ?>
		<?php echo $form->textField($model,'classroom_inep_id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'classroom_fk'); ?>
		<?php echo $form->textField($model,'classroom_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'enrollment_id'); ?>
		<?php echo $form->textField($model,'enrollment_id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'unified_class'); ?>
		<?php echo $form->textField($model,'unified_class'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edcenso_stage_vs_modality_fk'); ?>
		<?php echo $form->textField($model,'edcenso_stage_vs_modality_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'another_scholarization_place'); ?>
		<?php echo $form->textField($model,'another_scholarization_place'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'public_transport'); ?>
		<?php echo $form->textField($model,'public_transport'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transport_responsable_government'); ?>
		<?php echo $form->textField($model,'transport_responsable_government'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_van'); ?>
		<?php echo $form->textField($model,'vehicle_type_van'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_microbus'); ?>
		<?php echo $form->textField($model,'vehicle_type_microbus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_bus'); ?>
		<?php echo $form->textField($model,'vehicle_type_bus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_bike'); ?>
		<?php echo $form->textField($model,'vehicle_type_bike'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_animal_vehicle'); ?>
		<?php echo $form->textField($model,'vehicle_type_animal_vehicle'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_other_vehicle'); ?>
		<?php echo $form->textField($model,'vehicle_type_other_vehicle'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_waterway_boat_5'); ?>
		<?php echo $form->textField($model,'vehicle_type_waterway_boat_5'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_waterway_boat_5_15'); ?>
		<?php echo $form->textField($model,'vehicle_type_waterway_boat_5_15'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_waterway_boat_15_35'); ?>
		<?php echo $form->textField($model,'vehicle_type_waterway_boat_15_35'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_waterway_boat_35'); ?>
		<?php echo $form->textField($model,'vehicle_type_waterway_boat_35'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehicle_type_metro_or_train'); ?>
		<?php echo $form->textField($model,'vehicle_type_metro_or_train'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'student_entry_form'); ?>
		<?php echo $form->textField($model,'student_entry_form'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->