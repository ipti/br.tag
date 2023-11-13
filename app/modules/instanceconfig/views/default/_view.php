<?php
/* @var $this InstanceConfigController */
/* @var $data InstanceConfig */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('launch_grades')); ?>:</b>
	<?php echo CHtml::encode($data->launch_grades); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sedsp_sync')); ?>:</b>
	<?php echo CHtml::encode($data->sedsp_sync); ?>
	<br />


</div>