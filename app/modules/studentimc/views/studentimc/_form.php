<?php
/* @var $this StudentIMCController */
/* @var $model StudentIMC */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);

?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'student-imc-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="main form-content">
        <div class="tag-inner">
            <div class="row">
                <div class="column">
                    <h1><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <h3>Dados Antropométricos</h3>
                </div>
            </div>

            <?php echo $form->errorSummary($model); ?>

            <div class="row">
                <div class="column">
                    <div class="t-field-text">
                        <?php echo $form->labelEx($model, 'height', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'height', array('class' => 't-field-text__input js-height')); ?>
                        <?php echo $form->error($model, 'height'); ?>
                    </div>
                </div>
                <div class="column">
                    <div class="t-field-text">
                        <?php echo $form->labelEx($model, 'weight', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'weight', array('class' => 't-field-text__input js-weight')); ?>
                        <?php echo $form->error($model, 'weight'); ?>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="column">
                    <div class="t-field-text">
                        <?php echo $form->labelEx($model, 'IMC', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'IMC', array('class' => 't-field-text__input js-imc', 'readonly' => 'readonly')); ?>
                        <?php echo $form->error($model, 'IMC'); ?>
                    </div>
                </div>
                <div class="column <?= $model->isNewRecord ? "hide" : "" ?>">
                    <?php echo $form->labelEx($model, 'created_at', array('class' => 't-field-text__label')); ?>
                    <?php echo $form->textField($model, 'created_at', array('class' => 't-field-text__input', 'disabled' => 'disabled')); ?>
                    <?php echo $form->error($model, 'created_at'); ?>
                </div>
                <div class="column <?= $model->isNewRecord ? "" : "hide" ?>"></div>
            </div>


            <div class="row">
                <div class="column">
                    <div class="t-field-tarea">
                        <?php echo $form->labelEx($model, 'observations', array('class' => 't-field-tarea__label')); ?>
                        <?php echo $form->textArea($model, 'observations', array('rows' => 6, 'cols' => 60, 'maxlength' => 500, 'class' => 't-field-tarea__input', 'style' => 'height:150px;')); ?>
                        <?php echo $form->error($model, 'observations'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row buttons show--desktop" style="justify-content: end;margin-right: 20px;">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Criar' : 'Salvar', array('class' => 't-button-primary')); ?>
</div>

<div class="row reverse show--tablet">
    <div class="t-buttons-container">
        <div class="column">
            <button class="t-button-primary" type="submit" style="width:100%">
                <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
