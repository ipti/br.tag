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
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Editar Cotação: <?php echo CHtml::encode($model->title); ?></h1>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <?php $this->renderPartial('_form', [
        'model' => $model,
        'items' => $items,
        'suppliers' => $suppliers,
        'quotationItems' => $quotationItems,
    ]); ?>
</div>
