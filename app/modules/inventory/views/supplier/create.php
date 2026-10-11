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
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Novo Fornecedor</h1>
            <p>Cadastre um novo fornecedor para usar nas cotações de preço.</p>
        </div>
    </div>

    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
