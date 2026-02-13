<?php
/* @var $this RequestController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Minhas Solicitações');
$this->breadcrumbs=array(
	'Almoxarifado' => array('movement/index'),
	'Solicitações',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Minhas Solicitações</h1>
            <p>Acompanhe o status dos seus pedidos para a Secretaria.</p>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Nova Solicitação', array('create'), array('class'=>'t-button-primary')); ?>
        <?php echo CHtml::link('Voltar ao Estoque', array('movement/index'), array('class'=>'t-button-secondary')); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'inventory-request-grid',
                        'dataProvider'=>$dataProvider,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enableSorting' => false,
                        'afterAjaxUpdate' => 'js:function(id, data){ initDatatable(); }',
                        'columns'=>array(
                            array(
                                'header' => 'Item',
                                'value' => '$data->item->name',
                            ),
                            array(
                                'name' => 'quantity',
                                'value' => '$data->quantity . " " . $data->item->unit',
                            ),
                            array(
                                'name' => 'status',
                                'value' => '$data->getStatusText()',
                                'cssClassExpression' => '($data->status == InventoryRequest::STATUS_PENDING ? "text-warning" : ($data->status == InventoryRequest::STATUS_APPROVED ? "text-success" : "text-danger"))',
                            ),
                            array(
                                'name' => 'requested_at',
                                'value' => 'date("d/m/Y H:i", strtotime($data->requested_at))',
                            ),
                            array(
                                'header' => 'Ações',
                                'class' => 'CButtonColumn',
                                'template' => '{view} {update} {delete}',
                                'buttons' => array(
                                    'view' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/search-icon.svg',
                                    ),
                                    'update' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                        'visible' => '$data->status == InventoryRequest::STATUS_PENDING',
                                    ),
                                    'delete' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                        'visible' => '$data->status == InventoryRequest::STATUS_PENDING',
                                    ),
                                ),                                
                                'htmlOptions' => array('width' => '120px', 'style' => 'text-align: center'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
