<?php
/* @var $this ClassBoardController */
/* @var $data ClassBoard */
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_monday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_monday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_tuesday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_tuesday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_wednesday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_wednesday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_thursday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_thursday); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_friday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_friday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_saturday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_saturday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_day_sunday')); ?>:</b>
	<?php echo CHtml::encode($data->week_day_sunday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estimated_classes')); ?>:</b>
	<?php echo CHtml::encode($data->estimated_classes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('given_classes')); ?>:</b>
	<?php echo CHtml::encode($data->given_classes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('replaced_classes')); ?>:</b>
	<?php echo CHtml::encode($data->replaced_classes); ?>
	<br />

	*/ ?>

</div>