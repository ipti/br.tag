<div id="mainPage" class="main">
<?php
/* @var $this QuotationController */
/* @var $model InventoryQuotation */

$this->setPageTitle('TAG - Cotação de Preços');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Cotação de Preços',
];
?>

<h1>Cotação de Preços</h1>

<div class="row t-buttons-container">
    <?php echo CHtml::link('Nova Cotação', ['create'], ['class' => 't-button-primary']); ?>
    <?php echo CHtml::link('Fornecedores', ['supplier/index'], ['class' => 't-button-secondary']); ?>
</div>

<div class="row">
    <div class="column is-full clearfix">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php DataTableGridView::show(
    $this,
    [
        'id' => 'inventory-quotation-grid',
        'dataProvider' => $model->search(false),
        'itemsCssClass' => 'items',
        'enableSorting' => false,
        'columns' => [
            [
                'name' => 'title',
                'header' => 'Título',
                'type' => 'raw',
                'value' => 'CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl("inventory/quotation/view", array("id"=>$data->id)))',
                'htmlOptions' => ['class' => 'link-update-grid-view'],
            ],
            [
                'name' => 'date',
                'header' => 'Data',
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/yyyy", strtotime($data->date))',
            ],
            [
                'header' => 'Situação',
                'type' => 'raw',
                'value' => '"<span class=\"t-badge-" . ($data->status == InventoryQuotation::STATUS_OPEN ? "info" : "success") . "\">" . $data->statusText . "</span>"',
            ],
            [
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/search-icon.svg',
                    ],
                    'update' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                    ],
                    'delete' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                    ],
                ],
                'updateButtonOptions' => ['style' => 'margin-right: 20px;'],
                'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
                'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center'],
            ],
        ],
    ]
); ?>
            </div>
        </div>
    </div>
</div>
</div>
