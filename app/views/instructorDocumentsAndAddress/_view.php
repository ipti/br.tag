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

	<b><?php echo CHtml::encode($data->getAttributeLabel('cpf')); ?>:</b>
	<?php echo CHtml::encode($data->cpf); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area_of_residence')); ?>:</b>
	<?php echo CHtml::encode($data->area_of_residence); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cep')); ?>:</b>
	<?php echo CHtml::encode($data->cep); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_number')); ?>:</b>
	<?php echo CHtml::encode($data->address_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complement')); ?>:</b>
	<?php echo CHtml::encode($data->complement); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('neighborhood')); ?>:</b>
	<?php echo CHtml::encode($data->neighborhood); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_uf_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_uf_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edcenso_city_fk')); ?>:</b>
	<?php echo CHtml::encode($data->edcenso_city_fk); ?>
	<br />

	*/ ?>

</div>