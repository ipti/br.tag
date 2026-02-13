<?php
/* @var $this RequestController */
/* @var $model InventoryRequest */

$this->setPageTitle('TAG - Gerenciar Solicitações');
$this->breadcrumbs=array(
	'Almoxarifado' => array('movement/index'),
	'Gerenciar Solicitações',
);
?>

<style>
    table.dataTable {
        margin: 0 !important;
        width: 100% !important;
    }
</style>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Gerenciar Solicitações</h1>
            <p>Analise e responda às solicitações de itens das unidades escolares.</p>
        </div>
    </div>

    <div class="row">
        <div class="column is-full">
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php DataTableGridView::show(
                        $this,
                        array(
                            'id'=>'inventory-request-grid',
                            'dataProvider'=>$model->search(false),
                            'filter'=>$model,
                            'htmlOptions' => array('style' => 'width: 100%'),
                            'enableSorting' => false,
                            'columns'=>array(
                            array(
                                'name' => 'school_inep_fk',
                                'header' => 'Escola',
                                'value' => '$data->school->name',
                                'filter' => CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'),
                            ),
                            array(
                                'name' => 'item_id',
                                'value' => '$data->item->name',
                                'filter' => CHtml::listData(InventoryItem::model()->findAll(), 'id', 'name'),
                            ),
                            array(
                                'name' => 'quantity',
                                'value' => '$data->quantity . " " . $data->item->unit',
                            ),
                            array(
                                'name' => 'status',
                                'value' => '$data->getStatusText()',
                                'filter' => InventoryRequest::getStatusList(),
                            ),
                            array(
                                'name' => 'requested_at',
                                'value' => 'date("d/m/Y H:i", strtotime($data->requested_at))',
                            ),
                            array(
                                'header' => 'Ações',
                                'class' => 'CButtonColumn',
                                'template' => '{view} {update} {delete} {responder}',
                                'buttons' => array(
                                    'view' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/search-icon.svg',
                                    ),
                                    'update' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                    ),
                                    'delete' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    ),
                                    'responder' => array(
                                        'label' => 'Responder',
                                        'url' => 'Yii::app()->createUrl("inventory/request/view", array("id"=>$data->id))',
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/chevron-right.svg',
                                        'visible' => '$data->status == InventoryRequest::STATUS_PENDING',
                                    ),
                                ),                                
                                'htmlOptions' => array('width' => '150px', 'style' => 'text-align: center'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
