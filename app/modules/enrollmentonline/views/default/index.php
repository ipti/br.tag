<?php
/* @var $this DefaultController */

$this->breadcrumbs = [
    $this->module->id,
];
?>


<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Matricula Online'));
?>
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Matricula Online') ?></h1>
        </div>
    </div>

    <div class="tag-inner">

        <div class="">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
            <?php elseif (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            <?php else: ?>
                <div class="alert no-show"></div>
            <?php endif; ?>
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php
                $columns = [];

array_push(
    $columns,
    [
        'name' => 'name',
        'type' => 'raw',
        'value' => '"Jonh Doe Santos da Silva"',
        'htmlOptions' => ['width' => '300px', 'class' => 'link-update-grid-view'],
    ]
);
array_push(
    $columns,
    [
        'header' => 'Cpf',
        'value' => '"06856356298"',
        'htmlOptions' => ['width' => '200px'],
        'filter' => false
    ]
);
array_push(
    $columns,
    [
        'header' => 'Responsável',
        'value' => '"José Bezerra"',
        'htmlOptions' => ['width' => '200px'],
        'filter' => false
    ]
);
array_push(
    $columns,
    [
        'header' => 'Etapa',
        'type' => 'raw',
        'value' => '$data->edcensoStageVsModalityFk->name',
        'htmlOptions' => ['width' => '300px', 'class' => 'link-update-grid-view'],
    ]
);
array_push(
    $columns,
    [
        'name' => 'Status',
        'type' => 'raw',
        'value' => '"Aguardando"',
        'htmlOptions' => ['width' => '150px', 'class' => 'link-update-grid-view'],
    ]
);
array_push(
    $columns,
    [
        'name' => 'enrollmentStatus',
        'header' => 'Mat. Status',
        'value' => '"Criada"',
        'htmlOptions' => ['width' => '150px', 'class' => 'link-update-grid-view'],
    ]
);
array_push(
    $columns,
    [
        'name' => 'edcensoStageVsModalityFk',
        'header' => 'Rematricula',
        'value' => '"Não"',
        'htmlOptions' => ['width' => '100px'],
    ]
);
array_push(
    $columns,
    [
        'header' => 'Prioriedade',
        'value' => '"Sim"',
        'htmlOptions' => ['width' => '100px'],
        'filter' => false
    ]
);

array_push(
    $columns,
    [
        'header' => 'Ações',
        'class' => 'CButtonColumn',
        'template' => '{update}{delete}',
        'buttons' => [
            'update' => [
                'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',

            ],
            'delete' => [
                'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
            ]
        ],
        'updateButtonOptions' => ['style' => 'margin-right: 20px;'],
        'afterDelete' => 'function(link, success, data){
                                data = JSON.parse(data);
                                data.valid
                                    ? $(".alert").addClass("alert-success").removeClass("alert-error").removeClass("no-show").text(data.message)
                                    : $(".alert").removeClass("alert-success").addClass("alert-error").removeClass("no-show").text(data.message);
                            }',
        'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center;'],
    ]
);

$this->widget('zii.widgets.grid.CGridView', [
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed
                        table-striped table-hover table-primary table-vertical-center checkboxs',
    // 'afterAjaxUpdate' => 'function(id, data){initDatatable()}', // TODO: essa linha está causando erro quando SEDSP desabiitado
    'columns' => $columns,
]);
?>
                </div>
            </div>
        </div>

    </div>
</div>
