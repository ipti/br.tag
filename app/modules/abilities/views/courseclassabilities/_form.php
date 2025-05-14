<?php
/* @var $this CourseClassAbilitiesController */
/* @var $model CourseClassAbilities */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'course-class-abilities-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>

    <div class="main">
        <div class="mobile-row ">
            <div class="column ">
                <h1><?php echo $title; ?></h1>
            </div>
            <div class="column clearfix align-items--center justify-content--end show--desktop">
                <button class="t-button-primary  last save-student" type="submit">
                    <?= $modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="t-field-text column is-two-fifths">
                <?php echo $form->labelEx($model, 'description', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 1500, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>

            <div class="t-field-text column is-two-fifths">
                <?php echo $form->labelEx($model, 'code', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($model, 'code', array('size' => 20, 'maxlength' => 20, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model, 'code'); ?>
            </div>
        </div>
        <div class="row">
            <div class="t-field-text column is-two-fifths">
                <?php echo $form->labelEx($model, 'edcenso_discipline_fk', array('class' => 't-field-text__label')); ?>
                <?php echo $form->dropDownList(
                    $model,
                    'edcenso_discipline_fk',
                    CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),
                    array(
                        'key' => 'id',
                        'class' => 'select-search-on t-field-select__input select2-container',
                        'prompt' => 'Selecione o componente curricular/eixo...',
                    )
                );
                ?>
                <?php echo $form->error($model, 'edcenso_discipline_fk'); ?>
            </div>

            <div class="t-field-text column is-two-fifths">
                <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', array('class' => 't-field-text__label')); ?>
                <?php
                echo $form->dropDownList(
                    $model,
                    'edcenso_stage_vs_modality_fk',
                    CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'),
                    array(
                        "prompt" => "Selecione uma etapa",
                        "class" => "select-search-on t-field-select__input select2-container js-stage js-field-required"
                    )
                );
                ?>
                <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
            </div>
        </div>
        <div class="row reverse show--tablet">
          <div class="t-buttons-container">
            <div class="column clearfix">
                 <?php echo CHtml::submitButton($model->isNewRecord ? 'Criar' : 'Salvar', array('class' => 't-button-primary')); ?>
            </div>
          </div>
        </div>
    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->
