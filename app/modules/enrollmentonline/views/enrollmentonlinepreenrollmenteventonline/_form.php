<?php
/* @var $this EnrollmentOnlinePreEnrollmentEventOnlineController */
/* @var $model EnrollmentOnlinePreEnrollmentEventOnline */
/* @var $form CActiveForm */
?>


<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'enrollment-online-pre-enrollment-event-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="main">
        <div class="row">
            <div class="column">
                <h1>
                    <?php echo $title; ?>
                </h1>
            </div>
            <div class="column clearfix align-items--center justify-content--end show--desktop">
                <?php if (!$model->isNewRecord): ?><a class="t-button-secondary"
                        href="<?= Yii::app()->createUrl('/enrollmentonline/enrollmentonlinestudentidentification/index', ['eventId' => $model->id]) ?>">pré-matriculas</a><?php endif; ?>
                <button class="t-button-primary " type="submit">
                    <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model, 'name', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input')); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>
            </div>
            <div class="column">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model, 'start_date', ['class' => 't-field-text__label']); ?>
                    <?php
                    $options = DatePickerWidget::renderDatePickerFinal($model, 'start_date');
                    $options['htmlOptions']['class'] = 'js-field-required t-field-text__input';
                    $options['htmlOptions']['id'] = 'start_date_picker';
                    $options['options']['yearRange'] = '1930:' . (date('Y') + 1);
                    $this->widget('zii.widgets.jui.CJuiDatePicker', $options);
                    ?>
                    <?php echo $form->error($model, 'start_date'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model, 'end_date', ['class' => 't-field-text__label']); ?>
                    <?php
                    $options = DatePickerWidget::renderDatePickerFinal($model, 'end_date');
                    $options['htmlOptions']['class'] = 'js-field-required t-field-text__input';
                    $options['htmlOptions']['id'] = 'end_date_picker';
                    $options['options']['yearRange'] = '1930:' . (date('Y') + 1);
                    $this->widget('zii.widgets.jui.CJuiDatePicker', $options);
                    ?>
                    <?php echo $form->error($model, 'end_date'); ?>
                </div>
            </div>
            <div class="column">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model, 'year', ['class' => 't-field-text__label']); ?>
                    <?php echo $form->textField($model, 'year', ['class' => 't-field-text__input']); ?>
                    <?php echo $form->error($model, 'year'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <div class="t-field-select">
                    <?php
                    echo CHtml::label(
                        Yii::t('default', 'Etapas'),
                        'edcenso_stage_vs_modality_fk',
                        array(
                            'class' => 't-field-select__label--required',
                            'style' => 'width: 54px;'
                        )
                    );

                    $stages = EdcensoStageVsModality::model()->findAll(array(
                        'order' => 'name ASC'
                    ));

                    $selectedStages = CHtml::listData(
                        $model->enrollmentOnlineEventVsEdcensoStages,
                        'edcenso_stage_fk',
                        'edcenso_stage_fk'
                    );

                    echo CHtml::dropDownList(
                        'edcenso_stage_vs_modality_fk[]', // name
                        $selectedStages,                             // selected
                        CHtml::listData($stages, 'id', 'name'),
                        array(
                            'id' => 'edcenso_stage_vs_modality_fk',
                            'class' => 't-field-select__input js-stage-select select-search-on t-multiselect multiselect t-margin-none--bottom t-margin-none--top',
                            'multiple' => 'multiple',
                            'style' => 'width: 100%;'
                        )
                    );
                    ?>

                </div>
            </div>

            <div class="column">
            </div>
        </div>
        <div class="row">



            <div class="row reverse t-margin-large--top reverse show--tablet">
                <div class="t-buttons-container">
                    <div class="column"></div>
                    <div class="column"></div>
                    <div class="column">
                        <?php if (!$model->isNewRecord): ?><a class="t-button-secondary"
                                href="<?= Yii::app()->createUrl('/enrollmentonline/enrollmentonlinestudentidentification/index', ['eventId' => $model->id]) ?>">pré-matriculas</a><?php endif; ?>
                    </div>
                    <div class="column">
                        <button class="t-button-primary column" type="submit" style="width:100%;">
                            <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                        </button>
                    </div>
                </div>
            </div>


            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
</div>