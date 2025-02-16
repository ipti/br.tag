<?php

/**
 *
 * @var CActiveForm $this CActiveForm
 * @var Classroom $modelClassroom Classroom
 * @var EdcensoStageVsModality $edcensoStageVsModalities EdcensoStageVsModality
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classroom/form/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/dialogs.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);

$form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'classroom-form',
        'enableAjaxValidation' => false,
    )
);
?>

<div class="mobile-row ">
    <div class="column clearleft">
        <?php
        if (!$modelClassroom->isNewRecord && Yii::app()->features->isEnable("FEAT_SEDSP")) {
            $sedspSync = Classroom::model()->findByPk($modelClassroom->id)->sedsp_sync;
            ?>
            <div style="display: flex;align-items: center;margin-right: 10px;margin-top: 13px;">
                <?php if ($sedspSync) { ?>
                    <div style="font-weight: bold;margin-right: 20px;">
                        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/SyncTrue.png"
                            style="width: 25px; margin-right: 2px;">Sincronizado
                    </div>
                <?php } else { ?>
                    <div style="font-weight: bold;margin-right: 20px;">
                        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/notSync.png"
                            style="width: 25px;margin-right: 2px;">Não sincronizado
                    </div>
                <?php } ?>

                <a class="update-classroom-from-sedsp"
                    style="margin-right: 10px;background: #2e33b7;color: white;font-size: 13px;padding-left: 4px;padding-right: 4px;border-radius: 6px;">
                    Importar dados da SED
                </a>
            </div>
        <?php } ?>
        <h1>
            <?= $title; ?>
        </h1>
    </div>
    <div class="column clearfix align-items--center justify-content--end show--desktop">
        <button class="t-button-primary  last save-classroom" type="button">
            <?= $modelClassroom->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
        </button>
    </div>
</div>

<div class="tag-inner">
    <div class="widget widget-tabs border-bottom-none">

        <?= $form->errorSummary($modelClassroom); ?>
        <?php if (Yii::app()->user->hasFlash('success') && (!$modelClassroom->isNewRecord)) { ?>
            <div class="alert classroom-alert alert-success">
                <?= Yii::app()->user->getFlash('success') ?>
            </div>
        <?php } elseif (Yii::app()->user->hasFlash('error') && (!$modelClassroom->isNewRecord)) { ?>
            <div class="alert classroom-alert alert-error">
                <?= Yii::app()->user->getFlash('error') ?>
            </div>
        <?php } elseif (Yii::app()->features->isEnable("FEAT_SEDSP") && $disabledFields) { ?>
            <div class="alert classroom-alert alert-warning">
                Alguns campos foram desabilitados porque a turma possui alunos matriculados e o SEDSP não autoriza
                realizar edições em tais campos.
            </div>
        <?php } else { ?>
            <div class="alert classroom-alert no-show"></div>
        <?php } ?>
        <div class="t-tabs">
            <ul class="tab-classroom t-tabs__list">
                <li id="tab-classroom" class="active t-tabs__item">
                    <a class="t-tabs__link" href="#classroom" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?= Yii::t('default', 'Classroom') ?>
                    </a>
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-pedagogica-settings" class="t-tabs__item">
                    <a class="t-tabs__link" href="#pedagogica-settings" data-toggle="tab">
                        <span class="t-tabs__numeration">2</span>
                        <?php echo Yii::t('default', 'Configurações Pedagógicas') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-instructors" class="t-tabs__item">
                    <a class="t-tabs__link" href="#instructors" data-toggle="tab">
                        <span class="t-tabs__numeration">3</span>
                        <?php echo Yii::t('default', 'Instructors') ?>
                    </a>
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-students" class="t-tabs__item">
                    <a class="t-tabs__link" href="#students" data-toggle="tab">
                        <span class="t-tabs__numeration">4</span>
                        <?php echo Yii::t('default', 'Students') ?>
                    </a>
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-daily" class="t-tabs__item">
                    <a class="t-tabs__link" href="#daily" data-toggle="tab">
                        <span class="t-tabs__numeration">5</span>
                        <?php echo Yii::t('default', 'daily_order') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content form-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="classroom">
                    <div>
                        <h3>Dados Básicos</h3>
                    </div>

                    <div class="row">
                        <div class="column">
                            <?php
                            echo
                            $modelClassroom->isNewRecord ?
                                $form->hiddenField($modelClassroom, 'school_inep_fk', array('value' => Yii::app()->user->school)) :
                                $form->hiddenField($modelClassroom, 'school_inep_fk', array('value' => $modelClassroom->school_inep_fk))
                            ;
                            echo CHtml::hiddenField("teachingData", '', array('id' => 'teachingData'));
                            echo CHtml::hiddenField("disciplines", '', array('id' => 'disciplines'));
                            echo CHtml::hiddenField("events", '', array('id' => 'events'));
                            ?>
                            <!-- Nome -->
                            <div class="t-field-text">
                                <?= $form->label($modelClassroom, 'name', array('class' => 't-field-text__label--required')); ?>
                                <?= $form->textField($modelClassroom, 'name', array('size' => 60, 'maxlength' => 80, 'class' => 't-field-text__input', 'placeholder' => ' Nome completo')); ?>
                                <?= $form->error($modelClassroom, 'name'); ?>
                            </div>
                            <!-- Tipo de Mediação Didático-Pedagógica -->
                            <div class="t-field-select">
                                <?= $form->label($modelClassroom, 'pedagogical_mediation_type', array('class' => 't-field-select__label--required')); ?>
                                <?= $form->DropDownList($modelClassroom, 'pedagogical_mediation_type', array(null => 'Selecione o tipo', "1" => "Presencial", "3" => "Educação a Distância - EAD"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?= $form->error($modelClassroom, 'pedagogical_mediation_type'); ?>
                            </div>
                            <!-- Código Curso Educação Profissional -->
                            <div class="t-field-select">
                                <?= $form->label($modelClassroom, 'edcenso_professional_education_course_fk', array('class' => 't-field-select__label')); ?>
                                <?= $form->DropDownList($modelClassroom, 'edcenso_professional_education_course_fk', CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o curso', 'class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?= $form->error($modelClassroom, 'edcenso_professional_education_course_fk'); ?>
                            </div>
                            <!-- Local de Funcionamento Diferenciado -->
                            <div class="t-field-select" id="diff_location_container">
                                <?= $form->label($modelClassroom, 'diff_location', array('class' => 't-field-select__label--required')); ?>
                                <?= $form->DropDownList($modelClassroom, 'diff_location', array(null => 'Selecione a localização', 0 => 'A turma não está em local de funcionamento diferenciado', 1 => 'Sala anexa', 2 => 'Unidade de atendimento socioeducativo', 3 => 'Unidade prisional'), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?= $form->error($modelClassroom, 'diff_location'); ?>
                            </div>
                            <!-- Modalidade -->
                            <div class="t-field-select" id="modality">
                                <?= $form->label($modelClassroom, 'modality', array('class' => 't-field-select__label--required')); ?>
                                <?php
                                echo $form->DropDownList($modelClassroom, 'modality', array(
                                    '1' => 'Ensino Regular',
                                    '2' => 'Educação Especial - Modalidade Substitutiva',
                                    '3' => 'Educação de Jovens e Adultos (EJA)',
                                    '4' => 'Educação Profissional',
                                    '5' => 'Atendimento Educacional Especializado',
                                    '100' => 'Não se aplica'
                                ), array('prompt' => 'Selecione a Modalidade', 'class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?= $form->error($modelClassroom, 'modality'); ?>
                            </div>
                            <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")) { ?>
                                <!--Gov ID-->
                                <div class="t-field-text js-hide-not-required">
                                    <?= $form->label($modelClassroom, 'gov_id', array('class' => 't-field-text__label')); ?>
                                    <?= $form->textField(
                                        $modelClassroom,
                                        'gov_id',
                                        array(
                                            'size' => 60,
                                            'maxlength' => 12,
                                            'class' => 't-field-text__input',
                                            'placeholder' => 'Não possui',
                                            'disabled' => 'disabled'
                                        )
                                    ); ?>
                                    <button type="button" id="copy-gov-id" class="t-button-icon">
                                        <span class="t-icon-copy"></span>
                                    </button>
                                    <span id="copy-message" style="display:none;">
                                    </span>
                                    <?= $form->error($modelClassroom, 'gov_id'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="column">
                            <!-- Etapa de Ensino -->
                            <div class="t-field-select" id="stage_vs_modality">
                                <?= $form->label($modelClassroom, 'edcenso_stage_vs_modality_fk', array('class' => 't-field-select__label--required')); ?>
                                <?= $form->DropDownList($modelClassroom, 'edcenso_stage_vs_modality_fk', CHtml::listData($edcensoStageVsModalities, 'id', 'name'), array(
                                    'prompt' => 'Selecione o estágio vs modalidade',
                                    'class' => ($disabledFields ? 'select-search-off t-field-select__input disabled-field' : 'select-search-off t-field-select__input'), 'style' => 'width: 80%')); ?>
                                <?= $form->error($modelClassroom, 'edcenso_stage_vs_modality_fk'); ?>
                                <img class="loading-disciplines" style="display:none;position: fixed;margin: 5px 20px;"
                                    height="20px" width="20px"
                                    src="<?= Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif"
                                    alt="TAG Loading">
                            </div>



                             <!-- Periodo -->
                             <div class="t-field-select">
                                <?= $form->label($modelClassroom, 'period', ['class' => 't-field-select__label--required']) ?>
                                <?= $form->DropDownList($modelClassroom, 'period',  PeriodOptions::asArray(), ['class' => 'select-search-off t-field-select__input', 'prompt' => 'Selecione a unidade escolar']) ?>
                                <?= $form->error($modelClassroom, 'period'); ?>
                            </div>

                            <div class="t-field-checkbox-group">
                                <label class="t-field-checkbox__label">
                                    <?= Yii::t("default", "Period") ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'ignore_on_sagres', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox" for="Classroom_ignore_on_sagres">
                                        <?=Yii::t("default",  Classroom::model()->attributeLabels()['ignore_on_sagres']) ?>
                                    </label>

                                </div>
                            </div>



                            <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")): ?>
                                <!-- Unidade Escolar -->
                                <div class="t-field-select" id="sedsp_school_unity_fk">
                                    <?= $form->label($modelClassroom, 'Unidade Escolar', array('class' => 't-field-select__label--required')); ?>
                                    <?= $form->DropDownList($modelClassroom, 'sedsp_school_unity_fk', CHtml::listData(SedspSchoolUnities::model()->findAllByAttributes(array('school_inep_id_fk' => yii::app()->user->school)), 'id', 'description'), array('prompt' => 'Selecione a unidade escolar', 'class' => 'select-search-off t-field-select__input', 'disabled' => $disabledFields, 'style' => 'width: 80%')); ?>
                                    <?= $form->error($modelClassroom, 'sedsp_school_unity_fk'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?= $form->label($modelClassroom, "Turma", array('class' => 't-field-text__label--required')); ?>
                                    <?= $form->textField($modelClassroom, 'sedsp_acronym', array('size' => 2, 'maxlength' => 2, 'class' => 't-field-text__input', 'placeholder' => 'Ex: A, B, 1, A1, B1...', 'disabled' => $disabledFields)); ?>
                                    <?= $form->error($modelClassroom, 'sedsp_acronym'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?= $form->label($modelClassroom, "Sala de Aula", array('class' => 't-field-text__label--required')); ?>
                                    <?= $form->numberField($modelClassroom, 'sedsp_classnumber', array('min' => 1, 'max' => 99, 'size' => 2, 'maxlength' => 2, 'class' => 't-field-text__input', 'disabled' => $disabledFields)); ?>
                                    <?= $form->error($modelClassroom, 'sedsp_classnumber'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?= $form->label($modelClassroom, "Capacidade Fisica Maxima", array('class' => 't-field-text__label--required')); ?>
                                    <?= $form->numberField($modelClassroom, 'sedsp_max_physical_capacity', array('size' => 2, 'min' => 1, 'max' => 99, 'maxlength' => 2, 'class' => 't-field-text__input', 'disabled' => $disabledFields)); ?>
                                    <?= $form->error($modelClassroom, 'sedsp_max_physical_capacity'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <h3>Horário de funcionamento</h3>
                    </div>
                    <div class="row t-padding-small--bottom">
                        <div class="column">
                            <!-- turno -->
                            <div class="t-field-select">
                                <?= $form->label($modelClassroom, 'turn', array('class' => 't-field-select__label--required')); ?>
                                <?php
                                echo $form->DropDownList(
                                    $modelClassroom,
                                    'turn',
                                    array(
                                        null => 'Selecione o turno',
                                        'M' => 'Manhã',
                                        'T' => 'Tarde',
                                        'N' => 'Noite',
                                        'I' => 'Integral'
                                    ),
                                    array(
                                        'class' => 'select-search-off t-field-select__input',
                                        'style' => 'width: 100%',
                                        'disabled' => $disabledFields,
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('classroom/updateTime'),
                                            'success' => "function(data){
                                                				updateTime(data);
                                                		}",
                                        ),
                                    )
                                );
                                ?>
                                <?= $form->error($modelClassroom, 'turn'); ?>
                            </div>
                        </div>
                        <div class="column">

                            <!-- Dias da semana -->
                            <div class="control-group">
                                <div>
                                    <label class="t-field-text__label--required">
                                        <?= Yii::t('default', 'Week Days'); ?>
                                    </label>
                                </div>
                                <div class="uniformjs" id="Classroom_week_days">
                                    <table class="selecao_dias">
                                        <tr class="selecao_dias-checkbox">
                                            <td>S</td>
                                            <td>T</td>
                                            <td>Q</td>
                                            <td>Q</td>
                                            <td>S</td>
                                            <td>S</td>
                                            <td>D</td>
                                            <!-- <td><span style="margin: 0;"
                                                      class="btn-action single glyphicons circle_question_mark"
                                                      data-toggle="tooltip" data-placement="top"
                                                      data-original-title="<?= Yii::t('help', 'Week days'); ?>"><i></i></span>
                                            </td> -->
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_monday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_tuesday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_wednesday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_thursday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_friday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_saturday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td>
                                                <?= $form->checkBox($modelClassroom, 'week_days_sunday', array('value' => 1, 'uncheckValue' => 0, 'disabled' => $disabledFields)); ?>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <!-- hora inicial -->
                            <div class="t-field-text">
                                <?= $form->label($modelClassroom, 'initial_hour', array('class' => 't-field-text__label--required')); ?>
                                <?= $form->hiddenField($modelClassroom, 'initial_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                <?= $form->hiddenField($modelClassroom, 'initial_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                <?= CHtml::textField('Classroom_initial_time', $modelClassroom->initial_hour . '' . $modelClassroom->initial_minute, array('size' => 5, 'maxlength' => 5, 'class' => 't-field-text__input', 'placeholder' => ' Somente números', "disabled" => $disabledFields)); ?>
                                <!-- <?= Yii::t('help', 'Time'); ?> -->
                                <?= $form->error($modelClassroom, 'initial_hour'); ?>
                                <?= $form->error($modelClassroom, 'initial_minute'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <!-- hora final -->
                            <div class="t-field-text">
                                <?= $form->label($modelClassroom, 'final_hour', array('class' => 't-field-text__label--required', 'placeholder' => 'Somente números')); ?>
                                <?= $form->hiddenField($modelClassroom, 'final_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                <?= $form->hiddenField($modelClassroom, 'final_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                <?= CHtml::textField('Classroom_final_time', $modelClassroom->final_hour . '' . $modelClassroom->final_minute, array('size' => 5, 'maxlength' => 5, 'class' => 't-field-text__input', 'placeholder' => ' Somente números', 'disabled' => $disabledFields)); ?>
                                <!-- <?= Yii::t('help', 'Time'); ?> -->
                                <?= $form->error($modelClassroom, 'final_hour'); ?>
                                <?= $form->error($modelClassroom, 'final_minute'); ?>
                            </div>

                        </div>
                    </div>

                    <div>
                        <h3>Atendimento</h3>
                    </div>
                    <div class="row">
                        <div class="column">
                            <!-- tipo de atendimento -->
                            <div class="control-group hidden">
                                <label for=""></label>
                                <div class="">
                                    <?= $form->label($modelClassroom, 'school_year', array('class' => 't-field-text__label')); ?>
                                </div>
                                <div class="">
                                    <?= $form->textField($modelClassroom, 'school_year', array('value' => isset($modelClassroom->school_year) ? $modelClassroom->school_year : Yii::app()->user->year, 'size' => 5, 'maxlength' => 5)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="<?= Yii::t('help', 'School year'); ?>"><i></i></span>
                                    <?= $form->error($modelClassroom, 'school_year'); ?>
                                </div>
                            </div>
                            <!-- <div class="control-group">
                                <?= $form->label($modelClassroom, 'assistance_type', array('class' => 't-field-text__label')); ?>
                                <div class="">
                                    <?php
                                    echo $form->DropDownList(
                                        $modelClassroom,
                                        'assistance_type',
                                        $assistance_types,
                                        array('prompt' => 'Selecione o Tipo de Atendimento', 'class' => 'select-search-off')
                                    );
                                    ?>
                                    <?= $form->error($modelClassroom, 'assistance_type'); ?>
                                </div>
                            </div> -->
                            <!-- Tipo de Atendimento* -->
                            <div class="t-field-checkbox-group js-assistance-types-container" id="assistance_type">
                                <label class="t-field-checkbox__label--required">
                                    <?= Yii::t('default', 'Assistence Types'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'schooling', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                                    <label for="Escolarização">
                                        <?= Classroom::model()->attributeLabels()['schooling']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'complementary_activity', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                                    <label for="Atividade Complementar">
                                        <?= Classroom::model()->attributeLabels()['complementary_activity']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                                    <label for="Atendimento Educacional Especializado (AEE)">
                                        <?= Classroom::model()->attributeLabels()['aee']; ?>
                                    </label>
                                </div>
                            </div>
                            <!-- Participante do programa Mais Educação -->
                            <div class="control-group" id="mais_educacao">
                                <div id="none">
                                    <?= CHtml::activeHiddenField($modelClassroom, 'mais_educacao_participator', array('disabled' => 'disabled', )) ?>
                                </div>
                                <div class="t-field-checkbox" id="some">
                                    <?= $form->checkBox(
                                        $modelClassroom,
                                        'mais_educacao_participator',
                                        array('class' => 't-field-checkbox__input', 'id' => 'Classroom[mais_educacao_participator]')
                                    ); ?>
                                    <?= $form->error($modelClassroom, 'mais_educacao_participator'); ?>
                                    <?= $form->label(
                                        $modelClassroom,
                                        'mais_educacao_participator',
                                        array('class' => 't-field-checkbox__label', 'for' => 'Classroom[mais_educacao_participator]')
                                    );
                                    ?>

                                </div>
                            </div>
                            <div class="control-group" id="complementary_activity">
                                <div class="">
                                    <?= $form->label($modelClassroom, 'complementary_activity_type_1', array('class' => 't-field-text__label--required')); ?>
                                </div>
                                <div class="">
                                    <?= $form->dropDownList($modelClassroom, 'complementary_activity_type_1', CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'), array('multiple' => true, 'class' => 'select-search-on control-input', 'key' => 'id')); ?>
                                    <!-- <?= $form->dropDownList($modelClassroom, 'complementary_activity_type_1', CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'), array('multiple' => true, 'class' => 'select-ComplementaryAT', 'key' => 'id')); ?> -->

                                    <?= $form->error($modelClassroom, 'complementary_activity_type_1'); ?>
                                    <!-- atividade complementar -->
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <!-- atividades do  atendimento  educacional  especializado -->
                            <div class="t-field-checkbox-group" id="aee2">
                                <label class="t-field-checkbox__label">
                                    <?= Yii::t('default', 'Aee'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_braille', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino do Sistema Braille')); ?>
                                    <label class="t-field-checkbox" for="Ensino do Sistema Braille">
                                        <?= Classroom::model()->attributeLabels()['aee_braille']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_optical_nonoptical', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino do uso de recursos ópticos e não ópticos')); ?>
                                    <label class="t-field-checkbox"
                                        for="Ensino do uso de recursos ópticos e não ópticos">
                                        <?= Classroom::model()->attributeLabels()['aee_optical_nonoptical']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_cognitive_functions', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Estratégias para o desenvolvimento de processos mentais')); ?>
                                    <label class="t-field-checkbox"
                                        for="Estratégias para o desenvolvimento de processos mentais">
                                        <?= Classroom::model()->attributeLabels()['aee_cognitive_functions']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_mobility_techniques', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Técnicas de orientação e mobilidade')); ?>
                                    <label class="t-field-checkbox" for="Técnicas de orientação e mobilidade">
                                        <?= Classroom::model()->attributeLabels()['aee_mobility_techniques']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_libras', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino da Língua Brasileira de Sinais (Libras)')); ?>
                                    <label class="t-field-checkbox"
                                        for="Ensino da Língua Brasileira de Sinais (Libras)">
                                        <?= Classroom::model()->attributeLabels()['aee_libras']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_caa', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino de uso da Comunicação Alternativa e Aumentativa - CAA')); ?>
                                    <label class="t-field-checkbox"
                                        for="Ensino de uso da Comunicação Alternativa e Aumentativa - CAA">
                                        <?= Classroom::model()->attributeLabels()['aee_caa']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_curriculum_enrichment', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Estratégias para o enriquecimento curricular')); ?>
                                    <label class="t-field-checkbox" for="Estratégias para o enriquecimento curricular">
                                        <?= Classroom::model()->attributeLabels()['aee_curriculum_enrichment']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_soroban', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino do uso do Soroban')); ?>
                                    <label class="t-field-checkbox" for="Ensino do uso do Soroban">
                                        <?= Classroom::model()->attributeLabels()['aee_soroban']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_accessible_teaching', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino da usabilidade e das funcionalidades da informática acessível')); ?>
                                    <label class="t-field-checkbox"
                                        for="Ensino da usabilidade e das funcionalidades da informática acessível">
                                        <?= Classroom::model()->attributeLabels()['aee_accessible_teaching']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_portuguese', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino da Língua Portuguesa na modalidade escrita')); ?>
                                    <label class="t-field-checkbox"
                                        for="Ensino da Língua Portuguesa na modalidade escrita">
                                        <?= Classroom::model()->attributeLabels()['aee_portuguese']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelClassroom, 'aee_autonomous_life', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Estratégias para autonomia no ambiente escolar')); ?>
                                    <label class="t-field-checkbox"
                                        for="Estratégias para autonomia no ambiente escolar">
                                        <?= Classroom::model()->attributeLabels()['aee_autonomous_life']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="pedagogica-settings">
                    <div>
                        <h3>Configurações da Turma</h3>
                    </div>
                    <div class="row">
                        <div class="column">
                            <!-- Calendários da etapa -->
                            <div class="t-field-select">
                                <label class="t-field-select__label">Calendário</label>
                                <select class="select-search-on t-field-select__input select2-container" name="calendar_fk" id="Calendars" selectedOption="<?= $modelClassroom->calendar_fk ?>">
                                    <option value=''>Selecione um Calendário</option>
                                </select>
                            </div>
                            <!-- Estrutura de Avaliação -->
                            <div class="t-field-select js-grade-rules">
                                    <label class="t-field-select__label--required">
                                     Estrutura de Avaliação
                                    </label>
                                    <?php echo CHtml::dropDownList('grade_rules', '',  CHtml::listData($gradeRules, 'id', 'name'),
                                        array(
                                        'class' => 'select-search-on select2-container',
                                        'prompt' => 'Selecione a Regra de Avaliação',
                                        'id' => 'gradeRules', 'style' => 'width: 100%;')); ?>

                            </div>
                            <?php
                                if(TagUtils::isMultiStage( $modelClassroom->edcenso_stage_vs_modality_fk)):
                            ?>
                            <div class="js-mutiple-structure">
                            <h3>
                                Estruturas de Unidade Por Etapa
                            </h3>
                            <div class="column t-padding-small--bottom">

                                    <?php foreach($stages as $stage):?>
                                        <div class="row">
                                            <div class="column clearfix t-field-select">
                                                <label class="t-field-text__label--required">
                                                    <?= $stage->name ?>
                                                </label>
                                                <?php echo CHtml::dropDownList('grade_rules_' . $stage->id, $gradeRulesStages[$stage->id],  CHtml::listData($gradeRules, 'id', 'name'),
                                                    array(
                                                    'class' => 'select-search-on select2-container',
                                                    'prompt' => 'Selecione a Regra de Avaliação',
                                                    'id' => 'gradeRules', 'style' => 'width: 100%;')); ?>
                                            </div>
                                        </div>
                                    <?php endforeach;?>

                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                        </div>
                        <div class="column">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="instructors">
                    <div class="row-fluid">
                        <div class=" span12">
                            <!-- adicionar diciplina -->
                            <div class="row">
                                <a href="#" class="t-button-primary   add hidden-print" id="newDiscipline"><i></i>
                                    <?= Yii::t('default', 'Add Discipline/Teacher') ?>
                                </a>
                            </div>

                            <div class="separator"></div>
                            <?php
                            $teachingDataList = "<div>"
                                . "<div class='disciplines-with-container'><span><b>Componentes curriculares/eixos com Instrutores</b></span><div class='separator'></div>"
                                . "<ul id='DisciplinesWithInstructors'>";
                            $teachingDataArray = array();
                            $teachingDataDisciplines = array();
                            $disciplinesLabels = array();
                            $teachingDataNames = array();

                            $instructors = InstructorIdentification::model()->findAll();
                            foreach ($instructors as $instructor) {
                                $teachingDataNames[$instructor->id] = $instructor->name;
                            }
                            $roleName = [null, "Docente", "Auxiliar/assistente educacional", "Profissional/monitor de atividade complementar", "Tradutor e Intérprete de Libras", "EAD - Docente Titular", "EAD - Docente Tutor", "Guia-Intérprete", "Profissional de apoio escolar para aluno(a)s com deficiência", "Docente Substituto"];
                            $contractTypeName = [null, "Concursado/Efetivo", "Temporário", "Terceirizado", "CLT"];
                            $i = 0;
                            foreach ($modelTeachingData as $key => $model) {
                                $regentText = "";
                                $classRegent = "";
                                if ($model->regent == 1) {
                                    $regentText = "&nbsp(Regente)";
                                    $classRegent = "regent-teacher";
                                }
                                $teachingDataList .= "<li class='" . $classRegent . "' instructor='" . $model->instructor_fk . "'><span>" . $model->instructorFk->name . "</span><span>" . $regentText . "</span><span> - " . $roleName[$model->role] . "</span>"
                                    . '<a  href="#" class="deleteTeachingData delete" title="Excluir" regent="' . $model->regent . '">
                                              </a>';
                                $teachingDataList .= "<ul>";

                                $teachingDataArray[$i] = array();
                                $teachingDataArray[$i]['Instructor'] = $model->instructor_fk;
                                $teachingDataArray[$i]["Inep"] = $model->instructorFk->inep_id;
                                $teachingDataArray[$i]['Classroom'] = $model->classroom_id_fk;
                                $teachingDataArray[$i]['Role'] = $model->role;
                                $teachingDataArray[$i]['ContractType'] = $model->contract_type;
                                $teachingDataArray[$i]['RegentTeacher'] = $model->regent;
                                $teachingDataArray[$i]['Disciplines'] = array();

                                foreach ($model->teachingMatrixes as $teachingMatrix) {
                                    $teachingDataList .= "<li discipline='" . $teachingMatrix->curricularMatrixFk->disciplineFk->id . "'>"
                                        . '<a href="#" class="deleteTeachingData delete" title="Excluir"></a>'
                                        . "<span class='disciplines-list'>" . $teachingMatrix->curricularMatrixFk->disciplineFk->name
                                        . '</span>'
                                        . "</li>";
                                    array_push($teachingDataDisciplines, $teachingMatrix->curricularMatrixFk->disciplineFk->id);
                                    array_push($teachingDataArray[$i]['Disciplines'], $teachingMatrix->curricularMatrixFk->disciplineFk->id);
                                }

                                $teachingDataList .= "</ul></li>";
                                $i++;
                            }
                            $teachingDataList .= "</ul></div>";

                            //Pega a lista de disciplinas que possuem instrutores e tira as duplicatas
                            $teachingDataDisciplines = array_unique($teachingDataDisciplines);
                            //Pega a lista de disciplinas da turma
                            $disciplinesArray = ClassroomController::classroomDiscipline2array($modelClassroom);

                            //Pega a diferença entre a lista de disciplinas com instrutores e a lista de disciplinas da turma
                            $disciplinesWithoutInstructor = array_diff($disciplinesArray, $teachingDataDisciplines);

                            //monta a lista com as disciplinas que não possuem instrutor
                            $teachingDataList .= "<div class='disciplines-without-container'><span><b>Componentes curriculares/eixos sem Instrutores</b></span><div class='separator'></div>"
                                . "<ul id='DisciplinesWithoutInstructors'>";
                            $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
                            foreach ($disciplinesWithoutInstructor as $disciplineId => $value) {
                                if ($value == 2) {
                                    $teachingDataList .= "<li discipline='" . $disciplineId . "'><span>" . $disciplinesLabels[$disciplineId] . "</span>"
                                        . '<a href="#" class="deleteTeachingData delete" title="Excluir"></a>';
                                }
                            }
                            $teachingDataList .= "</ul></div></div>";

                            echo $teachingDataList;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="students">
                    <div class="row-fluid">
                        <container>
                            <row>
                                <div class="reports">
                                    <?php if (TagUtils::isInstance("BUZIOS")): ?>
                                        <div class="reports_cards">
                                            <a class="t-button-secondary" rel="noopener" target="_blank"
                                                href="<?= @Yii::app()->createUrl('classroom/batchupdatenrollment', array('id' => $modelClassroom->id)); ?>">
                                                <span class="t-icon-printer"></span>
                                                <?= Yii::t('default', 'Sinalizar rematricula') ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="reports_cards">
                                        <a class="t-button-secondary" rel="noopener" target="_blank"
                                            href="<?= @Yii::app()->createUrl('classroom/batchupdatetransport', array('id' => $modelClassroom->id)); ?>">
                                            <!-- <img alt="impressora" src="<?= Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                            <span class="t-icon-printer"></span>
                                            <?= Yii::t('default', 'Atualizar transporte') ?>
                                        </a>
                                    </div>
                                    <div class="reports_cards">
                                        <a class="t-button-secondary" rel="noopener" target="_blank"
                                            href="<?= Yii::app()->createUrl('classroom/batchupdatetotal', array('id' => $modelClassroom->id)) ?>">
                                            <!-- <img alt="impressora" src="<?= Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                            <span class="t-icon-printer"></span>
                                            <?= Yii::t('default', 'Atualização em Lote') ?>
                                        </a>
                                    </div>
                                    <div class="reports_cards">
                                        <a class="t-button-secondary" rel="noopener" target="_blank"
                                            href="<?= Yii::app()->createUrl('reports/enrollmentperclassroomreport', array('id' => $modelClassroom->id)) ?>">
                                            <!-- <img alt="impressora" src="<?= Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                            <span class="t-icon-printer"></span>
                                            <?= Yii::t('default', 'Relatório de Matrícula') ?>
                                        </a>
                                    </div>
                                    <div class="reports_cards">
                                        <a class="t-button-secondary" rel="noopener" target="_blank"
                                            href="<?= Yii::app()->createUrl('reports/studentperclassroom', array('id' => $modelClassroom->id)) ?>">
                                            <!-- <img alt="impressora" src="<?= Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                            <span class="t-icon-printer"></span>
                                            <?= Yii::t('default', 'Lista de Alunos') ?>
                                        </a>
                                    </div>
                                    <div class="reports_cards">
                                        <a class="t-button-secondary" rel="noopener" target="_blank"
                                            href="<?= Yii::app()->createUrl('forms/StudentsFileForm', array('classroom_id' => $modelClassroom->id, 'type' => 1)) ?>">
                                            <!-- <img alt="impressora" src="<?= Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                            <span class="t-icon-printer"></span>
                                            <?= Yii::t('default', 'Fichas de Matrícula') ?>
                                        </a>
                                    </div>
                                    <div class="reports_cards">
                                        <a class="t-button-secondary" rel="noopener" target="_blank"
                                            href="<?= Yii::app()->createUrl('forms/AtaSchoolPerformance', array('id' => $modelClassroom->id)) ?>">
                                            <!-- <img alt="impressora" src="<?= Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                            <span class="t-icon-printer"></span>
                                            <?= Yii::t('default', 'Ata de Notas') ?>
                                        </a>
                                    </div>
                                    <?php if (Yii::app()->features->isEnable("FEAT_SEDSP") && count($modelEnrollments) > 0): ?>
                                        <div class="reports_cards">
                                            <button class="t-button-primary sync-enrollments">
                                                <span class="t-icon-export"></span>
                                                Sincronizar Matrículas (SEDSP)
                                            </button>
                                            <img class="loading-sync" style="display:none;margin: 10px 20px;" height="30px"
                                                width="30px"
                                                src="<?= Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif"
                                                alt="TAG Loading">
                                        </div>
                                    <?php endif ?>
                                </div>
                            </row>
                        </container>

                        <div class="btn-group pull-right responsive-menu">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                Menu
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?= Yii::app()->createUrl('classroom/batchupdatenrollment', array('id' => $modelClassroom->id)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Sinalizar Rematricula') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->createUrl('classroom/batchupdatetransport', array('id' => $modelClassroom->id)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Atualizar transporte') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->createUrl('classroom/batchupdatetotal', array('id' => $modelClassroom->id)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Atualização em Lote') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->createUrl('reports/enrollmentperclassroomreport', array('id' => $modelClassroom->id)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Relatório de Matrícula') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->createUrl('reports/studentperclassroom', array('id' => $modelClassroom->id)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Lista de Alunos') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->createUrl('forms/StudentsFileForm', array('classroom_id' => $modelClassroom->id, 'type' => 1)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Fichas de Matrícula') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->createUrl('forms/AtaSchoolPerformance', array('id' => $modelClassroom->id)) ?>"
                                        target="blank" class="hidden-print"><i></i>
                                        <?= Yii::t('default', 'Ata de Notas') ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="widget-StudentsList" class="widget" style="margin-top: 8px;">
                            <?php
                            $columnCount = Yii::app()->features->isEnable("FEAT_SEDSP") ? 6 : 5;
                            ?>
                            <style type="text/css" media="print">
                                a[href]:after {
                                    content: "" !important;
                                }
                            </style>
                            <table id="StudentsList" class="table table-bordered table-striped" style="display: table;">
                                <thead>
                                    <tr>
                                        <th class='span1'>
                                            <?= Yii::t('default', 'Mover/Cancelar') ?>
                                        </th>
                                        <th>
                                            <?= Yii::t('default', 'Ordem') ?>
                                        </th>
                                        <th>
                                            <?= Yii::t('default', 'Enrollment') ?>
                                        </th>
                                        <th>
                                            <?= Yii::t('default', 'Name') ?>
                                        </th>
                                        <th>
                                            <?= Yii::t('default', 'Status') ?>
                                        </th>
                                        <?= Yii::app()->features->isEnable("FEAT_SEDSP") ? "<th>Sincronizado</th>" : "" ?>
                                        <th>
                                            <?= Yii::t('default', 'Print') ?>
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    if (count($modelEnrollments) > 0):
                                        $i = 1;
                                        foreach ($modelEnrollments as $enrollment): ?>
                                            <tr>
                                                <td text-align="center">
                                                    <input value="<?= $enrollment["enrollmentId"] ?>" name="enrollments[]"
                                                        type='checkbox' />
                                                </td>
                                                <td width="30">
                                                    <?= $enrollment["daily_order"] ?? $i ?>
                                                </td>
                                                <td enrollmentid="<?= $enrollment["enrollmentId"] ?>">
                                                    <?= $enrollment["enrollmentId"] ?>
                                                </td>
                                                <td>
                                                    <a
                                                        href="<?= Yii::app()->createUrl('student/update', array('id' => $enrollment["studentId"])) ?>">
                                                        <?= $enrollment["studentName"] ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?= $enrollment["status"] ?>
                                                </td>
                                                <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")): ?>
                                                    <td class="sync-column">
                                                        <?php if ($enrollment["synced"]) { ?>
                                                            <img src="<?= Yii::app()->theme->baseUrl; ?>/img/SyncTrue.png"
                                                                style="width: 21px;" alt="synced">
                                                        <?php } else { ?>
                                                            <img src="<?= Yii::app()->theme->baseUrl; ?>/img/notSync.png"
                                                                style="width: 21px" alt="not synced">
                                                        <?php } ?>
                                                    </td>
                                                <?php endif ?>
                                                <td width="140">
                                                    <a href="<?= @Yii::app()->createUrl('forms/StudentFileForm', array('type' => $type, 'enrollment_id' => $enrollment["enrollmentId"])); ?>"
                                                        target="_blank" rel="noopener"> <i class="fa fa-eye" style="color:#3F45EA; "></i>
                                                        Ficha de Matrícula
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        endforeach;
                                        ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="center" colspan="<?= $columnCount ?>">Não há alunos matriculados.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfooter>
                                    <?php
                                    echo "<tr><td>Total:</td><td colspan='" . ($columnCount - 1) . "'>" . count($modelEnrollments) . "</td></tr>";
                                    echo '<tr><td colspan="' . $columnCount . '">';
                                    echo chtml::dropDownList(
                                        'toclassroom',
                                        "",
                                        CHtml::listData(Classroom::model()->findAll(
                                            "school_year = :sy AND school_inep_fk = :si order by name",
                                            array("sy" => (Yii::app()->user->year), "si" => yii::app()->user->school)
                                        ), 'id', 'name'),
                                        array(
                                            'class' => 'span5',
                                            'empty' => '**EXCLUIR MATRICULAS**'
                                        )
                                    );
                                    echo '<input value="Mover/Excluir" type="submit" class="t-button-primary " style="margin-left:10px"><i></i></input></td></tr>';
                                    ?>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="daily">
                    <div class="row">
                        <button id="js-alphabetic-order" type="button" class="t-button-secondary"><span class="t-icon-arrow-az"></span>Ordem Alfabética</button>
                    </div>
                    <ul id="js-t-sortable" class="t-sortable t-margin-none--left">
                        <?php
                        if (isset($modelEnrollments)) {
                            $i = 1;
                            foreach ($modelEnrollments as $enrollment) {
                                ?>
                                <li id="<?= $enrollment["enrollmentId"] ?>" class="ui-state-default">
                                    <span class="t-icon-slip"></span>
                                    <?= $enrollment["dailyOrder"] ?>
                                    <span>
                                        <?= $enrollment["studentName"] ?>
                                    </span>
                                </li>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </ul>

                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>

    <div id="teachingdata-dialog-form" title="<?= Yii::t('default', 'New Discipline'); ?>">
        <div class="alert alert-error no-curricular-matrix-error">Preencha a matriz curricular da etapa de ensino
            selecionada nesta turma para adicionar Componentes curriculares/eixos.
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?= CHtml::label(Yii::t("default", "Instructor"), "Instructors", array('class' => 't-field-text__label')) ?>
                    <?= CHtml::DropDownList("Instructors", '', CHtml::listData(InstructorIdentification::model()->findAll(), 'id', 'name'), array('prompt' => 'Sem Instrutor', 'class' => 'select-search-on control-input')); ?>
                </div>
                <div class="control-group">
                    <label class="t-field-text__label">Componentes curriculares/eixos <span style="margin: 0;"
                            class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip"
                            data-placement="right"
                            data-original-title="Serão listadas apenas as componentes curriculares/eixos inseridos na matriz curricular desta etapa de ensino selecionada na turma.">
                            <!-- <i></i> -->
                        </span></label>
                    <select id="Disciplines" class="select-disciplines" multiple></select>
                </div>
                <div class="control-group">
                    <?= CHtml::label(Yii::t("default", "Role"), "Role", array('class' => 't-field-text__label')) ?>
                    <?php
                    echo CHtml::DropDownList("Role", '', array(
                        null => 'Selecione um Cargo',
                        1 => 'Docente',
                        2 => 'Auxiliar/assistente educacional',
                        3 => 'Profissional/monitor de atividade complementar',
                        4 => 'Tradutor e Intérprete de Libras',
                        5 => "EAD - Docente Titular",
                        6 => "EAD - Docente Tutor",
                        7 => "Guia-Intérprete",
                        8 => "Profissional de apoio escolar para aluno(a) com deficiência",
                        9 => "Docente Substituto",
                    ), array('class' => 'select-search-off'));
                    ?>
                </div>
                <div class="control-group">
                    <?= CHtml::label(Yii::t("default", "Contract Type"), "ContractType", array('class' => 't-field-text__label')) ?>
                    <?php
                    echo CHtml::DropDownList("ContractType", '', array(
                        null => 'Selecione tipo de Contrato',
                        1 => 'Concursado/Efetivo',
                        2 => 'Temporário',
                        3 => 'Terceirizado',
                        4 => 'CLT',
                    ), array('class' => 'select-search-off'));
                    ?>
                </div>
                <div class="control-group regent-teacher-container" style="display:none">
                    <?= CHtml::checkBox("RegentTeacher", false, array('value' => '1', 'id' => 'RegentTeacher')); ?>
                    <?= CHtml::label(Yii::t("default", "Regent Teacher"), "RegentTeacher", array('class' => 't-field-text__label', 'style' => 'display: inline-block')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-content" id="importClassroomFromSEDSP" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                        style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Importar turma da SEDSP</h4>
            </div>
            <form method="post"
                action="<?= $this->createUrl('sedsp/default/importClassroomFromSedsp', array('id' => $modelClassroom->id, 'gov_id' => $modelClassroom->gov_id)); ?>">
                <div class="centered-loading-gif">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div class="modal-body">
                    <div class="alert alert-error no-show"></div>
                    <div class="row-fluid">
                        Você tem certeza?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                        </button>
                        <button type="button" class="btn btn-primary import-classroom-button">Confirmar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
    $this->widget('application.widgets.AlertCensoWidget', array('prefix' => 'classroom', 'dataId' => $_GET['id']));
}
?>

<script type="text/javascript">
    ////////////////////////////////////////////////
    // Variables and Initialization               //
    ////////////////////////////////////////////////
    var teachingData = <?= json_encode($teachingDataArray); ?>;
    var disciplines = <?= json_encode($disciplinesArray); ?>;
    var disciplinesLabels = <?= json_encode($disciplinesLabels); ?>;
    var teachingDataNames = <?= json_encode($teachingDataNames); ?>;

    var form = '#Classroom_';
    var formTeaching = '#InstructorTeachingData_';
    var lesson = {};
    var lessons = {};
    var lesson_id = 1;
    var lesson_start = 1;
    var lesson_end = 2;

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var myTeachingDataDialog;

    var instructor = $("#insertclass-instructor");
    var uInstructor = $("#insertclass-update-instructor");
    var discipline = $("#discipline");
    var role = $("#Role");
    var instructors = $("#Instructors");
    var uDiscipline = $("#update-discipline");

    var classroomId = '<?= $modelClassroom->id; ?>';

    var firstTime = true;

    var baseURL = '<?= Yii::app()->theme->baseUrl; ?>';
    var getAssistanceURL = '<?= Yii::app()->createUrl('classroom/getassistancetype') ?>';
    var jsonCompActv = '<?= json_encode($complementaryActivities); ?>';
    var updateLessonUrl = '<?= CController::createUrl('classroom/updateLesson'); ?>';
    var addLessonUrl = '<?= CController::createUrl('classroom/addLesson'); ?>';
    var deleteLessonUrl = '<?= CController::createUrl('classroom/deleteLesson'); ?>';

    var btnCreate = "<?= Yii::t('default', 'Create'); ?>";
    var btnCancel = "<?= Yii::t('default', 'Cancel'); ?>";

    $("#print").on('click', function () {
        window.print();
    });
</script>
