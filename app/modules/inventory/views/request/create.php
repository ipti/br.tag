<?php
/* @var $this RequestController */
/* @var $model InventoryRequest */

$this->setPageTitle('TAG - Nova Solicitação');
$this->breadcrumbs=array(
	'Almoxarifado' => array('movement/index'),
	'Nova Solicitação',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Nova Solicitação de Itens</h1>
            <p>Solicite itens para a Secretaria de Educação.</p>
        </div>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
    </div>
</div>
