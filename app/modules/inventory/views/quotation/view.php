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
            <p>
                Data: <?php echo Yii::app()->dateFormatter->format('dd/MM/yyyy', strtotime($model->date)); ?>
                &nbsp;·&nbsp;
                Situação:
                <span class="t-badge-<?php echo $model->status == InventoryQuotation::STATUS_OPEN ? 'info' : 'success'; ?>">
                    <?php echo $model->statusText; ?>
                </span>
            </p>
            <?php if (!empty($model->observation)): ?>
                <p><?php echo nl2br(CHtml::encode($model->observation)); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Editar', ['update', 'id' => $model->id], ['class' => 't-button-secondary']); ?>
        <?php echo CHtml::link('Voltar', ['index'], ['class' => 't-button-secondary']); ?>
    </div>

    <?php if (empty($itemsGrouped)): ?>
        <div class="row">
            <div class="column is-full">
                <p>Nenhum item cotado nesta cotação.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($itemsGrouped as $group): ?>
        <div class="row">
            <div class="column is-full">
                <div class="widget clearmargin">
                    <div class="widget-body">
                        <h2><?php echo CHtml::encode($group['name']); ?></h2>
                        <table class="tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center">
                            <thead>
                                <tr>
                                    <th>Fornecedor</th>
                                    <th>Valor Cotado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($group['quotes'] as $quote): ?>
                                    <tr>
                                        <td><?php echo CHtml::encode($quote->supplierFk->name); ?></td>
                                        <td>R$ <?php echo number_format((float) $quote->quoted_value, 2, ',', '.'); ?></td>
                                        <td>
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
            </div>
        </div>
    <?php endforeach; ?>
</div>
