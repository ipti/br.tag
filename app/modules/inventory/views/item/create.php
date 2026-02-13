<?php
/* @var $this ItemController */
/* @var $model InventoryItem */

$this->setPageTitle('TAG - Criar Item');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('movement/index'),
	'Itens'=>array('index'),
	'Criar',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Novo Item no Catálogo</h1>
            <p>Cadastre um novo item para ser utilizado no almoxarifado.</p>
        </div>
    </div>

    <?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
