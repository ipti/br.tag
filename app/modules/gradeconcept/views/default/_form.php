<?php
/** @var $this GradeConceptController */
/** @var $model GradeConcept */
/** @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', [
    'id' => 'grade-concept-form',
    'enableAjaxValidation' => false,
]); ?>

<div class="form">

	<div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Cadastrar Conceito' : 'Atualizar Conceito' ?></h1>
            <p></p>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <button id="saveConcept" class="t-button-primary" type="button">
                <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

    <div class="column t-field-select clearfix is-one-quarter">
        <?php echo $form->label($model, 'name', ['class' => 't-field-text__label--required']); ?>
        <?php echo $form->textField($model, 'name', ['id' => 'conceptName', 'size' => 50, 'class' => 't-field-text__input clear-margin--all js-amount', 'placeholder' => 'Nome']); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="column t-field-select clearfix is-one-quarter">
        <?php echo $form->label($model, 'acronym', ['class' => 't-field-text__label--required']); ?>
        <?php echo $form->textField($model, 'acronym', ['id' => 'conceptAcronym', 'size' => 5, 'class' => 't-field-text__input clear-margin--all js-amount', 'placeholder' => 'Acrônimo']); ?>
        <?php echo $form->error($model, 'acronym'); ?>
    </div>

    <div class="column t-field-select clearfix is-one-quarter">
        <?php echo $form->label($model, 'value', ['class' => 't-field-text__label--required']); ?>
        <?php echo $form->textField($model, 'value', ['id' => 'conceptValue', 'class' => 't-field-text__input clear-margin--all js-amount', 'placeholder' => 'Valor']); ?>
        <?php echo $form->error($model, 'value'); ?>
    </div>

    <div class="row show--tablet">
        <div class="column clearfix">
            <div class="t-buttons-container">
                <a class="t-button-primary" type="submit">
                    <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
                </a>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
