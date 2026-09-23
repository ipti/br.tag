<?php
/* @var $this TemplatesController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Planos Modelo MACETE');
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Planos Modelo MACETE</h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo MaceteRoutes::url(MaceteRoutes::TEMPLATES_CREATE); ?>">
                    Novo modelo
                </a>
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_INDEX); ?>">
                    Voltar para planos
                </a>
            </div>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', [
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center',
                    'columns' => [
                        [
                            'header' => 'Modelo',
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->name), MaceteRoutes::url(MaceteRoutes::TEMPLATES_UPDATE, ["id" => $data->id]))',
                            'htmlOptions' => ['width' => '22%', 'class' => 'link-update-grid-view'],
                        ],
                        [
                            'header' => 'Código',
                            'name' => 'code',
                            'value' => 'CHtml::encode($data->code)',
                            'htmlOptions' => ['width' => '10%'],
                        ],
                        [
                            'header' => 'Etapas',
                            'name' => 'edcenso_stage_vs_modality_fk',
                            'value' => '$data->getStageNames()',
                            'htmlOptions' => ['width' => '16%'],
                        ],
                        [
                            'header' => 'Componente',
                            'name' => 'edcenso_discipline_fk',
                            'value' => '$data->getDisciplineNames()',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Habilidades',
                            'type' => 'raw',
                            'value' => 'CHtml::encode($data->getAbilityCodes())',
                            'htmlOptions' => ['width' => '12%'],
                        ],
                        [
                            'header' => 'Criado por',
                            'name' => 'created_by_users_fk',
                            'value' => '$data->createdByUsersFk !== null ? $data->createdByUsersFk->name : ""',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Acoes',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{use}{delete}',
                            'buttons' => [
                                'update' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::TEMPLATES_UPDATE, ["id" => $data->id])',
                                ],
                                'use' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/buttonIcon/start.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_CREATE, ["templateId" => $data->id])',
                                    'options' => ['title' => 'Usar como plano'],
                                ],
                                'delete' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::TEMPLATES_DELETE, ["id" => $data->id])',
                                ],
                            ],
                            'updateButtonOptions' => ['style' => 'margin-right: 12px;'],
                            'deleteButtonOptions' => ['style' => 'cursor: pointer; margin-left: 12px;'],
                            'htmlOptions' => ['width' => '90px', 'style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
