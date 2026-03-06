<?php
/* @var $this ItemController */
/* @var $model InventoryItem */

$this->setPageTitle('TAG - Criar Item');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Itens' => ['index'],
    'Criar',
];
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Novo Item no Catálogo</h1>
            <p>Cadastre um novo item para ser utilizado no almoxarifado.</p>
        </div>
    </div>

    <?php $this->renderPartial('_form', ['model' => $model]); ?>
</div>
