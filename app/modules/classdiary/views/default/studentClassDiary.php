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
<?php
    $disabled = ($studentFault === true) ?  '' :  'disabled';
?>
<div class="main">
    <h1><?php echo $student->name; ?></h1>
    <div class="row">
        <div class="column is-half clear-margin--all">         
            <div class="t-field-tarea">
                    <?= chtml::label("Justificativa de falta", "title"); ?>
                    <?php echo CHtml::textArea("justification", $justification, array('maxlength' => 500, 'disabled' => $disabled )); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column is-half clear-margin--all">
            <div class="t-field-tarea">
                        <?= chtml::label("observação", "title"); ?>
                        <?php echo CHtml::textArea("student_observation", $student_observation, array('maxlength' => 500)); ?>
            </div>
        </div>
    </div>
        <div class="mobile-row">
            <?php echo CHtml::submitButton('Salvar', array('class' => 't-button-primary clear-margin--all')); ?>
        </div>
    
    <?php $this->endWidget(); ?>
</div>