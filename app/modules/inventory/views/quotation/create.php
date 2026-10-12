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
    <?php $this->renderPartial('_form', [
        'model' => $model,
        'items' => $items,
        'suppliers' => $suppliers,
        'quotationItems' => $quotationItems,
    ]); ?>
</div>
