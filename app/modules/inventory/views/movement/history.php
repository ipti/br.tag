<?php
/* @var $this MovementController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Histórico de Movimentações');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('index'),
	'Histórico',
);

$isAdmin = TagUtils::isAdmin();
?>

<style>
    .text-success { color: #28a745 !important; }
    .text-danger { color: #dc3545 !important; }
    .font-bold { font-weight: bold !important; }
</style>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Histórico de Movimentações</h1>
            <p>Registro cronológico de todas as entradas e saídas realizadas no sistema.</p>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Voltar ao Estoque', array('index'), array('class'=>'t-button-secondary')); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'inventory-movement-grid',
                        'dataProvider'=>$dataProvider,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enableSorting' => false,
                        'afterAjaxUpdate' => 'js:function(id, data){ initDatatable(); }',
                        'columns'=>array(
                            array(
                                'name' => 'date',
                                'header' => 'Data',
                                'value' => 'date("d/m/Y", strtotime($data->date))',
                            ),
                            array(
                                'name' => 'type',
                                'header' => 'Tipo',
                                'value' => '$data->type == InventoryMovement::TYPE_ENTRY ? "Entrada" : "Saída"',
                                'cssClassExpression' => '$data->type == InventoryMovement::TYPE_ENTRY ? "text-success font-bold" : "text-danger font-bold"',
                            ),
                            array(
                                'header' => 'Item',
                                'value' => '$data->item->name',
                            ),
                            array(
                                'name' => 'quantity',
                                'header' => 'Qtd',
                                'value' => '$data->quantity . " " . $data->item->unit',
                                'cssClassExpression' => '$data->type == InventoryMovement::TYPE_ENTRY ? "text-success font-bold" : "text-danger font-bold"',
                            ),
                            array(
                                'name' => 'destination',
                                'header' => 'Origem/Destino',
                            ),
                            array(
                                'header' => 'Escola',
                                'value' => '$data->school_inep_fk ? $data->school->name : "Almoxarifado Central"',
                                'visible' => $isAdmin,
                            ),
                            array(
                                'header' => 'Usuário',
                                'value' => '$data->user->name',
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
