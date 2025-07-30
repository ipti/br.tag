<?php
/* @var $this CoursePlanController */
/* @var $data CoursePlan */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('school_inep_fk')); ?>:</b>
	<?php echo CHtml::encode($data->school_inep_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modality_fk')); ?>:</b>
	<?php echo CHtml::encode($data->modality_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_fk')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('users_fk')); ?>:</b>
	<?php echo CHtml::encode($data->users_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fkid')); ?>:</b>
	<?php echo CHtml::encode($data->fkid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('situation')); ?>:</b>
	<?php echo CHtml::encode($data->situation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observation')); ?>:</b>
	<?php echo CHtml::encode($data->observation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	*/ ?>

</div>