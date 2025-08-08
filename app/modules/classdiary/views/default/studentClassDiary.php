<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs = [
    $this->module->id,
];
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$form = $this->beginWidget(
    'CActiveForm',
    [
        'id' => 'StudentClassDiary',
        'enableAjaxValidation' => false,
    ]
);
?>
<?php
$disabled = ($studentFault === true) ? '' : 'disabled';
?>
<div class="main">
    <h1><?php echo $student->name; ?></h1>
    <hr class="row t-separator">
    <div class="row">
        <div class="column is-half clearfix">
            <div class="t-field-tarea clear-margin--bottom">
                    <?= CHtml::label('Justificativa de falta', 'title'); ?>
                    <?php echo CHtml::textArea('justification', $justification, ['maxlength' => 500, 'disabled' => $disabled]); ?>
            </div>
        </div>
    </div>
    <hr class="row t-separator">
    <div class="row">
        <div class="column is-half clearfix">
            <h3>Avaliação Individual do Aluno</h3>
            <div class="t-field-tarea">
                        <?= CHtml::label('Observação', 'title'); ?>
                        <?php echo CHtml::textArea('student_observation', $student_observation, ['maxlength' => 500]); ?>
            </div>
        </div>
    </div>
        <div class="mobile-row">
            <?php echo CHtml::submitButton('Salvar', ['class' => 't-button-primary clear-margin--all']); ?>
        </div>

    <?php $this->endWidget(); ?>
</div>
