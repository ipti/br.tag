<?php
/* @var $this SupplierController */
/* @var $model InventorySupplier */

$this->setPageTitle('TAG - Criar Fornecedor');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Fornecedores' => ['index'],
    'Criar',
];
?>

<div id="mainPage" class="main">
    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
