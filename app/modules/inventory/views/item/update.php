<?php
/* @var $this ItemController */
/* @var $model InventoryItem */

$this->setPageTitle('TAG - Editar Item');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('movement/index'),
	'Itens'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Editar',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Editar Item: <?php echo $model->name; ?></h1>
            <p>Atualize as informações do item no catálogo.</p>
        </div>
    </div>

    <?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
