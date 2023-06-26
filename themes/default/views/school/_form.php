<?php

/**
 * @var CActiveForm $this CActiveForm
 * @var $modelSchoolIdentification SchoolIdentification
 */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/school/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/pagination.js', CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

<div class="row-fluid">
    <div class="span12" style="height: 63px;">
        <h1><?php echo $title; ?></h1>
        <span class="subtitle">
            <?php echo Yii::t('default', 'Fields with * are required.') ?>
        </span>

        <!-- style="line-height: 190px;" -->
        <div class="tag-buttons-container buttons">
            <a data-toggle="tab" class='hide-responsive t-button-secondary prev' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            <?= $modelSchoolIdentification->isNewRecord ? "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>
            <button class="t-button-primary  last save-school-button" type="button">
                <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="tag-inner">

    <div class="widget widget-tabs border-bottom-none">
        <?php echo $form->errorSummary($modelSchoolIdentification); ?>
        <?php echo $form->errorSummary($modelSchoolStructure); ?>
        <?php echo $form->errorSummary($modelManagerIdentification); ?>
        <div class="alert alert-error school-error no-show"></div>
        <div class="t-tabs">
            <ul class="js-tab-school t-tabs__list">
                <li id="tab-school-indentify" class="active t-tabs__item"><a class="t-tabs__link" href="#school-indentify" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Identification') ?>
                    </a>
                     <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-addressContact" class="t-tabs__item">
                    <a class="t-tabs__link" href="#school-addressContact" data-toggle="tab">
                        <span class="t-tabs__numeration">2</span>
                        <?php echo Yii::t('default', 'Address and Contact') ?>

                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>

                <li id="tab-school-structure" class="t-tabs__item"><a class="t-tabs__link" href="#school-structure" data-toggle="tab">
                        <span class="t-tabs__numeration">3</span>
                        <?php echo Yii::t('default', 'Structure') ?>

                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-equipment" class="t-tabs__item"><a class="t-tabs__link" href="#school-equipment" data-toggle="tab">
                        <span class="t-tabs__numeration">4</span>
                        <?php echo Yii::t('default', 'Equipments') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-manager" class="t-tabs__item"><a class="t-tabs__link" href="#school-manager" data-toggle="tab">
                        <span class="t-tabs__numeration">5</span>
                        <?php echo Yii::t('default', 'manager') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-education" class="t-tabs__item"><a class="t-tabs__link" href="#school-education" data-toggle="tab">
                        <span class="t-tabs__numeration">6</span>
                        <?php echo Yii::t('default', 'Educational Data') ?>
                    </a>
                    <?php if (!$modelSchoolIdentification->isNewRecord) : ?>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                    <?php endif ?>
                </li>
                <?php if (!$modelSchoolIdentification->isNewRecord) : ?>
                    <li id="tab-school-reports" class="t-tabs__item hide-responsive">

                        <a class="t-tabs__link" href="#school-reports" data-toggle="tab">
                            <span class="t-tabs__numeration">7</span>
                            <?php echo Yii::t('default', 'Relatórios') ?>
                        </a>

                    </li>
                <?php endif ?>
            </ul>
        </div>
        <div class="box-links-previous-next" class="">
            <a data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            <a data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default', 'Next') ?>
            </a>
            <button class="btn btn-icon btn-primary last glyphicons circle_ok pull-right save-school-button" type="button">
                <i></i> <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>

        </div>
        <div class="widget-body form-school form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="school-indentify">
                    <div>
                        <h3>Dados Básicos</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'name', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'name', array('size' => 100, 'maxlength' => 100, 'placeholder' => 'Digite o Nome da Escola')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.') . " " . Yii::t('help', 'Min length') . '4'; ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'name'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'administrative_dependence', array('class' => 'control-label')); ?>
                                <?php echo $form->dropDownList($modelSchoolIdentification, 'administrative_dependence', array(null => 'Selecione a dependencia administrativa', 1 => 'Federal', 2 => 'Estadual', 3 => 'Municipal', 4 => 'Privada'), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'administrative_dependence'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'act_of_acknowledgement', array('class' => 'control-label')); ?>
                                <?php echo $form->textArea($modelSchoolIdentification, 'act_of_acknowledgement', array('placeholder' => 'Digite o Ato de Reconhecimento')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'act_of_acknowledgement'); ?>
                            </div>
                            <div class="control-group">
                                    <?php echo $form->labelEx($modelSchoolIdentification, 'logo_file_content', array('class' => 'control-label widthfull')); ?>
                                    <button class="btn btn-icon glyphicons upload upload-logo-button" type="button">
                                        <i></i>Anexar
                                    </button>
                                    <span class="uploaded-logo-name"><?php echo $modelSchoolIdentification->logo_file_name !== null ? 
                                    $modelSchoolIdentification->logo_file_name . '<a href="'.Yii::app()->controller->createUrl('school/removeLogo', array('id' => $modelSchoolIdentification->inep_id)).'" class="deleteTeachingData" title="Excluir"></a>' : '' ?> </span>
                                    <?php echo $form->fileField($modelSchoolIdentification, 'logo_file_content'); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'logo_file_content'); ?>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'inep_id', array('class' => 'control-label')); ?>
                                <?php 
                                echo
                                $modelSchoolIdentification->isNewRecord ?
                                $form->textField($modelSchoolIdentification, 'inep_id', array('size' => 8, 'maxlength' => 8, 'placeholder' => 'Digite o Código INEP')) 
                                :  $form->textField($modelSchoolIdentification, 'inep_id', array('size' => 8, 'maxlength' => 8, 'placeholder' => 'Digite o Código INEP', 'disabled' => 'disabled')) ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'School code in the registration INEP'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'inep_id'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'situation', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolIdentification, 'situation', array(null => 'Selecione a situação', 1 => 'Em Atividade', 2 => 'Paralisada', 3 => 'Extinta'), array('class' => 'select-search-off control-input')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Current situation school run'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'situation'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'initial_date', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'initial_date', array('size' => 10, 'maxlength' => 10, 'placeholder' => 'Digite a Data Inicial (Dia/Mês/Ano)')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Initial Date Help'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'initial_date'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'final_date', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'final_date', array('size' => 10, 'maxlength' => 10, 'placeholder' => 'Digite a Data Final (Dia/Mês/Ano)')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Final Date Help'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'final_date'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'regulation', array('class' => 'control-label')); ?>
                                <?php echo $form->dropDownList($modelSchoolIdentification, 'regulation', array(null => 'Selecione a situação de regulamentação', 0 => 'Não', 1 => 'Sim', 2 => 'Em tramitação'), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'regulation'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Órgãos que a escola está vinculada</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="t-field-checkbox-group" id="SchoolIdentification_linked_organ">
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_mec', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['linked_mec']; ?>
                                    </label>
                                </div>    
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_army', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['linked_army']; ?>    
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_helth', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['linked_helth']; ?>    
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'linked_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['linked_other']; ?>    
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Esfera do Órgão regulador</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="t-field-checkbox-group" id="SchoolIdentification_regulation_organ">
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'regulation_organ_federal', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_federal']; ?>    
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'regulation_organ_state', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_state']; ?>    
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?= $form->checkBox($modelSchoolIdentification, 'regulation_organ_municipal', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_municipal']; ?>    
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3>Outras informações</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'private_school_organization_civil_society', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolIdentification, 'private_school_organization_civil_society', array(null => 'Selecione', 0 => 'Não', 1 => 'Sim'), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'private_school_organization_civil_society'); ?>
                            </div>
                            <div class="control-group  hide-responsive">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'ies_code', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'ies_code', array('placeholder' => 'Digite o Código da IES')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'ies_code'); ?>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group  hide-responsive">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'inep_head_school', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'inep_head_school', array('placeholder' => 'Digite o Código da Escola Sede')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'inep_head_school'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-addressContact">
                    <div>
                        <h3>Endereço</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'cep', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'cep', array(
                                    'placeholder' => 'Digite o CEP',
                                    'size' => 8, 'maxlength' => 8,
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('Instructor/getcitybycep'),
                                        'data' => array('cep' => 'js:this.value'),
                                        'success' => "function(data){
                                    data = jQuery.parseJSON(data);
                                    if(data.UF == null) $(formIdentification+'cep').val('').trigger('focusout');
                                    $(formIdentification+'edcenso_uf_fk').val(data['UF']).trigger('change').select2('readonly',data.UF != null);
                                    setTimeout(function(){
                                    $(formIdentification+'edcenso_city_fk').val(data['City']).trigger('change').select2('readonly',data.City != null);
                                    }, 500);
                                }"
                                    )
                                )); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Valid Cep') . " " . Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '8.'; ?>"><i></i></span> -->

                                <?php echo $form->error($modelSchoolIdentification, 'cep'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <?php //@done s1 - Atualizar a lista de Orgão Regional de Educação também.
                                echo $form->dropDownList($modelSchoolIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                    'prompt' => 'Selecione um estado',
                                    'class' => 'select-search-on control-input',
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('school/updateUfDependencies'),
                                        'success' => "function(data){
                                        data = jQuery.parseJSON(data);
                                        valR = $('#SchoolIdentification_edcenso_regional_education_organ_fk').val();
                                        valC = $('#SchoolIdentification_edcenso_city_fk').val();
                                        
                                        $('#SchoolIdentification_edcenso_regional_education_organ_fk').html(data.Regional).val(valR).trigger('change');
                                        $('#SchoolIdentification_edcenso_city_fk').html(data.City).val(valC).trigger('change');
                                    }",
                                    )
                                )); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'edcenso_uf_fk'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address_neighborhood', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'address_neighborhood', array('size' => 50, 'maxlength' => 50, 'placeholder' => 'Digite o Bairro ou Povoado')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'address_neighborhood'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address_complement', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'address_complement', array('size' => 20, 'maxlength' => 20, 'placeholder' => 'Digite o Complemento')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'address_complement'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_district_fk', array('class' => 'control-label')); ?>
                                <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_district_fk', CHtml::listData(EdcensoDistrict::model()->findAllByAttributes(array('edcenso_city_fk' => $modelSchoolIdentification->edcenso_city_fk), array('order' => 'name')), 'code', 'name'), array('prompt' => 'Selecione um distrito', 'class' => 'select-search-on control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'edcenso_district_fk'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'latitude', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'latitude', array('placeholder' => 'Digite a Latitude')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'latitude'); ?>
                            </div>
                            <div class="control-group">
                                <?php //@done s1 - Tem que filtrar de acordo com o estado e cidade, no momento está listando todos 
                                ?>
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', array('class' => 'control-label')); ?>
                                <?php
                                $criteria = new CDbCriteria();
                                $criteria->select = 't.*';
                                $criteria->join =  'LEFT JOIN edcenso_city city ON city.id = t.edcenso_city_fk ';
                                $criteria->condition = 'city.edcenso_uf_fk = "' . $modelSchoolIdentification->edcenso_uf_fk . '"';
                                $criteria->order = 'name';
                                echo $form->dropDownList($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', CHtml::listData(EdcensoRegionalEducationOrgan::model()->findAll($criteria), 'code', 'name'), array('prompt' => 'Selecione o órgão', 'class' => 'select-search-on control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'edcenso_regional_education_organ_fk'); ?>
                            </div>

                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'address', array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Digite o Endereço')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'address'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address_number', array('class' => 'control-label widthfull')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'address_number', array('size' => 10, 'maxlength' => 10, 'class' => 'span2', 'placeholder' => 'Digite o Número')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'address_number'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelSchoolIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                    'prompt' => 'Selecione uma cidade',
                                    'class' => 'select-search-on control-input',
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('school/updateCityDependencies'),
                                        'success' => "function(data){
                                        data = jQuery.parseJSON(data);
                                        valD = $('#SchoolIdentification_edcenso_district_fk').val();
                                        $('#SchoolIdentification_edcenso_district_fk').html(data.District).val(valD).trigger('change');
                                    }",
                                    )
                                )); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'edcenso_city_fk'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'location', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolIdentification, 'location', array(null => 'Selecione a localização', 1 => 'Urbano', 2 => 'Rural'), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'location'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'id_difflocation', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolIdentification, 'id_difflocation', array(null => 'Selecione a localização', 1 => 'Área de assentamento', 2 => 'Terra indígena', 3 => 'Área onde se localiza a comunidade remanescente de quilombos', 7 => 'A escola não está em área diferenciada'), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'id_difflocation'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'longitude', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'longitude', array('placeholder' => 'Digite a Longitude')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'longitude'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'offer_or_linked_unity', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolIdentification, 'offer_or_linked_unity', array(null => 'Selecione a localização', 0 => 'Não', 1 => 'Unidade vinculada a escola de Educação Básica', 2 => 'Unidade ofertante de Ensino Superior'), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'offer_or_linked_unity'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3>Contato</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'ddd', array('class' => 'control-label widthfull')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'ddd', array('size' => 2, 'maxlength' => 2, 'class' => 'span2', 'placeholder' => '(__)')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'ddd'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'email', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'email', array('size' => 50, 'maxlength' => 50, 'placeholder' => 'Digite o E-mail')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'E-mail'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'email'); ?>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'phone_number', array('class' => 'control-label ')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'phone_number', array('size' => 9, 'maxlength' => 9, 'placeholder' => '_____-____')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Phone'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'phone_number'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'other_phone_number', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'other_phone_number', array('size' => 9, 'maxlength' => 9, 'placeholder' => '_____-____')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Phone'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolIdentification, 'other_phone_number'); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="school-structure">
                    <div>
                        <h3>Estrutura Física</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'classroom_count', array('class' => 'control-label required')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'classroom_count', array('placeholder' => 'Digite o Número de Salas de Aula')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'classroom_count'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_climate_roomspublic', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'dependencies_climate_roomspublic', array('placeholder' => 'Digite o Número de Salas Climatizadas')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'dependencies_climate_roomspublic'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_acessibility_roomspublic', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'dependencies_acessibility_roomspublic', array('placeholder' => 'Digite o Número de Salas com Acessibilidade')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'dependencies_acessibility_roomspublic'); ?>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'used_classroom_count', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'used_classroom_count', array('placeholder' => 'Digite o Número de Salas de Aulas em Uso')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'used_classroom_count'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_outside_roomspublic', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'dependencies_outside_roomspublic', array('placeholder' => 'Digite o Número de Salas utilizadas fora do prédio')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'dependencies_outside_roomspublic'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3>Funcionários</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'employees_count', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'employees_count', array('placeholder' => 'Digite o Total de Funcionários')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'employees_count'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_administrative_assistant', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_administrative_assistant', array('placeholder' => 'Digite o Número de Auxiliares Administrativos')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_administrative_assistant'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_service_assistant', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_service_assistant', array('placeholder' => 'Digite o Número de Auxiliares de Serviços Gerais')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_service_assistant'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_coordinator_shift', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_coordinator_shift', array('placeholder' => 'Digite o Número de Coordenadores')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span>-->
                                <?php echo $form->error($modelSchoolStructure, 'workers_coordinator_shift'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_nutritionist', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_nutritionist', array('placeholder' => 'Digite o Número de Nutricionistas')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_nutritionist'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_school_secretary', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_school_secretary', array('placeholder' => 'Digite o Número de Secretário(a)s')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_school_secretary'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_security_guards', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_security_guards', array('placeholder' => 'Digite o Número de Seguranças')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_security_guards'); ?>
                            </div>
                        </div>
                        <div class=" span7">
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_librarian', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_librarian', array('placeholder' => 'Digite o Número de Bibliotecários')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_librarian'); ?>
                            </div>

                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_firefighter', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_firefighter', array('placeholder' => 'Digite o Número de Bombeiros')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_firefighter'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_speech_therapist', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_speech_therapist', array('placeholder' => 'Digite o Número de Fonoaudiólogos')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_speech_therapist'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_psychologist', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_psychologist', array('placeholder' => 'Digite o Número de Psicólogos')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_psychologist'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_cooker', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_cooker', array('placeholder' => 'Digite o Número de Cozinheiros ou Merendeiras')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_cooker'); ?>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_support_professionals', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_support_professionals', array('placeholder' => 'Digite o Número de Profissionais de Apoio Pedagógico')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_support_professionals'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'workers_monitors', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'workers_monitors', array('placeholder' => 'Digite o Número de Monitores')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'workers_monitors'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Local de Funcionamento</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5 hide-responsive">
                            <div class="control-group">
                                <div class="t-field-checkbox-group" id="SchoolStructure_operation_location">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_building', array('value' => 1, 'uncheckValue' => 0)); ?> 
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['operation_location_building']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other_school_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other_school_room']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_barracks', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['operation_location_barracks']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_socioeducative_unity', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['operation_location_socioeducative_unity']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_prison_unity', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['operation_location_prison_unity']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'building_occupation_situation', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'building_occupation_situation', array(null => "Selecione a forma de ocupação", "1" => "Próprio", "2" => "Alugado", "3" => "Cedido"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'building_occupation_situation'); ?>
                            </div>
                        </div>
                        <div class=" span7 hide-responsive">
                            <div class="control-group hide-responsive">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'shared_building_with_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'shared_building_with_school', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['shared_building_with_school']; ?>
                                        <?php echo $form->error($modelSchoolStructure, 'shared_building_with_school'); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'shared_school_inep_id_1', array('class' => 'control-label')); ?>
                                <?php echo $form->dropDownList($modelSchoolStructure, 'shared_school_inep_id_1', CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'), array('multiple' => true, 'key' => 'inep_id', 'class' => 'select-schools control-input multiselect')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'shared_school_inep_id_1'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Dependencias</h3>
                    </div>
                    <div class="row-fluid hide-responsive dependencies-container">
                        <div class="span5">
                            <div class="control-group hide-responsive">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_warehouse', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_warehouse']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_green_area', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_green_area']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_auditorium', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_auditorium']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_prysical_disability_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox ow">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_prysical_disability_bathroom']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_child_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_child_bathroom']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_bathroom_workes', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_workes']; ?>                                        
                                        </label>    
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_bathroom_with_shower', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_with_shower']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_library', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_library']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_kitchen', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_kitchen']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_storeroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_storeroom']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_student_accomodation', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_accomodation']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructor_accomodation', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructor_accomodation']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_reading_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_reading_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructors_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructors_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_aee_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_aee_room']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_secretary_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_secretary_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_vocational_education_workshop', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_vocational_education_workshop']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_recording_and_editing_studio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_recording_and_editing_studio']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_none']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="span7 hide-responsive">
                            <div class="control-group hide-responsive">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_science_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_science_lab']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_info_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_info_lab']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_professional_specific_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_professional_specific_lab']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_playground', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_playground']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_covered_patio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_covered_patio']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_uncovered_patio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_uncovered_patio']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_pool', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_pool']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_indoor_sports_court', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_indoor_sports_court']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_outdoor_sports_court', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_outdoor_sports_court']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_refectory', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_refectory']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_student_repose_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_repose_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_arts_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_arts_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_music_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_music_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_dance_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_dance_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_multiuse_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_multiuse_room']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_yardzao', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_yardzao']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_vivarium', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_vivarium']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_principal_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['dependencies_principal_room']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid  hide-responsive">
                        <div class="span5">
                            <div>
                                <h3 class="titulos required">Suprimento de água</h3>
                            </div>
                            <div class="control-group hide-responsive water-supply-container">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['water_supply_public']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_artesian_well', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['water_supply_artesian_well']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_well'); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['water_supply_well']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_river', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['water_supply_river']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['water_supply_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Potable Water'); ?></label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'provide_potable_water', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['provide_potable_water']; ?>    
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="span7">
                            <div>
                                <h3 class="titulos required">Suprimento de alimento</h3>
                            </div>
                            <div class="control-group">
                                <!-- <div class="controls">
                                    <label class="control-label"><?php echo Yii::t('default', 'Food Supply'); ?></label>
                                </div> -->
                                <div class="">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'supply_food', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['supply_food']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'feeding', array('class' => 'control-label required indicator')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'feeding', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Oferece"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'feeding'); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Suprimento de Energia</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group hide-responsive energy-supply-container">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_public']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_generator', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_generator_alternative', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator_alternative']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'ppp_updated', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'ppp_updated', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['ppp_updated']; ?>    
                                        <?php echo $form->error($modelSchoolStructure, 'ppp_updated'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'website', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'website', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['website']; ?>    
                                        <?php echo $form->error($modelSchoolStructure, 'website'); ?>
                                    </label>
                                </div>
                            </div>
                            <!-- Acessability -->
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Esgoto</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group hide-responsive sewage-container">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sewage_public']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_fossa', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_fossa_common', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa_common']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sewage_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group hide-responsive">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'space_schoolenviroment', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'space_schoolenviroment', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['space_schoolenviroment']; ?>    
                                        <?php echo $form->error($modelSchoolStructure, 'space_schoolenviroment'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'community_integration', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'community_integration', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['community_integration']; ?>    
                                        <?php echo $form->error($modelSchoolStructure, 'community_integration'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Destino do Lixo</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group hide-responsive garbage_destination_container">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_collect', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_collect']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_burn', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_burn']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_bury', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_bury']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_public']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_throw_away', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_throw_away']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="span7">
                            </div> -->
                    </div>
                    <div>
                        <h3 class="titulos required">Tratamento do Lixo</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group hide-responsive garbage-treatment-container">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'treatment_garbage_parting_garbage', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['treatment_garbage_parting_garbage']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'treatment_garbage_resuse', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['treatment_garbage_resuse']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_recycle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_recycle']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'traetment_garbage_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['traetment_garbage_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="span7">
                            </div> -->
                    </div>
                    <div>
                        <h3 class="titulos required">Acessibilidade</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5 accessbility-container">
                            <div class=" control-group">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_handrails_guardrails', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_handrails_guardrails']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_elevator', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_elevator']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_tactile_floor', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_tactile_floor']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_doors_80cm', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_doors_80cm']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_ramps', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_ramps']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_sound_signaling', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_sound_signaling']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_tactile_singnaling', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_tactile_singnaling']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessability_visual_signaling', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessability_visual_signaling']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'acessabilty_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['acessabilty_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="span7">
                            </div> -->
                    </div>
                    <div>
                        <h3 class="titulos required">Órgãos em Funcionamento na Escola</h3>
                    </div>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group hide-responsive board-organ-container">
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_association_parent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['board_organ_association_parent']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_association_parentinstructors', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['board_organ_association_parentinstructors']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_board_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['board_organ_board_school']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_student_guild', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['board_organ_student_guild']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_others', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['board_organ_others']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'board_organ_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['board_organ_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="span7">
                            </div> -->
                    </div>
                    <!-- <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'ppp_updated', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'ppp_updated', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'ppp_updated'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'website', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'website', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'website'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'space_schoolenviroment', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'space_schoolenviroment', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'space_schoolenviroment'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx($modelSchoolStructure, 'community_integration', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'community_integration', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'community_integration'); ?>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="tab-pane" id="school-manager">
                    <div>
                        <h3>Dados do Gestor</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'name', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'name', array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Digite o Nome do Gestor')); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full name of school manager'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelManagerIdentification, 'name'); ?>
                            </div>
                            <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'birthday_date', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'birthday_date', array('size' => 10, 'maxlength' => 10)); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full name of school manager'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelManagerIdentification, 'birthday_date'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'sex', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php echo $form->DropDownList($modelManagerIdentification, 'sex', array(null => "Selecione o sexo", "1" => "Masculino", "2" => "Feminino"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelManagerIdentification, 'sex'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'nationality', array('class' => 'control-label')); ?>
                                <?php
                                echo $form->dropDownList($modelManagerIdentification, 'nationality', array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"), array('class' => 'select-search-off control-input'), array('ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('student/getnations'),
                                    'update' => '#ManagerIdentification_edcenso_nation_fk'
                                )));
                                ?>
                                <?php echo $form->error($modelManagerIdentification, 'nationality'); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Estado</label>
                                <?php
                                echo $form->dropDownList($modelManagerIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'),array('class' => 'select-search-off control-input',"disabled" => "disabled","prompt" => "Selecione uma cidade",));
                                ?>
                                <?php echo $form->error($modelManagerIdentification, 'edcenso_uf_fk'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'number_ato', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelManagerIdentification, 'number_ato', array( 'placeholder' => 'Digite o número ato')); ?>
                                <?php echo $form->error($modelManagerIdentification, 'number_ato'); ?>
                            </div>
            
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'role', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelManagerIdentification, 'role', array(null => "Selecione o cargo", "1" => "Diretor", "2" => "Outro Cargo"), array('class' => 'select-search-off control-input')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Role of the school manager'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelManagerIdentification, 'role'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'filiation', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelManagerIdentification, 'filiation', array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelManagerIdentification, 'filiation'); ?>
                            </div>
                            <div class="manager-filiation-container" style="display: none;">
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_1', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_1', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite o Nome Completo da filiação 1')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_1'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_1_cpf', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_1_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite o CPF da filiação 1')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_1_cpf'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_1_rg', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_1_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite o RG da filiação 1')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_1_rg'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_1_scholarity', array('class' => 'control-label')); ?>
                                    <?php
                                    echo $form->dropDownList($modelManagerIdentification, 'filiation_1_scholarity', array(
                                        null => "Selecione a escolaridade da filiação 1",
                                        0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                        3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                        6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                    ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear control-input'));
                                    ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_1_scholarity'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_1_job', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_1_job', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite a Profissão da filiação 1')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_1_job'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'residence_zone', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelManagerIdentification, 'residence_zone', array(null => "Selecione uma zona", "1" => "URBANA", "2" => "RURAL"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelManagerIdentification, 'residence_zone'); ?>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'cpf', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'cpf', array('size' => 60, 'maxlength' => 14, 'placeholder' => 'Digite o CPF do Gestor')); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'CPF school manager. Numbers only.'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelManagerIdentification, 'cpf'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'color_race', array('class' => 'control-label')); ?>
                                <?php
                                echo $form->DropDownList($modelManagerIdentification, 'color_race', array(
                                    null => "Selecione a cor/raça",
                                    "0" => "Não declarada",
                                    "1" => "Branca",
                                    "2" => "Preta",
                                    "3" => "Parda",
                                    "4" => "Amarela",
                                    "5" => "Indígena"
                                ), array('class' => 'select-search-off control-input'));
                                ?>
                                <?php echo $form->error($modelManagerIdentification, 'color_race'); ?>
                            </div>
                            <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'email', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'email', array('size' => 50, 'maxlength' => 50, 'placeholder' => 'Digite o E-mail do Gestor')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'email'); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label">País de Origem</label>
                                <?php
                                echo $form->dropDownList($modelManagerIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma nação", 'class' => 'select-search-on nationality-sensitive no-br t-input__text control-input', 'disabled' => 'disabled'));
                                ?>
                                <?php echo $form->error($modelManagerIdentification, 'edcenso_nation_fk'); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Cidade</label>
                                <?php
                                echo $form->dropDownList($modelManagerIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelManagerIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                    "prompt" => "Selecione uma cidade",
                                    "disabled" => "disabled",
                                    'class' => 'select-search-on nationality-sensitive br control-input',
                                ));
                                ?>
                                <?php echo $form->error($modelManagerIdentification, 'edcenso_city_fk'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'contract_type', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelManagerIdentification, 'contract_type', array(null => "Selecione o vínculo", "1" => "Concursado/Efetivo", "2" => "Temporário", "3" => "Terceirizado", "4" => "CLT"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelManagerIdentification, 'contract_type'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelManagerIdentification, 'access_criterion', array('class' => 'control-label')); ?>
                                <?php echo $form->textArea($modelManagerIdentification, 'access_criterion', array('placeholder' => 'Digite a Especificação do Critério de Acesso')); ?>
                                <?php echo $form->error($modelManagerIdentification, 'access_criterion'); ?>
                            </div>
                            <div class="control-group" style="height: 60px;"></div>
                            <div class="manager-filiation-container"  style="display: none;">
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_2', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_2', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite o Nome Completo da filiação 2')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_2'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_2_cpf', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_2_cpf', array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite o CPF da filiação 2')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_2_cpf'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_2_rg', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_2_rg', array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite o RG da filiação 2')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_2_rg'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_2_scholarity', array('class' => 'control-label')); ?>
                                    <?php
                                    echo $form->dropDownList($modelManagerIdentification, 'filiation_2_scholarity', array(
                                        null => "Selecione a escolaridade da filiação 2",
                                        0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                        3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                        6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'
                                    ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear control-input'));
                                    ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_2_scholarity'); ?>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelManagerIdentification, 'filiation_2_job', array('class' => 'control-label')); ?>
                                    <?php echo $form->textField($modelManagerIdentification, 'filiation_2_job', array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear control-input", 'placeholder' => 'Digite a Profissão da filiação 2')); ?>
                                    <?php echo $form->error($modelManagerIdentification, 'filiation_2_job'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="school-equipment">
                    <div>
                        <h3>Eletrônicos</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_dvd', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_dvd', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de DVDs')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_dvd'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_stereo_system', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_stereo_system', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Aparelhos de Som')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_stereo_system'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_tv', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_tv', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Tvs')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_tv'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_blackboard', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_blackboard', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Quadros Negros')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_blackboard'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_overhead_projector', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_overhead_projector', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Retroprojetores')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_overhead_projector'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_desktop', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_desktop', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Computadores de Mesa (Desktop)')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_desktop'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_tabletstudent', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_tabletstudent', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Tablets de Uso Estudantil')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_tabletstudent'); ?>
                            </div>
                            <div class=" span12">
                                <div class="control-group hide-responsive equipments-container">
                                    <label class="control-label required"><?php echo Yii::t('default', 'Existing equipment at the school for technical and administrative use'); ?>
                                        *</label>
                                    <div class="t-field-checkbox-group">
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_satellite_dish', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_satellite_dish']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_computer', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_computer']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_copier', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_copier']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_printer', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_printer']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_multifunctional_printer', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_multifunctional_printer']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_scanner', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_scanner']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_inexistent']; ?>    
                                            </label>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="control-group equipments-material-container">
                                    <label class="control-label required"><?php echo Yii::t('default', 'Material, sociocultural and/or pedagogical instruments in use at school for the development of teaching and learning activities'); ?> *</label>
                                    <div class="t-field-checkbox-group">
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_multimedia_collection', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_multimedia_collection']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_toys_early', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_toys_early']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_scientific_materials', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_scientific_materials']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_equipment_amplification', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_equipment_amplification']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_musical_instruments', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_musical_instruments']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_educational_games', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_educational_games']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_cultural', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_cultural']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_cultural', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_professional_education']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_sports', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_sports']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingdeafs', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingdeafs']; ?>
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingindian', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingindian']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">  
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingethnic', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingethnic']; ?>    
                                            </label>
                                        </div>
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'equipments_material_teachingrural', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingrural']; ?>    
                                            </label>
                                        </div>  
                                        <div class="t-field-checkbox">
                                            <?php echo $form->checkBox($modelSchoolStructure, 'instruments_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            <label class="t-field-checkbox">
                                                <?php echo SchoolStructure::model()->attributeLabels()['instruments_inexistent']; ?>    
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" span7">
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_vcr', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_vcr', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de VCRs')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_vcr'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_data_show', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_data_show', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de DataShows')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_data_show'); ?>
                            </div>
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_fax', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_fax', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Faxs')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_fax'); ?>
                            </div>

                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_camera', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_camera', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Máquinas Fotográficas')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_camera'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'administrative_computers_count', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'administrative_computers_count', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Computadores de Uso Administrativo')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'administrative_computers_count'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'student_computers_count', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'student_computers_count', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Computadores de Uso Infantil')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'student_computers_count'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_qtd_notebookstudent', array('class' => 'control-label')); ?>
                                <?php echo $form->textField($modelSchoolStructure, 'equipments_qtd_notebookstudent', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input', 'placeholder' => 'Digite o Número de Notebooks de Uso Estudantil')); ?>
                                <!-- <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span> -->
                                <?php echo $form->error($modelSchoolStructure, 'equipments_qtd_notebookstudent'); ?>
                            </div>


                        </div>
                        <div class=" span7">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'bandwidth', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'bandwidth', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['bandwidth']; ?>    
                                        <?php echo $form->error($modelSchoolStructure, 'bandwidth'); ?>
                                    </label>
                                </div>
                                
                            </div>

                            <div class="control-group internet-access-container">
                                <label class="control-label required"><?php echo Yii::t('default', 'Internet Access'); ?> *</label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_administrative', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_administrative']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_educative_process', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_educative_process']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_student', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_student']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_community', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_community']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Internet Access Connected Devices'); ?></label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_connected_desktop', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_connected_desktop']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_connected_personaldevice', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_connected_personaldevice']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_broadband', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_broadband']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group internet-access-local-container">
                                <label class="control-label required"><?php echo Yii::t('default', 'Internet Access Local'); ?> *</label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_local_cable', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_cable']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_local_wireless', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_wireless']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'internet_access_local_inexistet', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_inexistet']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-education">
                    <div>
                        <h3>Eletrônicos</h3>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'aee', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'aee', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Não exclusivamente", "2" => "Exclusivamente"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'aee'); ?>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'complementary_activities', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'complementary_activities', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Não exclusivamente", "2" => "Exclusivamente"), array('class' => 'select-search-off control-input')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'complementary_activities'); ?>
                            </div>

                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'basic_education_cycle_organized', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'basic_education_cycle_organized', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['basic_education_cycle_organized']; ?>        
                                        <?php echo $form->error($modelSchoolStructure, 'basic_education_cycle_organized'); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'different_location', array('class' => 'control-label')); ?>
                                <?php
                                echo $form->DropDownList($modelSchoolStructure, 'different_location', array(
                                    null => "Selecione a localização",
                                    "1" => "Área de assentamento",
                                    "2" => "Terra indígena",
                                    "3" => "Área remanescente de quilombos",
                                    "4" => "Unidade de uso sustentável",
                                    "5" => "Unidade de uso sustentável em terra indígena",
                                    "6" => "Unidade de uso sustentável em área remanescente de quilombos",
                                    "7" => "Não se aplica",
                                ), array('class' => 'select-search-off control-input'));
                                ?>
                                <?php echo $form->error($modelSchoolStructure, 'different_location'); ?>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Sociocultural Didactic Material'); ?></label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_none']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_quilombola', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_quilombola']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_native', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_native']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'native_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'native_education', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['native_education']; ?>
                                        <?php echo $form->error($modelSchoolStructure, 'native_education'); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group" id="native_education_language">
                                <label class="control-label"><?php echo Yii::t('default', 'Native Education Language'); ?></label>
                                <div id="native_education_lenguage_none">
                                    <?php echo CHtml::activeHiddenField($modelSchoolStructure, 'native_education_language_native', array('value' => null, 'disabled' => 'disabled'));
                                    echo CHtml::activeHiddenField($modelSchoolStructure, 'native_education_language_portuguese', array('value' => null, 'disabled' => 'disabled')); ?>
                                </div>
                                <div class="t-field-checkbox-group" id="native_education_lenguage_some">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_native', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_native']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_portuguese', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_portuguese']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk', CHtml::listData(EdcensoNativeLanguages::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione a língua indígena", 'class' => 'select-search-on control-input'));
                                ?>
                                <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk'); ?>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk2', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk2', CHtml::listData(EdcensoNativeLanguages::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione a língua indígena", 'class' => 'select-search-on control-input'));
                                ?>
                                <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk2'); ?>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk3', array('class' => 'control-label')); ?>
                                <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk3', CHtml::listData(EdcensoNativeLanguages::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione a língua indígena", 'class' => 'select-search-on control-input'));
                                ?>
                                <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk3'); ?>
                            </div>

                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'brazil_literate', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'brazil_literate', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['brazil_literate']; ?>    
                                        <?php echo $form->error($modelSchoolStructure, 'brazil_literate'); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'open_weekend', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'open_weekend', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['open_weekend']; ?>        
                                        <?php echo $form->error($modelSchoolStructure, 'open_weekend'); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'pedagogical_formation_by_alternance', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <!-- <?php echo $form->labelEx($modelSchoolStructure, 'pedagogical_formation_by_alternance', array('class' => 'control-label')); ?> -->
                                        <?php echo SchoolStructure::model()->attributeLabels()['pedagogical_formation_by_alternance']; ?>        
                                        <?php echo $form->error($modelSchoolStructure, 'pedagogical_formation_by_alternance'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Modalities'); ?></label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_regular', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['modalities_regular']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_especial', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['modalities_especial']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_eja', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['modalities_eja']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_professional', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['modalities_professional']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>
                                    
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Organization of Education'); ?></label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_series_year', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_series_year']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_semester_periods', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_semester_periods']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_elementary_cycle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_elementary_cycle']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_non_serialgroups', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_non_serialgroups']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_modules', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_modules']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'org_teaching_regular_alternation', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_regular_alternation']; ?>    
                                        </label>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Selection Exam'); ?></label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'select_adimission', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['select_adimission']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group booking-container">
                                    <label class="control-label"><?php echo Yii::t('default', 'Reservation by Quota System'); ?></label>
                                <div class="t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_self_declaredskin', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_self_declaredskin']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_income', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_income']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_public_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_public_school']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_disabled_person', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_disabled_person']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_others', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_others']; ?>    
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($modelSchoolStructure, 'booking_enrollment_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_inexistent']; ?>    
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'stages_concept_grades', array('class' => 'control-label')); ?>
                                <?php echo $form->dropDownList($modelSchoolStructure, 'stages_concept_grades', CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'), array('multiple' => true, 'prompt' => 'Selecione o estágio vs modalidade', 'class' => 'select-search-on t-multiselect control-input multiselect')); ?>
                                <?php echo $form->error($modelSchoolStructure, 'stages_concept_grades'); ?>
                                <div class="add-stages-options">Adicionar: <span class="add-fundamental-menor">Fundamental Menor</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-reports">
                    <container>
                        <row class="reports">
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/reportsMonthlyTransaction', array('id' => $modelSchoolIdentification->inep_id, 'type' => 1)); ?>">
                                <span class="t-icon-printer"></span>   
                                <!-- <img  alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                    Movimentação Mensal Anos Iniciais
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/reportsMonthlyTransaction', array('id' => $modelSchoolIdentification->inep_id, 'type' => 2)); ?>">
                                    
                                <!-- <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                <span class="t-icon-printer"></span>
                                    Movimentação Mensal Anos Finais
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/reportsMonthlyTransaction', array('id' => $modelSchoolIdentification->inep_id, 'type' => 3)); ?>">
                                <span class="t-icon-printer"></span>    
                                <!-- <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                    Movimentação Mensal Educação Infantil
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/reports', array('id' => $modelSchoolIdentification->inep_id)); ?>">
                                <span class="t-icon-printer"></span>    
                                <!-- <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                    Resumo Mensal de Frequência
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/record', array('id' => $modelSchoolIdentification->inep_id, 'type' => 1)); ?>">
                                <span class="t-icon-printer"></span>
                                <!-- <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                    Histórico Ensino Regular
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/record', array('id' => $modelSchoolIdentification->inep_id, 'type' => 2)); ?>">
                                <span class="t-icon-printer"></span>    
                                <!-- <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> -->
                                    Histórico Ensino EJA
                                </a>
                            </div>
                        </row>
                    </container>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
</div>

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
    $this->widget('application.widgets.AlertCensoWidget', array('prefix' => 'scholl', 'dataId' => $_GET['id']));
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