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
        <div class="row">
            <div class="column">
                <h1>
                    <?php echo $title; ?>
                </h1>
            </div>
        </div>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="t-field-text column">
                <?php echo $form->labelEx($model, 'description', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 1500, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>

            <div class="t-field-text column">
                <?php echo $form->labelEx($model, 'code', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($model, 'code', array('size' => 20, 'maxlength' => 20, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model, 'code'); ?>
            </div>
        </div>
        <div class="row">
            <div class="t-field-text column">
                <?php echo $form->labelEx($model, 'edcenso_discipline_fk', array('class' => 't-field-text__label')); ?>
                <?php echo $form->dropDownList(
                    $model,
                    'edcenso_discipline_fk',
                    CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),
                    array(
                        'key' => 'id',
                        'class' => 'select-search-on t-field-select__input',
                        'prompt' => 'Selecione o componente curricular/eixo...',
                    )
                );
                ?>
                <?php echo $form->error($model, 'edcenso_discipline_fk'); ?>
            </div>

            <div class="t-field-text column">
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



        <!-- <div class="row">
        <?php echo $form->labelEx($model, 'parent_fk'); ?>
        <?php echo $form->textField($model, 'parent_fk'); ?>
        <?php echo $form->error($model, 'parent_fk'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->textField($model, 'type', array('size' => 50, 'maxlength' => 50)); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div> -->

        <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 't-button-primary')); ?>
        </div>
    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->
