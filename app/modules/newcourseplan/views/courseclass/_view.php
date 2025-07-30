<?php
/* @var $this CourseclassController */
/* @var $data CourseClass */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order')); ?>:</b>
	<?php echo CHtml::encode($data->order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('course_plan_fk')); ?>:</b>
	<?php echo CHtml::encode($data->course_plan_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fkid')); ?>:</b>
	<?php echo CHtml::encode($data->fkid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('methodology')); ?>:</b>
	<?php echo CHtml::encode($data->methodology); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	*/ ?>

</div>