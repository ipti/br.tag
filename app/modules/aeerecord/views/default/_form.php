<?php
/* @var $this StudentAeeRecordController */
/* @var $model StudentAeeRecord */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
'id'=>'student-aee-record-form',
'enableAjaxValidation'=>false,
)); ?>

<div class="form">

    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Cadastrar Ficha AEE' : 'Atualizar Ficha AEE' ?></h1>
            <p></p>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <button id="" class="t-button-primary" type="button">
                <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column t-field-select clearfix is-one-quarter">
            <?php echo CHtml::label('Selecione a turma', 'classroom_fk', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="classroomSelect" name="classroom">
                <option value="selecione">Selecione</option>
            </select>
        </div>
        <div class="column t-field-select clearfix is-third">
            <?php echo CHtml::label('Selecione o aluno', 'student_fk', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="studentSelect" name="student">
                <option value="selecione">Selecione</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="column t-field-tarea clearfix is-three-fifths">
            <?php echo CHtml::label('Necessidades de aprendizagem ', 'learning_needs', array('class' => 't-field-tarea__label--required')); ?>
            <?php echo CHtml::activeTextArea($model, "learning_needs", array('class' => "t-field-tarea__input large", 'maxlength' => 500, 'style' => 'resize: none')); ?>
        </div>
    </div>

    <div class="row">
        <div class="column t-field-tarea clearfix is-three-fifths">
            <?php echo CHtml::label('Caracterização pedagógica ', 'characterization', array('class' => 't-field-tarea__label--required')); ?>
            <?php echo CHtml::activeTextArea($model, "characterization", array('class' => "t-field-tarea__input large", 'maxlength' => 1000, 'style' => 'resize: none; min-height: 200px; max-height: 200px')); ?>
        </div>
    </div>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'learning_needs'); ?>
		<?php echo $form->textField($model,'learning_needs',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'learning_needs'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'characterization'); ?>
		<?php echo $form->textField($model,'characterization',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'characterization'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'student_fk'); ?>
		<?php echo $form->textField($model,'student_fk'); ?>
		<?php echo $form->error($model,'student_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'school_fk'); ?>
		<?php echo $form->textField($model,'school_fk',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'school_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'classroom_fk'); ?>
		<?php echo $form->textField($model,'classroom_fk'); ?>
		<?php echo $form->error($model,'classroom_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'instructor_fk'); ?>
		<?php echo $form->textField($model,'instructor_fk'); ?>
		<?php echo $form->error($model,'instructor_fk'); ?>
	</div> -->

<?php $this->endWidget(); ?>

</div><!-- form -->
