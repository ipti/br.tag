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
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'inventory-quotation-form',
    'enableAjaxValidation' => false,
]); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model, 'title', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textField($model, 'title', ['size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input', 'placeholder' => 'Ex: Material de limpeza - 1º trimestre 2026']); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>

    <div class="row">
        <div class="column is-one-fourth t-field-text clearfix">
            <?php echo $form->labelEx($model, 'date', ['class' => 't-field-text__label']); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($model, 'date')); ?>
            <?php echo $form->error($model, 'date'); ?>
        </div>
        <?php
        Yii::app()->clientScript->registerScript('inventory-quotation-date-mask', "
            initDateFieldMaskAndValidation('#" . CHtml::activeId($model, 'date') . "');
        ");
?>
        <div class="column is-one-fourth t-field-select clearfix">
            <?php echo $form->labelEx($model, 'status', ['class' => 't-field-select__label']); ?>
            <?php echo $form->dropDownList($model, 'status', InventoryQuotation::getStatusList(), ['class' => 't-field-select__input select2-container']); ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>
    </div>

    <div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model, 'observation', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textArea($model, 'observation', ['rows' => 4, 'cols' => 50, 'class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'observation'); ?>
        </div>
    </div>

    <div class="column">
        <h2>Itens Cotados</h2>
    </div>

    <div class="js-quotation-items-rows" data-next-index="<?php echo count($quotationItems); ?>">
        <?php foreach ($quotationItems as $index => $quotationItem): ?>
            <div class="row js-quotation-item-row">
                <div class="column is-one-third t-field-select clearfix">
                    <label class="t-field-select__label">Item</label>
                    <?php echo CHtml::dropDownList(
    "quotationItems[$index][item_id]",
    $quotationItem['item_id'],
    $itemOptions,
    ['class' => 'select-search-on t-field-select__input select2-container', 'prompt' => 'Selecione o item']
); ?>
                </div>
                <div class="column is-one-third t-field-select clearfix">
                    <label class="t-field-select__label">Fornecedor</label>
                    <?php echo CHtml::dropDownList(
                        "quotationItems[$index][supplier_id]",
                        $quotationItem['supplier_id'],
                        $supplierOptions,
                        ['class' => 'select-search-on t-field-select__input select2-container', 'prompt' => 'Selecione o fornecedor']
                    ); ?>
                </div>
                <div class="column is-one-sixth t-field-text clearfix">
                    <label class="t-field-text__label">Valor Cotado (R$)</label>
                    <?php echo CHtml::textField(
                        "quotationItems[$index][quoted_value]",
                        $quotationItem['quoted_value'],
                        ['class' => 't-field-text__input', 'placeholder' => '0,00']
                    ); ?>
                </div>
                <div class="column is-one-sixth justify-content--end">
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
            <div class="column is-one-third t-field-select clearfix">
                <label class="t-field-select__label">Item</label>
                <?php echo CHtml::dropDownList(
                        'quotationItems[__index__][item_id]',
                        '',
                        $itemOptions,
                        ['class' => 'select-search-on t-field-select__input select2-container', 'prompt' => 'Selecione o item']
                    ); ?>
            </div>
            <div class="column is-one-third t-field-select clearfix">
                <label class="t-field-select__label">Fornecedor</label>
                <?php echo CHtml::dropDownList(
                    'quotationItems[__index__][supplier_id]',
                    '',
                    $supplierOptions,
                    ['class' => 'select-search-on t-field-select__input select2-container', 'prompt' => 'Selecione o fornecedor']
                ); ?>
            </div>
            <div class="column is-one-sixth t-field-text clearfix">
                <label class="t-field-text__label">Valor Cotado (R$)</label>
                <input type="text" class="t-field-text__input" name="quotationItems[__index__][quoted_value]" placeholder="0,00">
            </div>
            <div class="column is-one-sixth justify-content--end">
                <button type="button" class="t-button-secondary js-quotation-remove-item">Remover</button>
            </div>
        </div>
    </script>

    <div class="row t-buttons-container">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Criar Cotação' : 'Salvar Alterações', ['class' => 't-button-primary']); ?>
        <?php echo CHtml::link('Cancelar', ['index'], ['class' => 't-button-secondary']); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
