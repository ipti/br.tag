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
    <hr class="row t-separator">
    <div class="row">
        <div class="column is-half clear-margin--all">         
            <div class="t-field-tarea clear-margin--bottom">
                    <?= chtml::label("Justificativa de falta", "title"); ?>
                    <?php echo CHtml::textArea("justification", $justification, array('maxlength' => 500, 'disabled' => $disabled )); ?>
            </div>
        </div>
    </div>
    <hr class="row t-separator">
    <div class="row">
        <div class="column is-half clear-margin--all">
            <h3>Avaliação Individual do Aluno</h3>
            <div class="t-field-tarea">
                        <?= chtml::label("Observação", "title"); ?>
                        <?php echo CHtml::textArea("student_observation", $student_observation, array('maxlength' => 500)); ?>
            </div>
        </div>
    </div>
        <div class="mobile-row">
            <?php echo CHtml::submitButton('Salvar', array('class' => 't-button-primary clear-margin--all')); ?>
        </div>
    
    <?php $this->endWidget(); ?>
</div>