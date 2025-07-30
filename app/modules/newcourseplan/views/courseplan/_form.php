<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'course-plan-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column flex is-two-fifths">
            <div class="t-field-text">
                <?php echo CHtml::label(yii::t('default', 'Name'), 'name', array('class' => 'control-label t-field-text__label--required')); ?>
                <?php echo $form->textField(
                    $model,
                    'name',
                    array(
                        'size' => 400,
                        'maxlength' => 500,
                        'class' => 't-field-text__input',
                        'placeholder' => 'Digite o Nome do Plano',
                        'readonly' => $readonly
                    )
                ); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column flex is-two-fifths">
            <div class="t-field-select">
                <?php echo CHtml::label(yii::t('default', 'Stage'), 'modality_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                <?php
                echo $form->dropDownList($model, 'modality_fk', CHtml::listData($stages, 'id', 'name'), array(
                    'key' => 'id',
                    'class' => 'select-search-on t-field-select__input',
                    'prompt' => 'Selecione a etapa...',
                ));
                ?>
                <img class="js-course-plan-loading-disciplines" style="margin: 10px 20px;" height="30px" width="30px"
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column flex is-two-fifths">
            <div id="disciplinesContainer" class="t-field-select">
                <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                <?php echo $form->dropDownList($model, 'discipline_fk', array(), array(
                    'key' => 'id',
                    'class' => 'select-search-on t-field-select__input',
                    'initVal' => $model->discipline_fk,
                    'prompt' => 'Selecione o componente curricular/eixo...',
                ));
                ?>
                <img class="js-course-plan-loading-abilities" style="margin: 10px 20px;" height="30px" width="30px"
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column flex is-two-fifths">
            <div class="t-field-text">
                <?php echo CHtml::label(yii::t('default', 'Start Date'), 'start_date', array('class' => 'control-label t-field-select__label--required')); ?>
                <?php
                // $model->start_date = $this->dataConverter($model->start_date, 1);
                echo $form->textField($model, 'start_date', array(
                    'size' => 400,
                    'maxlength' => 500,
                    'class' => 't-field-text__input js-date date js-start-date',
                    'id' => 'courseplan_start_date',
                    'readonly' => $readonly
                )); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column flex is-two-fifths">
            <div class="t-field-text">
                <?php echo CHtml::label(yii::t('default', 'Situation'), 'situation', array('class' => 'control-label t-field-select__label--required')); ?>
                <?php
                echo $form->textField($model, 'situation', array(
                    'size' => 400,
                    'maxlength' => 500,
                    'readonly' => true,
                    'class' => 't-field-text__input'
                )); ?>
            </div>
        </div>
    </div>

    <div class="t-field-text buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
