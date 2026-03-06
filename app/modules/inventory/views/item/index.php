<?php
/* @var $this ItemController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Catálogo de Itens');
$this->breadcrumbs = [
    'Almoxarifado' => ['movement/index'],
    'Catálogo',
];

$isAdmin = TagUtils::isAdmin();
?>

<h1>Catálogo de Itens</h1>

<div class="row t-buttons-container">
    <?php echo CHtml::link('Novo Item', ['create'], ['class' => 't-button-primary']); ?>
</div>

<div class="row">
    <div class="column is-full">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php DataTableGridView::show(
    $this,
    [
        'id' => 'inventory-item-grid',
        'dataProvider' => $model->search(false),
        'filter' => $model,
        'itemsCssClass' => 'items', // Reset to default or custom
        'enableSorting' => false,
        'columns' => [
            [
                'name' => 'id',
                'header' => 'ID',
                'htmlOptions' => ['width' => '50px']
            ],
            [
                'name' => 'name',
                'header' => 'Nome',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, Yii::app()->createUrl("inventory/item/update", array("id"=>$data->id)))',
                'htmlOptions' => ['class' => 'link-update-grid-view'],
            ],
            [
                'name' => 'unit',
                'header' => 'Unidade',
            ],
            [
                'name' => 'description',
                'header' => 'Descrição',
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
                    ]
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
