<?php
/* @var $this LessonRecordController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Registros MACETE');
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Registros de Aula MACETE</h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONRECORD_CREATE); ?>">
                    Registrar aula
                </a>
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONPLAN_INDEX); ?>">
                    Planos MACETE
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
                            'header' => 'Data',
                            'name' => 'lesson_date',
                            'value' => 'date("d/m/Y", strtotime($data->lesson_date))',
                            'htmlOptions' => ['width' => '10%'],
                        ],
                        [
                            'header' => 'Plano',
                            'name' => 'lesson_plan_fk',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->lessonPlanFk->name), MaceteRoutes::url(MaceteRoutes::LESSONRECORD_UPDATE, ["id" => $data->id]))',
                            'htmlOptions' => ['width' => '20%', 'class' => 'link-update-grid-view'],
                        ],
                        [
                            'header' => 'Turma',
                            'name' => 'classroom_fk',
                            'value' => '$data->classroomFk !== null ? $data->classroomFk->name : ""',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Componente',
                            'name' => 'edcenso_discipline_fk',
                            'value' => '$data->disciplineFk !== null ? $data->disciplineFk->name : ""',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Professor',
                            'name' => 'users_fk',
                            'value' => '$data->usersFk !== null ? $data->usersFk->name : ""',
                            'htmlOptions' => ['width' => '16%'],
                        ],
                        [
                            'header' => 'Habilidades',
                            'type' => 'raw',
                            'value' => 'CHtml::encode($data->getAbilityCodes())',
                            'htmlOptions' => ['width' => '12%'],
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
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'update' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONRECORD_UPDATE, ["id" => $data->id])',
                                ],
                                'delete' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONRECORD_DELETE, ["id" => $data->id])',
                                ],
                            ],
                            'updateButtonOptions' => ['style' => 'margin-right: 20px;'],
                            'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
                            'htmlOptions' => ['width' => '80px', 'style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

