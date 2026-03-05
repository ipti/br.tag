<?php

/**
 * @var DefaultController $this DefaultController
 * @var EdcensoDiscipline $model EdcensoDiscipline
 * @var EdcensoBaseDisciplines[] $edcensoBaseDisciplines EdcensoBaseDiscipline[]
 * @var CActiveForm $form CActiveForm
 */

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'edcenso-discipline-_form-form',
    'enableAjaxValidation' => false,
));

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

if (!$model->isNewRecord) {
    $cant_change_censo_discipline = $model->edcenso_base_discipline_fk < 99;
}

?>

<div class="form">
    <div class="row-fluid hidden-print">
        <div class="span12">
            <h1>
                <?php echo $title; ?>
            </h1>
            <div class="tag-buttons-container buttons">
                <button class="t-button-primary pull-right" type="submit">
                    <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success') && (!$modelProfessional->isNewRecord)): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="widget widget-tabs border-bottom-none">
            <div class="row">
                <div class="column">
                    <?php echo $form->errorSummary($model); ?>

                    <?php echo $form->hiddenField($model, 'id'); ?>

                    <div class="t-field-text">
                        <?php echo $form->label($model, 'name', ['class' => 'control-label t-field-text__label--required']); ?>
                        <?php echo $form->textField($model, 'name'); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>

                    <div class="t-field-text">
                        <?php echo $form->label($model, 'abbreviation', ['class' => 't-field-text__label control-label']); ?>
                        <?php echo $form->textField($model, 'abbreviation'); ?>
                        <?php echo $form->error($model, 'abbreviation'); ?>
                    </div>

                    <div class="t-field-select">
                        <?php echo $form->label($model, 'edcenso_base_discipline_fk', array('class' => 'control-label t-field-select__label required')); ?>
                        <?php echo $form->dropDownList($model, 'edcenso_base_discipline_fk', CHtml::listData($edcensoBaseDisciplines, "id", "name"), array('prompt' => 'Selecione um componente...', 'class' => 'select-search-on t-field-select__input select2-container', 'disabled' => $cant_change_censo_discipline)); ?>
                        <?php echo $form->error($model, 'edcenso_base_discipline_fk'); ?>
                    </div>
                    <div class="t-field-checkbox">
                        <?php echo $form->checkBox($model, 'requires_exam', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input js-requires_exam')); ?>
                        <?php echo $form->label($model, 'requires_exam', array('class' => 't-field-checkbox__label')); ?>
                        <?php echo $form->error($model, 'requires_exam'); ?>
                    </div>

                    <div class="t-field-text js-report_text hide">
                        <?php echo $form->label($model, 'report_text', array('class' => 't-field-text__label control-label')); ?>
                        <?php echo $form->textField($model, 'report_text', array('class'=> 't-field-text__input')); ?>
                        <?php echo $form->error($model, 'report_text'); ?>
                        <span style="font-size:12px">Esse texto vai aparecer nos relatórios quando o a disciplina não exigir nota</span>
                    </div>
                </div>
                <div class="column"></div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
