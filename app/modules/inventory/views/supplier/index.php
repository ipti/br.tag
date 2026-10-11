<?php
/* @var $this SupplierController */
/* @var $model InventorySupplier */

$this->setPageTitle('TAG - Fornecedores');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Fornecedores',
];
?>

<h1>Fornecedores</h1>

<div class="row t-buttons-container">
    <?php echo CHtml::link('Novo Fornecedor', ['create'], ['class' => 't-button-primary']); ?>
</div>

<div class="row">
    <div class="column is-full">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php DataTableGridView::show(
    $this,
    [
        'id' => 'inventory-supplier-grid',
        'dataProvider' => $model->search(false),
        'filter' => $model,
        'itemsCssClass' => 'items',
        'enableSorting' => false,
        'columns' => [
            [
                'name' => 'name',
                'header' => 'Nome',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, Yii::app()->createUrl("inventory/supplier/update", array("id"=>$data->id)))',
                'htmlOptions' => ['class' => 'link-update-grid-view'],
            ],
            [
                'name' => 'document',
                'header' => 'CNPJ/CPF',
            ],
            [
                'name' => 'phone',
                'header' => 'Telefone',
            ],
            [
                'name' => 'email',
                'header' => 'E-mail',
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
