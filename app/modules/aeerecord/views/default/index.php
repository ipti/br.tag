<?php
/** @var $this StudentAeeRecordController */
/** @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Ficha AEE'));
$this->breadcrumbs = [
    'Student Aee Records',
];

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->menu = [
    ['label' => 'Create StudentAeeRecord', 'url' => ['create']],
    ['label' => 'Manage StudentAeeRecord', 'url' => ['admin']],
];
?>
<div id="mainPage" class="main">
    <div class="row">
        <h1>Fichas AEE</h1>
    </div>

    <div class="row">
        <div class="t-buttons-container">
            <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('aeerecord/default/create')?>">Adicionar Ficha</a>
        </div>
    </div>

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

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', [
                    'id' => 'farmer-register-grid',
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => [
                        'id',
                        [
                            'name' => 'studentName',
                            'header' => 'Aluno',
                            'value' => '$data->studentFk->name',
                        ],
                        [
                            'name' => 'classroomName',
                            'header' => 'Turma',
                            'value' => '$data->classroomFk->name',
                        ],
                        [
                            'name' => 'date',
                            'header' => 'Data',
                            'value' => 'date("d/m/Y", strtotime($data->date))',
                        ],
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
                                    'options' => ['class' => 'delete-button'],
                                ]
                            ],
                            'updateButtonOptions' => ['style' => 'margin-right: 20px;', 'class' => ''],
                            'deleteButtonOptions' => ['style' => 'cursor: pointer;', 'class' => ''],
                            'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
