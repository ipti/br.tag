<?php
/* @var $this MovementController */
/* @var $model InventoryMovement */

$this->setPageTitle('TAG - Histórico de Movimentações');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('index'),
	'Histórico',
);
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
<script>
$(document).ready(function() {
    // Simple date mask function
    function maskDate(val) {
        val = val.replace(/\D/g, "");
        val = val.replace(/(\d{2})(\d)/, "$1/$2");
        val = val.replace(/(\d{2})(\d)/, "$1/$2");
        return val.substring(0, 10);
    }
    
    // Apply mask on input
    $(document).on('input', '.date-mask', function() {
        $(this).val(maskDate($(this).val()));
    });
});
</script>

                <div class="widget-header">
                    <h3 class="widget-title">Movimentações</h3>
                </div>
                <div class="widget-body">
                    <?php DataTableGridView::show($this, array(
                        'id'=>'inventory-movement-grid',
                        'dataProvider'=>$model->search(false),
                        'filter'=>$model,
                        'enableHistory'=>false,
                        'columns'=>array(
                            array(
                                'name' => 'date',
                                'header' => 'Data',
                                'value' => 'date("d/m/Y", strtotime($data->date))',
                                'filter' => CHtml::activeTextField($model, 'date', array('placeholder' => 'dd/mm/aaaa', 'class' => 'form-control date-mask')),
                            ),
                            array(
                                'name' => 'type',
                                'header' => 'Tipo',
                                'value' => '$data->type == InventoryMovement::TYPE_ENTRY ? "Entrada" : "Saída"',
                                'cssClassExpression' => '$data->type == InventoryMovement::TYPE_ENTRY ? "text-success font-bold" : "text-danger font-bold"',
                                'filter' => CHtml::activeDropDownList($model, 'type', array(
                                    InventoryMovement::TYPE_ENTRY => 'Entrada',
                                    InventoryMovement::TYPE_EXIT => 'Saída'
                                ), array('empty' => 'Todos', 'class' => 'form-control')),
                            ),
                            array(
                                'name' => 'item_id',
                                'header' => 'Item',
                                'value' => '$data->item->name',
                                'filter' => CHtml::activeDropDownList($model, 'item_id', 
                                    CHtml::listData(InventoryItem::model()->findAll(), 'id', 'name'),
                                    array('empty' => 'Todos', 'class' => 'form-control')
                                ),
                            ),
                            array(
                                'name' => 'quantity',
                                'header' => 'Qtd',
                                'value' => '$data->quantity . " " . $data->item->unit',
                                'cssClassExpression' => '$data->type == InventoryMovement::TYPE_ENTRY ? "text-success font-bold" : "text-danger font-bold"',
                                'filter' => false,
                            ),
                            array(
                                'name' => 'destination',
                                'header' => 'Origem/Destino',
                                'filter' => CHtml::activeTextField($model, 'destination', array('class' => 'form-control')),
                            ),
                            array(
                                'name' => 'school_inep_fk',
                                'header' => 'Escola',
                                'value' => '$data->school_inep_fk ? $data->school->name : "Almoxarifado Central"',
                                'visible' => $isAdmin,
                                'filter' => $isAdmin ? CHtml::activeDropDownList($model, 'school_inep_fk',
                                    CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'),
                                    array('empty' => 'Todos', 'class' => 'form-control')
                                ) : false,
                            ),
                            array(
                                'name' => 'user_id',
                                'header' => 'Usuário',
                                'value' => '$data->user->name',
                                'filter' => CHtml::activeDropDownList($model, 'user_id',
                                    CHtml::listData(Users::model()->findAll(), 'id', 'name'),
                                    array('empty' => 'Todos', 'class' => 'form-control')
                                ),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
