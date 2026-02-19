<?php
/* @var $this ItemController */
/* @var $model InventoryItem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventory-item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model,'name', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
	</div>

	<div class="row">
        <div class="column is-one-fourth t-field-text clearfix">
            <?php echo $form->labelEx($model,'unit', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model,'unit',array('size'=>50,'maxlength'=>50, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'unit'); ?>
        </div>
        <div class="column is-one-fourth t-field-text clearfix">
            <?php echo $form->labelEx($model,'minimum_stock', ['class' => 't-field-text__label']); ?>
            <?php echo $form->numberField($model,'minimum_stock',array('size'=>10, 'class' => 't-field-text__input', 'step' => 'any', 'min' => '0')); ?>
            <?php echo $form->error($model,'minimum_stock'); ?>
        </div>
    </div>

	<div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model,'description', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'description'); ?>
        </div>
	</div>

	<div class="row t-buttons-container">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Criar Item' : 'Salvar Alterações', ['class' => 't-button-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
