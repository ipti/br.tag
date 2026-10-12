<?php
/* @var $this QuotationController */
/* @var $model InventoryQuotation */
/* @var $items InventoryItem[] */
/* @var $suppliers InventorySupplier[] */
/* @var $quotationItems array */

$this->setPageTitle('TAG - Editar Cotação de Preços');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Cotação de Preços' => ['index'],
    CHtml::encode($model->title) => ['view', 'id' => $model->id],
    'Editar',
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
