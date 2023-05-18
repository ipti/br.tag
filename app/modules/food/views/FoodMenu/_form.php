<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/* @var $form CActiveForm */
?>

<div class="form main">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-menu-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php echo $form->errorSummary($modelFoodMenu); ?>
	<div class="row">
		<div class="column">
			<div class="row">
				<div class="column">
					<?php echo $form->labelEx($modelFoodMenu,'description'); ?>
					<?php echo $form->textField($modelFoodMenu,'description',array('size'=>60,'maxlength'=>100)); ?>
					<?php echo $form->error($modelFoodMenu,'description'); ?>
				</div>
				<div class="column">
					<?php echo $form->labelEx($modelFoodMenu,'stage_fk'); ?>
					<?php echo $form->textField($modelFoodMenu,'stage_fk'); ?>
					<?php echo $form->error($modelFoodMenu,'stage_fk'); ?>
				</div>
			</div>
			<div class="row">
				<div class="column">
					<?php echo $form->labelEx($modelFoodMenu,'start_date'); ?>
					<?php echo $form->textField($modelFoodMenu,'start_date'); ?>
					<?php echo $form->error($modelFoodMenu,'start_date'); ?>
				</div>

				<div class="column">
					<?php echo $form->labelEx($modelFoodMenu,'final_date'); ?>
					<?php echo $form->textField($modelFoodMenu,'final_date'); ?>
					<?php echo $form->error($modelFoodMenu,'final_date'); ?>
				</div>
			</div>
		</div>
		
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($modelFoodMenu->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->