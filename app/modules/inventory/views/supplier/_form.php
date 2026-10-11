<?php
/* @var $this SupplierController */
/* @var $model InventorySupplier */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'inventory-supplier-form',
    'enableAjaxValidation' => false,
]); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model, 'name', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="column is-one-third t-field-text clearfix">
            <?php echo $form->labelEx($model, 'document', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model, 'document', ['size' => 20, 'maxlength' => 20, 'class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'document'); ?>
        </div>
        <div class="column is-one-third t-field-text clearfix">
            <?php echo $form->labelEx($model, 'phone', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model, 'phone', ['size' => 20, 'maxlength' => 20, 'class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>
        <div class="column is-one-third t-field-text clearfix">
            <?php echo $form->labelEx($model, 'email', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model, 'email', ['size' => 50, 'maxlength' => 150, 'class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Criar Fornecedor' : 'Salvar Alterações', ['class' => 't-button-primary']); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
