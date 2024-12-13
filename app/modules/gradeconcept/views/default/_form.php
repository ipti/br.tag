<?php
/* @var $this GradeConceptController */
/* @var $model GradeConcept */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
'id'=>'grade-concept-form',
'enableAjaxValidation'=>false,
)); ?>

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
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acronym'); ?>
		<?php echo $form->textField($model,'acronym',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'acronym'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->