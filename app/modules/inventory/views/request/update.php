<?php
/* @var $this RequestController */
/* @var $model InventoryRequest */

$this->setPageTitle('TAG - Editar Solicitação');
$this->breadcrumbs=array(
	'Almoxarifado' => array('movement/index'),
	'Solicitações' => array('index'),
	'Editar Solicitação',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Editar Solicitação #<?php echo $model->id; ?></h1>
            <p>Altere os dados da solicitação enviada à Secretaria.</p>
        </div>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
    </div>
</div>
