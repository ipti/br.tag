<?php
/* @var $this SupplierController */
/* @var $model InventorySupplier */

$this->setPageTitle('TAG - Editar Fornecedor');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Fornecedores' => ['index'],
    'Editar: ' . CHtml::encode($model->name),
];
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Editar Fornecedor: <?php echo CHtml::encode($model->name); ?></h1>
        </div>
    </div>

    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
