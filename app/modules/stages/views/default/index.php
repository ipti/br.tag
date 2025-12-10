<?php
/** @var DefaultController $this EdcensoDisciplineController */
/** @var EdcensoDiscipline $model EdcensoDiscipline */

$this->setPageTitle('TAG - Adicionar Etapas');
$title = 'Adicionar Etapas';

?>


<div id="mainPage" class="main">
    <div class="row-fluid box-instructor">
        <div class="span12">
            <h1>Adicionar Etapas</h1>
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('stages/default/create')?>">Adicionar Etapas</a>
            </div>
        </div>
        <div class="btn-group pull-right mt-30 responsive-menu dropdown-margin">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::app()->createUrl('stages/default/create')?>"><i></i> Adicionar Etapa</a></li>
            </ul>
        </div>
    </div>
<div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', [
                    'id' => 'edcenso-discipline-grid',
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => [
                        'id',
                        'name',
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
                                    'visible' => '$data->is_edcenso_stage == 0',
                                ]
                            ],
                            'updateButtonOptions' => ['style' => 'margin-right: 20px;', 'class' => 'stageUpdate'],
                            'deleteButtonOptions' => ['style' => 'cursor: pointer;', 'class' => 'stageDelete'],
                            'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
