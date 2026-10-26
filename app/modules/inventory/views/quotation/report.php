<?php
/* @var $this QuotationController */
/* @var $model InventoryQuotation */
/* @var $itemsGrouped array ['item_id' => ['name' => string, 'quotes' => InventoryQuotationItem[], 'lowestValue' => float]] */
/* @var $school SchoolIdentification|null */

$this->setPageTitle('TAG - Relatório de Cotação - ' . $model->title);
?>

<div class="inventory-quotation-report">
    <div class="inventory-quotation-report__actions">
        <button type="button" class="t-button-secondary" onclick="window.print()">
            <span class="t-icon-printer"></span>Imprimir
        </button>
    </div>

    <div class="inventory-quotation-report__header">
        <?php if ($school !== null): ?>
            <p>PREFEITURA MUNICIPAL DE <?php echo CHtml::encode(mb_strtoupper($school->edcensoCityFk->name ?? '')); ?></p>
            <p><?php echo CHtml::encode($school->name); ?></p>
        <?php endif; ?>
        <p>ALMOXARIFADO</p>
        <h1>Relatório de Cotação de Preços</h1>
    </div>

    <div class="inventory-quotation-report__meta">
        <p><strong>Cotação:</strong> <?php echo CHtml::encode($model->title); ?></p>
        <p><strong>Data:</strong> <?php echo Yii::app()->dateFormatter->format('dd/MM/yyyy', strtotime($model->date)); ?></p>
        <p><strong>Situação:</strong> <?php echo $model->statusText; ?></p>
        <?php if (!empty($model->observation)): ?>
            <p><strong>Observações:</strong> <?php echo nl2br(CHtml::encode($model->observation)); ?></p>
        <?php endif; ?>
    </div>

    <?php if (empty($itemsGrouped)): ?>
        <p>Nenhum item cotado nesta cotação.</p>
    <?php endif; ?>

    <?php foreach ($itemsGrouped as $group): ?>
        <div class="inventory-quotation-report__group">
            <h2><?php echo CHtml::encode($group['name']); ?></h2>
            <table class="inventory-quotation-report__table">
                <thead>
                    <tr>
                        <th class="is-supplier">Fornecedor</th>
                        <th class="is-value">Valor Cotado</th>
                        <th class="is-highlight">Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($group['quotes'] as $quote): ?>
                        <?php $isLowest = (float) $quote->quoted_value == $group['lowestValue']; ?>
                        <tr class="<?php echo $isLowest ? 'is-lowest' : ''; ?>">
                            <td class="is-supplier"><?php echo CHtml::encode($quote->supplierFk->name); ?></td>
                            <td class="is-value">R$ <?php echo number_format((float) $quote->quoted_value, 2, ',', '.'); ?></td>
                            <td class="is-highlight"><?php echo $isLowest ? 'Menor valor' : ''; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>

    <div class="inventory-quotation-report__footer">
        Gerado em <?php echo date('d/m/Y \à\s H:i'); ?>
    </div>
</div>
