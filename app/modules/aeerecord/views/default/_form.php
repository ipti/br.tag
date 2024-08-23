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
            <a class="t-button-secondary <?php echo $model->isNewRecord ? 'hide' : '' ?>" target="_blank"  rel="noopener"
                href="<?php echo Yii::app()->createUrl('aeerecord/reports/aeeRecordReport&id='.$model->id) ?>">
                <span class="t-icon-printer"></span>Imprimir
            </a>
            <button id="saveAeeRecord" class="t-button-primary" type="button">
                <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

    <div class="row <?php echo $model->isNewRecord ? 'hide' : '' ?>">
        <div class="column clearfix">
            <p id="js-classroom-name"><b class="text-bold">Turma: </b></p>
            <p id="js-student-name"><b class="text-bold">Aluno: </b></p>
            <p id="js-instructor-name"><b class="text-bold">Professor: </b></p>
            <p id="js-date-name"><b class="text-bold">Data: </b></p>
        </div>
    </div>

    <div class="row <?php echo $model->isNewRecord ? '' : 'hide' ?>">
        <div class="column t-field-select clearfix is-one-quarter">
            <?php echo CHtml::label('Turma', 'classroom_fk', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="classroomSelect" name="classroom">
                <option value="">Selecione a turma</option>
            </select>
        </div>
        <div id="studentContainer" class="column t-field-select clearfix is-third hide">
            <?php echo CHtml::label('Aluno', 'student_fk', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="studentSelect" name="student">
                <option value="">Selecione o aluno</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="column t-field-tarea clearfix is-three-fifths">
            <?php echo CHtml::label('Necessidades de aprendizagem ', 'learning_needs', array('class' => 't-field-tarea__label')); ?>
            <?php echo CHtml::activeTextArea($model, "learning_needs", array('id'=>"learningNeeds",'class' => "t-field-tarea__input large", 'maxlength' => 1000, 'style' => 'resize: none')); ?>
        </div>
    </div>

    <div class="row">
        <div class="column t-field-tarea clearfix is-three-fifths">
            <?php echo CHtml::label('Caracterização pedagógica ', 'characterization', array('class' => 't-field-tarea__label')); ?>
            <?php echo CHtml::activeTextArea($model, "characterization", array('id'=>"characterization",'class' => "t-field-tarea__input large", 'maxlength' => 3000, 'style' => 'resize: none; min-height: 200px; max-height: 200px')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
