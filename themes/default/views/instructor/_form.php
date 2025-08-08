<?php

/**
 * @var $cs CClientScript
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/instructor/form/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/instructor/form/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/instructor/form/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/instructor/form/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);

$cs->registerScript('VARS', "
    var GET_INSTITUTIONS = '" . $this->createUrl('instructor/getInstitutions') . "';
", CClientScript::POS_BEGIN);

$form = $this->beginWidget(
    'CActiveForm',
    [
        'id' => 'instructor-form',
        'enableAjaxValidation' => false,
    ]
);

$isModel = isset($modelInstructorIdentification->id);
?>

<div class="row-fluid">
    <div class="span12" style="height: 63px; margin-left: 3px">
        <h1><?php echo $title; ?></h1>
        <span class="subtitle"><?php echo Yii::t('default', 'Fields with * are required.') ?></span>
        <div class="tag-buttons-container buttons hide-responsive" id="btnSection">
            <a data-toggle="tab" class='t-button-secondary  prev' style="display:none;">
                <?php echo Yii::t('default', 'Previous') ?><i></i>
            </a>
            <?= $modelInstructorIdentification->isNewRecord ?
                "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . '</a>' : '' ?>
            <button class="t-button-primary  last pull-right save-instructor" type="button">
                <?= $modelInstructorIdentification->isNewRecord ?
                    Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="tag-inner">
    <div class="box-links-previous-next mb-20">
        <a data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display:none;">
            <?php echo Yii::t('default', 'Previous') ?><i></i>
        </a>
        <a data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'>
            <?php echo Yii::t('default', 'Next') ?>
            <i></i>
        </a>
        <?php echo CHtml::htmlButton(
                        '<i></i>' . ($modelInstructorIdentification->isNewRecord ?
                Yii::t('default', 'Create') :
                Yii::t('default', 'Save')),
                        [
                            'class' => 'btn btn-icon btn-primary last glyphicons circle_ok pull-right',
                            'style' => 'display:none',
                            'type' => 'submit'
                        ]
                    ); ?>
    </div>
    <div class="widget widget-tabs border-bottom-none">
        <?php
        echo $form->errorSummary($modelInstructorIdentification);
echo $form->errorSummary($modelInstructorDocumentsAndAddress);
echo isset($error['documentsAndAddress']) ? $error['documentsAndAddress'] : '';
echo $form->errorSummary($modelInstructorVariableData);
echo isset($error['variableData']) ? $error['variableData'] : '';
?>
        <div class="alert alert-error instructor-error no-show"></div>
        <div class="t-tabs">
            <ul class="tab-instructor t-tabs__list">
                <li id="tab-instructor-identify" class="active t-tabs__item"><a href="#instructor-identify" data-toggle="tab" class="t-tabs__link">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Identification') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-instructor-address" class="t-tabs__item">
                    <a href="#instructor-address" data-toggle="tab" class="t-tabs__link">
                        <span class="t-tabs__numeration">2</span>
                        <?php echo Yii::t('default', 'Address') ?>
                    </a>
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                </li>
                <li id="tab-instructor-data" class="t-tabs__item">
                    <a href="#instructor-data" data-toggle="tab" class="t-tabs__link">
                        <span class="t-tabs__numeration">3</span>
                        <?php echo Yii::t('default', 'Variable Data') ?>
                    </a>
                    <?= !$modelInstructorIdentification->isNewRecord ? '<img src="' . Yii::app()->theme->baseUrl . '/img/seta-tabs.svg" alt="seta">' : ''?>

                </li>
                <?= !$modelInstructorIdentification->isNewRecord ?
        '<li id="tab-instructor-data" class="t-tabs__item">
                    <a href="#instructor-classroom" data-toggle="tab" class="t-tabs__link">
                        <span class="t-tabs__numeration">4</span>'
                . Yii::t('default', 'Classrooms') .
            '</a>
                </li>' : ''
?>
            </ul>
        </div>

        <div class="widget-body form-instructor form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="instructor-identify">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'name',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'name',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'class' => 'js-trim-name',
                                            'placeholder' => 'Digite o Nome de Apresentação'
                                        ]
                                    );
?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'Instructor Full Social Name'); ?>"><i></i></span>-->

                                    <?php echo $form->error($modelInstructorIdentification, 'name'); ?>
                                </div>
                            </div>
                            <div class="controls">
                                <label class="checkbox show-instructor-civil-name-box">
                                    Esse é um nome social?
                                    <input type="checkbox" id="show-instructor-civil-name" <?php if ($modelInstructorIdentification->civil_name !== null) {
                                        echo 'checked';
                                    } ?>>
                                </label>
                            </div>
                            <div class="control-group instructor-civil-name" style="display: none;">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'civil_name',
                                        ['class' => 't-field-select__label--required']
                                    );
?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
    $modelInstructorIdentification,
    'civil_name',
    [
        'size' => 60,
        'maxlength' => 100,
        'class' => 'js-trim-name',
        'placeholder' => 'Digite o Nome Civil'
    ]
);
?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Instructor Full Civil Name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorIdentification, 'civil_name'); ?>
                                </div>
                            </div>

                            <div class="control-group" id="cpfInstructor">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorDocumentsAndAddress,
    'cpf',
    ['class' => 't-field-select__label--required']
);
?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
    $modelInstructorDocumentsAndAddress,
    'cpf',
    [
        'size' => 14,
        'maxlength' => 14
    ]
);
?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'CPF Numbers'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'email',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'email',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Email'
                                        ]
                                    );
?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'Email'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelInstructorIdentification, 'email'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'nis',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'nis',
                                        [
                                            'size' => 11,
                                            'maxlength' => 11,
                                            'placeholder' => 'Digite o NIS'
                                        ]
                                    );
?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '11'; ?>"><i></i></span> -->
                                    <?php echo $form->error($modelInstructorIdentification, 'nis'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'birthday_date',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div style="margin-left: 23px">
									<?php
                                                                                                                $this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($modelInstructorIdentification, 'birthday_date'));
echo CHtml::link('	Limpar', '#', [
    'onclick' => '$("#' . CHtml::activeId($modelInstructorIdentification, 'birthday_date') . '").datepicker("setDate", null); return false;',
]);
echo $form->error($modelInstructorIdentification, 'birthday_date');
?>
								</div>
                            </div>

                            <div class="control-group" id="gender-select">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'sex',
    ['class' => 't-field-select__label--required ']
);
?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownlist(
    $modelInstructorIdentification,
    'sex',
    [
        null => 'Selecione um sexo',
        1 => 'Masculino',
        2 => 'Feminino'
    ],
    ['class' => 'select-search-off control-input']
); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'sex'); ?>
                                </div>
                            </div>

                            <div class="control-group" id="colorRace">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'color_race',
                                        ['class' => 't-field-select__label--required']
                                    );
?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
    $modelInstructorIdentification,
    'color_race',
    [
        null => 'Selecione uma raça',
        0 => 'Não Declarada',
        1 => 'Branca',
        2 => 'Preta',
        3 => 'Parda',
        4 => 'Amarela',
        5 => 'Indígena'
    ],
    ['class' => 'select-search-off control-input']
);
?>
                                    <?php echo $form->error($modelInstructorIdentification, 'color_race'); ?>
                                </div>
                            </div>

                            <div class="control-group" id="filiation-select">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'filiation',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'filiation',
                                        [
                                            null => 'Selecione uma opção',
                                            0 => 'Não declarado',
                                            1 => 'Declarado'
                                        ],
                                        ['class' => 'select-search-off control-input']
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'filiation'); ?>
                                </div>
                            </div>

                            <div class="control-group" id="filiation-select_1">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'filiation_1',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'filiation_1',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Nome Completo da Filiação'
                                        ]
                                    ); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('default', 'Full name'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelInstructorIdentification, 'filiation_1'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'filiation_2',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'filiation_2',
                                        [
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Nome Completo do Pai'
                                        ]
                                    ); ?>
                                    <!-- <span class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('default', 'Full name'); ?>"><i></i></span> -->
                                    <?php echo $form->error($modelInstructorIdentification, 'filiation_2'); ?>
                                </div>
                            </div>
                            <div class="control-group js-is-indigenous hide">
                                    <div class="controls">
                                        <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'id_indigenous_people',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $form->dropDownList(
                                            $modelInstructorIdentification,
                                            'id_indigenous_people',
                                            CHtml::listData(EdcensoIndigenousPeople::model()->findAll(['order' => 'id_indigenous_people']), 'id_indigenous_people', 'name'),
                                            ['class' => 'select-search-on control-input', 'prompt' => 'Selecione um povo indígena']
                                        )?>
                                    <?php echo $form->error($modelInstructorIdentification, 'id_indigenous_people'); ?>
                                </div>
                            </div>
                        </div>

                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group" id="nationality_select">
                                <div class="controls">
                                    <?php echo $form->label(
                                            $modelInstructorIdentification,
                                            'nationality',
                                            ['class' => 't-field-select__label--required']
                                        ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'nationality',
                                        [
                                            null => 'Selecione uma nacionalidade',
                                            1 => 'Brasileira',
                                            2 => 'Brasileira nascido no Exterior ou Naturalizado',
                                            3 => 'Estrangeira'
                                        ],
                                        ['class' => 'select-search-on control-input']
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'nationality'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'edcenso_nation_fk',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'edcenso_nation_fk',
                                        CHtml::listData(EdcensoNation::model()
                                            ->findAll(['order' => 'name ASC']), 'id', 'name'),
                                        [
                                            'prompt' => 'Selecione um país',
                                            'class' => 'select-search-on control-input'
                                        ],
                                        ['options' => [76 => ['selected' => true]]]
                                    );
?>
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_nation_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="state-select">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorIdentification,
    'edcenso_uf_fk',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'edcenso_uf_fk',
                                        CHtml::listData(EdcensoUf::model()
                                            ->findAll(['order' => 'name']), 'id', 'name'),
                                        [
                                            'prompt' => 'Selecione um estado',
                                            'class' => 'select-search-on control-input'
                                        ]
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group" id="city-select">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorIdentification,
                                        'edcenso_city_fk',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'edcenso_city_fk',
                                        CHtml::listData(
                                            EdcensoCity::model()->findAllByAttributes(
                                                ['edcenso_uf_fk' => $modelInstructorIdentification->edcenso_uf_fk]
                                            ),
                                            'id',
                                            'name'
                                        ),
                                        [
                                            'prompt' => 'Selecione uma cidade',
                                            'class' => 'select-search-on control-input'
                                        ]
                                    );
?>
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="required">
                                        <?php echo $form->label(
    $modelInstructorIdentification,
    'deficiency',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo CHtml::activeCheckBox($modelInstructorIdentification, 'deficiency'); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'deficiency'); ?>
                                </div>
                            </div>

                            <div class="control-group deficiencies-container">
                                <div class="controls">
                                    <label class="required">
                                        <?php echo Yii::t('default', 'Deficiency Type'); ?>
                                        *
                                    </label>
                                </div>
                                <div class="controls" id="InstructorIdentification_deficiencies">
                                    <label class="checkbox">
                                        <?php
                                                                                                                        echo InstructorIdentification::model()->attributeLabels()['deficiency_type_blindness'];
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_blindness',
    [
        'value' => 1, 'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_low_vision'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_low_vision',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_monocular_vision'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_monocular_vision',
    [
        'value' => 1, 'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_deafness'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_deafness',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_disability_hearing'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_disability_hearing',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_deafblindness'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_deafblindness',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_phisical_disability'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_phisical_disability',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_intelectual_disability',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_autism'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_autism',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorIdentification::model()->attributeLabels()['deficiency_type_gifted'];
?>
                                        <?php
echo $form->checkBox(
    $modelInstructorIdentification,
    'deficiency_type_gifted',
    ['value' => 1, 'uncheckValue' => 0]
);
?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php
                                echo $form->hiddenField(
    $modelInstructorIdentification,
    'deficiency_type_multiple_disabilities'
);
?>
                                <?php
echo $form->error(
    $modelInstructorIdentification,
    'deficiency_type_multiple_disabilities'
);
?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="instructor-address">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="separator"></div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php
    echo $form->label(
    $modelInstructorDocumentsAndAddress,
    'cep',
    ['class' => 't-field-select__label--required']
);
?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
    $modelInstructorDocumentsAndAddress,
    'cep',
    [
        'placeholder' => 'Digite o CEP',
        'ajax' => [
            'type' => 'POST',
            'url' => CController::createUrl(
                'Instructor/getcitybycep'
            ),
            'data' => ['cep' => 'js:this.value'],
            'success' => 'function(data){
                                                updateCep(data);
                                                }'
        ],
    ]
); ?>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'cep'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php
                                                                                                            echo $form->label(
                                        $modelInstructorDocumentsAndAddress,
                                        'address',
                                        ['class' => 't-field-select__label--required']
                                    );
?>
                                </div>
                                <div class="controls">
                                    <?php
echo $form->textField(
    $modelInstructorDocumentsAndAddress,
    'address',
    [
        'size' => 60,
        'maxlength' => 100,
        'placeholder' => 'Digite o Endereço'
    ]
);
?>
                                    <?php
echo $form->error(
    $modelInstructorDocumentsAndAddress,
    'address'
);
?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorDocumentsAndAddress,
    'address_number',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                                                                                            echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'address_number',
                                        [
                                            'size' => 10,
                                            'maxlength' => 10,
                                            'placeholder' => 'Digite o Número'
                                        ]
                                    );
?>
                                    <?php
echo $form->error($modelInstructorDocumentsAndAddress, 'address_number');
?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php
echo $form->label(
    $modelInstructorDocumentsAndAddress,
    'complement',
    [
        'class' => 't-field-select__label--required'
    ]
); ?>
                                </div>
                                <div class="controls">
                                    <?php
echo $form->textField(
    $modelInstructorDocumentsAndAddress,
    'complement',
    [
        'size' => 20, 'maxlength' => 20, 'placeholder' => 'Digite o Complemento'
    ]
); ?>

                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'complement'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorDocumentsAndAddress,
    'neighborhood',
    [
        'class' => 't-field-select__label--required'
    ]
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'neighborhood',
                                        [
                                            'size' => 50,
                                            'maxlength' => 50,
                                            'placeholder' => 'Digite o Bairro ou Povoado'
                                        ]
                                    ); ?>

                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'neighborhood'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php
                                    echo $form->label(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_uf_fk',
                                        [
                                            'class' => 't-field-select__label--required'
                                        ]
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_uf_fk',
                                        CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'),
                                        [
                                            'prompt' => 'Selecione um estado',
                                            'class' => 'select-search-on control-input'
                                        ]
                                    ); ?>

                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_city_fk',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_city_fk',
                                        CHtml::listData(EdcensoCity::model()->findAllByAttributes(
                                            ['edcenso_uf_fk' => $modelInstructorDocumentsAndAddress->edcenso_uf_fk]
                                        ), 'id', 'name'),
                                        [
                                            'prompt' => 'Selecione uma cidade',
                                            'class' => 'select-search-on control-input'
                                        ]
                                    );
?>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'edcenso_city_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive" id="location-select">
                                <div class="controls">
                                    <?php echo $form->label(
    $modelInstructorDocumentsAndAddress,
    'diff_location',
    ['class' => 't-field-select__label--required']
); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorDocumentsAndAddress,
                                        'diff_location',
                                        [
                                            null => 'Selecione a localização',
                                            7 => 'Não reside em área de localização diferenciada',
                                            3 => 'Área onde se localiza comunidade remanescente de quilombos',
                                            2 => 'Terra indígena', 1 => 'Área de assentamento',
                                            8 => 'Área onde se localiza povos e comunidades tradicionais'
                                        ],
                                        ['class' => 'select-search-on control-input']
                                    ); ?>
                                    <div class="controls">
                                        <?php echo $form->error(
                                        $modelInstructorDocumentsAndAddress,
                                        'diff_location'
                                    ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group" id="zone-select">
                                <div class="controls">
                                    <?php echo $form->label(
                                            $modelInstructorDocumentsAndAddress,
                                            'area_of_residence',
                                            ['class' => 't-field-select__label--required required']
                                        ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownlist(
                                        $modelInstructorDocumentsAndAddress,
                                        'area_of_residence',
                                        [null => 'Selecione uma localização', 1 => 'URBANA', 2 => 'RURAL'],
                                        ['class' => 'select-search-off control-input']
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelInstructorDocumentsAndAddress,
                                        'area_of_residence'
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="instructor-data">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->label(
                                        $modelInstructorVariableData,
                                        'scholarity',
                                        ['class' => 't-field-select__label--required']
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownlist($modelInstructorVariableData, 'scholarity', [
                                        null => 'Selecione uma escolaridade',
                                        1 => 'Fundamental Incompleto',
                                        2 => 'Fundamental Completo',
                                        3 => 'Ensino Médio - Normal/Magistério',
                                        4 => 'Ensino Médio - Normal/Magistério Indígena',
                                        5 => 'Ensino Médio',
                                        6 => 'Superior'
                                    ], ['class' => 'select-search-off control-input']); ?>
                                    <?php echo $form->error($modelInstructorVariableData, 'scholarity'); ?>
                                </div>
                            </div>

                            <div class="separator"></div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="t-field-select__label--required">Pos-Graduação</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_specialization'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'post_graduation_specialization',
    [
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['post_graduation_master'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'post_graduation_master',
    [
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['post_graduation_doctorate'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'post_graduation_doctorate',
    ['value' => 1, 'uncheckValue' => 0]
); ?>
                                    </label>
                                    <label class="checkbox" style="display:none;">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['post_graduation_none'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'post_graduation_none',
    [
        'checked' => true,
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="t-field-select__label--required">Outros Cursos</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_nursery'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_nursery',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_pre_school'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_pre_school',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_basic_education_initial_years'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_basic_education_initial_years',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_basic_education_final_years'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_basic_education_final_years',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_high_school'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_high_school',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_education_of_youth_and_adults'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_education_of_youth_and_adults',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_special_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_special_education',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_native_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_native_education',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_field_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_field_education',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_environment_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_environment_education',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_human_rights_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_human_rights_education',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_bilingual_education_for_the_deaf'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_bilingual_education_for_the_deaf',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_education_and_tic'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_education_and_tic',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorVariableData::model()->attributeLabels()['other_courses_sexual_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_sexual_education',
    [
        'class' => 'other_courses',
        'value' => 1,

        'uncheckValue' => 0
    ]
); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorVariableData::model()->attributeLabels()['other_courses_child_and_teenage_rights'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_child_and_teenage_rights',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorVariableData::model()->attributeLabels()['other_courses_ethnic_education'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_ethnic_education',
    [
        'class' => 'other_courses', 'value' => 1, 'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_other'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_other',
    [
        'class' => 'other_courses',
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                    <label class="checkbox" style="display:none;">
                                        <?php
echo InstructorVariableData::model()->attributeLabels()['other_courses_none'];
echo $form->checkBox(
    $modelInstructorVariableData,
    'other_courses_none',
    [
        'value' => 1,
        'uncheckValue' => 0
    ]
);
?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="innerLR" id="instructorVariableData">
                                <div class="widget widget-tabs border-bottom-none">
                                    <div class="widget-head">
                                        <ul class="tab-instructordata">
                                            <li id="tab-instructor-data1" class="sub-active"><a class="glyphicons certificate" href="#instructor-data1" data-toggle="tab">
                                                    <?php echo Yii::t('default', 'Course') . ' 1' ?>
                                                </a></li>
                                            <li id="tab-instructor-data2"><a class="glyphicons certificate" href="#instructor-data2" data-toggle="tab">
                                                    <?php echo Yii::t('default', 'Course') . ' 2' ?>
                                                </a></li>
                                            <li id="tab-instructor-data3"><a class="glyphicons certificate" href="#instructor-data3" data-toggle="tab">
                                                    <?php echo Yii::t('default', 'Course') . ' 3' ?>
                                                </a></li>
                                        </ul>
                                    </div>

                                    <div class="widget-body form-horizontal">
                                        <div class="tab-content">

                                            <div class="sub-active" id="instructor-data1">
                                                <div class="row-fluid">
                                                    <div class=" span6">
                                                        <div class="separator"></div>
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                        echo $form->label(
    $modelInstructorVariableData,
    'high_education_situation_1',
    [
        'class' => 't-field-text__label--required required indicator'
    ]
);
?>

                                                            </div>
                                                            <div class="controls" id="highEducationSituation">
                                                                <?php echo $form->DropDownlist($modelInstructorVariableData, 'high_education_situation_1', [null => 'Selecione a situação', 1 => 'Concluído', 2 => 'Em andamento'], ['class' => 'select-search-off control-input']); ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_situation_1'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_formation_1',
    [
        'class' => 't-field-select__label--required'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->CheckBox(
    $modelInstructorVariableData,
    'high_education_formation_1',
    [
        'value' => 1, 'uncheckValue' => 0
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_formation_1'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo CHtml::label(Yii::t(
    'default',
    'Area'
), 'high_education_course_area1', [
    'class' => 't-field-select__label--required'
]);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::DropDownList(
    'high_education_course_area1',
    '',
    CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(['group' => 'cod, area', 'select' => 'cod, area']), 'cod', 'area'),
    [
        'class' => 'select-search-off control-input',
        'prompt' => 'Selecione a Área de Atuação',
        'ajax' => [
            'type' => 'POST',
            'url' => CController::createUrl(
                'instructor/getCourses&tdid=1'
            ),
            'success' => "function(data){
                                                                            val =
                                                                            $(
                                                                                '#InstructorVariableData_high_education_course_code_1_fk').val();
                                                                            $(
                                                                                '#InstructorVariableData_high_education_course_code_1_fk').html(
                                                                                    data).val(val).trigger('change');
                                                                        }",
        ]
    ]
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                                                                                                                                                echo $form->label(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_1_fk',
                                                                    [
                                                                        'class' => 't-field-select__label--required'
                                                                    ]
                                                                );
?>
                                                            </div>
                                                            <div class="controls" id="highEducationCode">
                                                                <?php echo $form->DropDownlist(
    $modelInstructorVariableData,
    'high_education_course_code_1_fk',
    CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(['order' => 'name']), 'id', 'name'),
    [
        'prompt' => 'Selecione o curso 1',
        'class' => 'select-search-on control-input',
    ]
);
echo
$form->error(
    $modelInstructorVariableData,
    'high_education_course_code_1_fk'
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_initial_year_1',
    [
        'class' => 't-field-select__label--required'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_initial_year_1',
    [
        'size' => 4, 'maxlength' => 4
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_initial_year_1'
);
?>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->label(
    $modelInstructorVariableData,
    'high_education_final_year_1',
    [
        'class' => 't-field-select__label--required'
    ]
);
?>
                                                            </div>
                                                            <div class="controls" id="highEducationYear">
                                                                <?php echo $form->textField($modelInstructorVariableData, 'high_education_final_year_1', ['size' => 4, 'maxlength' => 4]); ?>
                                                                <!-- <span class="btn-action single glyphicons circle_question_mark"
                                                                      data-toggle="tooltip" data-placement="top"
                                                                      data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span> -->
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_final_year_1'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                Estado
                                                            </div>
                                                            <div class="controls">
                                                                <select id="IES">
                                                                    <?php
    $ufs = [
        '00' => 'Selecione um estado',
        '11' => 'Rondônia',
        '12' => 'Acre',
        '13' => 'Amazonas',
        '14' => 'Roraima',
        '15' => 'Pará',
        '16' => 'Amapá',
        '17' => 'Tocantins',
        '21' => 'Maranhão',
        '22' => 'Piauí',
        '23' => 'Ceará',
        '24' => 'Rio Grande do Norte',
        '25' => 'Paraíba',
        '26' => 'Pernambuco',
        '27' => 'Alagoas',
        '28' => 'Sergipe',
        '29' => 'Bahia',
        '31' => 'Minas Gerais',
        '32' => 'Espírito Santo',
        '33' => 'Rio de Janeiro',
        '35' => 'São Paulo',
        '41' => 'Paraná',
        '42' => 'Santa Catarina',
        '43' => 'Rio Grande do Sul',
        '50' => 'Mato Grosso do Sul',
        '51' => 'Mato Grosso',
        '52' => 'Goiás',
        '53' => 'Distrito Federal'
    ];

foreach ($ufs as $k => $uf) {
    if ($k == $modelInstructorVariableData->highEducationInstitutionCode1Fk->edcenso_uf_fk) {
        echo '<option value=' . $k . ' selected>' . $uf . '</option>';
    } else {
        echo '<option value=' . $k . ' >' . $uf . '</option>';
    }
}
?>
                                                                </select>
                                                                <?php echo $form->error(
    $modelInstructorIdentification,
    'edcenso_uf_fk'
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->label(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_institution_code_1_fk',
                                                                    ['class' => 't-field-select__label--required required indicator']
                                                                ); ?>

                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->DropDownList($modelInstructorVariableData, 'high_education_institution_code_1_fk', [$modelInstructorVariableData->high_education_institution_code_1_fk => $modelInstructorVariableData->high_education_institution_code_1_fk->name], ['style' => 'width:425px;', 'class' => 'select-search-on control-input']); ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_institution_code_1_fk'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="instructor-data2">
                                                <div class="row-fluid">
                                                    <div class=" span6">

                                                        <div class="separator"></div>
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->label(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_2',
                                                                    ['class' => 't-field-select__label--required required indicator']
                                                                ); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->DropDownList(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_2',
                                                                    [
                                                                        null => 'Selecione a situação',
                                                                        1 => 'Concluído',
                                                                        2 => 'Em Andamento'
                                                                    ],
                                                                    [
                                                                        'class' => 'select-search-off control-input'
                                                                    ]
                                                                ); ?>
                                                                <?php echo $form->error(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_2'
                                                                ); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->label(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_formation_2',
                                                                    [
                                                                        'class' => 't-field-select__label--required'
                                                                    ]
                                                                ); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->CheckBox(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_formation_2'
                                                                );
?>
                                                                <?php echo $form->error(
    $modelInstructorVariableData,
    'high_education_formation_2'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo CHtml::label(Yii::t('default', 'Area'), 'high_education_course_area2', ['class' => 't-field-select__label--required']); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::DropDownList(
    'high_education_course_area2',
    '',
    CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(['group' => 'cod, area', 'select' => 'cod, area']), 'cod', 'area'),
    [
        'class' => 'select-search-off control-input',
        'prompt' => 'Selecione a Área de Atuação',
        'ajax' => [
            'type' => 'POST',
            'url' => CController::createUrl('instructor/getCourses&tdid=2'),
            'success' => "function(data){
                                                                            val = $('#InstructorVariableData_high_education_course_code_2_fk').val();
                                                                            $('#InstructorVariableData_high_education_course_code_2_fk').html(data).val(val).trigger('change');
                                                                        }",
        ]
    ]
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                                                                                                                                                echo $form->label(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_2_fk',
                                                                    [
                                                                        'class' => 't-field-select__label--required'
                                                                    ]
                                                                );
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->DropDownList(
    $modelInstructorVariableData,
    'high_education_course_code_2_fk',
    CHtml::listData(
        EdcensoCourseOfHigherEducation::model()->findAll(),
        'id',
        'name'
    ),
    [
        'prompt' => 'Selecione o curso 2',
        'class' => 'select-search-on control-input'
    ]
); ?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_course_code_2_fk'
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->label($modelInstructorVariableData, 'high_education_initial_year_2', ['class' => 't-field-select__label--required']); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_initial_year_2',
    [
        'size' => 4, 'maxlength' => 4
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_initial_year_2'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label($modelInstructorVariableData, 'high_education_final_year_2', ['class' => 't-field-select__label--required']);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_final_year_2',
    ['size' => 4, 'maxlength' => 4]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_final_year_2'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_institution_code_2_fk',
    [
        'class' => 't-field-select__label--required required indicator'
    ]
);
?>


                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_institution_code_2_fk',
    [
        'style' => 'width:425px;'
    ]
); ?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_institution_code_2_fk'
); ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="instructor-data3">
                                                <div class="row-fluid">
                                                    <div class=" span6">
                                                        <div class="separator"></div>
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_situation_3',
    [
        'class' => 't-field-select__label--required required indicator'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->DropDownList(
    $modelInstructorVariableData,
    'high_education_situation_3',
    [
        null => 'Selecione a situação',
        1 => 'Concluído',
        2 => 'Em Andamento'
    ],
    [
        'class' => 'select-search-off control-input'
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_situation_3'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_formation_3',
    [
        'class' => 't-field-select__label--required'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::activeCheckBox(
    $modelInstructorVariableData,
    'high_education_formation_3'
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_formation_3'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo CHtml::label(Yii::t(
    'default',
    'Area'
), 'high_education_course_area3', [
    'class' => 't-field-select__label--required'
]);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::DropDownList(
    'high_education_course_area3',
    '',
    CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(['group' => 'cod, area', 'select' => 'cod, area']), 'cod', 'area'),
    [
        'class' => 'select-search-off control-input',
        'prompt' => 'Selecione a Área de Atuação',
        'ajax' => [
            'type' => 'POST',
            'url' => CController::createUrl(
                'instructor/getCourses&tdid=3'
            ),
            'success' => "function(data){
                                                                            val = $('#InstructorVariableData_high_education_course_code_3_fk').val();
                                                                            $('#InstructorVariableData_high_education_course_code_3_fk').html(data).val(val).trigger('change');
                                                                        }",
        ]
    ]
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                                                                                                                                                echo $form->label(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_3_fk',
                                                                    [
                                                                        'class' => 't-field-select__label--required'
                                                                    ]
                                                                );
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->DropDownList(
    $modelInstructorVariableData,
    'high_education_course_code_3_fk',
    CHtml::listData(
        EdcensoCourseOfHigherEducation::model()->findAll(),
        'id',
        'name'
    ),
    [
        'prompt' => 'Selecione o curso 3',
        'class' => 'select-search-on control-input'
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_course_code_3_fk'
); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_initial_year_3',
    [
        'class' => 't-field-select__label--required'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_initial_year_3',
    [
        'size' => 4,
        'maxlength' => 4
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_initial_year_3'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->label(
    $modelInstructorVariableData,
    'high_education_final_year_3',
    [
        'class' => 't-field-select__label--required'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_final_year_3',
    [
        'size' => 4, 'maxlength' => 4
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_final_year_3'
);
?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
echo $form->label(
    $modelInstructorVariableData,
    'high_education_institution_code_3_fk',
    [
        'class' => 't-field-select__label--required required indicator'
    ]
);
?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
echo $form->textField(
    $modelInstructorVariableData,
    'high_education_institution_code_3_fk',
    [
        'style' => 'width:425px;'
    ]
);
?>
                                                                <?php
echo $form->error(
    $modelInstructorVariableData,
    'high_education_institution_code_3_fk'
);
?>
                                                            </div>
                                                        </div>
                                                    </div>
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
                <?php
                    if (!$modelInstructorIdentification->isNewRecord):
                        ?>
                <div class="tab-pane" id="instructor-classroom">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="js-add-classrooms-cards">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    endif;
?>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
    $this->widget('application.widgets.AlertCensoWidget', ['prefix' => 'instructor', 'dataId' => $_GET['id']]);
}
?>

<script type="text/javascript">
    var formInstructorIdentification = '#InstructorIdentification_';
    var formInstructorvariableData = "#InstructorVariableData_";

    var filter = {
        1: 0,
        2: 0,
        3: 0
    };
    var actualFilter = 0;

    var id = {
        1: formInstructorvariableData + "high_education_institution_code_1_fk",
        2: formInstructorvariableData + "high_education_institution_code_2_fk",
        3: formInstructorvariableData + "high_education_institution_code_3_fk"
    };
</script>
