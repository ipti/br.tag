<?php
/* @var $this StudentAeeRecordController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Ficha AEE'));
$this->breadcrumbs=array(
	'Student Aee Records',
);

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->menu=array(
	array('label'=>'Create StudentAeeRecord', 'url'=>array('create')),
	array('label'=>'Manage StudentAeeRecord', 'url'=>array('admin')),
);

$hasAdminAccess = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id);
?>
<div id="mainPage" class="main">
    <div class="row">
        <h1>Fichas AEE</h1>
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
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'farmer-register-grid',
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns'=>array(
                        'id',
                        array(
                            'name' => 'studentName',
                            'header' => 'Aluno',
                            'value' => '$data->studentFk->name',
                        ),
                        array(
                            'name' => 'classroomName',
                            'header' => 'Turma',
                            'value' => '$data->classroomFk->name',
                        ),
                        array(
                            'name' => 'date',
                            'header' => 'Data',
                            'value' => 'date("d/m/Y", strtotime($data->date))',
                        ),
                        array(
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{report}',
                            'buttons' => array(
                                'report' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/search-icon.svg',
                                    'url' => 'Yii::app()->createUrl("aeerecord/reports/aeeRecordReport", array("id"=>$data->id))',
                                    'options' => array(
                                        'target' => '_blank',
                                    ),
                                ),
                            ),
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                        ),
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>
