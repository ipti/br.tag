<?php
/* @var $this TimesheetController
 * @var $cs CClientScript
 * @var $filter CurricularMatrix
 * @var $dataProvider CActiveDataProvider
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.2');
$cs->registerScriptFile($baseScriptUrl . '/common/js/curricularmatrix.js?v=1.1', CClientScript::POS_END);
$cs->registerScript("vars", "var addMatrix = '" . $this->createUrl("addMatrix") . "';", CClientScript::POS_HEAD);
$this->setPageTitle('TAG - ' . Yii::t('curricularMatrixModule.index', 'Curricular Matrix'));
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= yii::t('curricularMatrixModule.index', 'Curricular Matrix') ?></h3>
    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)): ?>
        <div class="row-fluid">
            <div class="span5">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Stage'), 'stages', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::dropDownList("stages", [], CHtml::listData(EdcensoStageVsModality::model()->findAll(), "id", "name"), [
                        "multiple" => "multiple", "class" => "select-search-on span12"
                    ]) ?>
                </div>
            </div>
            <div class="span3">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Disciplines'), 'disciplines', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::dropDownList("disciplines", [], CHtml::listData(EdcensoDiscipline::model()->findAll(), "id", "name"), [
                        "multiple" => "multiple", "class" => "select-search-on span12"
                    ]) ?>
                </div>
            </div>
            <div class="span1">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Workload'), 'workload', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::numberField("workload", "0", ["min" => "0", "max" => "9999", "class" => "span12"]) ?>
                </div>
            </div>
            <div class="span1">
                <?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Credits'), 'credits', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::numberField("credits", "0", ["min" => "0", "max" => "99", "class" => "span12"]) ?>
                </div>
            </div>
            <div class="span2">
                <?= CHtml::label("&nbsp;", 'credits', ['class' => "control-label"]) ?>
                <div class="form-group ">
                    <?= CHtml::button(Yii::t('curricularMatrixModule.index', 'Add'), [
                        "id" => "add-matrix", "class" => "btn btn-primary"
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
    <?php
    $this->widget('zii.widgets.grid.CGridView', [
        'id' => 'matrizgridview', 'dataProvider' => $filter->search(), 'filter' => $filter,
        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
        'enablePagination' => TRUE, 'columns' => [
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
                }'
            ],
        ],
    ]);
    ?>
</div>