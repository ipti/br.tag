<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs=array(
	$this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'StudentClassDiary',
    'enableAjaxValidation' => false,
)
);
?>

<div class="main">
    <h1><?php echo $student->name; ?></h1>
    <div class="row">
        <div class="column clear-margin--all">         
            <div class="t-field-tarea">
                    <?= chtml::label("Justificativa de falta", "title"); ?>
                    <textarea class="justification-text"></textarea>
                    <input type="hidden" id="justification-classroomid">
                    <input type="hidden" id="justification-studentid">
                    <input type="hidden" id="justification-day">
                    <input type="hidden" id="justification-month">
                    <input type="hidden" id="justification-schedule">
                    <input type="hidden" id="justification-fundamentalmaior">
            </div>
        </div>
    </div>
        <div class="mobile-row">
            <?php echo CHtml::submitButton('Salvar', array('class' => 't-button-primary clear-margin--all')); ?>
        </div>
    
    <?php $this->endWidget(); ?>
</div>