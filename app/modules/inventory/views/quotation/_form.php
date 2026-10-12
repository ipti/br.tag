<?php
/* @var $this QuotationController */
/* @var $model InventoryQuotation */
/* @var $items InventoryItem[] */
/* @var $suppliers InventorySupplier[] */
/* @var $quotationItems array linhas já cadastradas (update) ou vazio (create) */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->getModule('inventory')->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/quotation/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);

$itemOptions = CHtml::listData($items, 'id', 'name');
$supplierOptions = CHtml::listData($suppliers, 'id', 'name');

if (empty($quotationItems)) {
    $quotationItems = [['item_id' => '', 'supplier_id' => '', 'quoted_value' => '']];
}

$submitLabel = $model->isNewRecord ? 'Criar' : 'Salvar';
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'inventory-quotation-form',
    'enableAjaxValidation' => false,
]); ?>

    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">
                <?php echo $model->isNewRecord ? 'Nova Cotação de Preços' : CHtml::encode($model->title); ?>
            </h1>
            <?php if ($model->isNewRecord): ?>
                <p>Registre os valores cotados por fornecedor para os itens do almoxarifado.</p>
            <?php endif; ?>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <?php echo CHtml::submitButton($submitLabel, ['class' => 't-button-primary']); ?>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="t-tabs">
        <ul class="t-tabs__list">
            <li class="active t-tabs__item">
                <a class="t-tabs__link" href="#quotation" data-toggle="tab">
                    <span class="t-tabs__numeration">1</span>
                    Cotação
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content form-content">
        <div class="tab-pane active" id="quotation">
            <div class="row">
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'title', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'title', ['size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input', 'placeholder' => 'Ex: Material de limpeza - 1º trimestre 2026']); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </div>
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'date', ['class' => 't-field-text__label']); ?>
                    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($model, 'date')); ?>
                    <?php echo $form->error($model, 'date'); ?>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('inventory-quotation-date-mask', "
                    initDateFieldMaskAndValidation('#" . CHtml::activeId($model, 'date') . "');
                ");
?>
                <div class="column t-field-select">
                    <?php echo $form->labelEx($model, 'status', ['class' => 't-field-select__label']); ?>
                    <?php echo $form->dropDownList($model, 'status', InventoryQuotation::getStatusList(), ['class' => 't-field-select__input select2-container']); ?>
                    <?php echo $form->error($model, 'status'); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-text">
                    <?php echo $form->labelEx($model, 'observation', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textArea($model, 'observation', ['rows' => 4, 'cols' => 50, 'class' => 't-field-text__input']); ?>
                    <?php echo $form->error($model, 'observation'); ?>
                </div>
            </div>

            <div class="column">
                <h2>Itens Cotados</h2>
            </div>

            <div class="js-quotation-items-rows column" data-next-index="<?php echo count($quotationItems); ?>">
                <?php foreach ($quotationItems as $index => $quotationItem): ?>
                    <div class="row js-quotation-item-row">
                        <div class="column no-overflow is-one-third t-field-select clearfix">
                            <label class="t-field-select__label">Item</label>
                            <?php echo CHtml::dropDownList(
    "quotationItems[$index][item_id]",
    $quotationItem['item_id'],
    $itemOptions,
    ['class' => 'select-search-on t-field-select__input select2-container', 'style' => 'width: 100%', 'prompt' => 'Selecione o item']
); ?>
                        </div>
                        <div class="column no-overflow is-one-third t-field-select clearfix">
                            <label class="t-field-select__label">Fornecedor</label>
                            <?php echo CHtml::dropDownList(
                                "quotationItems[$index][supplier_id]",
                                $quotationItem['supplier_id'],
                                $supplierOptions,
                                ['class' => 'select-search-on t-field-select__input select2-container', 'style' => 'width: 100%', 'prompt' => 'Selecione o fornecedor']
                            ); ?>
                        </div>
                        <div class="column no-overflow is-one-sixth t-field-text clearfix">
                            <label class="t-field-text__label">Valor Cotado (R$)</label>
                            <?php echo CHtml::textField(
                                "quotationItems[$index][quoted_value]",
                                $quotationItem['quoted_value'],
                                ['class' => 't-field-text__input', 'placeholder' => '0,00']
                            ); ?>
                        </div>
                        <div class="column no-overflow is-one-sixth justify-content--end">
                            <button type="button" class="t-button-secondary js-quotation-remove-item">Remover</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="column">
                    <button type="button" class="t-button-secondary js-quotation-add-item">+ Adicionar item cotado</button>
                </div>
            </div>

            <script type="text/template" id="quotation-item-template">
                <div class="row js-quotation-item-row">
                    <div class="column no-overflow is-one-third t-field-select clearfix">
                        <label class="t-field-select__label">Item</label>
                        <?php echo CHtml::dropDownList(
                                'quotationItems[__index__][item_id]',
                                '',
                                $itemOptions,
                                ['class' => 'select-search-on t-field-select__input select2-container', 'style' => 'width: 100%', 'prompt' => 'Selecione o item']
                            ); ?>
                    </div>
                    <div class="column no-overflow is-one-third t-field-select clearfix">
                        <label class="t-field-select__label">Fornecedor</label>
                        <?php echo CHtml::dropDownList(
                            'quotationItems[__index__][supplier_id]',
                            '',
                            $supplierOptions,
                            ['class' => 'select-search-on t-field-select__input select2-container', 'style' => 'width: 100%', 'prompt' => 'Selecione o fornecedor']
                        ); ?>
                    </div>
                    <div class="column no-overflow is-one-sixth t-field-text clearfix">
                        <label class="t-field-text__label">Valor Cotado (R$)</label>
                        <input type="text" class="t-field-text__input" name="quotationItems[__index__][quoted_value]" placeholder="0,00">
                    </div>
                    <div class="column no-overflow is-one-sixth justify-content--end">
                        <button type="button" class="t-button-secondary js-quotation-remove-item">Remover</button>
                    </div>
                </div>
            </script>

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
