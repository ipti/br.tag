<?php

/**
 * @var $this StudentController
 * @var $form CActiveForm
 * @var $cs CClientScript
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

$form = $this->beginWidget('CActiveForm', [
    'id' => 'student',
    'enableAjaxValidation' => false,
]);
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>
        <div class="tag-buttons-container buttons hide-responsive">
            <a data-toggle="tab" class='tag-button-light small-button prev' style="display: none;"><?php echo Yii::t('default', 'Previous') ?></a>
            <a data-toggle="tab" class='tag-button small-button next'><?php echo Yii::t('default', 'Next') ?>
            </a>
            <button class="tag-button small-button last  save-student" type="button">
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
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <?php
        echo $form->errorSummary($modelStudentIdentification);
        echo $form->errorSummary($modelStudentDocumentsAndAddress);
        ?>
        <div class="alert alert-error student-error no-show"></div>
        <div class="widget-head">
            <ul class="tab-student" style="display:none">
                <li id="tab-student-identify" class="active">
                    <a class="glyphicons vcard" href="#student-identify" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Identification') ?>
                    </a>
                </li>
                <li id="tab-student-documents">
                    <a class="glyphicons credit_card" href="#student-documents" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Documents') ?>
                    </a>
                </li>
                <li id="tab-student-address">
                    <a class="glyphicons home" href="#student-address" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Address') ?>
                    </a>
                </li>
                <li id="tab-student-enrollment">
                    <a class="glyphicons book_open" href="#student-enrollment" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Enrollment') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body form-horizontal">
            <div class="tab-content" style="display:none">
                <!-- Tab content Botão de próximo -->
                <div id="buttons-student" class="">
                    <a data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display: none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
                    <a data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default', 'Next') ?>
                        <i></i></a>
                    <?php echo CHtml::htmlButton('<i></i>' . ($modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), ['class' => 'pull-right btn btn-icon btn-primary last glyphicons circle_ok', 'style' => 'display:none', 'type' => 'submit']); ?>
                </div>
                <!-- Tab Student Identify -->
                <div class="tab-pane active" id="student-identify">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', ['value' => Yii::app()->user->school]); ?>
                                </div>
                            </div>
                            <!-- name student -->
                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'name', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'name', ['size' => 60, 'maxlength' => 100]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Student Full Name'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentIdentification, 'name'); ?>
                                </div>
                            </div>


                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'birthday', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'birthday', ['size' => 10, 'maxlength' => 10]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Birthday') . ' ' . Yii::t('help', 'Date'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentIdentification, 'birthday'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'sex', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentIdentification, 'sex', [null => 'Selecione o sexo', '1' => 'Masculino', '2' => 'Feminino'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'sex'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'color_race', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelStudentIdentification, 'color_race', [
                                        null => 'Selecione a cor/raça',
                                        '0' => 'Não declarada',
                                        '1' => 'Branca',
                                        '2' => 'Preta',
                                        '3' => 'Parda',
                                        '4' => 'Amarela',
                                        '5' => 'Indígena'
                                    ], ['class' => 'select-search-off control-input']);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'color_race'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentIdentification, 'filiation', [null => 'Selecione a filiação', '0' => 'Não declarado/Ignorado', '1' => 'Pai e/ou Mãe'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'id_email', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'id_email', ['size' => 60, 'maxlength' => 255]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'id_email'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'scholarity', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentIdentification, 'scholarity', [null => 'Selecione a escolaridade', '1' => 'Formação Geral', '2' => 'Modalidade Normal (Magistério)', '3' => 'Curso Técnico', '4' => 'Magistério Indígena Modalidade Normal'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'scholarity'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_1', ['size' => 60, 'maxlength' => 100, 'disabled' => 'disabled']); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Full name'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_1'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_rg', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_1_rg', ['size' => 60, 'maxlength' => 45]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_1_rg'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_cpf', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_1_cpf', ['size' => 60, 'maxlength' => 14]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_1_cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_scholarity', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'filiation_1_scholarity', [
                                        0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                        3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                        6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                    ], ['class' => 'select-search-off']);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_1_scholarity'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1_job', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_1_job', ['size' => 60, 'maxlength' => 100]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_1_job'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_2', ['size' => 60, 'maxlength' => 100, 'disabled' => 'disabled']); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Full name'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_2'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_rg', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_2_rg', ['size' => 60, 'maxlength' => 45]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_2_rg'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_cpf', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_2_cpf', ['size' => 60, 'maxlength' => 14]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_2_cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_scholarity', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'filiation_2_scholarity', [
                                        0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                        3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                        6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                    ], ['class' => 'select-search-off']);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_2_scholarity'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'filiation_2_job', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_2_job', ['size' => 60, 'maxlength' => 100]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation_2_job'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'nationality', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls required">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'nationality', [null => 'Selecione a nacionalidade', '1' => 'Brasileira', '2' => 'Brasileira: Nascido no exterior ou Naturalizado', '3' => 'Estrangeira'], ['class' => 'select-search-off control-input'], ['ajax' => [
                                        'type' => 'POST',
                                        'url' => CController::createUrl('student/getnations'),
                                        'update' => '#StudentIdentification_edcenso_nation_fk'
                                    ]]);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'nationality'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_nation_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione uma nação', 'class' => 'select-search-on nationality-sensitive no-br', 'disabled' => 'disabled']);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_nation_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_uf_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'), [
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities', ['rt' => 0]),
                                            'update' => '#StudentIdentification_edcenso_city_fk'
                                        ],
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on control-input nationality-sensitive br',
                                        'disabled' => 'disabled',
                                    ]);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_city_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(['edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk], ['order' => 'name']), 'id', 'name'), [
                                        'prompt' => 'Selecione uma cidade',
                                        'disabled' => 'disabled',
                                        'class' => 'select-search-on control-input nationality-sensitive br'
                                    ]);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>


                        </div>
                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'nis', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'nis', ['size' => 11, 'maxlength' => 11]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'NIS') . ' ' . Yii::t('help', 'Only Numbers'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'nis'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'inep_id', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'inep_id', ['size' => 60, 'maxlength' => 12]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'inep_id'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'responsable', [0 => 'Pai', 1 => 'Mãe', 2 => 'Outro', ], ['class' => 'select-search-off control-input']);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable_telephone', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_telephone', ['size' => 60, 'maxlength' => 15]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_telephone'); ?>
                                </div>
                            </div>

                            <div class="control-group" style="<?php echo (isset($modelStudentIdentification->responsable_name)) ? '' : 'display:none'; ?>" id="responsable_name">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable_name', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_name', ['size' => 60, 'maxlength' => 100]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_name'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable_rg', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_rg', ['size' => 60, 'maxlength' => 45]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_rg'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable_cpf', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_cpf', ['size' => 60, 'maxlength' => 14]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable_scholarity', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'responsable_scholarity', [
                                        0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                        3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                        6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                    ], ['class' => 'select-search-off control-input']);
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_scholarity'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'responsable_job', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_job', ['size' => 60, 'maxlength' => 100]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_job'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'bf_participator', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'bf_participator'); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'bf_participator'); ?>
                                </div>
                            </div>


                            <div class="control-group">
                                <div class="controls">

                                    <?php echo $form->labelEx($modelStudentIdentification, 'food_restrictions', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textArea($modelStudentIdentification, 'food_restrictions'); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'food_restrictions'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'send_year', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'send_year', ['value' => date('Y') + 1, 'uncheckValue' => (date('Y'))]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'send_year'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentIdentification, 'deficiency', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'deficiency', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'deficiency'); ?>
                                </div>
                            </div>

                            <div class="control-group deficiencies-container">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Deficiency Type'); ?>
                                        *</label>
                                </div>
                                <div class="controls" id="StudentIdentification_deficiencies">
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_blindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_blindness', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_low_vision', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafness', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_disability_hearing', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafblindness', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_phisical_disability', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_intelectual_disability', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_multiple_disabilities']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_multiple_disabilities', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_autism']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_autism', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_gifted']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_gifted', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group hide-responsive resources-container">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Required Resources'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_lector']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_lector', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_transcription']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_transcription', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_guide']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_guide', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_libras']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_libras', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_lip_reading']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_lip_reading', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_18']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_18', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_24']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_24', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_braille_test']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_braille_test', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <!-- problema aqui -->
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_proof_language']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_proof_language', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_cd_audio']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_cd_audio', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_video_libras']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_video_libras', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_none']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_none', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group hide-responsive" id="vaccine">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Vaccine'); ?></label>
                                </div>
                                <div class="controls vaccines-container">
                                    <?php foreach ($vaccines as $vaccine) : ?>
                                        <label class="checkbox">
                                            <?= $vaccine->name; ?>
                                            <?php echo CHtml::activeCheckBox($vaccine, 'vaccine_id[]', ['checked' => in_array($vaccine->id, $studentVaccinesSaves), 'value' => $vaccine->id, 'uncheckValue' => null, 'class' => 'vaccine-checkbox', 'code' => $vaccine->code]); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Student Documents -->
                <div class="tab-pane" id="student-documents">
                    <div class="row-fluid" style="padding: 0 0 0px 0;">
                        <div class="span12">
                            <div class="widget widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div class="widget-body in" style="height: auto;">
                                    <div>
                                        <h5 class="titulos">Documentos Entregues
                                            <!-- <i style="font-size: 0.8em;">(Marcar os documentos que foram entregues).</i> -->
                                        </h5>
                                    </div>
                                    <div class="control-group" id="received">
                                        <div class="controls">
                                            <div class="span3">
                                                <label class="checkbox">
                                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_cc']; ?>
                                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_cc', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->received_cc]); ?>
                                                </label>
                                                <label class="checkbox">
                                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_address']; ?>
                                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_address', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->received_address]); ?>
                                                </label>
                                            </div>
                                            <div class="span3">
                                                <label class="checkbox">
                                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_photo']; ?>
                                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_photo', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->received_photo]); ?>
                                                </label>
                                                <label class="checkbox">
                                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_nis']; ?>
                                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_nis', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->received_nis]); ?>
                                                </label>
                                            </div>
                                            <div class="span3">
                                                <label class="checkbox">
                                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_rg']; ?>
                                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_responsable_rg', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->received_responsable_rg]); ?>
                                                </label>
                                                <label class="checkbox">
                                                    <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_cpf']; ?>
                                                    <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_responsable_cpf', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->received_responsable_cpf]); ?>
                                                </label>
                                            </div>
                                            <?php if (INSTANCE == 'CLOC') : ?>
                                                <div class="span3">
                                                    <label class="checkbox">
                                                        <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['consent_form']; ?>
                                                        <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'consent_form', ['value' => 1, 'uncheckValue' => 0, 'checked' => ($modelStudentDocumentsAndAddress->id == '') ? 'checked' : $modelStudentDocumentsAndAddress->consent_form]); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="widget widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Certidão Civil
                                    </h5>
                                </div>
                                <div class="row-fluid">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'civil_certification', [null => 'Selecione o modelo', '1' => 'Modelo Antigo', '2' => 'Modelo Novo'], ['class' => 'select-search-off control-input nationality-sensitive br', 'disabled' => 'disabled']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_type', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'civil_certification_type', [null => 'Selecione o tipo', '1' => 'Nascimento', '2' => 'Casamento'], ['class' => 'select-search-off control-input nationality-sensitive br', 'disabled' => 'disabled']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_term_number', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_term_number', ['size' => 8, 'maxlength' => 8, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_sheet', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_sheet', ['size' => 4, 'maxlength' => 4, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_book', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_book', ['size' => 8, 'maxlength' => 8, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_date', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_date', ['size' => 10, 'maxlength' => 10, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <!-- <span
                                                    class="btn-action single glyphicons circle_question_mark"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="<?php echo Yii::t('help', 'Date'); ?>"><i></i></span> -->

                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'), [
                                                'ajax' => [
                                                    'type' => 'POST',
                                                    'url' => CController::createUrl('student/getcities', ['rt' => 1]),
                                                    'update' => '#StudentDocumentsAndAddress_notary_office_city_fk'
                                                ],
                                                'prompt' => 'Selecione um estado',
                                                'class' => 'select-search-on control-input nationality-sensitive br',
                                                'disabled' => 'disabled'
                                            ]);
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_city_fk', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(['edcenso_uf_fk' => $modelStudentDocumentsAndAddress->notary_office_uf_fk], ['order' => 'name']), 'id', 'name'), [
                                                'ajax' => [
                                                    'type' => 'POST',
                                                    'url' => CController::createUrl('student/getnotaryoffice'),
                                                    'update' => '#StudentDocumentsAndAddress_edcenso_notary_office_fk'
                                                ],
                                                'prompt' => 'Selecione uma cidade',
                                                'class' => 'select-search-on control-input nationality-sensitive br',
                                                'disabled' => 'disabled'
                                            ]);
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', CHtml::listData(EdcensoNotaryOffice::model()->findAllByAttributes(['city' => $modelStudentDocumentsAndAddress->notary_office_city_fk], ['order' => 'name']), 'cod', 'name') + ['7177' => 'OUTROS'], [
                                                'prompt' => 'Selecione um cartório',
                                                'class' => 'select-search-on control-input nationality-sensitive br', 'disabled' => 'disabled'
                                            ]);
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', ['disabled' => 'disabled', 'class' => 'nationality-sensitive br span6']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-scroll margin-bottom-none row-fluid" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Justificativa da falta de documentação
                                    </h5>
                                </div>
                                <div class="row-fluid" style="height: auto;">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentIdentification, 'no_document_desc', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($modelStudentIdentification, 'no_document_desc', [null => 'Selecione a justificativa', '1' => 'o(a) aluno(a) não possui os documentos pessoais solicitados', '2' => 'A escola não dispõe ou não recebeu os documentos pessoais do(a) aluno(a)'], ['class' => 'select-search-off control-input nationality-sensitive br', 'disabled' => 'disabled']); ?>
                                            <?php echo $form->error($modelStudentIdentification, 'no_document_desc'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="widget widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Cartão Nacional de Saúde
                                    </h5>
                                </div>
                                <div class="row-fluid" style="height: auto;">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cns', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cns', ['size' => 11, 'maxlength' => 15, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <!-- <span
                                                    class="btn-action single glyphicons circle_question_mark"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span> -->
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'cns'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Cadastro de Pessoa Física
                                    </h5>
                                </div>
                                <div class="row-fluid" style="height: auto;">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cpf', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', ['size' => 11, 'maxlength' => 14, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <!-- <span
                                                    class="btn-action single glyphicons circle_question_mark"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span> -->
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'cpf'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Registro Geral
                                    </h5>
                                </div>
                                <div class="row-fluid" style="height: auto;">

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number', ['size' => 20, 'maxlength' => 20, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>

                                            <!-- <span
                                                    class="btn-action single glyphicons circle_question_mark"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.'); ?>"><i></i></span> -->
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">

                                            <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', CHtml::listData(EdcensoOrganIdEmitter::model()->findAll(['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione um órgão emissor da identidade', 'class' => 'select-search-on control-input nationality-sensitive br', 'disabled' => 'disabled']);
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->dropDownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione um estado', 'class' => 'select-search-on control-input nationality-sensitive br', 'disabled' => 'disabled']);
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', ['size' => 10, 'maxlength' => 10, 'disabled' => 'disabled', 'class' => 'nationality-sensitive br']); ?>
                                            <!-- <span
                                                    class="btn-action single glyphicons circle_question_mark"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="<?php echo Yii::t('help', 'Date'); ?>"><i></i></span> -->
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Justiça
                                    </h5>
                                </div>
                                <div class="row-fluid" style="height: auto;">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'justice_restriction', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'justice_restriction', [null => 'Selecione', '0' => 'Não possui restrições', '1' => 'LA - Liberdade Assistida', '2' => 'PSC - Prestação de Serviços Comunitários'], ['class' => 'select-search-off control-input']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'justice_restriction'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" widget-scroll margin-bottom-none hide-responsive" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
                                <div>
                                    <h5 class="titulos">
                                        Passaporte
                                    </h5>
                                </div>
                                <div class="row-fluid" style="height: auto;">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', ['class' => 'control-label']); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', ['size' => 20, 'maxlength' => 20, 'disabled' => 'disabled', 'class' => 'nationality-sensitive n-br']); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Tab Student Address -->
                <div class="tab-pane" id="student-address">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'residence_zone', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'residence_zone', [null => 'Selecione uma zona', '1' => 'URBANA', '2' => 'RURAL'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cep', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->textField($modelStudentDocumentsAndAddress, 'cep', [
                                        'size' => 8,
                                        'maxlength' => 9
                                    ]);
                                    ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Valid Cep') . ' ' . Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '8.'; ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'cep'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'address', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'address', ['size' => 60, 'maxlength' => 100]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'address'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'number', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'number', ['size' => 10, 'maxlength' => 10]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'number'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'complement', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'complement', ['size' => 20, 'maxlength' => 20]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'complement'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'neighborhood', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'neighborhood', ['size' => 50, 'maxlength' => 50]); ?>
                                    <!-- <span
                                            class="btn-action single glyphicons circle_question_mark"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'), [
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities', ['rt' => 2]),
                                            'update' => '#StudentDocumentsAndAddress_edcenso_city_fk'
                                        ],
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on control-input'
                                    ]);
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_city_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(['edcenso_uf_fk' => $modelStudentDocumentsAndAddress->edcenso_uf_fk], ['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione uma cidade', 'class' => 'select-search-on control-input']);
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'diff_location', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'diff_location', [null => 'Selecione a localização', 7 => 'Não reside em área de localização diferenciada', 3 => 'Área onde se localiza comunidade remanescente de quilombos', 2 => 'Terra indígena', 1 => 'Área de assentamento'], ['class' => 'select-search-on control-input']); ?>
                                    <div class="controls">
                                        <?php echo $form->error($modelStudentDocumentsAndAddress, 'diff_location'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Tab Student Enrollment -->
                <div class="tab-pane" id="student-enrollment">
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->hiddenField($modelEnrollment, 'school_inep_id_fk', ['value' => Yii::app()->user->school]); ?>
                                </div>
                            </div>
                            <!-- turma -->
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'classroom_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    $stage = $modelStudentIdentification->getCurrentStageVsModality();
                                    $stages = implode(',', EdcensoStageVsModality::getNextStages($stage));
                                    $classrooms = Classroom::model()->findAll(
                                        'school_year = :year AND school_inep_fk = :school order by name',
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
                                        ['prompt' => 'Selecione uma Turma', 'class' => 'select-search-off control-input']
                                    ); ?>
                                    <?php echo $form->error($modelEnrollment, 'classroom_fk'); ?>
                                </div>
                            </div>
                            <!-- turma unificada -->
                            <div id="multiclass">
                                <div class="control-group">
                                    <div class="controls">
                                        <?php echo $form->labelEx($modelEnrollment, 'unified_class', ['class' => 'control-label']); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $form->DropDownList($modelEnrollment, 'unified_class', [null => 'Selecione o tipo de turma infantil', '1' => 'CRECHE', '2' => 'PRÉ-ESCOLA'], ['class' => 'select-search-off control-input']); ?>
                                        <?php echo $form->error($modelEnrollment, 'unified_class'); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <?php echo CHtml::label('Etapa', 'Stage', ['class' => 'control-label']); ?>
                                    </div>
                                    <div class="controls">
                                        <?php
                                        echo CHtml::dropDownList('Stage', null, [
                                            '0' => 'Selecione a Modalidade',
                                            '1' => 'Infantil',
                                            '2' => 'Fundamental Menor',
                                            '3' => 'Fundamental Maior',
                                            '4' => 'Médio',
                                            '5' => 'Profissional',
                                            '6' => 'EJA',
                                            '7' => 'Outros',
                                        ], [
                                            'class' => 'select-search-off control-input',
                                            'ajax' => [
                                                'type' => 'POST',
                                                'url' => CController::createUrl('enrollment/getmodalities'),
                                                'success' => 'function(data){
                                                $("#StudentEnrollment_edcenso_stage_vs_modality_fk").html(decodeHtml(data));
                                            }'
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <?php echo $form->labelEx($modelEnrollment, 'edcenso_stage_vs_modality_fk', ['class' => 'control-label']); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $form->dropDownList($modelEnrollment, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), ['prompt' => 'Selecione a etapa', 'class' => 'select-search-on control-input']); ?>
                                        <!-- <span style="margin: 0;"
                                              class="btn-action single glyphicons circle_question_mark"
                                              data-toggle="tooltip" data-placement="top"
                                              data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span> -->
                                        <?php echo $form->error($modelEnrollment, 'edcenso_stage_vs_modality_fk'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'admission_type', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'admission_type', ['1' => 'Rematrícula', '2' => 'Transferência interna', '3' => 'Transferência externa'], ['prompt' => 'Selecione', 'class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelEnrollment, 'admission_type'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'status', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'status', ['1' => 'Matriculado', '2' => 'Transferido', '3' => 'Cancelado', '4' => 'Evadido'], ['options' => ['1' => ['selected' => true]], 'prompt' => 'Selecione', 'class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelEnrollment, 'status'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'another_scholarization_place', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'another_scholarization_place', ['1' => 'Não recebe', '2' => 'Em hospital', '3' => 'Em domicílio'], ['class' => 'select-search-on control-input']); ?>
                                    <?php echo $form->error($modelEnrollment, 'another_scholarization_place'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'current_stage_situation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                            $modelEnrollment,
                                            'current_stage_situation',
                                            [
                                                null => 'Selecione',
                                                '0' => 'Primeira matrícula no curso',
                                                '1' => 'Promovido na série anterior do mesmo curso',
                                                '2' => 'Repetente'
                                            ],
                                            ['class' => 'select-search-off control-input']
                                        ); ?>
                                    <?php echo $form->error($modelEnrollment, 'current_stage_situation'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'previous_stage_situation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelEnrollment,
                                        'previous_stage_situation',
                                        [
                                            null => 'Selecione',
                                            '0' => 'Não frequentou',
                                            '1' => 'Reprovado',
                                            '2' => 'Afastado por transferência',
                                            '3' => 'Afastado por abandono',
                                            '4' => 'Matrícula final em Educação Infantil',
                                            '5' => 'Promovido'
                                        ],
                                        ['class' => 'select-search-off control-input']
                                    ); ?>
                                    <?php echo $form->error($modelEnrollment, 'previous_stage_situation'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'school_admission_date', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelEnrollment, 'school_admission_date', ['size' => 10, 'maxlength' => 10]); ?>
                                    <?php echo $form->error($modelEnrollment, 'school_admission_date'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls required">
                                    <?php echo $form->labelEx($modelEnrollment, 'public_transport', ['class' => 'control-label required']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelEnrollment, 'public_transport', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelEnrollment, 'public_transport'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_responsable">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelEnrollment, 'transport_responsable_government', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelEnrollment, 'transport_responsable_government', [null => 'Selecione o poder público do transporte', '1' => 'Estadual', '2' => 'Municipal'], ['class' => 'select-search-off control-input']); ?>
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
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_van', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_microbus', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bus', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bike', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_animal_vehicle', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_other_vehicle', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5_15', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_15_35', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_35', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group" id="">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Type of Specialized Educational Assistance'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox inline-block">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_cognitive_functions']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_cognitive_functions', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_autonomous_life']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_autonomous_life', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_curriculum_enrichment']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_curriculum_enrichment', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_accessible_teaching']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_accessible_teaching', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_libras']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_libras', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_portuguese']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_portuguese', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_soroban']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_soroban', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_braille']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_braille', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_mobility_techniques']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_mobility_techniques', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_caa']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_caa', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['aee_optical_nonoptical']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'aee_optical_nonoptical', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span11">
                            <?php
                            $error = $modelEnrollment->getErrors('enrollment_id');
                            if (count($error) > 0) {
                                ?>
                                <div class="alert alert-error">
                                    <?php echo $error[0]; ?>
                                </div>
                            <?php
                            } ?>
                            <div id="enrollment" class="widget widget-scroll margin-bottom-none table-responsive">
                                <div>
                                    <h4 class="titulos">
                                        <?php echo yii::t('default', 'Enrollments'); ?>
                                    </h4>
                                </div>
                                <div class="widget-body in" style="height: auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <td style="text-align: center !important;">Escola</td>
                                                <td style="text-align: center">Atualizar Ficha de Matrícula</td>
                                                <td style="text-align: center">Ano</td>
                                                <td style="text-align: center">Formulários</td>
                                                <td style="text-align: center; width: 15%;">Cancelar Matrícula</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($modelStudentIdentification->studentEnrollments as $me) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $me->schoolInepIdFk->name ?></td>
                                                    <td style="text-align: center">
                                                        <?php if ($me->classroomFk->school_year >= date('Y')) { ?>
                                                            <a href='<?php echo @Yii::app()->createUrl('enrollment/update', ['id' => $me->id]); ?>'>
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
                                                                // no break
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
                                                } ?>
                                                    <td>
                                                        <ul>
                                                            <?php
                                                            $forms = unserialize(FORMS);
                                                foreach ($forms as $form) {
                                                    $link = Yii::app()->createUrl('forms/' . $form['action'], ['type' => $type, 'enrollment_id' => $me->id]);
                                                    echo "<li><a target='_blank' href=" . $link . '>' . $form['name'] . '</a></li>';
                                                }
                                                if ($me->classroomFk->school_year == date('Y')) {
                                                    $date = date('Y-m-d');
                                                    $quizs = Quiz::model()->findAll('status=1 AND init_date <=:init_date AND final_date >=:final_date', [':init_date' => $date, ':final_date' => $date]);
                                                    if (count($quizs) > 0) {
                                                        foreach ($quizs as $quiz) {
                                                            $link = Yii::app()->createUrl('quiz/default/answer', ['quizId' => $quiz->id, 'studentId' => $me->studentFk->id]);
                                                            echo "<li><a target='_blank' href=" . $link . '>' . $quiz->name . '</a></li>';
                                                        }
                                                    }
                                                } ?>
                                                            <li><a href='<?php echo @Yii::app()->createUrl('forms/EnrollmentGradesReport', ['enrollment_id' => $me->id]) ?>' target="_blank">Rendimento Escolar Por Atividades</a></li>
                                                    </td>
                                                    <td style="text-align: center">
                                                        <?php if ($me->classroomFk->school_year >= date('Y')) { ?>
                                                            <a href='<?php echo @Yii::app()->createUrl('enrollment/delete', ['id' => $me->id]) ?>'><i class="fa fa-trash-o"></i></a>
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
                    </div>
                </div>
            </div>

        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
</div>

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
                                                $this->widget('application.widgets.AlertCensoWidget', ['prefix' => 'student', 'dataId' => $_GET['id']]);
                                            }
?>

<script type="text/javascript">
    var formIdentification = '#StudentIdentification_';
    var formDocumentsAndAddress = '#StudentDocumentsAndAddress_';
    var formEnrollment = '#StudentEnrollment_';
    var updateDependenciesURL = '<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>';
    var filled = -1;
</script>