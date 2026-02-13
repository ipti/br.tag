<?php
/* @var $this MovementController */
/* @var $model InventoryMovement */
/* @var $form CActiveForm */

$this->setPageTitle('TAG - Distribuir Itens');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('index'),
	'Distribuir Itens',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Distribuir Itens</h1>
            <p>Retire itens do Almoxarifado Central e envie para uma unidade escolar.</p>
        </div>
    </div>

    <div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'inventory-transfer-form',
        'enableAjaxValidation'=>false,
    )); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="column is-one-third t-field-select clearfix">
                <?php echo $form->labelEx($model,'item_id', ['class' => 't-field-select__label']); ?>
                <?php 
                $itemCriteria = new CDbCriteria();
                $itemCriteria->with = ['stocks'];
                $itemCriteria->together = true;
                $itemCriteria->condition = 'stocks.school_inep_fk IS NULL AND stocks.quantity > 0';
                
                echo $form->dropDownList($model,'item_id', CHtml::listData(InventoryItem::model()->findAll($itemCriteria), 'id', 'name'), array('prompt'=>'Selecione o Item no Central', 'class' => 't-field-select__input select2-container')); 
                ?>
                <?php echo $form->error($model,'item_id'); ?>
            </div>
            
            <div class="column is-one-third t-field-select clearfix">
                <label class="t-field-select__label required">Escola de Destino <span class="required">*</span></label>
                <?php echo $form->dropDownList($model,'school_inep_fk', CHtml::listData(SchoolIdentification::model()->findAll(['condition'=>'situation=1']), 'inep_id', 'name'), array('prompt'=>'Selecione a Escola', 'class' => 't-field-select__input select2-container')); ?>
                <?php echo $form->error($model,'school_inep_fk'); ?>
            </div>
        </div>

        <div class="row">
            <div class="column is-one-sixth t-field-text clearfix">
                <?php echo $form->labelEx($model,'quantity', ['class' => 't-field-text__label']); ?>
                <?php echo $form->numberField($model,'quantity', ['class' => 't-field-text__input', 'step' => 'any', 'min' => '0.01']); ?>
                <?php echo $form->error($model,'quantity'); ?>
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
            <?php echo CHtml::submitButton('Realizar Distribuição', ['class' => 't-button-primary']); ?>
            <?php echo CHtml::link('Cancelar', array('index'), array('class'=>'t-button-secondary')); ?>
        </div>

    <?php $this->endWidget(); ?>
    </div>
</div>
