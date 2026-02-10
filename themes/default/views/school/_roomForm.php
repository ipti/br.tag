<?php
/**
 * Room Form
 * @var CActiveForm $form
 * @var SchoolRoom $model
 */
?>

<div class="modal fade" id="roomModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?= $model->isNewRecord ? 'Adicionar Sala' : 'Editar Sala' ?></h4>
            </div>
            
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'room-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array('class' => 'form-horizontal'),
            )); ?>
            
            <div class="modal-body">
                <?= $form->errorSummary($model); ?>
                
                <?= $form->hiddenField($model, 'school_inep_fk'); ?>
                
                <div class="t-field-text">
                    <?= $form->label($model, 'name', array('class' => 't-field-text__label--required')); ?>
                    <?= $form->textField($model, 'name', array(
                        'size' => 60,
                        'maxlength' => 100,
                        'class' => 't-field-text__input',
                        'placeholder' => 'Ex: Sala 101, Laboratório de Informática, etc.'
                    )); ?>
                    <?= $form->error($model, 'name'); ?>
                </div>
                
                <div class="t-field-text">
                    <?= $form->label($model, 'capacity', array('class' => 't-field-text__label')); ?>
                    <?= $form->numberField($model, 'capacity', array(
                        'min' => 1,
                        'class' => 't-field-text__input',
                        'placeholder' => 'Capacidade máxima da sala'
                    )); ?>
                    <?= $form->error($model, 'capacity'); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="column t-buttons-container justify-content--center">
                    <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="t-button-primary">
                        <?= $model->isNewRecord ? 'Adicionar' : 'Salvar' ?>
                    </button>
                </div>
            </div>
            
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
