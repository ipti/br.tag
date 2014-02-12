<?php
/* @var $this ClassesController */
/* @var $data Classes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discipline_fk')); ?>:</b>
	<?php echo CHtml::encode($data->discipline_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('classroom_fk')); ?>:</b>
	<?php echo CHtml::encode($data->classroom_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('day')); ?>:</b>
	<?php echo CHtml::encode($data->day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('month')); ?>:</b>
	<?php echo CHtml::encode($data->month); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('classtype')); ?>:</b>
	<?php echo CHtml::encode($data->classtype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('given_class')); ?>:</b>
	<?php echo CHtml::encode($data->given_class); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('schedule')); ?>:</b>
	<?php echo CHtml::encode($data->schedule); ?>
	<br />

	*/ ?>

</div>