<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('register_type')); ?>:</b>
	<?php echo CHtml::encode($data->register_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('school_inep_id_fk')); ?>:</b>
	<?php echo CHtml::encode($data->school_inep_id_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inep_id')); ?>:</b>
	<?php echo CHtml::encode($data->inep_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nis')); ?>:</b>
	<?php echo CHtml::encode($data->nis); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birthday')); ?>:</b>
	<?php echo CHtml::encode($data->birthday); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sex')); ?>:</b>
	<?php echo CHtml::encode($data->sex); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color_race')); ?>:</b>
	<?php echo CHtml::encode($data->color_race); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filiation')); ?>:</b>
	<?php echo CHtml::encode($data->filiation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mother_name')); ?>:</b>
	<?php echo CHtml::encode($data->mother_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('father_name')); ?>:</b>
	<?php echo CHtml::encode($data->father_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nationality')); ?>:</b>
	<?php echo CHtml::encode($data->nationality); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_nation_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_nation_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_uf_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_uf_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_city_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_city_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_blindness')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_blindness); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_low_vision')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_low_vision); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_deafness')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_deafness); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_disability_hearing')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_disability_hearing); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_deafblindness')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_deafblindness); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_phisical_disability')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_phisical_disability); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_intelectual_disability')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_intelectual_disability); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_multiple_disabilities')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_multiple_disabilities); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_autism')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_autism); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_aspenger_syndrome')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_aspenger_syndrome); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_rett_syndrome')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_rett_syndrome); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_childhood_disintegrative_disorder')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_childhood_disintegrative_disorder); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deficiency_type_gifted')); ?>:</b>
	<?php echo CHtml::encode($data->deficiency_type_gifted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_aid_lector')); ?>:</b>
	<?php echo CHtml::encode($data->resource_aid_lector); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_aid_transcription')); ?>:</b>
	<?php echo CHtml::encode($data->resource_aid_transcription); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_interpreter_guide')); ?>:</b>
	<?php echo CHtml::encode($data->resource_interpreter_guide); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_interpreter_libras')); ?>:</b>
	<?php echo CHtml::encode($data->resource_interpreter_libras); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_lip_reading')); ?>:</b>
	<?php echo CHtml::encode($data->resource_lip_reading); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_zoomed_test_16')); ?>:</b>
	<?php echo CHtml::encode($data->resource_zoomed_test_16); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_zoomed_test_20')); ?>:</b>
	<?php echo CHtml::encode($data->resource_zoomed_test_20); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_zoomed_test_24')); ?>:</b>
	<?php echo CHtml::encode($data->resource_zoomed_test_24); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_braille_test')); ?>:</b>
	<?php echo CHtml::encode($data->resource_braille_test); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_none')); ?>:</b>
	<?php echo CHtml::encode($data->resource_none); ?>
	<br />

	*/ ?>

</div>