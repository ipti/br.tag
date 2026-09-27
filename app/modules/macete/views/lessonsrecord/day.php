<?php
/* @var $this LessonsrecordController */
/* @var $classroom Classroom */
/* @var $date string */
/* @var $records MaceteLessonRecord[] */

$dateParts = explode('-', $date);
$formattedDate = $dateParts[2] . '/' . $dateParts[1] . '/' . $dateParts[0];
$this->setPageTitle('TAG - Aulas registradas - ' . $formattedDate);

$createUrl = MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_CREATE, [
    'classroomId' => $classroom->id,
    'date' => $date,
]);

$dataProvider = new CArrayDataProvider($records, [
    'id' => 'macete-day-records',
    'pagination' => false,
]);
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Aulas registradas</h1>
            <div class="t-buttons-container">
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_INDEX); ?>">Voltar</a>
                <a class="t-button-primary" href="<?php echo $createUrl; ?>">Registrar nova aula</a>
            </div>
        </div>
    </div>

    <div class="mobile-row">
        <div class="t-badge-info"><b>Turma: </b><?php echo CHtml::encode($classroom->name); ?></div>
        <div class="t-badge-info"><b>Data: </b><?php echo $formattedDate; ?></div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <?php if (empty($records)): ?>
        <div class="tag-inner">
            <div class="widget clearmargin">
                <div class="widget-body">
                    <p>Nenhuma aula registrada neste dia ainda.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
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
                                'type' => 'raw',
                                'value' => 'CHtml::link(CHtml::encode($data->lessonPlanFk !== null ? $data->lessonPlanFk->name : "—"), MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_UPDATE, ["id" => $data->id]))',
                                'htmlOptions' => ['width' => '30%', 'class' => 'link-update-grid-view'],
                            ],
                            [
                                'header' => 'Componente',
                                'value' => '$data->disciplineFk !== null ? $data->disciplineFk->name : "—"',
                                'htmlOptions' => ['width' => '20%'],
                            ],
                            [
                                'header' => 'Conteúdo executado',
                                'type' => 'raw',
                                'value' => 'CHtml::encode(mb_substr(strip_tags((string) $data->executed_content), 0, 150))',
                            ],
                            [
                                'header' => 'Acoes',
                                'class' => 'CButtonColumn',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => [
                                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                        'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_UPDATE, ["id" => $data->id])',
                                    ],
                                    'delete' => [
                                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                        'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_DELETE, ["id" => $data->id])',
                                    ],
                                ],
                                'updateButtonOptions' => ['style' => 'margin-right: 12px;'],
                                'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
                                'htmlOptions' => ['width' => '90px', 'style' => 'text-align: center'],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
