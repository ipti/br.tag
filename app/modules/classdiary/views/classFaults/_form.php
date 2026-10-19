<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'class-faults-form',
    'enableAjaxValidation' => false,
]); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column is-one-fourth t-field-text clearfix">
            <?php echo $form->labelEx($model, 'schedule_fk', ['class' => 't-field-text__label']); ?>
            <?php echo $form->numberField($model, 'schedule_fk', ['class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'schedule_fk'); ?>
        </div>
        <div class="column is-one-fourth t-field-text clearfix">
            <?php echo $form->labelEx($model, 'student_fk', ['class' => 't-field-text__label']); ?>
            <?php echo $form->numberField($model, 'student_fk', ['class' => 't-field-text__input']); ?>
            <?php echo $form->error($model, 'student_fk'); ?>
        </div>
    </div>

    <div class="row">
        <div class="column is-one-half t-field-tarea clearfix">
            <?php echo $form->labelEx($model, 'justification', ['class' => 't-field-tarea__label']); ?>
            <?php echo $form->textArea($model, 'justification', ['rows' => 4, 'class' => 't-field-tarea__input']); ?>
            <?php echo $form->error($model, 'justification'); ?>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Criar Falta' : 'Salvar Alteracoes', ['class' => 't-button-primary']); ?>
        <?php echo CHtml::link('Voltar', ['index'], ['class' => 't-button-secondary']); ?>
    </div>

<?php $this->endWidget(); ?>
</div>
