<?php
/* @var $this LessonPlanController */
/* @var $dataProvider CActiveDataProvider */
/* @var $stages array */
/* @var $disciplines array */
/* @var $filters array */

$this->setPageTitle('TAG - Planos MACETE');
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Planos MACETE</h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONPLAN_CREATE); ?>">
                    Novo plano
                </a>
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONRECORD_CREATE); ?>">
                    Registrar aula
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

    <form method="get" class="mobile-row align-items--end">
        <input type="hidden" name="r" value="macete/lessonPlan/index">
        <div class="column t-field-select clearfix is-one-quarter">
            <?php echo CHtml::label('Etapa', 'stage', ['class' => 't-field-select__label']); ?>
            <?php echo CHtml::dropDownList(
                'stage',
                $filters['stage'],
                CHtml::listData($stages, 'id', 'name'),
                [
                    'class' => 'select-search-on t-field-select__input',
                    'prompt' => 'Todas as etapas',
                ]
            ); ?>
        </div>
        <div class="column t-field-select clearfix is-one-quarter">
            <?php echo CHtml::label('Componente', 'discipline', ['class' => 't-field-select__label']); ?>
            <?php echo CHtml::dropDownList(
                'discipline',
                $filters['discipline'],
                CHtml::listData($disciplines, 'id', 'name'),
                [
                    'class' => 'select-search-on t-field-select__input',
                    'prompt' => 'Todos os componentes',
                ]
            ); ?>
        </div>
        <div class="column t-field-select clearfix is-one-quarter">
            <?php echo CHtml::label('Status', 'status', ['class' => 't-field-select__label']); ?>
            <?php echo CHtml::dropDownList(
                'status',
                $filters['status'],
                MaceteLessonPlan::statusLabels(),
                [
                    'class' => 'select-search-on t-field-select__input',
                    'prompt' => 'Todos os status',
                ]
            ); ?>
        </div>
        <div class="column t-buttons-container clearfix">
            <button type="submit" class="t-button-primary">Filtrar</button>
            <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONPLAN_INDEX); ?>">Limpar</a>
        </div>
    </form>

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
                            'header' => 'Plano',
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->name), MaceteRoutes::url(MaceteRoutes::LESSONPLAN_UPDATE, ["id" => $data->id]))',
                            'htmlOptions' => ['width' => '20%', 'class' => 'link-update-grid-view'],
                        ],
                        [
                            'header' => 'Tema',
                            'name' => 'theme',
                            'value' => '$data->theme',
                            'htmlOptions' => ['width' => '22%'],
                        ],
                        [
                            'header' => 'Professor',
                            'name' => 'users_fk',
                            'value' => '$data->usersFk !== null ? $data->usersFk->name : ""',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Etapas',
                            'name' => 'edcenso_stage_vs_modality_fk',
                            'value' => '$data->getStageNames()',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Componente',
                            'name' => 'edcenso_discipline_fk',
                            'value' => '$data->disciplineFk !== null ? $data->disciplineFk->name : ""',
                            'htmlOptions' => ['width' => '12%'],
                        ],
                        [
                            'header' => 'Habilidades',
                            'type' => 'raw',
                            'value' => 'CHtml::encode($data->getAbilityCodes())',
                            'htmlOptions' => ['width' => '10%'],
                        ],
                        [
                            'header' => 'Status',
                            'type' => 'raw',
                            'value' => '"<span class=\"" . $data->getStatusBadgeClass() . "\">" . CHtml::encode($data->getStatusLabel()) . "</span>"',
                            'htmlOptions' => ['width' => '8%'],
                        ],
                        [
                            'header' => 'Acoes',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{record}{delete}',
                            'buttons' => [
                                'update' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONPLAN_UPDATE, ["id" => $data->id])',
                                ],
                                'record' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/buttonIcon/start.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONRECORD_CREATE, ["lessonPlanId" => $data->id])',
                                    'options' => ['title' => 'Registrar aula'],
                                ],
                                'delete' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONPLAN_DELETE, ["id" => $data->id])',
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
