<?php
/* @var $this QuotationController */
/* @var $model InventoryQuotation */
/* @var $itemsGrouped array ['item_id' => ['name' => string, 'quotes' => InventoryQuotationItem[], 'lowestValue' => float]] */

$this->setPageTitle('TAG - ' . $model->title);
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Cotação de Preços' => ['index'],
    CHtml::encode($model->title),
];
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo CHtml::encode($model->title); ?></h1>
            <div class="inventory-quotation__meta">
                <span>Data: <?php echo Yii::app()->dateFormatter->format('dd/MM/yyyy', strtotime($model->date)); ?></span>
                <span>·</span>
                <span>Situação:</span>
                <span class="t-badge-<?php echo $model->status == InventoryQuotation::STATUS_OPEN ? 'info' : 'success'; ?>">
                    <?php echo $model->statusText; ?>
                </span>
            </div>
            <?php if (!empty($model->observation)): ?>
                <p class="inventory-quotation__observation"><?php echo nl2br(CHtml::encode($model->observation)); ?></p>
            <?php endif; ?>
        </div>
        <div class="column clearfix align-items--center justify-content--end t-buttons-container">
            <?php echo CHtml::link('Editar', ['update', 'id' => $model->id], ['class' => 't-button-secondary']); ?>
            <?php if (!empty($itemsGrouped)): ?>
                <?php echo CHtml::link('<span class="t-icon-printer"></span>Gerar Relatório', ['report', 'id' => $model->id], ['class' => 't-button-primary', 'target' => '_blank']); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($itemsGrouped)): ?>
        <div class="row">
            <div class="column clearfix">
                <p>Nenhum item cotado nesta cotação.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($itemsGrouped as $group): ?>
        <div class="row inventory-quotation__group">
            <div class="column clearfix">
                <h2><?php echo CHtml::encode($group['name']); ?></h2>
                <table class="tag-table-primary tag-table table table-condensed table-striped table-hover table-vertical-center inventory-quotation__table">
                    <thead>
                        <tr>
                            <th class="is-supplier">Fornecedor</th>
                            <th class="is-value">Valor Cotado</th>
                            <th class="is-highlight"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($group['quotes'] as $quote): ?>
                            <tr>
                                <td class="is-supplier"><?php echo CHtml::encode($quote->supplierFk->name); ?></td>
                                <td class="is-value">R$ <?php echo number_format((float) $quote->quoted_value, 2, ',', '.'); ?></td>
                                <td class="is-highlight">
                                    <?php if ((float) $quote->quoted_value == $group['lowestValue']): ?>
                                        <span class="t-badge-success">Menor valor</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>
