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
$cs->registerScriptFile($baseScriptUrl . '/common/js/curricularmatrix.js?v=1.1', CClientScript::POS_END);
$cs->registerScript("vars", "var addMatrix = '" . $this->createUrl("addMatrix") . "';", CClientScript::POS_HEAD);
$this->setPageTitle('TAG - ' . Yii::t('curricularMatrixModule.index', 'Curricular Matrix'));
$cs->registerCssFile($themeUrl . '/css/template2.css');
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= yii::t('curricularMatrixModule.index', 'Curricular Matrix') ?></h3>
    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)): ?>
        <div class="tag-row">
            <div class="">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Stage'), 'stages', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::dropDownList("stages", [], CHtml::listData(EdcensoStageVsModality::model()->findAll(), "id", "name"), [
                        "multiple" => "multiple", "class" => "select-search-on control-input span12"
                    ]) ?>
                </div>
            </div>
            <div class="">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Disciplines'), 'disciplines', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::dropDownList("disciplines", [], CHtml::listData(EdcensoDiscipline::model()->findAll(), "id", "name"), [
                        "multiple" => "multiple", "class" => "select-search-on control-input span12"
                    ]) ?>
                </div>
            </div>
            <div class="">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Workload'), 'workload', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::numberField("workload", "0", ["min" => "0", "max" => "9999", "class" => "small-input"]) ?>
                </div>
            </div>
            <div class="">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Credits'), 'credits', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::numberField("credits", "0", ["min" => "0", "max" => "99", "class" => "small-input"]) ?>
                </div>
            </div>
            <div class="">
                <?= CHtml::label("&nbsp;", 'credits', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::button(Yii::t('curricularMatrixModule.index', 'Add'), [
                        "id" => "add-matrix", "class" => "t-button-primary "
                    ]) ?>
                </div>
            </div>
        </div>
        <hr>
    <?php endif ?>
    <div class="row-fluid alert-container">
        <div class="span12">
            <div class="alert"></div>
        </div>
    </div>
    <div class="widget">
        <div class="widget-body">
            <?php
            $this->widget('zii.widgets.grid.CGridView', [
                'id' => 'matrizgridview', 'dataProvider' => $dataProvider,
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
                        'class' => 'CButtonColumn',
                        'template' => Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? '{delete}' : '',
                        'afterDelete' => 'function(link, success, data){
                    data = JSON.parse(data);
                    if (data.valid) {
                        $(".alert").text(data.message).addClass("alert-success").removeClass("alert-error");
                    } else {
                        $(".alert").text(data.message).addClass("alert-error").removeClass("alert-success");
                    }
                    $(".alert-container").show();
                }',
                'buttons' => array(
                    'delete' => array(
                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                    ))
                    ],
                    
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="reuse">
        <a class="matrix-reuse"  href="javascript:;"><?php echo 'Reaproveitamento da Matriz Curricular de ' . (Yii::app()->user->year - 1) ?></a>
    </div>
</div>

<div class="modal fade" id="matrix-reuse-modal" tabindex="-1" role="dialog"
     aria-labelledby="Reaproveitamento de Matriz Curricular">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel">Reaproveitamento de Matriz Curricular</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <b>Tem certeza</b> que deseja reaproveitar a matriz curricular de <?php echo (Yii::app()->user->year - 1) ?>?</b>
                    </div>
                </div>

                <div class="modal-footer modal-container-buttons ">
                    <button type="button" class="tag-button-light small-button"
                            data-dismiss="modal">Cancelar</button>
                    <button type="button" class="t-button-primary "
                            data-dismiss="modal">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>