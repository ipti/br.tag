<?php

/**
 * @var CActiveForm $this CActiveForm
 * @var $modelSchoolIdentification SchoolIdentification
 */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/school/form/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/school/form/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', [
    'id' => 'school',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

<div class="mobile-row ">
    <div class="column clearleft">
        <h1><?php echo $title; ?></h1>
    </div>
    <div class="column clearfix align-items--center justify-content--end show--desktop">
        <a data-toggle="tab" class='hide-responsive t-button-secondary prev'
            style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
        <?= $modelSchoolIdentification->isNewRecord ? "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . '</a>' : '' ?>
        <button class="t-button-primary  last save-school-button" type="button">
            <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
        </button>
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
                <li id="tab-school-indentify" class="active t-tabs__item">
                    <a class="t-tabs__link first" href="#school-indentify" data-toggle="tab">
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

                <li id="tab-school-structure" class="t-tabs__item"><a class="t-tabs__link" href="#school-structure"
                        data-toggle="tab">
                        <span class="t-tabs__numeration">3</span>
                        <?php echo Yii::t('default', 'Structure') ?>

                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-equipment" class="t-tabs__item"><a class="t-tabs__link" href="#school-equipment"
                        data-toggle="tab">
                        <span class="t-tabs__numeration">4</span>
                        <?php echo Yii::t('default', 'Equipments') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-manager" class="t-tabs__item"><a class="t-tabs__link" href="#school-manager"
                        data-toggle="tab">
                        <span class="t-tabs__numeration">5</span>
                        <?php echo Yii::t('default', 'manager') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-school-education" class="t-tabs__item"><a class="t-tabs__link" href="#school-education"
                        data-toggle="tab">
                        <span class="t-tabs__numeration">6</span>
                        <?php echo Yii::t('default', 'Educational Data') ?>
                    </a>
                    <?php if (!$modelSchoolIdentification->isNewRecord): ?>
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                    <?php endif ?>
                </li>
                <?php if (!$modelSchoolIdentification->isNewRecord): ?>
                    <li id="tab-school-reports" class="t-tabs__item hide-responsive">

                        <a class="t-tabs__link" href="#school-reports" data-toggle="tab">
                            <span class="t-tabs__numeration">7</span>
                            <?php echo Yii::t('default', 'Relatórios') ?>
                        </a>

                    </li>
                <?php endif ?>
            </ul>
        </div>
        <div class="widget-body form-school form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="school-indentify">
                    <div>
                        <h3>Dados Básicos</h3>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
    $modelSchoolIdentification,
    'name',
    ['class' => 't-field-text__label--required']
); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'name',
                                    [
                                        'size' => 100,
                                        'maxlength' => 100,
                                        'placeholder' => 'Digite o Nome da Escola',
                                        'class' => 't-field-text__input',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'name'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'inep_id',
                                    ['class' => 't-field-text__label--required']
                                ); ?>
                                <?php
                                echo
                                    $modelSchoolIdentification->isNewRecord ?
                                    $form->textField(
                                        $modelSchoolIdentification,
                                        'inep_id',
                                        [
                                            'size' => 8,
                                            'maxlength' => 8,
                                            'placeholder' => 'Digite o Código INEP',
                                            'class' => 't-field-text__input'
                                        ]
                                    )
                                    : $form->textField(
                                        $modelSchoolIdentification,
                                        'inep_id',
                                        [
                                            'size' => 8,
                                            '
                                            maxlength' => 8,
                                            'placeholder' => 'Digite o Código INEP',
                                            'class' => 't-field-text__input',
                                            'disabled' => 'disabled'
                                        ]
                                    ) ?>
                                <?php echo $form->error(
                                        $modelSchoolIdentification,
                                        'inep_id'
                                    ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'administrative_dependence',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->dropDownList(
                                    $modelSchoolIdentification,
                                    'administrative_dependence',
                                    [
                                        null => 'Selecione a dependencia administrativa',
                                        1 => 'Federal',
                                        2 => 'Estadual',
                                        3 => 'Municipal',
                                        4 => 'Privada'
                                    ],
                                    [
                                        'class' => 'select-search-off t-field-select__input select2-container',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'administrative_dependence'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'situation',
                                    ['class' => 't-field-select__label']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolIdentification,
                                    'situation',
                                    [
                                        null => 'Selecione a situação',
                                        1 => 'Em Atividade',
                                        2 => 'Paralisada',
                                        3 => 'Extinta'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'situation'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-tarea">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'act_of_acknowledgement',
                                    ['class' => 't-field-tarea__label']
                                ); ?>
                                <?php echo $form->textArea(
                                    $modelSchoolIdentification,
                                    'act_of_acknowledgement',
                                    [
                                        'placeholder' => 'Digite o Ato de Reconhecimento',
                                        'class' => 't-field-tarea__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'act_of_acknowledgement'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'Inicio do período letivo',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php
                                $options = DatePickerWidget::renderDatePicker($modelSchoolIdentification, 'initial_date');
$options['htmlOptions'] = array_merge(isset($options['htmlOptions']) ? $options['htmlOptions'] : [], ['style' => 'background-color: #fff;']);
$this->widget('zii.widgets.jui.CJuiDatePicker', $options);
echo CHtml::link('	Limpar', '#', [
    'id' => 'initial_reset'
]);
echo $form->error($modelSchoolIdentification, 'initial_date');
?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
    $modelSchoolIdentification,
    'Final do período letivo',
    ['class' => 't-field-text__label']
); ?>
                                <?php
                                                                                                $options = DatePickerWidget::renderDatePickerFinal($modelSchoolIdentification, 'final_date');
$options['htmlOptions'] = array_merge(isset($options['htmlOptions']) ? $options['htmlOptions'] : [], ['style' => 'background-color: #fff;']);
$this->widget('zii.widgets.jui.CJuiDatePicker', $options);

echo CHtml::link('Limpar', '#', [
    'id' => 'final_reset'
]);
echo $form->error($modelSchoolIdentification, 'final_date');
?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
    $modelSchoolIdentification,
    'regulation',
    ['class' => 't-field-select__label--required']
); ?>
                                <?php echo $form->dropDownList(
                                    $modelSchoolIdentification,
                                    'regulation',
                                    [
                                        null => 'Selecione a situação de regulamentação',
                                        0 => 'Não',
                                        1 => 'Sim',
                                        2 => 'Em tramitação'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'regulation'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'logo_file_content',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <button class="btn btn-icon glyphicons upload upload-logo-button" type="button">
                                    <i></i>Anexar
                                </button>
                                <span
                                    class="uploaded-logo-name"><?php echo $modelSchoolIdentification->logo_file_name !== null ?
                                        $modelSchoolIdentification->logo_file_name . '<a href="' . Yii::app()->controller->createUrl('school/removeLogo', ['id' => $modelSchoolIdentification->inep_id]) . '" class="deleteTeachingData" title="Excluir"></a>' : '' ?>
                                </span>
                                <?php echo $form->fileField(
                                            $modelSchoolIdentification,
                                            'logo_file_content'
                                        ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'logo_file_content'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Órgãos que a escola está vinculada</h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox-group clear-margin--top" id="SchoolIdentification_linked_organ">
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'linked_mec',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_mec']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'linked_army',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_army']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'linked_helth',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_helth']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'linked_other',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['linked_other']; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Esfera do Órgão regulador</h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox-group clear-margin--top"
                            id="SchoolIdentification_regulation_organ">
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'regulation_organ_federal',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_federal']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'regulation_organ_state',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_state']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?= $form->checkBox(
                                    $modelSchoolIdentification,
                                    'regulation_organ_municipal',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?= SchoolIdentification::model()->attributeLabels()['regulation_organ_municipal']; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3>Outras informações</h3>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'private_school_organization_civil_society',
                                    ['class' => 't-field-select__label']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolIdentification,
                                    'private_school_organization_civil_society',
                                    [null => 'Selecione', 0 => 'Não', 1 => 'Sim'],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'private_school_organization_civil_society'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'ies_code',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'ies_code',
                                    ['placeholder' => 'Digite o Código da IES', 'class' => 't-field-text__input']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'ies_code'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'inep_head_school',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'inep_head_school',
                                    ['placeholder' => 'Digite o Código da Escola Sede', 'class' => 't-field-text__input']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'inep_head_school'
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-addressContact">
                    <div>
                        <h3>Endereço</h3>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'cep',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'cep',
                                    [
                                        'placeholder' => 'Digite o CEP',
                                        'size' => 8,
                                        'maxlength' => 8,
                                        'class' => 't-field-text__input',
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('Instructor/getcitybycep'),
                                            'data' => ['cep' => 'js:this.value'],
                                            'success' => "function(data){
                                    data = jQuery.parseJSON(data);
                                    if(data.UF === null) $(formIdentification+'cep').val('').trigger('focusout');
                                    $(formIdentification+'edcenso_uf_fk').val(data['UF']).trigger('change').select2('readonly',data.UF !== null);
                                    setTimeout(function(){
                                    $(formIdentification+'edcenso_city_fk').val(data['City']).trigger('change').select2('readonly',data.City !== null);
                                    }, 500);
                                }"
                                        ],
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'cep'); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'address',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'address',
                                    [
                                        'size' => 60,
                                        'maxlength' => 100,
                                        'placeholder' => 'Digite o Endereço',
                                        'class' => 't-field-text__input',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'address'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'edcenso_uf_fk',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php //@done s1 - Atualizar a lista de Orgão Regional de Educação também.
                                echo $form->dropDownList(
                                    $modelSchoolIdentification,
                                    'edcenso_uf_fk',
                                    CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'),
                                    [
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on t-field-select__input select2-container',
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
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'edcenso_uf_fk'); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'address_number',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'address_number',
                                    [
                                        'size' => 10,
                                        'maxlength' => 10,
                                        'class' => 't-field-text__input',
                                        'placeholder' => 'Digite o Número',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'address_number'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'address_neighborhood',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'address_neighborhood',
                                    [
                                        'size' => 50,
                                        'maxlength' => 50,
                                        'placeholder' => 'Digite o Bairro ou Povoado',
                                        'class' => 't-field-text__input',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'address_neighborhood'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'edcenso_city_fk',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->dropDownList(
                                    $modelSchoolIdentification,
                                    'edcenso_city_fk',
                                    CHtml::listData(EdcensoCity::model()->findAllByAttributes([
                                        'edcenso_uf_fk' => $modelSchoolIdentification->edcenso_uf_fk
                                    ], ['order' => 'name']), 'id', 'name'),
                                    [
                                        'prompt' => 'Selecione uma cidade',
                                        'class' => 'select-search-on t-field-select__input select2-container',
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('school/updateCityDependencies'),
                                            'success' => "function(data){
                                        data = jQuery.parseJSON(data);
                                        valD = $('#SchoolIdentification_edcenso_district_fk').val();
                                        $('#SchoolIdentification_edcenso_district_fk').html(data.District).val(valD).trigger('change');
                                    }",
                                        ],
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'edcenso_city_fk'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'address_complement',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'address_complement',
                                    [
                                        'size' => 20,
                                        'maxlength' => 20,
                                        'placeholder' => 'Digite o Complemento',
                                        'class' => 't-field-text__input',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'address_complement'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'location',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolIdentification,
                                    'location',
                                    [null => 'Selecione a localização', 1 => 'Urbano', 2 => 'Rural'],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'location'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'edcenso_district_fk',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->dropDownList(
                                    $modelSchoolIdentification,
                                    'edcenso_district_fk',
                                    CHtml::listData(EdcensoDistrict::model()->findAllByAttributes([
                                        'edcenso_city_fk' => $modelSchoolIdentification->edcenso_city_fk
                                    ], ['order' => 'name']), 'code', 'name'),
                                    [
                                        'prompt' => 'Selecione um distrito',
                                        'class' => 'select-search-on t-field-select__input select2-container',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'edcenso_district_fk'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'id_difflocation',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolIdentification,
                                    'id_difflocation',
                                    [
                                        null => 'Selecione a localização',
                                        1 => 'Área de assentamento',
                                        2 => 'Terra indígena',
                                        3 => 'Comunidade quilombola',
                                        7 => 'A escola não está em área diferenciada',
                                        8 => 'Área onde se localizam povos e comunidades tradicionais'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'id_difflocation'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'latitude',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'latitude',
                                    [
                                        'placeholder' => 'Digite a Latitude',
                                        'class' => 't-field-text__input',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'latitude'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'longitude',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'longitude',
                                    [
                                        'placeholder' => 'Digite a Longitude',
                                        'class' => 't-field-text__input',
                                        'disabled' => $disabledFields
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'longitude'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php //@done s1 - Tem que filtrar de acordo com o estado e cidade, no momento está listando todos
                                ?>
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'edcenso_regional_education_organ_fk',
                                    ['class' => 't-field-select__label']
                                ); ?>
                                <?php
                                $criteria = new CDbCriteria();
$criteria->select = 't.*';
$criteria->join = 'LEFT JOIN edcenso_city city ON city.id = t.edcenso_city_fk ';
$criteria->condition = 'city.edcenso_uf_fk = "' . $modelSchoolIdentification->edcenso_uf_fk . '"';
$criteria->order = 'name';
echo $form->dropDownList(
    $modelSchoolIdentification,
    'edcenso_regional_education_organ_fk',
    CHtml::listData(EdcensoRegionalEducationOrgan::model()->findAll($criteria), 'code', 'name'),
    ['prompt' => 'Selecione o órgão', 'class' => 'select-search-on t-field-select__input select2-container']
); ?>
                                <?php echo $form->error(
    $modelSchoolIdentification,
    'edcenso_regional_education_organ_fk'
); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'offer_or_linked_unity',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolIdentification,
                                    'offer_or_linked_unity',
                                    [
                                        null => 'Selecione a localização',
                                        0 => 'Não',
                                        1 => 'Unidade vinculada a escola de Educação Básica',
                                        2 => 'Unidade ofertante de Ensino Superior'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'offer_or_linked_unity'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3>Contato</h3>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label($modelSchoolIdentification, 'ddd', [
                                    'class' => 't-field-text__label'
                                ]); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'ddd', [
                                    'size' => 2,
                                    'maxlength' => 2,
                                    'class' => 't-field-text__input',
                                    'placeholder' => '(__)'
                                ]); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'ddd'); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label($modelSchoolIdentification, 'phone_number', [
                                    'class' => 't-field-text__label'
                                ]); ?>
                                <?php echo $form->textField($modelSchoolIdentification, 'phone_number', [
                                    'size' => 9,
                                    'maxlength' => 9,
                                    'class' => 't-field-text__input',
                                    'placeholder' => '_____-____'
                                ]); ?>
                                <?php echo $form->error($modelSchoolIdentification, 'phone_number'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'email',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'email',
                                    [
                                        'size' => 50,
                                        'maxlength' => 50,
                                        'placeholder' => 'Digite o E-mail',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'email'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolIdentification,
                                    'other_phone_number',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolIdentification,
                                    'other_phone_number',
                                    [
                                        'size' => 9,
                                        'maxlength' => 9,
                                        'placeholder' => '_____-____',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolIdentification,
                                    'other_phone_number'
                                ); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="school-structure">
                    <div>
                        <h3>Estrutura Física</h3>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'classroom_count',
                                    ['class' => 't-field-text__label--required']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'classroom_count',
                                    [
                                        'placeholder' => 'Digite o Número de Salas de Aula',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'classroom_count'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'used_classroom_count',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'used_classroom_count',
                                    [
                                        'placeholder' => 'Digite o Número de Salas de Aulas em Uso',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'used_classroom_count'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'dependencies_climate_roomspublic',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'dependencies_climate_roomspublic',
                                    [
                                        'placeholder' => 'Digite o Número de Salas Climatizadas',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'dependencies_climate_roomspublic'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'dependencies_outside_roomspublic',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'dependencies_outside_roomspublic',
                                    [
                                        'placeholder' => 'Digite o Número de Salas utilizadas fora do prédio',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolStructure, 'dependencies_outside_roomspublic'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'dependencies_acessibility_roomspublic',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'dependencies_acessibility_roomspublic',
                                    [
                                        'placeholder' => 'Digite o Número de Salas com Acessibilidade',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'dependencies_acessibility_roomspublic'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'dependencies_reading_corners',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'dependencies_reading_corners',
                                    [
                                        'placeholder' => 'Digite o Número de salas de leitura',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolStructure, 'dependencies_outside_roomspublic'); ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3>Funcionários</h3>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'employees_count',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'employees_count',
                                    [
                                        'placeholder' => 'Digite o Total de Funcionários',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'employees_count'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_librarian',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_librarian',
                                    [
                                        'placeholder' => 'Digite o Número de Bibliotecários',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_librarian'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_garden_planting_agricultural',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_garden_planting_agricultural',
                                    [
                                        'placeholder' => 'Digite o Número de Técnicos em Horta/Plantio/Agricultura',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_garden_planting_agricultural'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_administrative_assistant',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_administrative_assistant',
                                    [
                                        'placeholder' => 'Digite o Número de Auxiliares Administrativos',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_administrative_assistant'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_firefighter',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_firefighter',
                                    [
                                        'placeholder' => 'Digite o Número de Bombeiros',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_firefighter'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_service_assistant',
                                    [
                                        'class' => 't-field-text__label'
                                    ]
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_service_assistant',
                                    [
                                        'placeholder' => 'Digite o Número de Auxiliares de Serviços Gerais',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_service_assistant'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_speech_therapist',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_speech_therapist',
                                    [
                                        'placeholder' => 'Digite o Número de Fonoaudiólogos',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_speech_therapist'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_coordinator_shift',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_coordinator_shift',
                                    [
                                        'placeholder' => 'Digite o Número de Coordenadores',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_coordinator_shift'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_psychologist',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_psychologist',
                                    [
                                        'placeholder' => 'Digite o Número de Psicólogos',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_psychologist'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_nutritionist',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_nutritionist',
                                    [
                                        'placeholder' => 'Digite o Número de Nutricionistas',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_nutritionist'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_cooker',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_cooker',
                                    [
                                        'placeholder' => 'Digite o Número de Cozinheiros ou Merendeiras',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_cooker'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_school_secretary',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_school_secretary',
                                    [
                                        'placeholder' => 'Digite o Número de Secretário(a)s',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_school_secretary'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_support_professionals',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_support_professionals',
                                    [
                                        'placeholder' => 'Digite o Número de Profissionais de Apoio Pedagógico',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_support_professionals'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_security_guards',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_security_guards',
                                    [
                                        'placeholder' => 'Digite o Número de Seguranças',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_security_guards'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_monitors',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_monitors',
                                    [
                                        'placeholder' => 'Digite o Número de Monitores',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_monitors'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'workers_braille',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'workers_braille',
                                    [
                                        'placeholder' => 'Digite o Número de Assistentes ou Revisores em Braille',
                                        'class' => 't-field-text__input'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'workers_braille'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="titulos required">Local de Funcionamento</h3>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-checkbox-group clear-margin--top"
                                id="SchoolStructure_operation_location">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'operation_location_building',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_building']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'operation_location_other_school_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other_school_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'operation_location_barracks',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_barracks']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'operation_location_socioeducative_unity',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_socioeducative_unity']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'operation_location_prison_unity',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_prison_unity']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'operation_location_other',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other']; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->label(
                                        $modelSchoolStructure,
                                        'building_occupation_situation',
                                        [
                                            'class' => 't-field-select__label--required'
                                        ]
                                    ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'building_occupation_situation',
                                    [
                                        null => 'Selecione a forma de ocupação',
                                        '1' => 'Próprio',
                                        '2' => 'Alugado',
                                        '3' => 'Cedido'
                                    ],
                                    [
                                        'class' => 'select-search-off t-field-select__input select2-container'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'building_occupation_situation'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox clear-margin--top">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'shared_building_with_school',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox__label">
                                    <?php echo SchoolStructure::model()->attributeLabels()['shared_building_with_school']; ?>
                                    <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'shared_building_with_school'
                                ); ?>
                                </label>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->label(
                                        $modelSchoolStructure,
                                        'shared_school_inep_id_1',
                                        ['class' => 't-field-select__label']
                                    ); ?>
                                <?php echo $form->dropDownList(
                                    $modelSchoolStructure,
                                    'shared_school_inep_id_1',
                                    CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'),
                                    [
                                        'multiple' => true,
                                        'key' => 'inep_id',
                                        'class' => 'select-schools t-field-select__input select2-container multiselect'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'shared_school_inep_id_1'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Dependencias</h3>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-checkbox-group clear-margin--top dependencies-container">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'dependencies_warehouse',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_warehouse']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_green_area',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_green_area']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_auditorium',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_auditorium']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_prysical_disability_bathroom',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox ow">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_prysical_disability_bathroom']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_child_bathroom',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_child_bathroom']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_bathroom_workes',
                                        [
                                            'value' => 1,
                                            'uncheckValue' => 0
                                        ]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_workes']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_bathroom_with_shower',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_with_shower']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_library',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_library']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_kitchen',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_kitchen']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_storeroom',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_storeroom']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_student_accomodation',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_accomodation']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_instructor_accomodation',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructor_accomodation']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_reading_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_reading_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_instructors_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructors_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_aee_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_aee_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_secretary_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_secretary_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_vocational_education_workshop',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_vocational_education_workshop']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_recording_and_editing_studio',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_recording_and_editing_studio']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_garden_planting_agricultural',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_garden_planting_agricultural']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_none',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_none']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox-group clear-margin--top dependencies-container">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_science_lab',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_science_lab']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_info_lab',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_info_lab']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_professional_specific_lab',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_professional_specific_lab']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_playground',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_playground']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_covered_patio',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_covered_patio']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_uncovered_patio',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_uncovered_patio']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_pool',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_pool']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_indoor_sports_court',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_indoor_sports_court']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_outdoor_sports_court',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_outdoor_sports_court']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_refectory',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_refectory']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_student_repose_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_repose_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_arts_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_arts_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_music_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_music_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_dance_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_dance_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_multiuse_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_multiuse_room']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_yardzao',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_yardzao']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_vivarium',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_vivarium']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'dependencies_principal_room',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_principal_room']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div>
                                <h3 class="titulos required">Suprimento de água</h3>
                            </div>
                            <div class="t-field-checkbox-group clear-margin--top water-supply-container">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'water_supply_public',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_public']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'water_supply_artesian_well',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_artesian_well']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'water_supply_well'
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_well']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'water_supply_river',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_river']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'water_supply_car',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_car']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'water_supply_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_inexistent']; ?>
                                    </label>
                                </div>
                            </div>
                            <label class="control-label"><?php echo Yii::t('default', 'Potable Water'); ?></label>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'provide_potable_water',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <?php echo SchoolStructure::model()->attributeLabels()['provide_potable_water']; ?>
                                </label>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div>
                                <h3 class="titulos required">Suprimento de alimento</h3>
                            </div>
                            <!-- here -->
                            <div class="t-field-checkbox clear-margin--top ">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'supply_food',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-text__label--required">
                                    <?php echo SchoolStructure::model()->attributeLabels()['supply_food']; ?>
                                </label>
                            </div>
                            <div class="t-field-select--required">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'feeding',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'feeding',
                                    [
                                        null => 'Selecione o valor',
                                        '0' => 'Não oferece',
                                        '1' => 'Oferece'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'feeding'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="titulos required">Suprimento de Energia</h3>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-checkbox-group clear-margin--top energy-supply-container">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'energy_supply_public',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_public']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'energy_supply_generator',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'energy_supply_generator_alternative',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator_alternative']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'energy_supply_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_inexistent']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox clear-margin--top ">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'ppp_updated',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <!-- <?php echo $form->label($modelSchoolStructure, 'ppp_updated', ['class' => 'control-label']); ?> -->
                                    <?php echo SchoolStructure::model()->attributeLabels()['ppp_updated']; ?>
                                    <?php echo $form->error($modelSchoolStructure, 'ppp_updated'); ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'website',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox__label">
                                    <!-- <?php echo $form->label($modelSchoolStructure, 'website', ['class' => 'control-label']); ?> -->
                                    <?php echo SchoolStructure::model()->attributeLabels()['website']; ?>
                                    <?php echo $form->error($modelSchoolStructure, 'website'); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="titulos required">Esgoto</h3>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-checkbox-group clear-margin--top sewage-container">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'sewage_public',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_public']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'sewage_fossa',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'sewage_fossa_common',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa_common']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'sewage_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_inexistent']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox clear-margin--top">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'space_schoolenviroment',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <!-- <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'space_schoolenviroment',
                                    ['class' => 'control-label']
                                ); ?> -->
                                    <?php echo SchoolStructure::model()->attributeLabels()['space_schoolenviroment']; ?>
                                    <?php echo $form->error(
                                        $modelSchoolStructure,
                                        'space_schoolenviroment'
                                    ); ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'community_integration',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <!-- <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'community_integration',
                                    ['class' => 'control-label']
                                ); ?> -->
                                    <?php echo SchoolStructure::model()->attributeLabels()['community_integration']; ?>
                                    <?php echo $form->error(
                                        $modelSchoolStructure,
                                        'community_integration'
                                    ); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="titulos required">Destino do Lixo</h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox-group clear-margin--top garbage_destination_container">
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'garbage_destination_collect',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_collect']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'garbage_destination_burn',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_burn']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'garbage_destination_bury',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_bury']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'garbage_destination_public',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_public']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'garbage_destination_throw_away',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_throw_away']; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Tratamento do Lixo</h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox-group clear-margin--top garbage-treatment-container">
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'treatment_garbage_parting_garbage',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['treatment_garbage_parting_garbage']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'treatment_garbage_resuse',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['treatment_garbage_resuse']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'garbage_destination_recycle',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_recycle']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'traetment_garbage_inexistent',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['traetment_garbage_inexistent']; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="titulos required">Acessibilidade</h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox-group clear-margin--top accessbility-container">
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_handrails_guardrails',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_handrails_guardrails']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_elevator',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_elevator']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_tactile_floor',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_tactile_floor']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_doors_80cm',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_doors_80cm']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_ramps',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_ramps']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_light_signaling',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_light_signaling']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_sound_signaling',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_sound_signaling']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_tactile_singnaling',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_tactile_singnaling']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessability_visual_signaling',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessability_visual_signaling']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'acessabilty_inexistent',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['acessabilty_inexistent']; ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="titulos required">Órgãos em Funcionamento na Escola</h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox-group clear-margin--top board-organ-container">
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'board_organ_association_parent',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['board_organ_association_parent']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'board_organ_association_parentinstructors',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['board_organ_association_parentinstructors']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'board_organ_board_school',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['board_organ_board_school']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'board_organ_student_guild',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['board_organ_student_guild']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'board_organ_others',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['board_organ_others']; ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'board_organ_inexistent',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox">
                                    <?php echo SchoolStructure::model()->attributeLabels()['board_organ_inexistent']; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'ppp_updated',
                                    ['class' => 'control-label']
                                ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'ppp_updated',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelSchoolStructure,
                                        'ppp_updated'
                                    ); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelSchoolStructure,
                                        'website',
                                        ['class' => 'control-label']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'website',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelSchoolStructure,
                                        'website'
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->label($modelSchoolStructure, 'space_schoolenviroment', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'space_schoolenviroment', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'space_schoolenviroment'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->label($modelSchoolStructure, 'community_integration', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'community_integration', ['value' => 1, 'uncheckValue' => 0]); ?>
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
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'name',
                                        ['class' => 't-field-text__label--required']
                                    ); ?>
                                <?php echo $form->textField(
                                    $modelManagerIdentification,
                                    'name',
                                    [
                                        'size' => 60,
                                        'maxlength' => 100,
                                        'placeholder' => 'Digite o Nome do Gestor',
                                        'class' => 't-field-text__input',
                                        'id' => 'ManagerIdentification_name'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'name'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelManagerIdentification,
                                    'cpf',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelManagerIdentification,
                                    'cpf',
                                    [
                                        'size' => 60,
                                        'maxlength' => 14,
                                        'placeholder' => 'Digite o CPF do Gestor',
                                        'class' => 't-field-text__input',
                                        'id' => 'ManagerIdentification_cpf'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'cpf'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelManagerIdentification,
                                    'birthday_date',
                                    ['class' => 't-field-text__label--required']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelManagerIdentification,
                                    'birthday_date',
                                    ['size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input']
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'birthday_date'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label($modelManagerIdentification, 'color_race', ['class' => 't-field-select__label--required']); ?>
                                <?php
                                echo $form->DropDownList($modelManagerIdentification, 'color_race', [
                                    null => 'Selecione a cor/raça',
                                    '0' => 'Não declarada',
                                    '1' => 'Branca',
                                    '2' => 'Preta',
                                    '3' => 'Parda',
                                    '4' => 'Amarela',
                                    '5' => 'Indígena'
                                ], ['class' => 'select-search-off t-field-select__input select2-container', 'id' => 'color_race']);
?>
                                <?php echo $form->error($modelManagerIdentification, 'color_race'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
    $modelManagerIdentification,
    'sex',
    ['class' => 't-field-select__label--required']
); ?>
                                <?php echo $form->DropDownList(
                                    $modelManagerIdentification,
                                    'sex',
                                    [null => 'Selecione o sexo', '1' => 'Masculino', '2' => 'Feminino'],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'sex'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelManagerIdentification,
                                    'email',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelManagerIdentification,
                                    'email',
                                    [
                                        'size' => 50,
                                        'maxlength' => 50,
                                        'placeholder' => 'Digite o E-mail do Gestor',
                                        'class' => 't-field-text__input',
                                        'id' => 'ManagerIdentification_email'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'email'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label($modelManagerIdentification, 'nationality', [
                                    'class' => 't-field-select__label--required'
                                ]); ?>
                                <?php
                                echo $form->dropDownList(
                                    $modelManagerIdentification,
                                    'nationality',
                                    [
                                        null => 'Selecione a nacionalidade',
                                        '1' => 'Brasileira',
                                        '2' => 'Brasileira: Nascido no exterior ou Naturalizado',
                                        '3' => 'Estrangeira'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container'],
                                    [
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getnations'),
                                            'update' => '#ManagerIdentification_edcenso_nation_fk'
                                        ]
                                    ]
                                );
?>
                                <?php echo $form->error($modelManagerIdentification, 'nationality'); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <label class="t-field-select__input">País de Origem</label>
                                <?php
echo $form->dropDownList(
    $modelManagerIdentification,
    'edcenso_nation_fk',
    CHtml::listData(EdcensoNation::model()->findAll([
        'order' => 'name'
    ]), 'id', 'name'),
    [
        'prompt' => 'Selecione uma nação',
        'class' => 'select-search-on t-field-select__input select2-container nationality-sensitive no-br',
        'disabled' => 'disabled'
    ]
);
?>
                                <?php echo $form->error(
    $modelManagerIdentification,
    'edcenso_nation_fk'
); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <label class="t-field-select__label">Estado</label>
                                <?php
                                                                                                echo $form->dropDownList(
                                    $modelManagerIdentification,
                                    'edcenso_uf_fk',
                                    CHtml::listData(EdcensoUf::model()->findAll([
                                        'order' => 'name'
                                    ]), 'id', 'name'),
                                    [
                                        'class' => 'select-search-off t-field-select__input select2-container',
                                        'disabled' => 'disabled',
                                        'prompt' => 'Selecione uma cidade',
                                    ]
                                );
?>
                                <?php echo $form->error(
    $modelManagerIdentification,
    'edcenso_uf_fk'
); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <label class="t-field-select__label">Cidade</label>
                                <?php
                                                                                                echo $form->dropDownList(
                                    $modelManagerIdentification,
                                    'edcenso_city_fk',
                                    CHtml::listData(EdcensoCity::model()->findAllByAttributes([
                                        'edcenso_uf_fk' => $modelManagerIdentification->edcenso_uf_fk
                                    ], [
                                        'order' => 'name'
                                    ]), 'id', 'name'),
                                    [
                                        'prompt' => 'Selecione uma cidade',
                                        'disabled' => 'disabled',
                                        'class' => 'select-search-on nationality-sensitive br t-field-select__input select2-container',
                                    ]
                                );
?>
                                <?php echo $form->error($modelManagerIdentification, 'edcenso_city_fk'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
    $modelManagerIdentification,
    'number_ato',
    ['class' => 't-field-text__label']
); ?>
                                <?php echo $form->textField(
                                    $modelManagerIdentification,
                                    'number_ato',
                                    ['placeholder' => 'Digite o número ato', 'class' => 't-field-text__input']
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'number_ato'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelManagerIdentification,
                                    'contract_type',
                                    ['class' => 't-field-select__label']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelManagerIdentification,
                                    'contract_type',
                                    [
                                        null => 'Selecione o vínculo',
                                        '1' => 'Concursado/Efetivo',
                                        '2' => 'Temporário',
                                        '3' => 'Terceirizado',
                                        '4' => 'CLT'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'contract_type'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelManagerIdentification,
                                    'filiation',
                                    ['class' => 't-field-select__label--required']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelManagerIdentification,
                                    'filiation',
                                    [
                                        null => 'Selecione a filiação',
                                        '0' => 'Não declarado/Ignorado',
                                        '1' => 'Pai e/ou Mãe'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'filiation'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="manager-filiation-container" style="display: none;">
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                    $modelManagerIdentification,
                                    'filiation_1',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_1',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite o Nome Completo da filiação 1'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_1'
                                    ); ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_2',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_2',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite o Nome Completo da filiação 2'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_2'
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_1_cpf',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_1_cpf',
                                        [
                                            'size' => 60,
                                            'maxlength' => 14,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite o CPF da filiação 1'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_1_cpf'
                                    ); ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_2_cpf',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_2_cpf',
                                        [
                                            'size' => 60,
                                            'maxlength' => 14,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite o CPF da filiação 2'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_2_cpf'
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_1_rg',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_1_rg',
                                        [
                                            'size' => 60,
                                            'maxlength' => 45,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite o RG da filiação 1'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_1_rg'
                                    ); ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_2_rg',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_2_rg',
                                        [
                                            'size' => 60,
                                            'maxlength' => 45,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite o RG da filiação 2'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_2_rg'
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-select">
                                    <?php
                                    echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_1_scholarity',
                                        ['class' => 't-field-select__label']
                                    );
echo $form->dropDownList(
    $modelManagerIdentification,
    'filiation_1_scholarity',
    [
        null => 'Selecione a escolaridade da filiação 1',
        0 => 'Não sabe ler e escrever ',
        1 => 'Sabe ler e escrever',
        2 => 'Ens. Fund. Incompleto',
        3 => 'Ens. Fund. Completo',
        4 => 'Ens. Médio Incompleto',
        5 => 'Ens. Médio Completo',
        6 => 'Ens. Sup. Incompleto',
        7 => 'Ens. Sup. Completo'
    ],
    ['class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input select2-container']
);
?>
                                    <?php echo $form->error(
    $modelManagerIdentification,
    'filiation_1_scholarity'
); ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-select">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_2_scholarity',
                                        ['class' => 't-field-select__label']
                                    ); ?>
                                    <?php
                                    echo $form->dropDownList(
                                        $modelManagerIdentification,
                                        'filiation_2_scholarity',
                                        [
                                            null => 'Selecione a escolaridade da filiação 2',
                                            0 => 'Não sabe ler e escrever ',
                                            1 => 'Sabe ler e escrever',
                                            2 => 'Ens. Fund. Incompleto',
                                            3 => 'Ens. Fund. Completo',
                                            4 => 'Ens. Médio Incompleto',
                                            5 => 'Ens. Médio Completo',
                                            6 => 'Ens. Sup. Incompleto',
                                            7 => 'Ens. Sup. Completo'
                                        ],
                                        ['class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input select2-container']
                                    );
?>
                                    <?php echo $form->error(
    $modelManagerIdentification,
    'filiation_2_scholarity'
); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_1_job',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_1_job',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite a Profissão da filiação 1'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_1_job'
                                    ); ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-text">
                                    <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'filiation_2_job',
                                        ['class' => 't-field-text__label']
                                    ); ?>
                                    <?php echo $form->textField(
                                        $modelManagerIdentification,
                                        'filiation_2_job',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'class' => 'js-disabled-finputs js-finput-clear t-field-text__input',
                                            'placeholder' => 'Digite a Profissão da filiação 2'
                                        ]
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelManagerIdentification,
                                        'filiation_2_job'
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                        $modelManagerIdentification,
                                        'residence_zone',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                <?php echo $form->DropDownList(
                                    $modelManagerIdentification,
                                    'residence_zone',
                                    [null => 'Selecione uma zona', '1' => 'URBANA', '2' => 'RURAL'],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelManagerIdentification,
                                    'residence_zone'
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-equipment">
                    <div>
                        <h3>Eletrônicos</h3>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_dvd',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_dvd',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de DVDs'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_dvd'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_vcr',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_vcr',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de VCRs'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_vcr'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_stereo_system',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_stereo_system',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Aparelhos de Som'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_stereo_system'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_data_show',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_data_show',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de DataShows'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_data_show'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_tv',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_tv',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Tvs'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_tv'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_fax',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_fax',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Faxs'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_fax'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_qtd_blackboard',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_qtd_blackboard',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Quadros Negros'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_qtd_blackboard'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_camera',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_camera',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Máquinas Fotográficas'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_camera'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_overhead_projector',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_overhead_projector',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Retroprojetores'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_overhead_projector'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'administrative_computers_count',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'administrative_computers_count',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Computadores de Uso Administrativo'
                                    ]
                                ); ?>
                                <?php echo $form->error($modelSchoolStructure, 'administrative_computers_count'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_qtd_desktop',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_qtd_desktop',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Computadores de Mesa (Desktop)'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_qtd_desktop'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'student_computers_count',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'student_computers_count',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Computadores de Uso Infantil'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'student_computers_count'
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_qtd_tabletstudent',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_qtd_tabletstudent',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Tablets de Uso Estudantil'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_qtd_tabletstudent'
                                ); ?>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'equipments_qtd_notebookstudent',
                                    ['class' => 't-field-text__label']
                                ); ?>
                                <?php echo $form->textField(
                                    $modelSchoolStructure,
                                    'equipments_qtd_notebookstudent',
                                    [
                                        'size' => 4,
                                        'maxlength' => 4,
                                        'class' => 'equipments_input t-field-text__input',
                                        'placeholder' => 'Digite o Número de Notebooks de Uso Estudantil'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'equipments_qtd_notebookstudent'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-checkbox-group equipments-container">
                                <label class="t-field-checkbox-group__label--required">
                                    <?php echo Yii::t('default', 'Existing equipment at the school for technical and administrative use'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'equipments_satellite_dish',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_satellite_dish']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_computer',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_computer']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_copier',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_copier']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_printer',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_printer']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_multifunctional_printer',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_multifunctional_printer']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_scanner',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_scanner']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_inexistent']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'bandwidth',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <?php echo SchoolStructure::model()->attributeLabels()['bandwidth']; ?>
                                    <?php echo $form->error($modelSchoolStructure, 'bandwidth'); ?>
                                </label>
                            </div>

                            <label class="t-field-checkbox__label--required">
                                <?php echo Yii::t('default', 'Internet Access'); ?>
                            </label>
                            <div class="t-field-checkbox-group internet-access-container">
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'internet_access_administrative',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_administrative']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_educative_process',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_educative_process']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_student',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_student']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_community',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_community']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_inexistent']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-checkbox-group equipments-material-container">
                                <label
                                    class="t-field-checkbox-group__label--required"><?php echo Yii::t('default', 'Material, sociocultural and/or pedagogical instruments in use at school for the development of teaching and learning activities'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_multimedia_collection',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_multimedia_collection']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_toys_early',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_toys_early']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_scientific_materials',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_scientific_materials']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_equipment_amplification',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_equipment_amplification']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_garden_planting_agricultural',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_garden_planting_agricultural']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_musical_instruments',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_musical_instruments']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_educational_games',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_educational_games']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_cultural',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_cultural']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_professional_education',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_professional_education']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_sports',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_sports']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_teachingdeafs',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingdeafs']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_teachingindian',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingindian']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_teachingethnic',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingethnic']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_teachingrural',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingrural']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_teachingquilombola',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingquilombola']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'equipments_material_teachingspecial',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['equipments_material_teachingspecial']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'instruments_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['instruments_inexistent']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox-group">
                                <label
                                    class="t-field-checkbox-group__label"><?php echo Yii::t('default', 'Internet Access Connected Devices'); ?></label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_connected_desktop',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_connected_desktop']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_connected_personaldevice',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_connected_personaldevice']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_broadband',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_broadband']; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="t-field-checkbox-group internet-access-local-container">
                                <label class="t-field-checkbox-group__label--required">
                                    <?php echo Yii::t('default', 'Internet Access Local'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_local_cable',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_cable']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_local_wireless',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_wireless']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'internet_access_local_inexistet',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['internet_access_local_inexistet']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-education">
                    <div>
                        <h3>Eletrônicos</h3>
                    </div>

                    <div class="row">
                        <div class="column is-two-fifths clearleft">
                            <div class="t-field-select">
                                <?php echo $form->label(
                                        $modelSchoolStructure,
                                        'aee',
                                        ['class' => 't-field-select__label']
                                    ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'aee',
                                    [
                                        null => 'Selecione o valor',
                                        '0' => 'Não oferece',
                                        '1' => 'Não exclusivamente',
                                        '2' => 'Exclusivamente'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'aee'
                                ); ?>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'complementary_activities',
                                    ['class' => 't-field-select__label']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'complementary_activities',
                                    [
                                        null => 'Selecione o valor',
                                        '0' => 'Não oferece',
                                        '1' => 'Não exclusivamente',
                                        '2' => 'Exclusivamente'
                                    ],
                                    ['class' => 'select-search-off t-field-select__input select2-container']
                                ); ?>
                                <?php echo $form->error($modelSchoolStructure, 'complementary_activities'); ?>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                    $modelSchoolStructure,
                                    'basic_education_cycle_organized',
                                    ['value' => 1, 'uncheckValue' => 0]
                                ); ?>
                                <label class="t-field-checkbox__label">
                                    <?php echo SchoolStructure::model()->attributeLabels()['basic_education_cycle_organized']; ?>
                                    <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'basic_education_cycle_organized'
                                ); ?>
                                </label>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->label(
                                        $modelSchoolStructure,
                                        'different_location',
                                        ['class' => 't-field-select__label']
                                    ); ?>
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
                                ], ['class' => 'select-search-off t-field-select__input select2-container']);
?>
                                <?php echo $form->error($modelSchoolStructure, 'different_location'); ?>
                            </div>
                            <div class="t-field-checkbox-group">
                                <label class="t-field-checkbox-group__label">
                                    <?php echo Yii::t('default', 'Sociocultural Didactic Material'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
    $modelSchoolStructure,
    'sociocultural_didactic_material_none',
    ['value' => 1, 'uncheckValue' => 0]
); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_none']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'sociocultural_didactic_material_quilombola',
                                        [
                                            'value' => 1,
                                            'uncheckValue' => 0
                                        ]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_quilombola']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'sociocultural_didactic_material_native',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_native']; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'native_education',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <!-- <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'native_education',
                                    ['class' => 'control-label']
                                ); ?> -->
                                    <?php echo SchoolStructure::model()->attributeLabels()['native_education']; ?>
                                    <?php echo $form->error($modelSchoolStructure, 'native_education'); ?>
                                </label>
                            </div>

                            <div class="t-field-checkbox-group" id="native_education_language">
                                <label class="t-field-checkbox-group__label">
                                    <?php echo Yii::t('default', 'Native Education Language'); ?>
                                </label>
                                <div id="native_education_lenguage_none">
                                    <?php echo CHtml::activeHiddenField(
                                        $modelSchoolStructure,
                                        'native_education_language_native',
                                        ['value' => null, 'disabled' => 'disabled']
                                    );
echo CHtml::activeHiddenField(
    $modelSchoolStructure,
    'native_education_language_portuguese',
    ['value' => null, 'disabled' => 'disabled']
); ?>
                                </div>
                                <div class="t-field-checkbox-group" id="native_education_lenguage_some">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox(
    $modelSchoolStructure,
    'native_education_language_native',
    ['value' => 1, 'uncheckValue' => 0]
); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_native']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox(
                                            $modelSchoolStructure,
                                            'native_education_language_portuguese',
                                            ['value' => 1, 'uncheckValue' => 0]
                                        ); ?>
                                        <label class="t-field-checkbox">
                                            <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_portuguese']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->label(
                                            $modelSchoolStructure,
                                            'edcenso_native_languages_fk',
                                            ['class' => 't-field-select__label']
                                        ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'edcenso_native_languages_fk',
                                    CHtml::listData(EdcensoNativeLanguages::model()->findAll([
                                        'order' => 'name'
                                    ]), 'id', 'name'),
                                    [
                                        'prompt' => 'Selecione a língua indígena',
                                        'class' => 'select-search-on t-field-select__input select2-container'
                                    ]
                                ); ?>
                                <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'edcenso_native_languages_fk'
                                ); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'edcenso_native_languages_fk2',
                                    ['class' => 't-field-select__label']
                                ); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'edcenso_native_languages_fk2',
                                    CHtml::listData(EdcensoNativeLanguages::model()->findAll(
                                        ['order' => 'name']
                                    ), 'id', 'name'),
                                    [
                                        'prompt' => 'Selecione a língua indígena',
                                        'class' => 'select-search-on t-field-select__input select2-container'
                                    ]
                                );
?>
                                <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk2'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->label(
    $modelSchoolStructure,
    'edcenso_native_languages_fk3',
    ['class' => 't-field-select__label']
); ?>
                                <?php echo $form->DropDownList(
                                    $modelSchoolStructure,
                                    'edcenso_native_languages_fk3',
                                    CHtml::listData(EdcensoNativeLanguages::model()->findAll(['order' => 'name']), 'id', 'name'),
                                    [
                                        'prompt' => 'Selecione a língua indígena',
                                        'class' => 'select-search-on t-field-select__input select2-container'
                                    ]
                                );
?>
                                <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk3'); ?>
                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
    $modelSchoolStructure,
    'brazil_literate',
    ['value' => 1, 'uncheckValue' => 0]
); ?>
                                <label class="t-field-checkbox__label">
                                    <!-- <?php echo $form->label(
                                    $modelSchoolStructure,
                                    'brazil_literate',
                                    ['class' => 'control-label']
                                ); ?> -->
                                    <?php echo SchoolStructure::model()->attributeLabels()['brazil_literate']; ?>
                                    <?php echo $form->error($modelSchoolStructure, 'brazil_literate'); ?>
                                </label>
                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'open_weekend',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <?php echo SchoolStructure::model()->attributeLabels()['open_weekend']; ?>
                                    <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'open_weekend'
                                ); ?>
                                </label>
                            </div>
                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'pedagogical_formation_by_alternance',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                <label class="t-field-checkbox__label">
                                    <?php echo SchoolStructure::model()->attributeLabels()['pedagogical_formation_by_alternance']; ?>
                                    <?php echo $form->error(
                                    $modelSchoolStructure,
                                    'pedagogical_formation_by_alternance'
                                ); ?>
                                </label>
                            </div>
                        </div>
                        <div class="column clearleft--on-mobile is-two-fifths">
                            <div class="t-field-checkbox-group clear-margin--top">
                                <label class="t-field-checkbox-group__label">
                                    <?php echo Yii::t('default', 'Modalities'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'modalities_regular',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_regular']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'modalities_especial',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_especial']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'modalities_eja',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_eja']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'modalities_professional',
                                        [
                                            'value' => 1,
                                            'uncheckValue' => 0
                                        ]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_professional']; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="t-field-checkbox-group">
                                <label class="t-field-checkbox-group__label">
                                    <?php echo Yii::t('default', 'Organization of Education'); ?>
                                </label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'org_teaching_series_year',
                                        [
                                            'value' => 1,
                                            'uncheckValue' => 0
                                        ]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_series_year']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'org_teaching_semester_periods',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_semester_periods']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'org_teaching_elementary_cycle',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_elementary_cycle']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'org_teaching_non_serialgroups',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_non_serialgroups']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'org_teaching_modules',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_modules']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'org_teaching_regular_alternation',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['org_teaching_regular_alternation']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="t-field-checkbox-group">
                                <label
                                    class="t-field-checkbox-group__label"><?php echo Yii::t('default', 'Selection Exam'); ?></label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'select_adimission',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['select_adimission']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="t-field-checkbox-group booking-container">
                                <label
                                    class="t-field-checkbox-group__label"><?php echo Yii::t('default', 'Reservation by Quota System'); ?></label>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'booking_enrollment_self_declaredskin',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_self_declaredskin']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'booking_enrollment_income',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_income']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'booking_enrollment_public_school',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_public_school']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'booking_enrollment_disabled_person',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_disabled_person']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'booking_enrollment_others',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_others']; ?>
                                    </label>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox(
                                        $modelSchoolStructure,
                                        'booking_enrollment_inexistent',
                                        ['value' => 1, 'uncheckValue' => 0]
                                    ); ?>
                                    <label class="t-field-checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['booking_enrollment_inexistent']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group school-stages-container">
                                <label class="bold control-label">Etapas da Escola *</label>
                                <?php echo $form->dropDownList($modelSchoolStructure, 'stages', CHtml::listData(EdcensoStageVsModality::model()->findAll(['order' => 'name']), 'id', 'name'), ['multiple' => true, 'prompt' => 'Selecione o estágio vs modalidade', 'class' => 'select-search-on t-multiselect control-input multiselect']); ?>
                                <?php echo $form->error($modelSchoolStructure, 'stages'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="school-reports">
                    <container>
                        <row class="reports">
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl(
                                        'school/reportsMonthlyTransaction',
                                        [
                                            'id' => $modelSchoolIdentification->inep_id,
                                            'type' => 1
                                        ]
                                    ); ?>">
                                    <span class="t-icon-printer"></span>

                                    Movimentação Mensal Anos Iniciais
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl(
                                    'school/reportsMonthlyTransaction',
                                    ['id' => $modelSchoolIdentification->inep_id, 'type' => 2]
                                ); ?>">

                                    <span class="t-icon-printer"></span>
                                    Movimentação Mensal Anos Finais
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl(
                                    'school/reportsMonthlyTransaction',
                                    ['id' => $modelSchoolIdentification->inep_id, 'type' => 3]
                                ); ?>">
                                    <span class="t-icon-printer"></span>
                                    Movimentação Mensal Educação Infantil
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl(
                                    'school/reports',
                                    ['id' => $modelSchoolIdentification->inep_id]
                                ); ?>">
                                    <span class="t-icon-printer"></span>
                                    Resumo Mensal de Frequência
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/record', [
                                    'id' => $modelSchoolIdentification->inep_id,
                                    'type' => 1
                                ]); ?>">
                                    <span class="t-icon-printer"></span>
                                    Histórico Ensino Regular
                                </a>
                            </div>
                            <div class="reports_cards">
                                <a class="t-button-secondary" rel="noopener" target="_blank" href="<?= @Yii::app()->createUrl('school/record', [
                                    'id' => $modelSchoolIdentification->inep_id,
                                    'type' => 2
                                ]); ?>">
                                    <span class="t-icon-printer"></span>
                                    Histórico Ensino EJA
                                </a>
                            </div>
                        </row>
                    </container>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
        <div class="row reverse show--tablet">
            <div class="t-buttons-container">
                <div class="column clearfix">
                    <a data-toggle="tab" class='t-button-secondary prev' style="display:none;">
                        <?php echo Yii::t('default', 'Previous') ?>
                        <i></i></a>
                </div>
                <div class="column clearfix">
                    <?= $modelSchoolIdentification->isNewRecord ? "<a data-toggle='tab' class='t-button-primary nofloat next'>" . Yii::t('default', 'Next') . '</a>' : '' ?>
                    <a class="t-button-primary last save-school-button" type="button">
                        <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                    </a>
                </div>
            </div>
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