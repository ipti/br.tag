<?php
/* @var $this MovementController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Almoxarifado');
$this->breadcrumbs=array(
	'Almoxarifado',
);

$isAdmin = TagUtils::isAdmin();
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Almoxarifado</h1>
            <p>Controle de estoque e movimentações da unidade escolar.</p>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php if ($isAdmin): ?>
            <?php echo CHtml::link('Lançar Entrada', array('createEntry'), array('class'=>'t-button-primary')); ?>
            <?php echo CHtml::link('Distribuir Itens', array('transfer'), array('class'=>'t-button-primary')); ?>
            <?php echo CHtml::link('Gerenciar Catálogo', array('item/index'), array('class'=>'t-button-secondary')); ?>
        <?php endif; ?>
        <?php echo CHtml::link('Lançar Saída', array('createExit'), array('class'=>'t-button-primary')); ?>
        <?php echo CHtml::link('Histórico de Movimentos', array('history'), array('class'=>'t-button-secondary')); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php if ($lowStockProvider->getItemCount() > 0): ?>
                <div class="t-alert t-alert--warning" style="margin-bottom: 20px; border: 1px solid #E98305; padding: 15px; border-radius: 4px; background-color: #FFF5E6;">
                    <h3 style="color: #E98305; margin-top: 0; font-size: 16px;"><i class="fa fa-warning"></i> Alertas de Estoque Baixo</h3>
                    <ul style="margin-bottom: 0;">
                        <?php foreach ($lowStockProvider->getData() as $stock): ?>
                            <li>
                                <strong><?php echo CHtml::encode($stock->item->name); ?></strong>: 
                                <?php echo $stock->quantity; ?> <?php echo $stock->item->unit; ?> 
                                (Mínimo: <?php echo $stock->item->minimum_stock; ?>)
                                <?php if ($isAdmin): ?> - <em><?php echo $stock->school_inep_fk ? CHtml::encode($stock->school->name) : "Almoxarifado Central"; ?></em><?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <h2 class="t-title-small">Estoque Atual</h2>
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php DataTableGridView::show($this, array(
                        'id'=>'inventory-stock-grid',
                        'dataProvider'=>$dataProvider,
                        'enableSorting' => false,
                        'columns'=>array(
                            array(
                                'name' => 'item_id',
                                'header' => 'Item',
                                'value' => '$data->item->name',
                            ),
                            array(
                                'name' => 'school_inep_fk',
                                'header' => 'Escola',
                                'value' => '$data->school_inep_fk ? $data->school->name : "Almoxarifado Central"',
                                'visible' => $isAdmin,
                            ),
                            array(
                                'name' => 'quantity',
                                'header' => 'Quantidade',
                                'value' => '$data->quantity . " " . $data->item->unit',
                            ),
                            array(
                                'class'=>'CButtonColumn',
                                'template'=>'{view}',
                                'buttons'=>array(
                                    'view' => array(
                                        'url'=>'Yii::app()->createUrl("inventory/movement/history", array("item_id"=>$data->item_id))',
                                    ),
                                ),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
