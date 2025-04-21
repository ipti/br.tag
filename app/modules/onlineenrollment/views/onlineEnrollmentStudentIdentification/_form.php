<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $model OnlineEnrollmentStudentIdentification */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'online-enrollment-student-identification-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
    <div class="main form-content">
        <div class="row">
            <div class="column">
                <h1>
                    <?php echo $title; ?>
                </h1>
            </div>
        </div>
	    <!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	    <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="t-field-text column">
                <?php echo $form->labelEx($model,'school_inep_id_fk', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8, 'class'=>'t-field-text__input')); ?>
                <?php echo $form->error($model,'school_inep_id_fk'); ?>
            </div>

            <div class="t-field-text column">
                <?php echo $form->labelEx($model,'classroom_inep_id', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'classroom_inep_id',array('size'=>12,'maxlength'=>12, 'class'=>'t-field-text__input')); ?>
                <?php echo $form->error($model,'classroom_inep_id'); ?>
            </div>
       </div>
       <div class="row">
    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'classroom_fk', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'classroom_fk', array('class' => 't-field-text__input')); ?>
        <?php echo $form->error($model,'classroom_fk'); ?>
    </div>

    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'name', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'name', array('size'=>60, 'maxlength'=>100, 'class' => 't-field-text__input')); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'birthday', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'birthday', array('size'=>10, 'maxlength'=>10, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'birthday'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'cpf', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'cpf', array('size'=>11, 'maxlength'=>11, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'cpf'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'sex', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'sex', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'sex'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'color_race', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'color_race', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'color_race'); ?>
        </div>
    </div>
    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_blindness', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_blindness', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_blindness'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_low_vision', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_low_vision', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_low_vision'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_deafness', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_deafness', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_deafness'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_disability_hearing', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_disability_hearing', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_disability_hearing'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_deafblindness', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_deafblindness', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_deafblindness'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_phisical_disability', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_phisical_disability', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_phisical_disability'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_intelectual_disability', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_intelectual_disability', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_intelectual_disability'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_multiple_disabilities', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_multiple_disabilities', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_multiple_disabilities'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_autism', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_autism', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_autism'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'deficiency_type_gifted', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'deficiency_type_gifted', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'deficiency_type_gifted'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'last_change', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'last_change', array('class' => 't-field-text__input')); ?>
            <?php echo $form->error($model,'last_change'); ?>
        </div>
    </div>


    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'mother_name', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'mother_name',array('size'=>60,'maxlength'=>90, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'mother_name'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'father_name', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'father_name',array('size'=>60,'maxlength'=>90, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'father_name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'responsable_name', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'responsable_name',array('size'=>60,'maxlength'=>90, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'responsable_name'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'responsable_cpf', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'responsable_cpf',array('size'=>11,'maxlength'=>11, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'responsable_cpf'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'responsable_telephone', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'responsable_telephone',array('size'=>11,'maxlength'=>11, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'responsable_telephone'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'cep', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'cep',array('size'=>8,'maxlength'=>8, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'cep'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'address', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'address'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'number', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'number'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'complement', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'complement',array('size'=>60,'maxlength'=>100, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'complement'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'neighborhood', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'neighborhood',array('size'=>60,'maxlength'=>100, 'class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'neighborhood'); ?>
        </div>
    </div>

    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'zone', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'zone', array('class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'zone'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'edcenso_city_fk', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'edcenso_city_fk', array('class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'edcenso_city_fk'); ?>
        </div>
    </div>


    <div class="row">
        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'edcenso_uf_fk', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'edcenso_uf_fk', array('class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'edcenso_uf_fk'); ?>
        </div>

        <div class="t-field-text column">
            <?php echo $form->labelEx($model,'unavailable', array('class' => 't-field-text__label')); ?>
            <?php echo $form->textField($model,'unavailable', array('class'=>'t-field-text__input')); ?>
            <?php echo $form->error($model,'unavailable'); ?>
        </div>
    </div>

<div class="row">
    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'status_fk', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'status_fk', array('class'=>'t-field-text__input')); ?>
        <?php echo $form->error($model,'status_fk'); ?>
    </div>

    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'student_fk', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'student_fk', array('class'=>'t-field-text__input')); ?>
        <?php echo $form->error($model,'student_fk'); ?>
    </div>
</div>

<div class="row">
    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'edcenso_stage_vs_modality_fk', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'edcenso_stage_vs_modality_fk', array('class'=>'t-field-text__input')); ?>
        <?php echo $form->error($model,'edcenso_stage_vs_modality_fk'); ?>
    </div>

    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'event_pre_registration_fk', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'event_pre_registration_fk', array('class'=>'t-field-text__input')); ?>
        <?php echo $form->error($model,'event_pre_registration_fk'); ?>
    </div>
</div>

<div class="row">
    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'stages_vacancy_pre_registration_fk', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'stages_vacancy_pre_registration_fk', array('class'=>'t-field-text__input')); ?>
        <?php echo $form->error($model,'stages_vacancy_pre_registration_fk'); ?>
    </div>

    <div class="t-field-text column">
        <?php echo $form->labelEx($model,'created_at', array('class' => 't-field-text__label')); ?>
        <?php echo $form->textField($model,'created_at', array('class'=>'t-field-text__input')); ?>
        <?php echo $form->error($model,'created_at'); ?>
    </div>
</div>


        <div class="row">
            <div class="t-field-text column">
                <?php echo $form->labelEx($model,'updated_at',  array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($model,'updated_at', array('class'=>'t-field-text__input')); ?>
                <?php echo $form->error($model,'updated_at'); ?>
            </div>
            <div class="t-field-text column"></div>
        </div>

        <div  class="row buttons" style="width:106px;">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 't-button-primary')); ?>
        </div>


        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
