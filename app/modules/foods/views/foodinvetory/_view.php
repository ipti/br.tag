<?php
/* @var $this FoodInventoryController */
/* @var $data FoodInventory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('school_fk')); ?>:</b>
	<?php echo CHtml::encode($data->school_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('food_fk')); ?>:</b>
	<?php echo CHtml::encode($data->food_fk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('measurementUnit')); ?>:</b>
	<?php echo CHtml::encode($data->measurementUnit); ?>
	<br />


</div>