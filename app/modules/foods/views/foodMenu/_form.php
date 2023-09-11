<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-menu-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="main form-content">
		<div class="row">
			<div class="t-field-text column">
				<?php echo $form->labelEx($model, 'description', array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'description', array('class'=>'t-field-text__input')); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
			<div class="t-field-select column">
				<?php echo $form->labelEx($model,'stage_fk', array('class' => 't-field-select__label')); ?>
				<?php echo $form->dropDownList($model, 'stage_fk',[], array( 'class' => 'select-search-on t-field-select__input')); ?>
				<?php echo $form->error($model,'stage_fk'); ?>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<?php echo $form->labelEx($model,'start_date',  array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'start_date', array('class'=>'t-field-text__input')); ?>
				<?php echo $form->error($model,'start_date'); ?>
			</div>
			<div class="column">
				<?php echo $form->labelEx($model,'final_date', array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'final_date', array('class'=>'t-field-text__input')); ?>
				<?php echo $form->error($model,'final_date'); ?>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column is-half">
				<?php echo $form->labelEx($model,'observation', array('class' => 't-field-select__label')); ?>
				<?php echo $form->textField($model,'observation', array('class'=>'t-field-select__input')); ?>
				<?php echo $form->error($model,'observation'); ?>
			</div>
			<div class="column"></div>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->