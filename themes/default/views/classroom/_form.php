<?php

/**
 * 
 * @var CActiveForm $this CActiveForm
 * @var Classroom $modelClassroom Classroom
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classroom/form/_initialization.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/functions.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/validations.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classroom/form/pagination.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile($baseUrl . 'sass/css/main.css');

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classroom-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h1><?php echo $title; ?></h1>
        <div class="tag-buttons-container buttons">
            <button class="t-button-primary  last pull-right save-classroom" type="button">
                <?= $modelClassroom->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('success') && (!$modelClassroom->isNewRecord)) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">

        <?php echo $form->errorSummary($modelClassroom); ?>
        <div class="alert alert-error classroom-error no-show"></div>
        <div class="t-tabs">
            <ul class="tab-classroom t-tabs__list">
                <li id="tab-classroom" class="active t-tabs__item">
                    <a class="t-tabs__link" href="#classroom" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Classroom') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-instructors" class="t-tabs__item">
                    <a class="t-tabs__link" href="#instructors" data-toggle="tab">
                        <span class="t-tabs__numeration">2</span>
                        <?php echo Yii::t('default', 'Instructors') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-students" class="t-tabs__item">
                    <a class="t-tabs__link" href="#students" data-toggle="tab">
                        <span class="t-tabs__numeration">3</span>
                        <?php echo Yii::t('default', 'Students') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="classroom">
                    <div>
                        <h3>Dados Básicos</h3>
                    </div>
                    <div class="row">
                        <div class="column">
                            <?php
                            echo $form->hiddenField($modelClassroom, 'school_inep_fk', array('value' => Yii::app()->user->school));
                            echo CHtml::hiddenField("teachingData", '', array('id' => 'teachingData'));
                            echo CHtml::hiddenField("disciplines", '', array('id' => 'disciplines'));
                            echo CHtml::hiddenField("events", '', array('id' => 'events'));
                            ?>
                            <!-- Nome -->
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelClassroom, 'name', array('class' => 't-field-text__label--required')); ?>
                                <?php echo $form->textField($modelClassroom, 'name', array('size' => 60, 'maxlength' => 80, 'class' => 't-field-text__input', 'placeholder' => ' Nome completo')); ?>
                                <?php echo $form->error($modelClassroom, 'name'); ?>
                            </div>
                            <!-- Tipo de Mediação Didático-Pedagógica -->
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelClassroom, 'pedagogical_mediation_type', array('class' => 't-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelClassroom, 'pedagogical_mediation_type', array(null => 'Selecione o tipo', "1" => "Presencial", "2" => "Semipresencial", "3" => "Educação a Distância"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelClassroom, 'pedagogical_mediation_type'); ?>
                            </div>
                            <!-- Código Curso Educação Profissional -->
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelClassroom, 'edcenso_professional_education_course_fk', array('class' => 't-field-select__label')); ?>
                                <?php echo $form->DropDownList($modelClassroom, 'edcenso_professional_education_course_fk', CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o curso', 'class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelClassroom, 'edcenso_professional_education_course_fk'); ?>
                            </div>
                            <!-- Local de Funcionamento Diferenciado -->
                            <div class="t-field-select" id="diff_location_container">
                                <?php echo $form->labelEx($modelClassroom, 'diff_location', array('class' => 't-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelClassroom, 'diff_location', array(null => 'Selecione a localização', 0 => 'A turma não está em local de funcionamento diferenciado', 1 => 'Sala anexa', 2 => 'Unidade de atendimento socioeducativo', 3 => 'Unidade prisional'), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelClassroom, 'diff_location'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <!-- Etapa de Ensino -->
                            <div class="t-field-select" id="stage_vs_modality">
                                <?php echo $form->labelEx($modelClassroom, 'edcenso_stage_vs_modality_fk', array('class' => 't-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelClassroom, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o estágio vs modalidade', 'class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelClassroom, 'edcenso_stage_vs_modality_fk'); ?>
                                <i class="loading-disciplines fa fa-spin fa-spinner"></i>
                            </div>
                            <!-- Modalidade -->
                            <div class="t-field-select" id="modality">
                                <?php echo $form->labelEx($modelClassroom, 'modality', array('class' => 't-field-select__label--required')); ?>
                                <?php
                                echo $form->DropDownList($modelClassroom, 'modality', array(
                                    '1' => 'Ensino Regular',
                                    '2' => 'Educação Especial - Modalidade Substitutiva',
                                    '3' => 'Educação de Jovens e Adultos (EJA)'
                                ), array('prompt' => 'Selecione a Modalidade', 'class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelClassroom, 'modality'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3>Horário de funcionamento</h3>
                    </div>
                    <div class="row">
                        <div class="column">
                            <!-- hora inicial -->
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelClassroom, 'initial_hour', array('class' => 't-field-text__label--required')); ?>
                                <?php echo $form->hiddenField($modelClassroom, 'initial_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                <?php echo $form->hiddenField($modelClassroom, 'initial_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                <?php echo CHtml::textField('Classroom_initial_time', $modelClassroom->initial_hour . '' . $modelClassroom->initial_minute, array('size' => 5, 'maxlength' => 5, 'class' => 't-field-text__input', 'placeholder' => ' Somente números')); ?>
                                <!-- <?php echo Yii::t('help', 'Time'); ?> -->
                                <?php echo $form->error($modelClassroom, 'initial_hour'); ?>
                                <?php echo $form->error($modelClassroom, 'initial_minute'); ?>
                            </div>
                            <!-- turno -->
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelClassroom, 'turn', array('class' => 't-field-select__label--required')); ?>
                                <?php
                                echo $form->DropDownList($modelClassroom, 'turn', array(
                                    null => 'Selecione o turno',
                                    'M' => 'Manhã',
                                    'T' => 'Tarde',
                                    'N' => 'Noite',
                                    'I' => 'Integral'
                                ), array(
                                    'class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%',
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('classroom/updateTime'),
                                        'success' => "function(data){
                                                				updateTime(data);
                                                		}",
                                    ),
                                ));
                                ?>
                                <?php echo $form->error($modelClassroom, 'turn'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <!-- hora final -->
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelClassroom, 'final_hour', array('class' => 't-field-text__label--required', 'placeholder' => 'Somente números')); ?>
                                <?php echo $form->hiddenField($modelClassroom, 'final_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                <?php echo $form->hiddenField($modelClassroom, 'final_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                <?php echo CHtml::textField('Classroom_final_time', $modelClassroom->final_hour . '' . $modelClassroom->final_minute, array('size' => 5, 'maxlength' => 5, 'class' => 't-field-text__input', 'placeholder' => ' Somente números')); ?>
                                <!-- <?php echo Yii::t('help', 'Time'); ?> -->
                                <?php echo $form->error($modelClassroom, 'final_hour'); ?>
                                <?php echo $form->error($modelClassroom, 'final_minute'); ?>
                            </div>
                            
                            <!-- Dias da semana -->
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label required"><?php echo Yii::t('default', 'Week Days'); ?>
                                        *</label>
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
                                                      data-original-title="<?php echo Yii::t('help', 'Week days'); ?>"><i></i></span>
                                            </td> -->
                                        </tr>
                                        <tr>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_monday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_tuesday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_wednesday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_thursday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_friday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_saturday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td><?php echo $form->checkBox($modelClassroom, 'week_days_sunday', array('value' => 1, 'uncheckValue' => 0,)); ?></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div>
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
                                <div class="controls">
                                    <?php echo $form->labelEx($modelClassroom, 'school_year', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelClassroom, 'school_year', array('value' => isset($modelClassroom->school_year) ? $modelClassroom->school_year : Yii::app()->user->year, 'size' => 5, 'maxlength' => 5)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'School year'); ?>"><i></i></span>
                                    <?php echo $form->error($modelClassroom, 'school_year'); ?>
                                </div>
                            </div>
                            <!-- Tipo de Atendimento* -->
                            <div class="t-field-checkbox-group" id="aee2">
                                <label class="t-field-checkbox__label--required"><?php echo Yii::t('default', 'Assistence Types'); ?>*</label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'schooling', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input', 'id' => 'Escolarização')); ?>
                                    <label for="Escolarização">
                                        <?php echo Classroom::model()->attributeLabels()['schooling']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'complementary_activity', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input', 'id' => 'Atividade Complementar')); ?>
                                    <label for="Atividade Complementar">
                                        <?php echo Classroom::model()->attributeLabels()['complementary_activity']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input', 'id' => 'Atendimento Educacional Especializado (AEE)')); ?>
                                    <label for="Atendimento Educacional Especializado (AEE)">
                                        <?php echo Classroom::model()->attributeLabels()['aee']; ?>
                                    </label>
                                </div>
                            </div>
                            <!-- Participante do programa Mais Educação -->
                            <div class="control-group" id="mais_educacao">
                                <div id="none">
                                    <?php echo CHtml::activeHiddenField($modelClassroom, 'mais_educacao_participator', array('disabled' => 'disabled',)) ?>
                                </div>
                                <div class="t-field-checkbox" id="some">
                                    <?php echo $form->checkBox($modelClassroom, 'mais_educacao_participator', array('class' => 't-field-checkbox__input', 'id' => 'Classroom[mais_educacao_participator]')); ?>
                                    <?php echo $form->error($modelClassroom, 'mais_educacao_participator'); ?>
                                    <?php echo $form->labelEx($modelClassroom, 'mais_educacao_participator', array('class' => 't-field-checkbox__label', 'for' => 'Classroom[mais_educacao_participator]')); ?>

                                </div>
                            </div>
                            <div class="control-group" id="complementary_activity">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelClassroom, 'complementary_activity_type_1', array('class' => 'control-label required')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelClassroom, 'complementary_activity_type_1', CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'), array('multiple' => true, 'class' => 'select-search-on control-input', 'key' => 'id')); ?>
                                    <!-- <?php echo $form->dropDownList($modelClassroom, 'complementary_activity_type_1', CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'), array('multiple' => true, 'class' => 'select-ComplementaryAT', 'key' => 'id')); ?> -->

                                    <?php echo $form->error($modelClassroom, 'complementary_activity_type_1'); ?>
                                    <!-- atividade complementar -->
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <!-- atividades do  atendimento  educacional  especializado -->
                            <div class="t-field-checkbox-group" id="aee2">
                                <label class="t-field-checkbox__label"><?php echo Yii::t('default', 'Aee'); ?></label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_braille', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino do Sistema Braille')); ?>
                                    <label class="t-field-checkbox" for="Ensino do Sistema Braille">
                                        <?php echo Classroom::model()->attributeLabels()['aee_braille']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_optical_nonoptical', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino do uso de recursos ópticos e não ópticos')); ?>
                                    <label class="t-field-checkbox" for="Ensino do uso de recursos ópticos e não ópticos">
                                        <?php echo Classroom::model()->attributeLabels()['aee_optical_nonoptical']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_cognitive_functions', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Estratégias para o desenvolvimento de processos mentais')); ?>
                                    <label class="t-field-checkbox" for="Estratégias para o desenvolvimento de processos mentais">
                                        <?php echo Classroom::model()->attributeLabels()['aee_cognitive_functions']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_mobility_techniques', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Técnicas de orientação e mobilidade')); ?>
                                    <label class="t-field-checkbox" for="Técnicas de orientação e mobilidade">
                                        <?php echo Classroom::model()->attributeLabels()['aee_mobility_techniques']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_libras', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino da Língua Brasileira de Sinais (Libras)')); ?>
                                    <label class="t-field-checkbox" for="Ensino da Língua Brasileira de Sinais (Libras)">
                                        <?php echo Classroom::model()->attributeLabels()['aee_libras']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_caa', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino de uso da Comunicação Alternativa e Aumentativa - CAA')); ?>
                                    <label class="t-field-checkbox" for="Ensino de uso da Comunicação Alternativa e Aumentativa - CAA">
                                        <?php echo Classroom::model()->attributeLabels()['aee_caa']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_curriculum_enrichment', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Estratégias para o enriquecimento curricular')); ?>
                                    <label class="t-field-checkbox" for="Estratégias para o enriquecimento curricular">
                                        <?php echo Classroom::model()->attributeLabels()['aee_curriculum_enrichment']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_soroban', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino do uso do Soroban')); ?>
                                    <label class="t-field-checkbox" for="Ensino do uso do Soroban">
                                        <?php echo Classroom::model()->attributeLabels()['aee_soroban']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_accessible_teaching', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino da usabilidade e das funcionalidades da informática acessível')); ?>
                                    <label class="t-field-checkbox" for="Ensino da usabilidade e das funcionalidades da informática acessível">
                                        <?php echo Classroom::model()->attributeLabels()['aee_accessible_teaching']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_portuguese', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Ensino da Língua Portuguesa na modalidade escrita')); ?>
                                    <label class="t-field-checkbox" for="Ensino da Língua Portuguesa na modalidade escrita">
                                        <?php echo Classroom::model()->attributeLabels()['aee_portuguese']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelClassroom, 'aee_autonomous_life', array('value' => 1, 'uncheckValue' => 0, 'id' => 'Estratégias para autonomia no ambiente escolar')); ?>
                                    <label class="t-field-checkbox" for="Estratégias para autonomia no ambiente escolar">
                                        <?php echo Classroom::model()->attributeLabels()['aee_autonomous_life']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="instructors">
                    <div class="row-fluid">
                        <div class=" span12">
                            <!-- adicionar diciplina -->
                            <div class="row">
                                <a href="#" class="t-button-primary   add hidden-print" id="newDiscipline"><i></i><?php echo Yii::t('default', 'Add Discipline/Teacher') ?></a>
                            </div>

                            <div class="separator"></div>
                            <?php
                            $teachingDataList = "<div>"
                                . "<div class='disciplines-with-container'><span><b>Disciplinas com Instrutores</b></span><div class='separator'></div>"
                                . "<ul id='DisciplinesWithInstructors'>";
                            $teachingDataArray = array();
                            $teachingDataDisciplines = array();
                            $disciplinesLabels = array();
                            $teachingDataNames = array();

                            $instructors = InstructorIdentification::model()->findAll();
                            foreach ($instructors as $instructor) {
                                $teachingDataNames[$instructor->id] = $instructor->name;
                            }
                            $roleName = [null, "Professor", "Auxiliar", "Monitor", "Intérprete"];
                            $contractTypeName = [null, "Concursado/Efetivo", "Temporário", "Terceirizado", "CLT"];
                            $i = 0;
                            foreach ($modelTeachingData as $key => $model) {
                                $teachingDataList .= "<li instructor='" . $model->instructor_fk . "'><span>" . $model->instructorFk->name . "</span><span> - " . $roleName[$model->role] . "</span>"
                                    . '<a  href="#" class="deleteTeachingData delete" title="Excluir">
                                              </a>';
                                $teachingDataList .= "<ul>";

                                $teachingDataArray[$i] = array();
                                $teachingDataArray[$i]['Instructor'] = $model->instructor_fk;
                                $teachingDataArray[$i]['Classroom'] = $model->classroom_id_fk;
                                $teachingDataArray[$i]['Role'] = $model->role;
                                $teachingDataArray[$i]['ContractType'] = $model->contract_type;
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
                            $teachingDataList .= "<div class='disciplines-without-container'><span><b>Disciplinas sem Instrutores</b></span><div class='separator'></div>"
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
                        <div class='row hide-responsive' style="margin-bottom:20px">
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('classroom/batchupdatetransport', array('id' => $modelClassroom->id)); ?>">
                                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" />
                                    <?php echo Yii::t('default', 'Atualizar transporte') ?>
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?php echo Yii::app()->createUrl('classroom/batchupdatetotal', array('id' => $modelClassroom->id)) ?>">
                                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" />
                                    <?php echo Yii::t('default', 'Atualização em Lote') ?>
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?php echo Yii::app()->createUrl('reports/enrollmentperclassroomreport', array('id' => $modelClassroom->id)) ?>">
                                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" />
                                    <?php echo Yii::t('default', 'Relatório de Matrícula') ?>
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?php echo Yii::app()->createUrl('reports/studentperclassroom', array('id' => $modelClassroom->id)) ?>">
                                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" />
                                    <?php echo Yii::t('default', 'Lista de Alunos') ?>
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?php echo Yii::app()->createUrl('forms/StudentsFileForm', array('classroom_id' => $modelClassroom->id, 'type' => 1)) ?>">
                                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" />
                                    <?php echo Yii::t('default', 'Fichas de Matrícula') ?>
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?php echo Yii::app()->createUrl('forms/AtaSchoolPerformance', array('id' => $modelClassroom->id)) ?>">
                                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" />
                                    <?php echo Yii::t('default', 'Ata de Notas') ?>
                                </a>
                            </div>
                            <!-- <a href="<?php echo Yii::app()->createUrl('classroom/batchupdatetransport', array('id' => $modelClassroom->id)) ?>" target="blank" class="t-button-primary   hidden-print"><?php echo Yii::t('default', 'Atualizar transporte') ?>
                            </a> -->
                            <!-- <a href="<?php echo Yii::app()->createUrl('classroom/batchupdatetotal', array('id' => $modelClassroom->id)) ?>" target="blank" class="t-button-primary   hidden-print"><?php echo Yii::t('default', 'Atualização em Lote') ?>
                            </a> -->
                            <!-- <a href="<?php echo Yii::app()->createUrl('reports/enrollmentperclassroomreport', array('id' => $modelClassroom->id)) ?>" target="blank" class="t-button-primary   hidden-print"><?php echo Yii::t('default', 'Relatório de Matrícula') ?>
                            </a> -->
                            <!-- <a href="<?php echo Yii::app()->createUrl('reports/studentperclassroom', array('id' => $modelClassroom->id)) ?>" target="blank" class="t-button-primary   hidden-print"><?php echo Yii::t('default', 'Lista de Alunos') ?>
                            </a> -->
                            <!-- <a href="<?php echo Yii::app()->createUrl('forms/StudentsFileForm', array('classroom_id' => $modelClassroom->id, 'type' => 1)) ?>" target="blank" class="t-button-primary   hidden-print"><?php echo Yii::t('default', 'Fichas de Matrícula') ?>
                            </a> -->
                            <!-- <a href="<?php echo Yii::app()->createUrl('forms/AtaSchoolPerformance', array('id' => $modelClassroom->id)) ?>" target="blank" class="t-button-primary   hidden-print"><?php echo Yii::t('default', 'Ata de Notas') ?>
                            </a> -->
                        </div>

                        <div class="btn-group pull-right responsive-menu">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                Menu
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('classroom/batchupdatetransport', array('id' => $modelClassroom->id)) ?>" target="blank" class="hidden-print"><i></i><?php echo Yii::t('default', 'Atualizar transporte') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('classroom/batchupdatetotal', array('id' => $modelClassroom->id)) ?>" target="blank" class="hidden-print"><i></i><?php echo Yii::t('default', 'Atualização em Lote') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('reports/enrollmentperclassroomreport', array('id' => $modelClassroom->id)) ?>" target="blank" class="hidden-print"><i></i><?php echo Yii::t('default', 'Relatório de Matrícula') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('reports/studentperclassroom', array('id' => $modelClassroom->id)) ?>" target="blank" class="hidden-print"><i></i><?php echo Yii::t('default', 'Lista de Alunos') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('forms/StudentsFileForm', array('classroom_id' => $modelClassroom->id, 'type' => 1)) ?>" target="blank" class="hidden-print"><i></i><?php echo Yii::t('default', 'Fichas de Matrícula') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('forms/AtaSchoolPerformance', array('id' => $modelClassroom->id)) ?>" target="blank" class="hidden-print"><i></i><?php echo Yii::t('default', 'Ata de Notas') ?></a>
                                </li>
                            </ul>
                        </div>
                        <div id="widget-StudentsList" class="widget" style="margin-top: 8px;">
                            <?php
                            $enrollments = $modelClassroom->studentEnrollments;
                            ?>
                            <style type="text/css" media="print">
                                a[href]:after {
                                    content: "" !important;
                                }
                            </style>
                            <table id="StudentsList" class="table table-bordered table-striped" style="display: table;">
                                <thead>
                                    <tr>
                                        <th class='span1'><?php echo Yii::t('default', 'Mover/Cancelar') ?></th>
                                        <th><?php echo Yii::t('default', 'Pedido') ?></th>
                                        <th><?php echo Yii::t('default', 'Name') ?></th>
                                        <th><?php echo Yii::t('default', 'Print') ?></th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($enrollments)) {
                                        $i = 1;
                                        foreach ($enrollments as $enr) { ?>
                                            <tr>
                                                <td text-align="center"><input value="<?= $enr->id ?>" name="enrollments[]" type='checkbox' /></td>
                                                <td width="30"><?= $i ?></td>
                                                <td>
                                                    <a href="<?= Yii::app()->createUrl('student/update', array('id' => $enr->studentFk->id)) ?>"> <?= $enr->studentFk->name ?></a>
                                                </td>
                                                <td width="140">
                                                    <a href="<?php echo @Yii::app()->createUrl('forms/StudentFileForm', array('type' => $type, 'enrollment_id' => $enr->id)); ?>" target="_blank"> <i class="fa fa-eye" style="color:#3F45EA; "></i>
                                                        Ficha de Matrícula
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                        }
                                        ?>
                                        <tr>
                                            <th>Total:</th>
                                            <td colspan="3"><?= count($enrollments) ?></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <th colspan="4">Não há alunos matriculados.</th>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfooter>
                                    <?php
                                    echo "<tr><td colspan='3'>Total:</td><td>" . count($enrollments) . "</td></tr>";
                                    echo '<tr><td colspan="3">';
                                    echo chtml::dropDownList('toclassroom', "", CHtml::listData(Classroom::model()->findAll(
                                        "school_year = :sy AND school_inep_fk = :si order by name",
                                        array("sy" => (Yii::app()->user->year), "si" => yii::app()->user->school)
                                    ), 'id', 'name'), array(
                                        'class' => 'span5',
                                        'empty' => '**CANCELAR MATRICULAS**'
                                    ));
                                    echo '<input value="Mover/Cancelar" type="submit" class="t-button-primary " style="margin-left:10px"><i></i></input></td></tr>';
                                    ?>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>

    <div id="teachingdata-dialog-form" title="<?php echo Yii::t('default', 'New Discipline'); ?>">
        <div class="alert alert-error no-curricular-matrix-error">Preencha a matriz curricular da etapa de ensino
            selecionada nesta turma para adicionar disciplinas.
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t("default", "Instructor"), "Instructors", array('class' => 'control-label')) ?>
                    <?php echo CHtml::DropDownList("Instructors", '', CHtml::listData(InstructorIdentification::model()->findAll(), 'id', 'name'), array('prompt' => 'Sem Instrutor', 'class' => 'select-search-on control-input')); ?>
                </div>
                <div class="control-group">
                    <label class="control-label">Disciplinas <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="right" data-original-title="Serão listadas apenas as disciplinas inseridas na matriz curricular desta etapa de ensino selecionada na turma.">
                            <!-- <i></i> -->
                        </span></label>
                    <select id="Disciplines" class="select-disciplines" multiple></select>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t("default", "Role"), "Role", array('class' => 'control-label')) ?>
                    <?php
                    echo CHtml::DropDownList("Role", '', array(
                        null => 'Selecione um Cargo',
                        1 => 'Professor',
                        2 => 'Auxiliar',
                        3 => 'Monitor',
                        4 => 'Intérprete',
                    ), array('class' => 'select-search-off'));
                    ?>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t("default", "Contract Type"), "ContractType", array('class' => 'control-label')) ?>
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
            </div>
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
    var teachingData = <?php echo json_encode($teachingDataArray); ?>;
    var disciplines = <?php echo json_encode($disciplinesArray); ?>;
    var disciplinesLabels = <?php echo json_encode($disciplinesLabels); ?>;
    var teachingDataNames = <?php echo json_encode($teachingDataNames); ?>;

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

    var classroomId = '<?php echo $modelClassroom->id; ?>';

    var firstTime = true;

    var getAssistanceURL = '<?php echo Yii::app()->createUrl('classroom/getassistancetype') ?>';
    var jsonCompActv = '<?php echo json_encode($complementaryActivities); ?>';
    var updateLessonUrl = '<?php echo CController::createUrl('classroom/updateLesson'); ?>';
    var addLessonUrl = '<?php echo CController::createUrl('classroom/addLesson'); ?>';
    var deleteLessonUrl = '<?php echo CController::createUrl('classroom/deleteLesson'); ?>';

    var btnCreate = "<?php echo Yii::t('default', 'Create'); ?>";
    var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";

    $("#print").on('click', function() {
        window.print();
    });
</script>