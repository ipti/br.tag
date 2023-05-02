<?php

/**
 * @var StudentController $this StudentController
 * @var $form CActiveForm
 * @var $cs CClientScript
 * @var StudentIdentification $modelStudentIdentification StudentIdentification
 * @var StudentDocumentsAndAddress $modelStudentDocumentsAndAddress StudentDocumentsAndAddress
 *
 */
/* @var $modelStudentIdentification /app/models/StudentIdentification */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/student/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/validations.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/pagination.js', CClientScript::POS_END);

$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/functions.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile($baseUrl . 'sass/css/main.css');
/* */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'student',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid">
    <div class="span12">
        <h1><?php echo $title; ?></h1>
        <div class="tag-buttons-container buttons hide-responsive">
            <a data-toggle="tab" class='hide-responsive t-button-secondary prev' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            <?= $modelStudentIdentification->isNewRecord ? "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>
            <button class="t-button-primary  last  save-student" type="button">
                <?= $modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php elseif (Yii::app()->user->hasFlash('error')) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <?php
        echo $form->errorSummary($modelStudentIdentification);
        echo $form->errorSummary($modelStudentDocumentsAndAddress);
        ?>
        <div class="alert alert-error student-error no-show"></div>
        <div class="t-tabs js-tab-control">
            <ul class="tab-student t-tabs__list">
                <li id="tab-student-identify" class="t-tabs__item active">
                    <a class="t-tabs__link" href="#student-identify" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Identification') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-student-affiliation" class="t-tabs__item ">
                    <a class="t-tabs__link" href="#student-affiliation" data-toggle="tab">
                        <span class="t-tabs__numeration">2</span>
                        <?php echo Yii::t('default', 'Affiliation') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-student-documents" class="t-tabs__item ">
                    <a class="t-tabs__link" href="#student-documents" data-toggle="tab">
                        <span class="t-tabs__numeration">3</span>
                        <?php echo Yii::t('default', 'Documents') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-student-address" class="t-tabs__item ">
                    <a class="t-tabs__link" href="#student-address" data-toggle="tab">
                        <span class="t-tabs__numeration js-change-number-2">4</span>
                        <?php echo Yii::t('default', 'Address') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-student-enrollment" class="t-tabs__item ">
                    <a class="t-tabs__link" href="#student-enrollment" data-toggle="tab">
                        <span class="t-tabs__numeration js-change-number-3">5</span>
                        <?php echo Yii::t('default', 'Enrollment') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-student-health" class="t-tabs__item ">
                    <a class="t-tabs__link" href="#student-health" data-toggle="tab">
                        <span class="t-tabs__numeration js-change-number-4">6</span>
                        <?php echo Yii::t('default', 'Health') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div>
            <div class="tab-content form-content" style="display:none">
                <!-- Tab content Botão de próximo -->
                <!-- Tab Student Identify -->
                <div class="tab-pane active" id="student-identify">
                    <div>
                        <h3>
                            Dados Básicos
                        </h3>
                    </div>
                    <div class="row">
                        <div class="column">

                            <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
                            <!-- name student -->
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentIdentification, 'name', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'name', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome Social')); ?>
                                <span id="similarMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="warningNameIcon" onclick="displayRecords()" style="display: none;" src="<?php echo $themeUrl . '/img/warning-icon.svg' ?>" alt="icone aviso">
                                    <img id="errorNameIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <?php echo $form->error($modelStudentIdentification, 'name'); ?>
                            </div>
                            <!-- Nome social -->
                            <!-- <label class="checkbox show-student-civil-name-box">
                                   Esse é um nome social?
                                   <input type="checkbox" id="show-student-civil-name" <?php if ($modelStudentIdentification->civil_name != null) echo "checked"; ?>>
                            </label> -->
                            <!-- <div class="t-field-text student-civil-name" style="display: none;"> -->
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentIdentification, 'birthday', array('class' => 'control-label  t-field-text__label--required')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'birthday', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'birthday'); ?>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'sex', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'sex', array(null => "Selecione o sexo", "1" => "Masculino", "2" => "Feminino"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'sex'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'color_race', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                echo $form->DropDownList($modelStudentIdentification, 'color_race', array(
                                    null => "Selecione a cor/raça",
                                    "0" => "Não declarada",
                                    "1" => "Branca",
                                    "2" => "Preta",
                                    "3" => "Parda",
                                    "4" => "Amarela",
                                    "5" => "Indígena"
                                ), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'color_race'); ?>
                            </div>
                            <!-- Aqui -->
                            <!-- <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation', array('class' => 'control-label  t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'filiation', array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
                            </div> -->
                            <div class=" t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'id_email', array('class' => 'control-label  t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'id_email', array('size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Email')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'id_email'); ?>
                            </div>
                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'scholarity', array(null => "Selecione a escolaridade", "1" => "Formação Geral", "2" => "Modalidade Normal (Magistério)", "3" => "Curso Técnico", "4" => "Magistério Indígena Modalidade Normal"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'scholarity'); ?>
                            </div>
                            <!-- <div class="t-field-text js-hide-not-required js-visibility-fname">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o Nome Completo da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1'); ?>
                            </div> -->
                            <!-- <div class="t-field-text  js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o RG da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_rg'); ?>
                            </div> -->
                            <!-- <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_cpf', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_cpf'); ?>
                            </div> -->
                            <!-- <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'filiation_1_scholarity', array(
                                    null => "Selecione a escolaridade da mãe",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_scholarity'); ?>
                            </div> -->

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_job', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_job', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite a Profissão da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_job'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required js-visibility-fname">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o Nome Completo do Pai')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o RG do Pai')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_rg'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_cpf', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_cpf'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'filiation_2_scholarity', array(
                                    null => "Selecione a escolaridade do pai",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_scholarity'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_job', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_job', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite a Profissão do Pai')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_job'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'nationality', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'nationality', array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'), array('ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('student/getnations'),
                                    'update' => '#StudentIdentification_edcenso_nation_fk'
                                )));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'nationality'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_nation_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma nação", 'class' => 'select-search-on nationality-sensitive no-br t-input__text t-field-select__input', 'style' => 'width: 100%', 'disabled' => 'disabled'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'edcenso_nation_fk'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required js-change-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_uf_fk', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('student/getcities', array('rt' => 0)),
                                        'update' => '#StudentIdentification_edcenso_city_fk'
                                    ),
                                    "prompt" => "Selecione um estado",
                                    "class" => "select-search-on nationality-sensitive br t-field-select__input",
                                    "style" => "width: 100%",
                                    "disabled" => "disabled",
                                ));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'edcenso_uf_fk'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required js-change-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_city_fk', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                    "prompt" => "Selecione uma cidade",
                                    "disabled" => "disabled",
                                    'class' => 'select-search-on nationality-sensitive br t-field-select__input',
                                    'style' => 'width: 100%',
                                ));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'edcenso_city_fk'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentIdentification, 'civil_name', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'civil_name', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome Civil')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'civil_name'); ?>
                            </div>
                            <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'nis', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'nis', array('size' => 11, 'maxlength' => 11, 'placeholder' => 'Digite o NIS')); ?>
                                <span id="nisMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="errorNisIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <span id="nisMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="errorNisIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'nis'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'inep_id', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'inep_id', array('size' => 60, 'maxlength' => 12, 'class' => 't-field-text__input', 'placeholder' => 'Digite o ID INEP')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'inep_id'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required" style="width: 120%;">
                                <?php echo $form->labelEx($modelStudentIdentification, 'gov_id', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'gov_id', array('size' => 60, 'maxlength' => 12, 'class' => 't-field-text__input', 'placeholder' => 'Não possui', 'disabled' => 'disabled', 'style' => 'width:82.95%;')); ?>
                                <button type="button" id="copy-gov-id" style="background: none; border:none;"><span class="t-icon-copy"></span></button>
                                <span id="copy-message" style="display:none;"></span>
                                <?php echo $form->error($modelStudentIdentification, 'gov_id'); ?>
                            </div>
                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'responsable', array(null => "Selecione o responsável", 0 => 'Pai', 1 => 'Mãe', 2 => 'Outro',), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_telephone', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_telephone', array('size' => 60, 'maxlength' => 15, 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_telephone'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required" style="<?php echo (isset($modelStudentIdentification->responsable_name)) ? '' : 'display:none'; ?>" id="responsable_name">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_name', array('class' => 'control-label  t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_name', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Responsável')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_name'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_rg', array('size' => 60, 'maxlength' => 45, 'class' => 't-field-text__input', 'placeholder' => 'Digite o RG do Responsável')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_rg'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_cpf', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_cpf', array('size' => 60, 'maxlength' => 14)); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_cpf'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'responsable_scholarity', array(
                                    null => "Selecione a escolaridade",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_scholarity'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_job', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_job', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite a Profissão do Responsável')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_job'); ?>
                            </div>

                            <div class="t-field-checkbox js-hide-not-required">
                                <?php echo $form->checkBox($modelStudentIdentification, 'bf_participator', array('class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->labelEx($modelStudentIdentification, 'bf_participator', array('class' => 'control-label t-field-checkbox__label')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'bf_participator'); ?>
                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentIdentification, 'send_year', array('value' => date('Y') + 1, 'uncheckValue' => (date('Y')), 'class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'send_year'); ?>
                                <?php echo $form->labelEx($modelStudentIdentification, 'send_year', array('class' => 'control-label t-field-checkbox__label--required')); ?>

                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentIdentification, 'deficiency', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->labelEx($modelStudentIdentification, 'deficiency', array('class' => 'control-label t-field-checkbox__label--required')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'deficiency'); ?>
                            </div>

                            <div class="deficiencies-container js-change-required js-visibility-deficiencies">
                                <label class="control-label"><?php echo Yii::t('default', 'Deficiency Type'); ?>
                                    *</label>
                                <div id="StudentIdentification_deficiencies t-field-checkbox-group t-field-checkbox">
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_blindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_multiple_disabilities']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_multiple_disabilities', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_autism']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_autism', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_gifted']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                            <div class=" js-visibility-dresource hide-responsive resources-container">
                                <label class="control-label"><?php echo Yii::t('default', 'Required Resources'); ?></label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_lector']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_lector', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_transcription']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_transcription', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_guide']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_guide', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_libras']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_lip_reading']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_lip_reading', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_18']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_18', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_24']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_24', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_braille_test']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_braille_test', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_proof_language']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_proof_language', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_cd_audio']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_cd_audio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_video_libras']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_video_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_none']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Tab Student Affiliation -->
                <div class="tab-pane" id="student-affiliation">
                    <div>
                        <h3>
                            Filiação 1
                        </h3>
                    </div>
                    <div class="row">
                        <div class="column">

                            <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>

                            <!-- Filiação -->
                            <!-- <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation', array('class' => 'control-label  t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'filiation', array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
                            </div> -->
                            <div class="t-field-text js-hide-not-required js-visibility-fname">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o Nome Completo da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1'); ?>
                            </div>
                            <div class="t-field-text  js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o RG da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_rg'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_job', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_job', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite a Profissão do Responsável')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_job'); ?>
                            </div>
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentIdentification, 'birthday', array('class' => 'control-label  t-field-text__label--required')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'birthday', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'birthday'); ?>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'sex', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'sex', array(null => "Selecione o sexo", "1" => "Masculino", "2" => "Feminino"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'sex'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'color_race', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                echo $form->DropDownList($modelStudentIdentification, 'color_race', array(
                                    null => "Selecione a cor/raça",
                                    "0" => "Não declarada",
                                    "1" => "Branca",
                                    "2" => "Preta",
                                    "3" => "Parda",
                                    "4" => "Amarela",
                                    "5" => "Indígena"
                                ), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'color_race'); ?>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation', array('class' => 'control-label  t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'filiation', array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
                            </div>

                            <div class=" t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'id_email', array('class' => 'control-label  t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'id_email', array('size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Email')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'id_email'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'scholarity', array(null => "Selecione a escolaridade", "1" => "Formação Geral", "2" => "Modalidade Normal (Magistério)", "3" => "Curso Técnico", "4" => "Magistério Indígena Modalidade Normal"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'scholarity'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required js-visibility-fname">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o Nome Completo da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1'); ?>
                            </div>

                            <div class="t-field-text  js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o RG da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_rg'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_cpf', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_cpf'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'filiation_1_scholarity', array(
                                    null => "Selecione a escolaridade da mãe",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_scholarity'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_job', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_job', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite a Profissão da Mãe')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_job'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required js-visibility-fname">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o Nome Completo do Pai')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o RG do Pai')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_rg'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_cpf', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_cpf'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'filiation_2_scholarity', array(
                                    null => "Selecione a escolaridade do pai",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_scholarity'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_job', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_job', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite a Profissão do Pai')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_2_job'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'nationality', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'nationality', array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'), array('ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('student/getnations'),
                                    'update' => '#StudentIdentification_edcenso_nation_fk'
                                )));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'nationality'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_nation_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma nação", 'class' => 'select-search-on nationality-sensitive no-br t-input__text t-field-select__input', 'style' => 'width: 100%', 'disabled' => 'disabled'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'edcenso_nation_fk'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required js-change-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_uf_fk', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('student/getcities', array('rt' => 0)),
                                        'update' => '#StudentIdentification_edcenso_city_fk'
                                    ),
                                    "prompt" => "Selecione um estado",
                                    "class" => "select-search-on nationality-sensitive br t-field-select__input",
                                    "style" => "width: 100%",
                                    "disabled" => "disabled",
                                ));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'edcenso_uf_fk'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required js-change-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_city_fk', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                    "prompt" => "Selecione uma cidade",
                                    "disabled" => "disabled",
                                    'class' => 'select-search-on nationality-sensitive br t-field-select__input',
                                    'style' => 'width: 100%',
                                ));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'edcenso_city_fk'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_cpf', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")); ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_cpf'); ?>
                            </div>
                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'filiation_1_scholarity', array(
                                    null => "Selecione a escolaridade da mãe",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'filiation_1_scholarity'); ?>
                            </div>
                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'responsable', array(null => "Selecione o responsável", 0 => 'Pai', 1 => 'Mãe', 2 => 'Outro',), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable'); ?>
                            </div>
                            <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'nis', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'nis', array('size' => 11, 'maxlength' => 11, 'placeholder' => 'Digite o NIS')); ?>
                                <span id="nisMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="errorNisIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <span id="nisMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="errorNisIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'nis'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'inep_id', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'inep_id', array('size' => 60, 'maxlength' => 12, 'class' => 't-field-text__input', 'placeholder' => 'Digite o ID INEP')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'inep_id'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required" style="width: 120%;">
                                <?php echo $form->labelEx($modelStudentIdentification, 'gov_id', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'gov_id', array('size' => 60, 'maxlength' => 12, 'class' => 't-field-text__input', 'placeholder' => 'Não possui', 'disabled' => 'disabled', 'style' => 'width:82.95%;')); ?>
                                <button type="button" id="copy-gov-id" style="background: none; border:none;"><span class="t-icon-copy"></span></button>
                                <span id="copy-message" style="display:none;"></span>
                                <?php echo $form->error($modelStudentIdentification, 'gov_id'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_telephone', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_telephone', array('size' => 60, 'maxlength' => 15, 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_telephone'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required" style="<?php echo (isset($modelStudentIdentification->responsable_name)) ? '' : 'display:none'; ?>" id="responsable_name">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_name', array('class' => 'control-label  t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_name', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Responsável')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_name'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_rg', array('class' => 'control-label t-field-text__label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_rg', array('size' => 60, 'maxlength' => 45, 'class' => 't-field-text__input', 'placeholder' => 'Digite o RG do Responsável')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_rg'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_cpf', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelStudentIdentification, 'responsable_cpf', array('size' => 60, 'maxlength' => 14)); ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_cpf'); ?>
                            </div>
                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentIdentification, 'responsable_scholarity', array(
                                    null => "Selecione a escolaridade",
                                    0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                    3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                    6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                ), array('class' => 'select-search-off t-field-select__input', 'style' => 'width: 100%'));
                                ?>
                                <?php echo $form->error($modelStudentIdentification, 'responsable_scholarity'); ?>
                            </div>

                            <div class="t-field-checkbox js-hide-not-required">
                                <?php echo $form->checkBox($modelStudentIdentification, 'bf_participator', array('class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->labelEx($modelStudentIdentification, 'bf_participator', array('class' => 'control-label t-field-checkbox__label')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'bf_participator'); ?>
                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentIdentification, 'send_year', array('value' => date('Y') + 1, 'uncheckValue' => (date('Y')), 'class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'send_year'); ?>
                                <?php echo $form->labelEx($modelStudentIdentification, 'send_year', array('class' => 'control-label t-field-checkbox__label--required')); ?>

                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentIdentification, 'deficiency', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->labelEx($modelStudentIdentification, 'deficiency', array('class' => 'control-label t-field-checkbox__label--required')); ?>
                                <?php echo $form->error($modelStudentIdentification, 'deficiency'); ?>
                            </div>

                            <div class="deficiencies-container js-change-required js-visibility-deficiencies">
                                <label class="control-label"><?php echo Yii::t('default', 'Deficiency Type'); ?>
                                    *</label>
                                <div id="StudentIdentification_deficiencies t-field-checkbox-group t-field-checkbox">
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_blindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_multiple_disabilities']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_multiple_disabilities', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_autism']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_autism', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_gifted']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                            <div class=" js-visibility-dresource hide-responsive resources-container">
                                <label class="control-label"><?php echo Yii::t('default', 'Required Resources'); ?></label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_lector']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_lector', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_transcription']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_transcription', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_guide']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_guide', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_libras']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_lip_reading']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_lip_reading', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_18']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_18', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_24']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_24', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_braille_test']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_braille_test', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <!-- problema aqui -->
                                <!-- problema aqui -->
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_proof_language']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_proof_language', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_cd_audio']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_cd_audio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_video_libras']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_video_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo StudentIdentification::model()->attributeLabels()['resource_none']; ?>
                                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Tab Student Documents -->
                <div class="tab-pane" id="student-documents">
                    <h3 class="row">Documentos Entregues </h3>
                    <div class="row t-field-checkbox-group" id="received">
                        <div class="column">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_cc', array('value' => 1, 'class' => 't-field-checkbox__input', 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_cc)); ?>
                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_cc']; ?>
                            </label>
                            <label class="t-field-checkbox ">
                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_address', array('value' => 1, 'class' => 't-field-checkbox__input',  'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_address)); ?>
                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_address']; ?>
                            </label>
                        </div>
                        <div class="column">
                            <label class="t-field-checkbox ">
                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_photo', array('value' => 1, 'class' => 't-field-checkbox__input',  'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_photo)); ?>
                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_photo']; ?>
                            </label>
                            <label class="t-field-checkbox ">
                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_nis', array('value' => 1, 'class' => 't-field-checkbox__input', 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_nis)); ?>
                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_nis']; ?>
                            </label>
                        </div>
                        <div class="column">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_responsable_rg', array('value' => 1, 'class' => 't-field-checkbox__input', 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_responsable_rg)); ?>
                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_rg']; ?>
                            </label>
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_responsable_cpf', array('value' => 1, 'class' => 't-field-checkbox__input', 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_responsable_cpf)); ?>
                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_cpf']; ?>
                            </label>
                        </div>
                        <?php if (INSTANCE == "CLOC") : ?>
                            <div class="column">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'consent_form', array('value' => 1, 'class' => 't-field-checkbox__input', 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->consent_form)); ?>
                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['consent_form']; ?>
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="column">
                            <h3>
                                Certidão Civil
                            </h3>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification', array('class' => 't-field-select__label control-label')); ?>
                                <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'civil_certification', array(null => "Selecione o modelo", "1" => "Modelo Antigo", "2" => "Modelo Novo"), array("class" => "select-search-off t-field-select__input nationality-sensitive br", "disabled" => "disabled", "style" => "width:100%")); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                            </div>
                            <div class="js-hidden-oldDocuments-fields">
                                <div class="t-field-select">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_type', array('class' => 't-field-select__label control-label')); ?>
                                    <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'civil_certification_type', array(null => "Selecione o tipo", "1" => "Nascimento", "2" => "Casamento"), array("class" => "select-search-off t-field-select__input nationality-sensitive br", "disabled" => "disabled",  "style" => "width:100%")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('class' => 't-field-text__label control-label')); ?>
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('size' => 8, 'maxlength' => 8, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite o Nº do Termo')); ?>
                                    <span id="termMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <img id="errorTermIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                    </span>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('class' => 't-field-text__label control-label')); ?>
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('size' => 4, 'maxlength' => 4, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite a Folha')); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_book', array('class' => 't-field-text__label control-label')); ?>
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_book', array('size' => 8, 'maxlength' => 8, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite o Livro')); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_date', array('class' => 't-field-text__label control-label')); ?>
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_date', array('size' => 10, 'maxlength' => 10, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite a Data de Emissão da Certidão (Dia/Mês/Ano)')); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
                                </div>
                                <div class="t-field-select">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', array('class' => 't-field-select__label control-label')); ?>
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities', array('rt' => 1)),
                                            'update' => '#StudentDocumentsAndAddress_notary_office_city_fk'
                                        ),
                                        "prompt" => "Selecione um estado",
                                        "class" => "select-search-on t-field-select__input nationality-sensitive br",
                                        "disabled" => "disabled",
                                        "style" => "width:100%"
                                    ));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                                </div>
                                <div class="t-field-select">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_city_fk', array('class' => 't-field-select__label control-label')); ?>
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->notary_office_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getnotaryoffice'),
                                            'update' => '#StudentDocumentsAndAddress_edcenso_notary_office_fk'
                                        ),
                                        "prompt" => "Selecione uma cidade",
                                        "class" => "select-search-on t-field-select__input nationality-sensitive br",
                                        "disabled" => "disabled",
                                        "style" => "width:100%"
                                    ));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                                </div>
                                <div class="t-field-select">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', array('class' => 't-field-select__label control-label')); ?>
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', CHtml::listData(EdcensoNotaryOffice::model()->findAllByAttributes(array('city' => $modelStudentDocumentsAndAddress->notary_office_city_fk), array('order' => 'name')), 'cod', 'name') + array('7177' => 'OUTROS'), array(
                                        "prompt" => "Selecione um cartório",
                                        "class" => "select-search-on t-field-select__input nationality-sensitive br", "disabled" => "disabled",
                                        "style" => "width:100%"
                                    ));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                                </div>
                            </div>
                            <div class="t-field-text js-hidden-newDocument-field">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array("disabled" => "disabled", "class" => "nationality-sensitive br t-field-text__input")); ?>
                                <span id="registerMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="registerIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
                            </div>
                            <h3>
                                Justificativa da falta de documentação
                            </h3>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentIdentification, 'no_document_desc', array('class' => 't-field-select__label control-label')); ?>
                                <?php echo $form->DropDownList($modelStudentIdentification, 'no_document_desc', array(null => "Selecione a justificativa", "1" => "o(a) aluno(a) não possui os documentos pessoais solicitados", "2" => "A escola não dispõe ou não recebeu os documentos pessoais do(a) aluno(a)"), array("class" => "select-search-off t-field-select__input nationality-sensitive br", "disabled" => "disabled", "style" => "width:100%")); ?>
                                <?php echo $form->error($modelStudentIdentification, 'no_document_desc'); ?>
                            </div>
                        </div>
                        <div class="column">


                            <h3>
                                Cartão Nacional de Saúde
                            </h3>
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cns', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cns', array('size' => 11, 'maxlength' => 15, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite o Nº do CNS')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'cns'); ?>
                            </div>



                            <h3>
                                Cadastro de Pessoa Física
                            </h3>
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cpf', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 14, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br")); ?>
                                <span id="cpfMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                                    <img id="errorCPFIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>" alt="icone erro">
                                </span>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'cpf'); ?>
                            </div>


                            <h3>
                                Registro Geral
                            </h3>

                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number', array('size' => 20, 'maxlength' => 20, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite o Nº da Identidade')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', array('class' => 't-field-select__label control-label')); ?>
                                <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', CHtml::listData(EdcensoOrganIdEmitter::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um órgão emissor da identidade", "class" => "select-search-on t-field-select__input nationality-sensitive br", "disabled" => "disabled", "style" => "width:100%"));
                                ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', array('class' => 't-field-select__label control-label')); ?>
                                <?php echo $form->dropDownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um estado", "class" => "select-search-on t-field-select__input nationality-sensitive br", "disabled" => "disabled", "style" => "width:100%"));
                                ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                            </div>

                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('size' => 10, 'maxlength' => 10, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br", 'placeholder' => 'Digite a Data de Expedição da Identidade')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
                            </div>


                            <h3>
                                Justiça
                            </h3>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'justice_restriction', array('class' => 't-field-select__label control-label')); ?>
                                <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'justice_restriction', array(null => "Selecione", "0" => "Não possui restrições", "1" => "LA - Liberdade Assistida", "2" => "PSC - Prestação de Serviços Comunitários"), array('class' => 'select-search-off t-field-select__input', "style" => "width:100%")); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'justice_restriction'); ?>
                            </div>

                            <h3>
                                Passaporte
                            </h3>
                            <div class="t-field-text">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('size' => 20, 'maxlength' => 20, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive n-br", 'placeholder' => 'Digite o Passaporte ou Documento Estrangeiro')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Student Address -->
                <div class="tab-pane" id="student-address">
                    <div class="row">
                        <div class="column">
                            <div class="t-field-select">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'residence_zone', array('class' => 'control-label  t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'residence_zone', array(null => "Selecione uma zona", "1" => "URBANA", "2" => "RURAL"), array('class' => 'select-search-off t-field-select__input', 'style' => 'width:100%')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                            </div>
                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cep', array('class' => 'control-label t-field-text__label')); ?>
                                <?php
                                echo $form->textField($modelStudentDocumentsAndAddress, 'cep', array(
                                    'size' => 8,
                                    'maxlength' => 9,
                                    'class' => 't-field-text_input'
                                ));
                                ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'cep'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'address', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'address', array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Digite o Endereço', 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'address'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'number', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'number', array('size' => 10, 'maxlength' => 10, 'placeholder' => 'Digite o Número', 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'number'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'complement', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'complement', array('size' => 20, 'maxlength' => 20, 'placeholder' => 'Digite o Complemento', 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'complement'); ?>
                            </div>

                            <div class="t-field-text js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'neighborhood', array('class' => 't-field-text__label control-label')); ?>
                                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'neighborhood', array('size' => 50, 'maxlength' => 50, 'placeholder' => 'Digite o Bairro ou Povoado', 'class' => 't-field-text__input')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', array('class' => 't-field-select__label control-label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('student/getcities', array('rt' => 2)),
                                        'update' => '#StudentDocumentsAndAddress_edcenso_city_fk'
                                    ),
                                    "prompt" => "Selecione um estado",
                                    "class" => "select-search-on t-field-select__input",
                                    'style' => 'width:100%'
                                ));
                                ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                            </div>

                            <div class="t-field-select js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_city_fk', array('class' => 't-field-select__label control-label')); ?>
                                <?php
                                echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma cidade", "class" => "select-search-on t-field-select__input", 'style' => 'width:100%'));
                                ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
                            </div>

                            <div class="t-field-select  js-hide-not-required">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'diff_location', array('class' => 't-field-select__label control-label')); ?>
                                <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'diff_location', array(null => 'Selecione a localização', 7 => 'Não reside em área de localização diferenciada', 3 => 'Área onde se localiza comunidade remanescente de quilombos', 2 => 'Terra indígena', 1 => 'Área de assentamento'), array("class" => "select-search-on t-field-select__input", 'style' => 'width:100%')); ?>
                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'diff_location'); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="student-health">
                    <div class="row-fluid" style="padding: 0 0 0px 0;">
                        <div class="span12">
                            <div class="widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div class="widget-body in" style="height: auto;">
                                    <div class="control-group row" id="received" style="margin-left:34px;">
                                        <div class="column">
                                            <h3><?php echo Yii::t('default', 'Restrictions'); ?>

                                            </h3>
                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['celiac']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'celiac', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['diabetes']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'diabetes', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['hypertension']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'hypertension', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['iron_deficiency_anemia']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'iron_deficiency_anemia', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['sickle_cell_anemia']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'sickle_cell_anemia', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['lactose_intolerance']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'lactose_intolerance', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['malnutrition']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'malnutrition', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>


                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['obesity']; ?>
                                                <?php echo $form->checkBox($modelStudentRestrictions, 'obesity', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>

                                            <label class="checkbox">
                                                <?php echo StudentRestrictions::model()->attributeLabels()['others']; ?>
                                                <?php echo $modelStudentRestrictions->others != null ?
                                                    "<input type='checkbox' id='others-check' checked>" :
                                                    "<input type='checkbox' id='others-check'>" ?>
                                            </label>

                                            <div class="row others-text-box" style="display: none;">
                                                <?php echo $form->textArea($modelStudentRestrictions, 'others', array('rows' => 6, 'cols' => 50)); ?>
                                            </div>
                                        </div>


                                        <div class="column">
                                            <h3><?php echo Yii::t('default', 'Vaccine'); ?>
                                            </h3>
                                            <div class="vaccines-container">
                                                <?php foreach ($vaccines as $vaccine) : ?>
                                                    <label class="checkbox">
                                                        <?= $vaccine->name; ?>
                                                        <?php echo CHtml::activeCheckBox($vaccine, "vaccine_id[]", array('checked' => in_array($vaccine->id, $studentVaccinesSaves), 'value' => $vaccine->id, 'uncheckValue' => null, 'class' => 'vaccine-checkbox', 'code' => $vaccine->code)); ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
                <!-- Tab Student Enrollment -->
                <div class="tab-pane" id="student-enrollment">
                    <div class="row">
                        <a href="#" class="t-button-primary  " id="new-enrollment-button">Adicionar Matrícula</a>
                        <?php
                        echo  $modelStudentIdentification->isNewRecord ?  "" : '<a href=' . @Yii::app()->createUrl('student/transfer', array('id' => $modelStudentIdentification->id)) . ' class="t-button-primary" id="transfer-student">Transferir Matrícula</a>'
                        ?>
                    </div>
                    <div class="row" id="new-enrollment-form" style="display: none;">
                        <div class="column">
                            <?php echo $form->hiddenField($modelEnrollment, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>

                            <!-- turma -->
                            <div class="t-field-select t-input">
                                <?php echo $form->labelEx($modelEnrollment, 'classroom_fk', array('class' => 'control-label  t-input__label--required')); ?>
                                <?php
                                $stage = $modelStudentIdentification->getCurrentStageVsModality();
                                $stages = implode(",", EdcensoStageVsModality::getNextStages($stage));
                                $classrooms = Classroom::model()->findAll(
                                    "school_year = :year AND school_inep_fk = :school order by name",
                                    [
                                        ':year' => Yii::app()->user->year,
                                        ':school' => Yii::app()->user->school,
                                    ]
                                );
                                echo $form->dropDownList(
                                    $modelEnrollment,
                                    'classroom_fk',
                                    CHtml::listData(
                                        $classrooms,
                                        'id',
                                        'name'
                                    ),
                                    array("prompt" => "Selecione uma Turma", 'class' => 'select-search-off control-input  t-input__text')
                                ); ?>
                                <?php echo $form->error($modelEnrollment, 'classroom_fk'); ?>
                            </div>
                            <!-- turma unificada -->
                            <div id="multiclass">
                                <div class="t-field-select js-hide-not-required">
                                    <?php echo $form->labelEx($modelEnrollment, 'unified_class', array('class' => 'control-label')); ?>
                                    <?php echo $form->DropDownList($modelEnrollment, 'unified_class', array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA"), array('class' => 'select-search-off control-input')); ?>
                                    <?php echo $form->error($modelEnrollment, 'unified_class'); ?>
                                </div>
                                <div class="t-field-select js-hide-not-required">
                                    <?php echo CHtml::label("Etapa", 'Stage', array('class' => 'control-label')); ?>
                                    <?php
                                    echo CHtml::dropDownList("Stage", null, array(
                                        "0" => "Selecione a Modalidade",
                                        "1" => "Infantil",
                                        "2" => "Fundamental Menor",
                                        "3" => "Fundamental Maior",
                                        "4" => "Médio",
                                        "5" => "Profissional",
                                        "6" => "EJA",
                                        "7" => "Outros",
                                    ), array(
                                        'class' => 'select-search-off control-input',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('enrollment/getmodalities'),
                                            'success' => 'function(data){
                                                $("#StudentEnrollment_edcenso_stage_vs_modality_fk").html(decodeHtml(data));
                                            }'
                                        ),
                                    ));
                                    ?>
                                </div>
                                <div class="control-group js-hide-not-required">
                                    <?php echo $form->labelEx($modelEnrollment, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                    <?php echo $form->dropDownList($modelEnrollment, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array("prompt" => "Selecione a etapa", 'class' => 'select-search-on control-input')); ?>
                                    <?php echo $form->error($modelEnrollment, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group js-hide-not-required">
                                <?php echo $form->labelEx($modelEnrollment, 'admission_type', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelEnrollment, 'admission_type', array("1" => "Rematrícula", "2" => "Transferência interna", "3" => "Transferência externa"), array("prompt" => "Selecione", 'class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelEnrollment, 'admission_type'); ?>
                            </div>
                            <div class="control-group js-hide-not-required">
                                <?php echo $form->labelEx($modelEnrollment, 'status', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelEnrollment, 'status', array("1" => "Matriculado", "2" => "Transferido", "3" => "Cancelado", "4" => "Evadido"), array('options' => array('1' => array('selected' => true)), "prompt" => "Selecione", 'class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelEnrollment, 'status'); ?>
                            </div>
                            <div class="control-group js-hide-not-required">
                                <?php echo $form->labelEx($modelEnrollment, 'another_scholarization_place', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelEnrollment, 'another_scholarization_place', array("1" => "Não recebe", "2" => "Em hospital", "3" => "Em domicílio"), array('class' => 'select-search-on control-input')); ?>
                                <?php echo $form->error($modelEnrollment, 'another_scholarization_place'); ?>
                            </div>
                            <div class="control-group js-hide-not-required">
                                <?php echo $form->labelEx($modelEnrollment, 'current_stage_situation', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList(
                                    $modelEnrollment,
                                    'current_stage_situation',
                                    array(
                                        null => "Selecione",
                                        "0" => "Primeira matrícula no curso",
                                        "1" => "Promovido na série anterior do mesmo curso",
                                        "2" => "Repetente"
                                    ),
                                    array('class' => 'select-search-off control-input')
                                ); ?>
                                <?php echo $form->error($modelEnrollment, 'current_stage_situation'); ?>
                            </div>
                            <div class="control-group js-hide-not-required">
                                <?php echo $form->labelEx($modelEnrollment, 'previous_stage_situation', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList(
                                    $modelEnrollment,
                                    'previous_stage_situation',
                                    array(
                                        null => "Selecione",
                                        "0" => "Não frequentou",
                                        "1" => "Reprovado",
                                        "2" => "Afastado por transferência",
                                        "3" => "Afastado por abandono",
                                        "4" => "Matrícula final em Educação Infantil",
                                        "5" => "Promovido"
                                    ),
                                    array('class' => 'select-search-off control-input')
                                ); ?>
                                <?php echo $form->error($modelEnrollment, 'previous_stage_situation'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="separator"></div>
                            <div class="control-group js-hide-not-required">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'school_admission_date', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelEnrollment, 'school_admission_date', array('size' => 10, 'maxlength' => 10)); ?>
                                    <?php echo $form->error($modelEnrollment, 'school_admission_date'); ?>
                                </div>
                            </div>
                            <div class="t-field-checkbox js-hide-not-required">
                                <?php echo $form->checkBox($modelEnrollment, 'public_transport', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                                <?php echo $form->labelEx($modelEnrollment, 'public_transport', array('class' => 'control-label t-field-checkbox__label--required')); ?>
                            </div>
                            <div class="t-field-select t-input" id="transport_responsable">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'transport_responsable_government', array('class' => 'control-label t-input__label--required')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelEnrollment, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal"), array('class' => 'select-search-off control-input')); ?>
                                    <?php echo $form->error($modelEnrollment, 'transport_responsable_government'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive" id="transport_type">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Transport Type'); ?>
                                        *</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_van']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_van', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_microbus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bike', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_animal_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_other_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5_15', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_15_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group js-hide-not-required" id="">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Type of Specialized Educational Assistance'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox inline-block">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_cognitive_functions']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_cognitive_functions', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_autonomous_life']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_autonomous_life', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_curriculum_enrichment']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_curriculum_enrichment', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_accessible_teaching']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_accessible_teaching', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_libras']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_portuguese']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_portuguese', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_soroban']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_soroban', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_braille']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_braille', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_mobility_techniques']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_mobility_techniques', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_caa']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_caa', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_optical_nonoptical']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_optical_nonoptical', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group js-hide-not-required">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'observation', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textArea($modelEnrollment, 'observation', array('rows' => 6, 'cols' => 50)); ?>
                                    <?php echo $form->error($modelEnrollment, 'observation'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <?php
                        $error = $modelEnrollment->getErrors('enrollment_id');
                        if (count($error) > 0) {

                        ?>
                            <div class="alert alert-error">
                                <?php echo $error[0]; ?>
                            </div>
                        <?php } ?>
                        <div id="enrollment" class="widget widget-scroll margin-bottom-none table-responsive">
                            <h3>
                                <?php echo yii::t("default", "Enrollments"); ?>
                            </h3>
                            <table class="tag-table table-bordered table-striped" aria-describedby="tabela de matriculas">
                                <thead>
                                    <tr>
                                        <th style="text-align: center !important;">Escola</th>
                                        <th style="text-align: center">Atualizar Ficha de Matrícula</th>
                                        <th style="text-align: center">situação</th>
                                        <th style="text-align: center">Ano</th>
                                        <th style="text-align: center">Formulários</th>
                                        <th style="text-align: center; width: 15%;">Cancelar Matrícula</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelStudentIdentification->studentEnrollments as $me) {
                                    ?>
                                        <tr>
                                            <td><?php echo $me->schoolInepIdFk->name ?></td>
                                            <td style="text-align: center">
                                                <?php if ($me->classroomFk->school_year >=  Yii::app()->user->year) {  ?>
                                                    <a href='<?php echo @Yii::app()->createUrl('enrollment/update', array('id' => $me->id)); ?>'>
                                                        <i class="fa fa-pencil" style="color:#3F45EA; padding-right: 1%"></i>
                                                        <?php echo $me->classroomFk->name ?>

                                                    </a>
                                                <?php } else { ?>
                                                    <p title="Não é possível atualizar a Matrícula do ano anterior">
                                                        <?php echo $me->classroomFk->name ?><br />
                                                        (<?php echo @$me->edcensoStageVsModalityFk->name ?>)
                                                    </p>
                                                <?php } ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                switch ($me->status) {
                                                    case "1":
                                                        echo "Matriculado";
                                                        break;
                                                    case "2":
                                                        echo "Transferido";
                                                        break;
                                                    case "3":
                                                        echo "Cancelado";
                                                        break;
                                                    case "4":
                                                        echo "Evadido";
                                                        break;
                                                    default:
                                                        echo "";
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: center"><?php echo $me->classroomFk->school_year ?></td>
                                            <?php
                                            $type;
                                            if (@isset($me->classroomFk->edcensoStageVsModalityFk->stage)) {
                                                switch ($me->classroomFk->edcensoStageVsModalityFk->stage) {
                                                        // FALTA O CASO DE NECESSIDADES ESPECIAIS - ANALISAR COMO PODE SER TRATADO

                                                        //educação infantil
                                                    case 1:
                                                        $type = 0;
                                                        break;
                                                        //ensino fundamental
                                                    case 2:
                                                        $type = 1;
                                                        //ensino fundamental
                                                    case 3:
                                                        $type = 1;
                                                        break;
                                                        //ensino médio
                                                    case 4:
                                                        $type = 1;
                                                        break;
                                                        //educação profissional
                                                    case 5:
                                                        $type = 1;
                                                        break;
                                                        //educação de jovens e adultos
                                                    case 6:
                                                        $type = 3;
                                                        break;
                                                        //ensino fundamental OU multietapa
                                                    case 7:
                                                        $type = 1;
                                                        break;
                                                    case null:
                                                        $type = 1;
                                                        break;
                                                }
                                            }
                                            ?>
                                            <td>
                                                <ul>
                                                    <?php
                                                    $forms = unserialize(FORMS);
                                                    foreach ($forms as $form) {
                                                        $link = Yii::app()->createUrl('forms/' . $form['action'], array('type' => $type, 'enrollment_id' => $me->id));
                                                        echo "<li><a target='_blank' href=" . $link . ">" . $form['name'] . "</a></li>";
                                                    }
                                                    if ($me->classroomFk->school_year == date('Y')) {
                                                        $date = date('Y-m-d');
                                                        $quizs = Quiz::model()->findAll('status=1 AND init_date <=:init_date AND final_date >=:final_date', [':init_date' => $date, ':final_date' => $date]);
                                                        if (count($quizs) > 0) {
                                                            foreach ($quizs as $quiz) {
                                                                $link = Yii::app()->createUrl('quiz/default/answer', array('quizId' => $quiz->id, 'studentId' => $me->studentFk->id));
                                                                echo "<li><a target='_blank' href=" . $link . ">" . $quiz->name . "</a></li>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <li><a href='<?php echo @Yii::app()->createUrl('forms/EnrollmentGradesReport', array('enrollment_id' => $me->id)) ?>' target="_blank">Rendimento Escolar Por Atividades</a></li>
                                            </td>
                                            <td style="text-align: center">
                                                <?php if ($me->classroomFk->school_year >= date('Y') && $me->status == 1) { ?>
                                                    <a href='<?php echo @Yii::app()->createUrl('enrollment/delete', array('id' => $me->id)) ?>'><i class="fa fa-trash-o"></i></a>
                                                <?php } else if ($me->classroomFk->school_year >= date('Y') && $me->status == 2) { ?>
                                                    <i class="fa fa-minus" title="Não é possível cancelar a Matrícula transferida"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-minus" title="Não é possível cancelar a Matrícula do ano anterior"></i>
                                                <?php } ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <?php
                if (isset($_GET['censo']) && isset($_GET['id'])) {
                    $this->widget('application.widgets.AlertCensoWidget', array('prefix' => 'student', 'dataId' => $_GET['id']));
                }
                ?>

                <script type="text/javascript">
                    var formIdentification = '#StudentIdentification_';
                    var formDocumentsAndAddress = '#StudentDocumentsAndAddress_';
                    var formEnrollment = '#StudentEnrollment_';
                    var updateDependenciesURL = '<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>';
                    var filled = -1;
                </script>