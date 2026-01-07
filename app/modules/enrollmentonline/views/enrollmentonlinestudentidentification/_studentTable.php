<?php
$columns = [];

array_push(
    $columns,
    array(
        'name' => 'name',
        'type' => 'raw',
        'value' => ' CHtml::link($data->name,Yii::app()->createUrl("enrollmentonline/enrollmentonlinestudentidentification/update",array("id"=>$data->id)))',
        'htmlOptions' => array('width' => '300px', 'class' => 'link-update-grid-view'),
    )
);
array_push(
    $columns,
    array(
        'header' => 'Cpf',
        'value' => '$data->cpf',
        'htmlOptions' => array('width' => '200px'),
        'filter' => false
    )
);
array_push(
    $columns,
    array(
        'header' => 'Responsável',
        'value' => '$data->responsable_name',
        'htmlOptions' => array('width' => '200px'),
        'filter' => false
    )
);
array_push(
    $columns,
    array(
        'header' => 'Etapa',
        'type' => 'raw',
        'value' => '$data->edcensoStageVsModalityFk->name',
        'htmlOptions' => array('width' => '300px', 'class' => 'link-update-grid-view'),
    )
);
array_push(
    $columns,
    array(
        'header' => 'Ações',
        'class' => 'CButtonColumn',
        'template' => '{update}{delete}',
        'buttons' => array(
            'update' => array(
                'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',

            ),
            'delete' => array(
                'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
            )
        ),
        'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
        'afterDelete' => 'function(link, success, data){
                                data = JSON.parse(data);
                                data.valid
                                    ? $(".alert").addClass("alert-success").removeClass("alert-error").removeClass("no-show").text(data.message)
                                    : $(".alert").removeClass("alert-success").addClass("alert-error").removeClass("no-show").text(data.message);
                            }',
        'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center;'),
    )
);


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed
                        table-striped table-hover table-primary table-vertical-center checkboxs',
    // Remova qualquer inicialização automática do DataTables se não estiver usando DataTables
    'afterAjaxUpdate' => 'function(id, data){initDatatable()}', // TODO: essa linha está causando erro quando SEDSP desabiitado
    'columns' => $columns,
));
