<?php
/* @var $this RequestController */
/* @var $model InventoryRequest */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventory-request-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

    <?php if (TagUtils::isAdmin()): ?>
    <div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model,'school_inep_fk', ['class' => 't-field-text__label']); ?>
            <?php echo $form->dropDownList($model,'school_inep_fk', CHtml::listData(SchoolIdentification::model()->findAll(['condition' => 'situation=1']), 'inep_id', 'name'), array('empty' => 'Selecione a Escola', 'class' => 't-field-text__input select-search-on')); ?>
            <?php echo $form->error($model,'school_inep_fk'); ?>
        </div>
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model,'user_id', ['class' => 't-field-text__label']); ?>
            <?php echo $form->dropDownList($model,'user_id', CHtml::listData(Users::model()->findAll(['order' => 'name']), 'id', 'name'), array('empty' => 'Selecione o Solicitante', 'class' => 't-field-text__input select-search-on')); ?>
            <?php echo $form->error($model,'user_id'); ?>
        </div>
    </div>
    <?php endif; ?>

	<div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model,'item_id', ['class' => 't-field-text__label']); ?>
            <?php echo $form->dropDownList($model,'item_id', CHtml::listData(InventoryItem::model()->findAll(), 'id', 'name'), array('empty' => 'Selecione o Item', 'class' => 't-field-text__input select-search-on')); ?>
            <?php echo $form->error($model,'item_id'); ?>
        </div>
	</div>

	<div class="row">
        <div class="column is-one-fourth t-field-text clearfix">
            <?php echo $form->labelEx($model,'quantity', ['class' => 't-field-text__label']); ?>
            <?php echo $form->numberField($model,'quantity',array('size'=>10, 'class' => 't-field-text__input', 'step' => 'any', 'min' => '0')); ?>
            <?php echo $form->error($model,'quantity'); ?>
        </div>
	</div>

	<div class="row">
        <div class="column is-one-half t-field-text clearfix">
            <?php echo $form->labelEx($model,'justification', ['class' => 't-field-text__label']); ?>
            <?php echo $form->textArea($model,'justification',array('rows'=>4, 'cols'=>50, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'justification'); ?>
        </div>
	</div>

	<div class="row t-buttons-container">
		<?php echo CHtml::submitButton('Enviar Solicitação', ['class' => 't-button-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
