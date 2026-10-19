<?php
/* @var $this ClassFaultsController */
/* @var $data ClassFaults */
?>

<div class="view t-padding-small--all">
    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), ['view', 'id' => $data->id]); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('schedule_fk')); ?>:</b>
    <?php echo CHtml::encode($data->schedule_fk); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('student_fk')); ?>:</b>
    <?php echo CHtml::encode($data->student_fk); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('justification')); ?>:</b>
    <?php echo CHtml::encode($data->justification); ?>
    <br />
</div>
