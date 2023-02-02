<?php

/**
 * @var $form CActiveForm
 */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/school/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/pagination.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');

$form = $this->beginWidget('CActiveForm', [
    'id' => 'school',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

<div class="row-fluid">
    <div class="span12" style="height: 63px;">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>
        <span class="subtitle">
            <?php echo Yii::t('default', 'Fields with * are required.') ?>
        </span>

        <!-- style="line-height: 190px;" -->
        <div class="tag-buttons-container buttons">
            <a data-toggle="tab" class='hide-responsive tag-button-light small-button prev' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            <a data-toggle="tab" class='tag-button small-button next'><?php echo Yii::t('default', 'Next') ?>
            </a>
            <button class="tag-button small-button last save-school-button" type="button">
                <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="tag-inner">

    <div class="widget widget-tabs border-bottom-none">
        <?php echo $form->errorSummary($modelSchoolIdentification); ?>
        <?php echo $form->errorSummary($modelSchoolStructure); ?>
        <div class="alert alert-error school-error no-show"></div>
        <div class="widget-head">
            <ul class="tab-school">
                <li id="tab-school-indentify" class="active"><a class="glyphicons edit" href="#school-indentify" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Identification') ?>
                    </a></li>
                <li id="tab-school-addressContact"><a class="glyphicons settings" href="#school-addressContact" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Address and Contact') ?>
                    </a></li>
                <li id="tab-school-structure"><a class="glyphicons settings" href="#school-structure" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Structure') ?>
                    </a></li>
                <li id="tab-school-equipment"><a class="glyphicons imac" href="#school-equipment" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Equipments') ?>
                    </a></li>
                <li id="tab-school-education"><a class="glyphicons book" href="#school-education" data-toggle="tab">
                        <!-- <i></i> -->
                        <?php echo Yii::t('default', 'Educational Data') ?>
                    </a></li>
                <?php if (!$modelSchoolIdentification->isNewRecord) : ?>
                    <li id="tab-school-reports" class="hide-responsive">
                        <a class="glyphicons book" href="#school-reports" data-toggle="tab">
                            <!-- <i></i> -->
                            <?php echo Yii::t('default', 'Relatórios') ?>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
        <div class="box-links-previous-next" class="">
            <a data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            <a data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default', 'Next') ?>
                <!-- <i></i> -->
            </a>
            <button class="btn btn-icon btn-primary last glyphicons circle_ok pull-right save-school-button" type="button">
                <i></i> <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>

        </div>
        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <!-- Tab content -->
                <div class="tab-pane active" id="school-indentify">
                    <div>
                        <h5 class="titulos">Dados Básicos</h5>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'name', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'name', ['size' => 100, 'maxlength' => 100, ]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.') . ' ' . Yii::t('help', 'Min length') . '4'; ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'name'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'administrative_dependence', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'administrative_dependence', [null => 'Selecione a dependencia administrativa', 1 => 'Federal', 2 => 'Estadual', 3 => 'Municipal', 4 => 'Privada'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'administrative_dependence'); ?>
                                </div>
                            </div>
                            <?php if ($modelSchoolIdentification->logo_file_name !== null) {
    echo CHtml::image(Yii::app()->controller->createUrl('school/displayLogo', ['id' => $modelSchoolIdentification->inep_id]), 'logo', ['width' => 40, 'style' => 'margin: -10px 0 15px 145px', 'class' => 'logo-preview']);
}
                            ?>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'act_of_acknowledgement', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textArea($modelSchoolIdentification, 'act_of_acknowledgement'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'act_of_acknowledgement'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'logo_file_content', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <button class="btn btn-icon glyphicons upload upload-logo-button" type="button">
                                        <i></i>Anexar
                                    </button>
                                    <span class="uploaded-logo-name"></span>
                                    <?php echo $form->fileField($modelSchoolIdentification, 'logo_file_content'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'logo_file_content'); ?>
                                </div>
                            </div>

                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'inep_id', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'inep_id', ['size' => 8, 'maxlength' => 8, ]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'School code in the registration INEP'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'inep_id'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'situation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'situation', [null => 'Selecione a situação', 1 => 'Em Atividade', 2 => 'Paralisada', 3 => 'Extinta'], ['class' => 'select-search-off control-input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Current situation school run'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'situation'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'initial_date', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'initial_date', ['size' => 10, 'maxlength' => 10]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Initial Date Help'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'initial_date'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'final_date', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'final_date', ['size' => 10, 'maxlength' => 10]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Final Date Help'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'final_date'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'regulation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'regulation', [null => 'Selecione a situação de regulamentação', 0 => 'Não', 1 => 'Sim', 2 => 'Em tramitação'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'regulation'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos">Dados do Gestor</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'manager_name', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'manager_name', ['size' => 60, 'maxlength' => 100]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full name of school manager'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'manager_name'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'manager_email', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'manager_email', ['size' => 50, 'maxlength' => 50]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'E-mail'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'manager_email'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'manager_role', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'manager_role', [null => 'Selecione o cargo', '1' => 'Diretor', '2' => 'Outro Cargo'], ['class' => 'select-search-off control-input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Role of the school manager'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'manager_role'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'manager_cpf', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'manager_cpf', ['size' => 11, 'maxlength' => 11]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'CPF school manager. Numbers only.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'manager_cpf'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'manager_contract_type', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'manager_contract_type', [null => 'Selecione o vínculo', '1' => 'Concursado/Efetivo', '2' => 'Temporário', '3' => 'Terceirizado', '4' => 'CLT'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'manager_contract_type'); ?>
                                </div>
                            </div>
                            <div class="control-group  hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'manager_access_criterion', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textArea($modelSchoolIdentification, 'manager_access_criterion'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'manager_access_criterion'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Órgãos que a escola está vinculada</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="controls" id="SchoolIdentification_linked_organ">
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_mec']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_mec', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_army']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_army', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_helth']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_helth', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_other']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_other', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Esfera do Órgão regulador</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="controls" id="SchoolIdentification_regulation_organ">
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_federal']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'regulation_organ_federal', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_state']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'regulation_organ_state', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_municipal']; ?>
                                    <?= $form->checkBox($modelSchoolIdentification, 'regulation_organ_municipal', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos">Outras informações</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'private_school_organization_civil_society', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'private_school_organization_civil_society', [null => 'Selecione', 0 => 'Não', 1 => 'Sim'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'private_school_organization_civil_society'); ?>
                                </div>
                            </div>
                            <div class="control-group  hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'ies_code', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'ies_code'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'ies_code'); ?>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group  hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'inep_head_school', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'inep_head_school'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'inep_head_school'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-addressContact">
                    <div>
                        <h5 class="titulos">Endereço</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'cep', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'cep', [
                                        'size' => 8, 'maxlength' => 8,
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('Instructor/getcitybycep'),
                                            'data' => ['cep' => 'js:this.value'],
                                            'success' => "function(data){
                                     data = jQuery.parseJSON(data);
                                     if(data.UF == null) $(formIdentification+'cep').val('').trigger('focusout');
                                     $(formIdentification+'edcenso_uf_fk').val(data['UF']).trigger('change').select2('readonly',data.UF != null);
                                     setTimeout(function(){
                                        $(formIdentification+'edcenso_city_fk').val(data['City']).trigger('change').select2('readonly',data.City != null);
                                        }, 500);
                                    }"
                                        ]
                                    ]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Valid Cep') . ' ' . Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '8.'; ?>"><i></i></span> -->

                                    <?php echo $form->error($modelSchoolIdentification, 'cep'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_uf_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php //@done s1 - Atualizar a lista de Orgão Regional de Educação também.
                                    echo $form->dropDownList($modelSchoolIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'), [
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on control-input',
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('school/updateUfDependencies'),
                                            'success' => "function(data){
                                            data = jQuery.parseJSON(data);
                                            valR = $('#SchoolIdentification_edcenso_regional_education_organ_fk').val();
                                            valC = $('#SchoolIdentification_edcenso_city_fk').val();
                                            
                                            $('#SchoolIdentification_edcenso_regional_education_organ_fk').html(data.Regional).val(valR).trigger('change');
                                            $('#SchoolIdentification_edcenso_city_fk').html(data.City).val(valC).trigger('change');
                                        }",
                                        ]
                                    ]); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'address_neighborhood', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address_neighborhood', ['size' => 50, 'maxlength' => 50, ]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'address_neighborhood'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'address_complement', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address_complement', ['size' => 20, 'maxlength' => 20, ]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'address_complement'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_district_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_district_fk', CHtml::listData(EdcensoDistrict::model()->findAllByAttributes(['edcenso_city_fk' => $modelSchoolIdentification->edcenso_city_fk], ['order' => 'name']), 'code', 'name'), ['prompt' => 'Selecione um distrito', 'class' => 'select-search-on control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_district_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'latitude', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'latitude'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'latitude'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php //@done s1 - Tem que filtrar de acordo com o estado e cidade, no momento está listando todos
                                    ?>
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    $criteria = new CDbCriteria();
                                    $criteria->select = 't.*';
                                    $criteria->join = 'LEFT JOIN edcenso_city city ON city.id = t.edcenso_city_fk ';
                                    $criteria->condition = 'city.edcenso_uf_fk = "' . $modelSchoolIdentification->edcenso_uf_fk . '"';
                                    $criteria->order = 'name';
                                    echo $form->dropDownList($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', CHtml::listData(EdcensoRegionalEducationOrgan::model()->findAll($criteria), 'code', 'name'), ['prompt' => 'Selecione o órgão', 'class' => 'select-search-on control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_regional_education_organ_fk'); ?>
                                </div>
                            </div>

                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'address', ['class' => 'control-label']); ?>

                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address', ['size' => 60, 'maxlength' => 100, ]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'address'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'address_number', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address_number', ['size' => 10, 'maxlength' => 10, 'class' => 'span2']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'address_number'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_city_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(['edcenso_uf_fk' => $modelSchoolIdentification->edcenso_uf_fk], ['order' => 'name']), 'id', 'name'), [
                                        'prompt' => 'Selecione uma cidade',
                                        'class' => 'select-search-on control-input',
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('school/updateCityDependencies'),
                                            'success' => "function(data){
                                            data = jQuery.parseJSON(data);
                                            valD = $('#SchoolIdentification_edcenso_district_fk').val();
                                            $('#SchoolIdentification_edcenso_district_fk').html(data.District).val(valD).trigger('change');
                                        }",
                                        ]
                                    ]); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'location', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'location', [null => 'Selecione a localização', 1 => 'Urbano', 2 => 'Rural'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'location'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'id_difflocation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'id_difflocation', [null => 'Selecione a localização', 1 => 'Área de assentamento', 2 => 'Terra indígena', 3 => 'Área onde se localiza a comunidade remanescente de quilombos', 7 => 'A escola não está em área diferenciada'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'id_difflocation'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'longitude', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'longitude'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'longitude'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'offer_or_linked_unity', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'offer_or_linked_unity', [null => 'Selecione a localização', 0 => 'Não', 1 => 'Unidade vinculada a escola de Educação Básica', 2 => 'Unidade ofertante de Ensino Superior'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'offer_or_linked_unity'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos">Contrato</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'ddd', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'ddd', ['size' => 2, 'maxlength' => 2, 'class' => 'span2']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'ddd'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'email', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'email', ['size' => 50, 'maxlength' => 50]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'E-mail'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'email'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'phone_number', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'phone_number', ['size' => 9, 'maxlength' => 9]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Phone'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'phone_number'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'other_phone_number', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'other_phone_number', ['size' => 9, 'maxlength' => 9]); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Phone'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolIdentification, 'other_phone_number'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="school-structure">
                    <div>
                        <h5 class="titulos">Estrutura Física</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'classroom_count', ['class' => 'control-label required']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'classroom_count'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'classroom_count'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_climate_roomspublic', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'dependencies_climate_roomspublic'); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'dependencies_climate_roomspublic'); ?>
                                </div>
                            </div>

                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'used_classroom_count', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'used_classroom_count'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'used_classroom_count'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_outside_roomspublic', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'dependencies_outside_roomspublic'); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'dependencies_outside_roomspublic'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_acessibility_roomspublic', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'dependencies_acessibility_roomspublic'); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'dependencies_acessibility_roomspublic'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos">Funcionários</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'employees_count', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'employees_count'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'employees_count'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_administrative_assistant', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_administrative_assistant'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_administrative_assistant'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_service_assistant', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_service_assistant'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_service_assistant'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_coordinator_shift', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_coordinator_shift'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span>-->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_coordinator_shift'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_nutritionist', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_nutritionist'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_nutritionist'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_school_secretary', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_school_secretary'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_school_secretary'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_security_guards', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_security_guards'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_security_guards'); ?>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_librarian', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_librarian'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_librarian'); ?>
                                </div>
                            </div>

                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_firefighter', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_firefighter'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_firefighter'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_speech_therapist', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_speech_therapist'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_speech_therapist'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_psychologist', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_psychologist'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_psychologist'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_cooker', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_cooker'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_cooker'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_support_professionals', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_support_professionals'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_support_professionals'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'workers_monitors', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'workers_monitors'); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'workers_monitors'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <h5 class="titulos required">Local de Funcionamento</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6 hide-responsive">
                            <div class="control-group">
                                <div class="controls" id="SchoolStructure_operation_location">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_building'];
                                        echo $form->checkBox($modelSchoolStructure, 'operation_location_building', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other_school_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other_school_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_barracks']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_barracks', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_socioeducative_unity']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_socioeducative_unity', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_prison_unity']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_prison_unity', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class=" span6 hide-responsive">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'building_occupation_situation', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'building_occupation_situation', [null => 'Selecione a forma de ocupação', '1' => 'Próprio', '2' => 'Alugado', '3' => 'Cedido'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'building_occupation_situation'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'shared_building_with_school', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'shared_building_with_school', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'shared_building_with_school'); ?>
                                </div>
                            </div>
                        
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'shared_school_inep_id_1', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolStructure, 'shared_school_inep_id_1', CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'), ['multiple' => true, 'key' => 'inep_id', 'class' => 'select-schools control-input multiselect']); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'shared_school_inep_id_1'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Dependencias</h3>
                    </div>
                    <div class="row-fluid  hide-responsive dependencies-container">
                        <div class=" span6">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_warehouse']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_warehouse', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_green_area']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_green_area', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_auditorium']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_auditorium', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_prysical_disability_bathroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_prysical_disability_bathroom', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_child_bathroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_child_bathroom', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_workes']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_bathroom_workes', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_with_shower']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_bathroom_with_shower', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_library']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_library', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_kitchen']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_kitchen', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_storeroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_storeroom', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_accomodation']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_student_accomodation', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructor_accomodation']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructor_accomodation', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_reading_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_reading_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructors_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructors_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_aee_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_aee_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_secretary_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_secretary_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_vocational_education_workshop']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_vocational_education_workshop', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_none']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_none', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class=" span6  hide-responsive">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_science_lab']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_science_lab', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_info_lab']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_info_lab', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_professional_specific_lab']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_professional_specific_lab', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_playground']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_playground', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_covered_patio']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_covered_patio', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_uncovered_patio']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_uncovered_patio', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_pool']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_pool', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_indoor_sports_court']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_indoor_sports_court', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_outdoor_sports_court']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_outdoor_sports_court', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_refectory']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_refectory', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_repose_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_student_repose_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_arts_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_arts_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_music_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_music_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_dance_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_dance_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_multiuse_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_multiuse_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_yardzao']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_yardzao', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_vivarium']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_vivarium', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_principal_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_principal_room', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Suprimento de água</h5>
                    </div>
                    <div class="row-fluid  hide-responsive">
                        <div class="span6">
                            <div class="control-group hide-responsive water-supply-container">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_public']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_public', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_artesian_well']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_artesian_well', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_well']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_well'); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_river']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_river', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Potable Water'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['provide_potable_water']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'provide_potable_water', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Food Supply'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['supply_food']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'supply_food', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'feeding', ['class' => 'control-label required indicator']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'feeding', [null => 'Selecione o valor', '0' => 'Não oferece', '1' => 'Oferece'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'feeding'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Suprimento de Energia</h5>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group hide-responsive energy-supply-container">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_public']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_public', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_generator', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator_alternative']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_generator_alternative', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Acessability -->
                    </div>
                    <div>
                        <h5 class="titulos required">Esgoto</h5>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group hide-responsive sewage-container">
                            <div class="controls">
                                <label class="checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['sewage_public']; ?>
                                    <?php echo $form->checkBox($modelSchoolStructure, 'sewage_public', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa']; ?>
                                    <?php echo $form->checkBox($modelSchoolStructure, 'sewage_fossa', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa_common']; ?>
                                    <?php echo $form->checkBox($modelSchoolStructure, 'sewage_fossa_common', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['sewage_inexistent']; ?>
                                    <?php echo $form->checkBox($modelSchoolStructure, 'sewage_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Destino do Lixo</h5>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group hide-responsive garbage_destination_container">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_collect']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_collect', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_burn']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_burn', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_bury']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_bury', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_public']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_public', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_throw_away']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_throw_away', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>



                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Tratamento do Lixo</h5>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group hide-responsive garbage-treatment-container">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['treatment_garbage_parting_garbage']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'treatment_garbage_parting_garbage', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['treatment_garbage_resuse']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'treatment_garbage_resuse', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_recycle']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_recycle', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['traetment_garbage_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'traetment_garbage_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Acessibilidade</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span6 accessbility-container">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_handrails_guardrails']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_handrails_guardrails', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_elevator']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_elevator', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_tactile_floor']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_tactile_floor', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_doors_80cm']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_doors_80cm', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_ramps']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_ramps', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_sound_signaling']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_sound_signaling', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_tactile_singnaling']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_tactile_singnaling', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessability_visual_signaling']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_visual_signaling', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['acessabilty_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessabilty_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="titulos required">Órgãos em Funcionamento na Escola</h5>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group hide-responsive board-organ-container">
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['board_organ_association_parent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_association_parent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['board_organ_association_parentinstructors']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_association_parentinstructors', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['board_organ_board_school']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_board_school', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['board_organ_student_guild']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_student_guild', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['board_organ_others']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_others', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['board_organ_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'ppp_updated', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'ppp_updated', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'ppp_updated'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'website', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'website', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'website'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'space_schoolenviroment', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'space_schoolenviroment', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'space_schoolenviroment'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'community_integration', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'community_integration', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'community_integration'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-equipment">
                    <div>
                        <h5 class="titulos">Eletrônicos</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_dvd', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_dvd', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_dvd'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_stereo_system', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_stereo_system', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_stereo_system'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_tv', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_tv', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_tv'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_blackboard', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_blackboard', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_blackboard'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_overhead_projector', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_overhead_projector', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_overhead_projector'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_desktop', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_desktop', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_desktop'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_tabletstudent', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_tabletstudent', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_tabletstudent'); ?>
                                </div>
                            </div>
                            <div class=" span6">
                                <div class="control-group hide-responsive equipments-container">
                                    <div class="controls">
                                        <label class="control-label required"><?php echo Yii::t('default', 'Existing equipment at the school for technical and administrative use'); ?>
                                            *</label>
                                    </div>
                                    <div class="controls">
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_satellite_dish']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_satellite_dish', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_computer']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_computer', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_copier']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_copier', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_printer']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_printer', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_multifunctional_printer']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_multifunctional_printer', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_scanner']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_scanner', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_inexistent']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="control-group equipments-material-container">
                                    <div class="controls">
                                        <label class="control-label required"><?php echo Yii::t('default', 'Material, sociocultural and/or pedagogical instruments in use at school for the development of teaching and learning activities'); ?> *</label>
                                    </div>
                                    <div class="controls">
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_multimedia_collection']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_multimedia_collection', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_toys_early']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_toys_early', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_scientific_materials']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_scientific_materials', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_equipment_amplification']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_equipment_amplification', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_musical_instruments']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_musical_instruments', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_educational_games']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_educational_games', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_cultural']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_cultural', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_professional_education']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_cultural', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_sports']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_sports', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingindian']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingindian', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingethnic']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingethnic', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingrural']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingrural', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                        <label class="checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['instruments_inexistent']; ?>
                                            <?php echo $form->checkBox($modelSchoolStructure, 'instruments_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_vcr', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_vcr', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_vcr'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_data_show', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_data_show', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_data_show'); ?>
                                </div>
                            </div>


                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_fax', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_fax', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_fax'); ?>
                                </div>
                            </div>

                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_camera', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_camera', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . ' ' . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_camera'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'administrative_computers_count', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'administrative_computers_count', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'administrative_computers_count'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'student_computers_count', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'student_computers_count', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'student_computers_count'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_notebookstudent', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_notebookstudent', ['size' => 4, 'maxlength' => 4, 'class' => 'equipments_input']); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_notebookstudent'); ?>
                                </div>
                            </div>


                        </div>
                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'bandwidth', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'bandwidth', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'bandwidth'); ?>
                                </div>
                            </div>

                            <div class="control-group internet-access-container">
                                <div class="controls">
                                    <label class="control-label required"><?php echo Yii::t('default', 'Internet Access'); ?> *</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_administrative']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_administrative', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_educative_process']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_educative_process', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_student']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_student', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_community']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_community', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Internet Access Connected Devices'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_connected_desktop']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_connected_desktop', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_connected_personaldevice']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_connected_personaldevice', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_broadband']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_broadband', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group internet-access-local-container">
                                <div class="controls">
                                    <label class="control-label required"><?php echo Yii::t('default', 'Internet Access Local'); ?> *</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_cable']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_local_cable', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_wireless']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_local_wireless', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_inexistet']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_local_inexistet', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-education">
                    <div>
                        <h5 class="titulos">Eletrônicos</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'aee', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'aee', [null => 'Selecione o valor', '0' => 'Não oferece', '1' => 'Não exclusivamente', '2' => 'Exclusivamente'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'aee'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'complementary_activities', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'complementary_activities', [null => 'Selecione o valor', '0' => 'Não oferece', '1' => 'Não exclusivamente', '2' => 'Exclusivamente'], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'complementary_activities'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'basic_education_cycle_organized', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'basic_education_cycle_organized', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'basic_education_cycle_organized'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'different_location', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelSchoolStructure, 'different_location', [
                                        null => 'Selecione a localização',
                                        '1' => 'Área de assentamento',
                                        '2' => 'Terra indígena',
                                        '3' => 'Área remanescente de quilombos',
                                        '4' => 'Unidade de uso sustentável',
                                        '5' => 'Unidade de uso sustentável em terra indígena',
                                        '6' => 'Unidade de uso sustentável em área remanescente de quilombos',
                                        '7' => 'Não se aplica',
                                    ], ['class' => 'select-search-off control-input']);
                                    ?>
                                    <?php echo $form->error($modelSchoolStructure, 'different_location'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Sociocultural Didactic Material'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_none']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_none', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_quilombola']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_quilombola', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_native']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_native', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'native_education', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'native_education', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'native_education'); ?>
                                </div>
                            </div>

                            <div class="control-group" id="native_education_language">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Native Education Language'); ?></label>
                                </div>
                                <div id="native_education_lenguage_none">
                                    <?php echo CHtml::activeHiddenField($modelSchoolStructure, 'native_education_language_native', ['value' => null, 'disabled' => 'disabled']);
                                    echo CHtml::activeHiddenField($modelSchoolStructure, 'native_education_language_portuguese', ['value' => null, 'disabled' => 'disabled']); ?>
                                </div>
                                <div class="controls" id="native_education_lenguage_some">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_native']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_native', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_portuguese']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_portuguese', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk', CHtml::listData(EdcensoNativeLanguages::model()->findAll(['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione a língua indígena', 'class' => 'select-search-on control-input']);
                                    ?>
                                    <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk2', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk2', CHtml::listData(EdcensoNativeLanguages::model()->findAll(['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione a língua indígena', 'class' => 'select-search-on control-input']);
                                    ?>
                                    <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk2'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk3', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk3', CHtml::listData(EdcensoNativeLanguages::model()->findAll(['order' => 'name']), 'id', 'name'), ['prompt' => 'Selecione a língua indígena', 'class' => 'select-search-on control-input']);
                                    ?>
                                    <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk3'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'brazil_literate', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'brazil_literate', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'brazil_literate'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'open_weekend', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'open_weekend', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'open_weekend'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'pedagogical_formation_by_alternance', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'pedagogical_formation_by_alternance', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'pedagogical_formation_by_alternance'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Modalities'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_regular']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_regular', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_especial']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_especial', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_eja']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_eja', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_professional']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_professional', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Organization of Education'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_series_year']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_series_year', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_semester_periods']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_semester_periods', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_elementary_cycle']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_elementary_cycle', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_non_serialgroups']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_non_serialgroups', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_modules']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_modules', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_regular_alternation']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_regular_alternation', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Selection Exam'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['select_adimission']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'select_adimission', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group booking-container">
                                <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Reservation by Quota System'); ?></label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_self_declaredskin']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_self_declaredskin', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_income']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_income', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_public_school']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_public_school', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_disabled_person']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_disabled_person', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_others']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_others', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_inexistent', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'stages_concept_grades', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolStructure, 'stages_concept_grades', CHtml::listData(EdcensoStageVsModality::model()->findAll(['order' => 'name']), 'id', 'name'), ['multiple' => true, 'prompt' => 'Selecione o estágio vs modalidade', 'class' => 'select-search-on  control-input multiselect']); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'stages_concept_grades'); ?>
                                    <div class="add-stages-options">Adicionar: <span class="add-fundamental-menor">Fundamental Menor</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-reports">
                    <div><a target="_blank" href="<?= @Yii::app()->createUrl('school/reportsMonthlyTransaction', ['id' => $modelSchoolIdentification->inep_id, 'type' => 1]); ?>">Movimentação
                            Mensal Anos Iniciais</a></div>
                    <div><a target="_blank" href="<?= @Yii::app()->createUrl('school/reportsMonthlyTransaction', ['id' => $modelSchoolIdentification->inep_id, 'type' => 2]); ?>">Movimentação
                            Mensal Anos Finais</a></div>
                    <div><a target="_blank" href="<?= @Yii::app()->createUrl('school/reportsMonthlyTransaction', ['id' => $modelSchoolIdentification->inep_id, 'type' => 3]); ?>">Movimentação
                            Mensal Educação Infantil</a></div>
                    <div><a target="_blank" href="<?= @Yii::app()->createUrl('school/reports', ['id' => $modelSchoolIdentification->inep_id]); ?>">Resumo
                            Mensal de Frequência</a></div>
                    <div><a target="_blank" href="<?= @Yii::app()->createUrl('school/record', ['id' => $modelSchoolIdentification->inep_id, 'type' => 1]); ?>">Histórico
                            Ensino Regular</a></div>
                    <div><a target="_blank" href="<?= @Yii::app()->createUrl('school/record', ['id' => $modelSchoolIdentification->inep_id, 'type' => 2]); ?>">Histórico
                            Ensino EJA</a></div>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
</div>

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
                                        $this->widget('application.widgets.AlertCensoWidget', ['prefix' => 'scholl', 'dataId' => $_GET['id']]);
                                    }
?>

<script type="text/javascript">
    var formIdentification = '#SchoolIdentification_';
    var formStructure = '#SchoolStructure_';
    var date = new Date();
    var actual_year = date.getFullYear();
    var initial_date;
    var final_date;
</script>