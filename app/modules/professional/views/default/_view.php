<?php
/* @var $this ProfessionalController */
/* @var $data Professional */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_professional')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_professional), array('view', 'id'=>$data->id_professional)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cpf_professional')); ?>:</b>
	<?php echo CHtml::encode($data->cpf_professional); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('specialty')); ?>:</b>
	<?php echo CHtml::encode($data->specialty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inep_id_fk')); ?>:</b>
	<?php echo CHtml::encode($data->inep_id_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fundeb')); ?>:</b>
	<?php echo CHtml::encode($data->fundeb); ?>
	<br />


</div>