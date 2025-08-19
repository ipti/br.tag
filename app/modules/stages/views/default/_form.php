<?php
/* @var $this DefaultController */
/* @var $model EdcensoStageVsModality */
/* @var $form CActiveForm */

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\stages\resources\_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'edcenso-stage-vs-modality-form',
    'enableAjaxValidation' => false,
));

?>

<div class="form">
    <div class="mobile-row ">
        <div class="column clearleft">
            <h1><?php echo $model->isNewRecord ? 'Criar Etapa' : 'Atualizar Etapa'?></h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <button id="saveStage" class="t-button-primary" type="submit">
                <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column is-two-fifths clearfix">
            <div class="t-field-text">
                <?php echo $form->label($model, 'name', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model, 'name', array('id' => 'stageName', 'size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="column is-two-fifths clearfix">
                <div class="t-field-select">
                    <?php echo $form->label($model, 'edcenso_associated_stage_id', array('class' => 't-field-select__label--required')); ?>
                    <?php echo $form->dropDownList($model, 'edcenso_associated_stage_id',
                        Chtml::listData(EdcensoStageVsModality::model()->findAll("is_edcenso_stage = 1"), 'id', 'name'),
                        array(
                            'prompt' => "Selecione...",
                            'id' => "edcenso_associated_stage_id",
                            'class' => 't-field-select__input select2-container',
                            'disabled' => $model->is_edcenso_stage == 1
                        )
                    ); ?>
                    <?php echo $form->error($model, 'edcenso_associated_stage_id'); ?>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="column is-two-fifths clearfix">
            <div class="t-field-select">
                <?php echo $form->label($model, 'Modalidade', array('class' => 't-field-select__label--required')); ?>
                <?php echo $form->dropDownList($model, 'stage', array(
                    "0" => "Selecione a Modalidade",
                    "1" => "Infantil",
                    "2" => "Fundamental Menor",
                    "3" => "Fundamental Maior",
                    "4" => "Médio",
                    "5" => "Profissional",
                    "6" => "EJA",
                    "7" => "Outros",
                ), array(
                    'id' => "stage",
                    'class' => 't-field-select__input select2-container'
                )); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column is-two-fifths clearfix">
            <div class="t-field-text">
                <?php echo $form->label($model, 'alias', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model, 'alias', array('id' => 'stageAlias', 'size' => 15, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model, 'alias'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column clearfix">
        <div class="t-field-checkbox">
            <?php echo $form->checkBox(
                $model,
                'unified_frequency',
                array('value' => 1, 'uncheckValue' => 0)
                ); ?>
            <?php echo $form->label($model, 'unified_frequency', array('class' => 't-field-text__label')); ?>
        </div>
        </div>
    </div>

    <div class="row show--tablet">
        <div class="column clearfix">
            <div class="t-buttons-container">
                <a class="t-button-primary" type="submit">
                    <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                </a>
            </div>
        </div>
    </div>


    <?php $this->endWidget(); ?>

</div>
