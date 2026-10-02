<?php

/**
 * @var StudentController $this StudentController
 * @var StudentIdentification $modelStudentIdentification The student model
 * @var StudentEnrollment $modelEnrollment The enrollment model
 * @var SchoolIdentification[] $modelSchool List of schools
 * @var StudentEnrollment[] $originEnrollments Active enrollments the student can be transferred from
 */

$originEnrollmentOptions = [];
foreach ($originEnrollments as $originEnrollment) {
    $originEnrollmentOptions[$originEnrollment->id] = $originEnrollment->classroomFk->name . ' (' . $originEnrollment->classroomFk->school_year . ')';
}
$this->setPageTitle('TAG - ' . Yii::t('default', 'Transfer Student'));

$modulePath = Yii::app()->getModule('student')->getBasePath();
$cs = Yii::app()->getClientScript();

$initJs = Yii::app()->getAssetManager()->publish($modulePath . '/resources/js/transfer/_initialization.js');
$cs->registerScriptFile($initJs . '?v=' . TAG_VERSION, CClientScript::POS_END);

$functionsJs = Yii::app()->getAssetManager()->publish($modulePath . '/resources/js/transfer/functions.js');
$cs->registerScriptFile($functionsJs . '?v=' . TAG_VERSION, CClientScript::POS_END);

$validationsJs = Yii::app()->getAssetManager()->publish($modulePath . '/resources/js/transfer/validations.js');
$cs->registerScriptFile($validationsJs . '?v=' . TAG_VERSION, CClientScript::POS_END);
?>

<div id="mainPage" class="main">
    <?php
if (Yii::app()->user->hasFlash('error')) {
    echo '<div class="alert alert-error">' . CHtml::encode(Yii::app()->user->getFlash('error')) . '</div>';
}
if (Yii::app()->user->hasFlash('success')) {
    echo '<div class="alert alert-success">' . CHtml::encode(Yii::app()->user->getFlash('success')) . '</div>';
}
?>

    <div class="row">
        <h1><?php echo Yii::t('default', 'Transferir Aluno'); ?>: <?php echo CHtml::encode($modelStudentIdentification->name); ?></h1>
    </div>

    <?php $form = $this->beginWidget('CActiveForm', [
        'id' => 'transfer-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <div class="row">
        <div class="column is-two-fifths">
            <div class="t-field-select">
                <label class="t-field-select__label"><?php echo Yii::t('default', 'Matrícula a Transferir'); ?></label>
                <?php echo CHtml::dropDownList(
                    'origin_enrollment_id',
                    '',
                    $originEnrollmentOptions,
                    [
                        'prompt' => Yii::t('default', 'Selecione a Matrícula/Turma de Origem'),
                        'class' => 'select-search-on t-field-select__input select2-container',
                        'required' => true,
                    ]
                ); ?>
            </div>
        </div>

        <div class="column is-two-fifths">
            <div class="t-field-select js-select-school-classroom">
                <?php echo $form->label($modelEnrollment, 'school_inep_id_fk', ['class' => 't-field-select__label']); ?>
                <?php echo $form->dropDownList(
    $modelEnrollment,
    'school_inep_id_fk',
    CHtml::listData($modelSchool, 'inep_id', 'name'),
    [
        'prompt' => 'Selecione a Escola de Destino',
        'class' => 'select-search-on t-field-select__input select2-container',
    ]
); ?>
                <?php echo $form->error($modelEnrollment, 'school_inep_id_fk'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column is-two-fifths">
            <div class="t-field-select">
                <?php echo $form->label($modelEnrollment, 'classroom_fk', ['class' => 't-field-select__label']); ?>
                <?php echo $form->dropDownList(
                    $modelEnrollment,
                    'classroom_fk',
                    [],
                    [
                        'prompt' => 'Selecione a Turma de Destino',
                        'class' => 'select-search-on t-field-select__input select2-container',
                        'disabled' => true,
                    ]
                ); ?>
                <?php echo $form->error($modelEnrollment, 'classroom_fk'); ?>
            </div>
        </div>

        <div class="column is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($modelEnrollment, 'transfer_date', ['class' => 't-field-text__label']); ?>
                <?php echo $form->textField($modelEnrollment, 'transfer_date', [
                    'class' => 't-field-text__input',
                    'placeholder' => 'dd/mm/aaaa',
                    'maxlength' => 10,
                ]); ?>
                <?php echo $form->error($modelEnrollment, 'transfer_date'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <button type="submit" class="t-button-primary"><?php echo Yii::t('default', 'Transferir'); ?></button>
    </div>

    <?php $this->endWidget(); ?>
</div>
