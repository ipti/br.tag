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
    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
