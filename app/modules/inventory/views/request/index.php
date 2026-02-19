<?php
/* @var $this RequestController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Solicitações da Escola');
$this->breadcrumbs=array(
	'Almoxarifado' => array('movement/index'),
	'Solicitações',
);
?>

<h1>Solicitações da Escola</h1>

<div class="row t-buttons-container">
    <?php echo CHtml::link('Nova Solicitação', array('create'), array('class'=>'t-button-primary')); ?>
</div>

<div class="row">
    <div class="column is-full">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php DataTableGridView::show(
                    $this,
                    array(
                        'id'=>'inventory-request-grid',
                        'dataProvider'=>$dataProvider,
                        'enableSorting' => false,
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
