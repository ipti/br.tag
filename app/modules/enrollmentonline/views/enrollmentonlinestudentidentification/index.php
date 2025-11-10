<?php
/* @var $this DefaultController */

$this->breadcrumbs = [
    'Enrollment Online Student Identifications',
];

$this->menu = [
    ['label' => 'Create EnrollmentOnlineStudentIdentification', 'url' => ['create']],
    ['label' => 'Manage EnrollmentOnlineStudentIdentification', 'url' => ['admin']],
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
                        array(
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => '$data->name',
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
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>
