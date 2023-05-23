<?php
/* @var $this TimesheetController
 * @var $cs CClientScript
 * @var $filter CurricularMatrix
 * @var $dataProvider CActiveDataProvider
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.2');
$cs->registerScriptFile($baseScriptUrl . '/common/js/curricularmatrix.js', CClientScript::POS_END);
$cs->registerScript("vars", "var addMatrix = '" . $this->createUrl("addMatrix") . "';", CClientScript::POS_HEAD);
$this->setPageTitle('TAG - ' . Yii::t('curricularMatrixModule.index', 'Curricular Matrix'));
// $cs->registerCssFile($themeUrl . '/css/template2.css');
?>




<div class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?= yii::t('curricularMatrixModule.index', 'Curricular Matrix') ?></h1>
        </div>
    </div>
    <div class="column">
        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) : ?>
            <form onsubmit="return false">
                <div class="row align-items--end justify-content--space-between">
                    <div class="column clear-margin--left">
                        <div class="t-multiselect">
                            <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Stage'), 'stages', ['class' => "control-label"]) ?>
                            <?= CHtml::dropDownList("stages", [], CHtml::listData(EdcensoStageVsModality::model()->findAll(), "id", "name"), [
                                "multiple" => "multiple", "class" => "t-field-select__input select-search-on control-input multiselect"
                            ]) ?>
                        </div>
                    </div>
                    <div class="column">
                        <div class="t-multiselect">
                            <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Disciplines'), 'disciplines', ['class' => "control-label"]) ?>
                            <?= CHtml::dropDownList("disciplines", [], CHtml::listData(EdcensoDiscipline::model()->findAll(), "id", "name"), [
                                "multiple" => "multiple", "class" => "t-field-select__input select-search-on control-input multiselect"
                            ]) ?>
                        </div>
                    </div>
                    <div class="column">
                        <div class="t-field-number">
                            <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Workload'), 'workload', ['class' => "t-field-number__label control-label"]) ?>
                            <?= CHtml::numberField("workload", "0", ["min" => "0", "max" => "9999", "class" => "t-field-number__input", 'style' => 'height: 23px; border: 1px solid #aaa;']) ?>
                        </div>
                    </div>
                    <div class="column">
                        <div class="t-field-number">
                            <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Credits'), 'credits', ['class' => "t-field-number__label control-label"]) ?>
                            <?= CHtml::numberField("credits", "0", ["min" => "0", "max" => "99", "class" => "t-field-text__input", 'style' => 'height: 23px; border: 1px solid #aaa;']) ?>
                        </div>
                    </div>
                    <div class="column">
                        <?= CHtml::button(Yii::t('curricularMatrixModule.index', 'Add'), [
                            "id" => "add-matrix", "class" => "t-button-primary", "style" => "margin: 1.5em 0 !important;"
                        ]) ?>
                    </div>
                </div>
            </form>
            <hr>
        <?php endif ?>

        <div class="widget">
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert" style="display:none">

                    </div>
                </div>
            </div>
            <?php if (Yii::app()->user->hasFlash('success')) : ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="alert alert-success">
                            <?php echo Yii::app()->user->getFlash('success') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if (Yii::app()->user->hasFlash('error')) : ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="alert alert-error">
                            <?php echo Yii::app()->user->getFlash('error') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <div class="widget-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', [
                    'id' => 'matrizgridview', 'dataProvider' => $dataProvider, 'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table curricularmatrix-table tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'enablePagination' => false, 'columns' => [
                        [
                            'header' => Yii::t('curricularMatrixModule.index', 'Stage'),
                            'name' => 'stage_fk',
                            'value' => '$data->stageFk->name',
                        ], [
                            'header' => Yii::t('curricularMatrixModule.index', 'Discipline'),
                            'name' => 'discipline_fk',
                            'value' => '$data->disciplineFk->name',
                        ], [
                            'header' => Yii::t('curricularMatrixModule.index', 'Workload'),
                            'name' => 'workload',
                            'htmlOptions' => ['width' => '150px']
                        ], [
                            'header' => Yii::t('curricularMatrixModule.index', 'Credits'),
                            'name' => 'credits',
                            'htmlOptions' => ['width' => '150px']
                        ], [
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? '{delete}' : '',
                            'buttons' => array(
                                'delete' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',

                                )
                            )
                        ],

                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="reuse">
            <a class="matrix-reuse" href="javascript:;"><?php echo 'Reaproveitamento da Matriz Curricular de ' . (Yii::app()->user->year - 1) ?></a>
        </div>
    </div>
</div>
<div class="modal fade modal-content" id="matrix-reuse-modal" tabindex="-1" role="dialog" aria-labelledby="Reaproveitamento de Matriz Curricular">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Reaproveitamento de Matriz Curricular</h4>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="row-fluid">
                    <b>Tem certeza</b> que deseja reaproveitar a matriz curricular de <?php echo (Yii::app()->user->year - 1) ?>?</b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="tag-button-light btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>