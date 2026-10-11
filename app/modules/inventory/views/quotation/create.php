<?php
/* @var $this QuotationController */
/* @var $model InventoryQuotation */
/* @var $items InventoryItem[] */
/* @var $suppliers InventorySupplier[] */
/* @var $quotationItems array */

$this->setPageTitle('TAG - Nova Cotação de Preços');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Cotação de Preços' => ['index'],
    'Nova Cotação',
];
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Nova Cotação de Preços</h1>
            <p>Registre os valores cotados por fornecedor para os itens do almoxarifado.</p>
        </div>
    </div>

    <?php $this->renderPartial('_form', [
        'model' => $model,
        'items' => $items,
        'suppliers' => $suppliers,
        'quotationItems' => $quotationItems,
    ]); ?>
</div>
