<?php
/* @var $this ItemController */
/* @var $model InventoryItem */

$this->setPageTitle('TAG - Detalhes do Item');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('movement/index'),
	'Itens'=>array('index'),
	$model->name,
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Detalhes: <?php echo $model->name; ?></h1>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Editar', array('update', 'id'=>$model->id), array('class'=>'t-button-secondary')); ?>
        <?php echo CHtml::link('Voltar', array('index'), array('class'=>'t-button-secondary')); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php $this->widget('zii.widgets.CDetailView', array(
                'data'=>$model,
                'attributes'=>array(
                    'id',
                    'name',
                    'unit',
                    'description',
                    'created_at',
                    'updated_at',
                ),
            )); ?>
        </div>
    </div>
</div>
