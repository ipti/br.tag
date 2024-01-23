<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Classrooms'));

    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new Classroom'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
    );

    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/classroom/index/functions.js?v='.TAG_VERSION, CClientScript::POS_END);

    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Classrooms') ?></h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('classroom/create') ?>"> Adicionar
                    turma</a>
                <div class="mobile-row">
                    <a class="t-button-secondary"
                       href="<?php echo Yii::app()->createUrl('reports/numberstudentsperclassroomreport') ?>"
                       target="_blank">Relatório Alunos/Turma</a>
                    <a class="t-button-secondary"
                       href="<?php echo Yii::app()->createUrl('reports/instructorsperclassroomreport') ?>"
                       target="_blank">Relatório Professores/Turma</a>
                </div>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="btn-group pull-right responsive-menu">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::app()->createUrl('classroom/create') ?>" class=""><i></i> Adicionar
                        turma</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('reports/numberstudentsperclassroomreport') ?>"
                       class=""><i></i>Relatório Alunos/Turma</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('reports/instructorsperclassroomreport') ?>"
                       class=""><i></i>Relatório Professores/Turma</a></li>
            </ul>
        </div>
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
                    array_push($columns,
                        array(
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name,Yii::app()->createUrl("classroom/update",array("id"=>$data->id)))',
                            'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
                        )
                    );
                    array_push($columns,
                        array(
                            'name' => 'enrollmentsCount',
                            'header' => 'Mat. Ativas / Total',
                            'value' => '$data->activeEnrollmentsCount ."/". $data->enrollmentsCount',
                        )
                    );
                    array_push($columns,
                        array(
                            'name' => 'edcensoStageVsModalityFk',
                            'header' => 'Etapa',
                            'value' => '$data->edcensoStageVsModalityFk->name',
                            'htmlOptions' => array('width' => '400px'),
                        )
                    );
                    array_push($columns,
                        array(
                            'header' => 'Horário',
                            'value' => '$data->initial_hour.":".$data->initial_minute." - ".$data->final_hour.":".$data->final_minute',
                            'htmlOptions' => array('width' => '200px'),
                            'filter' => false
                        )
                    );
                    array_push($columns,
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
                    if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                        array_push($columns,
                            array(
                                'header' => 'Sincronizado',
                                'class' => 'CButtonColumn',
                                'template' => '{sync}{unsync}',
                                'buttons' => array(
                                    'sync' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/SyncTrue.png',
                                        'visible' => '$data->sedsp_sync',
                                        'options' => array('class' => 'sync', 'style' => "width: 25px; display: inline-block")
                                    ),
                                    'unsync' => array(
                                        'url' => 'Yii::app()->createUrl("classroom/syncToSedsp",array("id"=>$data->id))',
                                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/notSync.png',
                                        'visible' => '!$data->sedsp_sync',
                                        'options' => array('class' => 'unsync', 'style' => "width: 25px; display: inline-block")
                                    ),
                                ),
                                'htmlOptions' => array('style' => 'text-align: center'),
                            )
                        );
                    }

                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => false,
                        'enableSorting' => false,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed
                        table-striped table-hover table-primary table-vertical-center checkboxs',
                        // 'afterAjaxUpdate' => 'function(id, data){initDatatable()}', // TODO: essa linha está causando erro quando SEDSP desabiitado
                        'columns' => $columns,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="t-menu-item__link">
            <a href="<?php echo Yii::app()->createUrl("wizard/configuration/classroom"); ?>"><?php echo Yii::t('default', 'Reaproveitamento de Turmas') . ' ' . (Yii::app()->user->year - 1) ?></a>
        </div>
    </div>
</div>

<div class="modal fade modal-content" id="syncClassroomToSEDSP" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"
                id="myModalLabel">Sincronizar Turma para o SEDSP</h4>
        </div>
        <form method="post" action="">
            <div class="centered-loading-gif">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <div class="modal-body">
                <div class="alert alert-error no-show"></div>
                <div class="row-fluid">
                    Você tem certeza?
                    <input type="hidden" name="sync-classroom-url" id="sync-classroom-url" value="">
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Cancelar
                    </button>
                    <button type="button"
                            class="btn btn-primary sync-classroom-button">Confirmar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
