<?php
/* @var $this ClassFaultsController */
/* @var $data ClassFaults */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('class_fk')); ?>:</b>
	<?php echo CHtml::encode($data->class_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('student_fk')); ?>:</b>
	<?php echo CHtml::encode($data->student_fk); ?>
	<br />


</div>