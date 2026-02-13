<?php
/* @var $this MovementController */
/* @var $model InventoryMovement */
/* @var $form CActiveForm */

$this->setPageTitle('TAG - Lançamento de Entrada');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('index'),
	'Entrada',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Lançamento de Entrada</h1>
            <p>Registre a entrada de novos itens no estoque de uma escola.</p>
        </div>
    </div>

    <div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'inventory-movement-entry-form',
        'enableAjaxValidation'=>false,
    )); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="column is-one-third t-field-select clearfix">
                <?php echo $form->labelEx($model,'item_id', ['class' => 't-field-select__label']); ?>
                <?php echo $form->dropDownList($model,'item_id', CHtml::listData(InventoryItem::model()->findAll(), 'id', 'name'), array('prompt'=>'Selecione o Item', 'class' => 't-field-select__input select2-container')); ?>
                <?php echo $form->error($model,'item_id'); ?>
            </div>
            
            <div class="column is-one-third t-field-select clearfix">
                <?php echo $form->labelEx($model,'school_inep_fk', ['class' => 't-field-select__label']); ?>
                <?php 
                $schools = CHtml::listData(SchoolIdentification::model()->findAll(['condition'=>'situation=1']), 'inep_id', 'name');
                if (TagUtils::isAdmin()) {
                    $schools = ['' => 'Almoxarifado Central'] + $schools;
                }
                echo $form->dropDownList($model,'school_inep_fk', $schools, array('prompt'=>'Selecione o Destino', 'class' => 't-field-select__input select2-container')); 
                ?>
                <?php echo $form->error($model,'school_inep_fk'); ?>
            </div>
        </div>

        <div class="row">
            <div class="column is-one-sixth t-field-text clearfix">
                <?php echo $form->labelEx($model,'quantity', ['class' => 't-field-text__label']); ?>
                <?php echo $form->numberField($model,'quantity', ['class' => 't-field-text__input', 'step' => 'any', 'min' => '0']); ?>
                <?php echo $form->error($model,'quantity'); ?>
            </div>

            <div class="column is-one-third t-field-text clearfix">
                <?php echo $form->labelEx($model,'destination', ['class' => 't-field-text__label']); ?>
                <?php echo $form->textField($model,'destination',array('size'=>60,'maxlength'=>255, 'class' => 't-field-text__input', 'placeholder' => 'Ex: Fornecedor X, Doação')); ?>
                <?php echo $form->error($model,'destination'); ?>
            </div>

            <div class="column is-one-sixth t-field-text clearfix">
                <?php echo $form->labelEx($model,'date', ['class' => 't-field-text__label']); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($model, 'date'));
                ?>
                <?php echo $form->error($model,'date'); ?>
            </div>

            <?php
            Yii::app()->clientScript->registerScript('inventory-date-mask', "
                initDateFieldMaskAndValidation('#" . CHtml::activeId($model, 'date') . "');
            ");
            ?>
        </div>

        <div class="row t-buttons-container">
            <?php echo CHtml::submitButton('Confirmar Entrada', ['class' => 't-button-primary']); ?>
            <?php echo CHtml::link('Cancelar', array('index'), array('class'=>'t-button-secondary')); ?>
        </div>

    <?php $this->endWidget(); ?>
    </div>
</div>
