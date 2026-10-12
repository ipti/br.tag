<?php
/* @var $this SupplierController */
/* @var $model InventorySupplier */
/* @var $form CActiveForm */

$submitLabel = $model->isNewRecord ? 'Criar Fornecedor' : 'Salvar Alterações';
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'inventory-supplier-form',
    'enableAjaxValidation' => false,
]); ?>

    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">
                <?php echo $model->isNewRecord ? 'Novo Fornecedor' : CHtml::encode($model->name); ?>
            </h1>
            <?php if ($model->isNewRecord): ?>
                <p>Cadastre um novo fornecedor para usar nas cotações de preço.</p>
            <?php endif; ?>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <?php echo CHtml::submitButton($submitLabel, ['class' => 't-button-primary']); ?>
        </div>
    </div>

    <?php echo $form->errorSummary($model); ?>

    <div class="t-tabs">
        <ul class="t-tabs__list">
            <li class="active t-tabs__item">
                <a class="t-tabs__link" href="#supplier" data-toggle="tab">
                    <span class="t-tabs__numeration">1</span>
                    Fornecedor
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content form-content">
        <div class="tab-pane active" id="supplier">
            <div class="row">
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'name', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input']); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'document', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'document', ['size' => 20, 'maxlength' => 20, 'class' => 't-field-text__input']); ?>
                    <?php echo $form->error($model, 'document'); ?>
                </div>
            </div>
            <div class="row">
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'phone', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'phone', ['size' => 20, 'maxlength' => 20, 'class' => 't-field-text__input']); ?>
                    <?php echo $form->error($model, 'phone'); ?>
                </div>
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'email', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'email', ['size' => 50, 'maxlength' => 150, 'class' => 't-field-text__input']); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row show--tablet">
        <div class="column clearfix">
            <div class="t-buttons-container">
                <?php echo CHtml::submitButton($submitLabel, ['class' => 't-button-primary']); ?>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
