<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $model OnlineEnrollmentStudentIdentification */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);

$adminOrManager = !Yii::app()->user->isGuest &&
    (Yii::app()->authManager->checkAccess('admin', Yii::app()->user->loginInfos->id) ||
        Yii::app()->authManager->checkAccess('manager', Yii::app()->user->loginInfos->id));


?>


<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', [
        'id' => 'online-enrollment-student-identification-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]); ?>
    <div id="<?= $model->isNewRecord ? 'content' : '' ?>"
        class="<?= $model->isNewRecord ? 'enrolment-online-content' : '' ?>">
        <div id="loading-popup" class="hide">
            <img class="js-grades-loading" height="60px" width="60px" src="/themes/default/img/loadingTag.gif"
                alt="TAG Loading">
        </div>
        <div class="main <?= $model->isNewRecord ? 'form-content' : '' ?>">
            <div class="row">
                <div class="column">
                    <h1>
                        <?php echo $title; ?>
                    </h1>
                </div>
                <div class="column clearfix align-items--center justify-content--end show--desktop">
                    <a data-toggle="tab" class='hide-responsive t-button-secondary prev'
                        style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
                    <?= $model->isNewRecord ? "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>

                    <button class="t-button-primary last " type="submit" style="display:none;">
                        <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                    </button>

                </div>
            </div>
            <?php if (Yii::app()->user->hasFlash('success') && (!$model->isNewRecord)): ?>
                <div class="alert alert-success">
                    <?= Yii::app()->user->getFlash('success') ?>
                </div>
            <?php endif; ?>
            <div class="alert alert-success js-alert-enrollment-online hide">
                <?= Yii::app()->user->getFlash('success') ?>
            </div>
            <div class="alert alert-error hide js-alert"></div>
            <div class="t-tabs js-tab-control" style="margin-left: 1em;">
                <ul class="tab-student t-tabs__list">
                    <li id="tab-student-identify" class="t-tabs__item active">
                        <a class="t-tabs__link first" href="#student-identify" data-toggle="tab">
                            <span class="t-tabs__numeration">1</span>
                            <?php echo Yii::t('default', 'Student Data') ?>
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
                    <li id="tab-student-address" class="t-tabs__item ">
                        <a class="t-tabs__link" href="#student-address" data-toggle="tab">
                            <span class="t-tabs__numeration">3</span>
                            <?php echo Yii::t('default', 'Address') ?>
                        </a>
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                    </li>
                    <li id="tab-student-enrollment" class="t-tabs__item ">
                        <a class="t-tabs__link" href="#student-enrollment" data-toggle="tab">
                            <span class="t-tabs__numeration js-change-number-3">4</span>
                            <?php echo Yii::t('default', 'Enrollment') ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php echo $form->errorSummary($model); ?>
            <div class="tab-content">
                <div class="tab-pane active" id="student-identify">
                    <div class="row">
                        <h3 class="column">
                            Dados Básicos
                        </h3>
                    </div>
                    <?php echo $form->hiddenField($model, 'id', ['id' => 'online-enrollment-id', 'class' => 'js-online-enrollment-id']); ?>
                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'name', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input js-field-required', 'placeholder' => 'Digite o Nome de Apresentação']); ?>
                            <?php echo $form->error($model, 'name'); ?>
                        </div>
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'birthday', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            $options = DatePickerWidget::renderDatePicker($model, 'birthday');
                            $options['htmlOptions']['class'] = 'js-field-required';
                            $this->widget('zii.widgets.jui.CJuiDatePicker', $options);
                            ?>
                            <?php echo $form->error($model, 'birthday'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-select" id="nationality-select">
                                <?= $form->label($model, 'nationality', array('class' => 't-field-select__label--required')); ?>
                                <?=
                                $form->dropDownList(
                                    $model,
                                    'nationality',
                                    array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"),
                                    array('class' => 'select-search-off t-field-select__input select2-container js-field-required js-nationality-select')

                                );
                                ?>
                                <?php echo $form->error($model, 'nationality'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-select">
                                <?php echo $form->label($model, 'edcenso_nation_fk', array('class' => 't-field-select__label--required')); ?>
                                <?php
                                echo CHtml::hiddenField('EnrollmentOnlineStudentIdentification[edcenso_nation_fk]', $model->edcenso_nation_fk, [
                                    'class' => 'js-edcenso_nation_fk_hidden'
                                ]);
                                echo $form->dropDownList(
                                    $model,
                                    'edcenso_nation_fk',
                                    CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'),
                                    array("prompt" => "Selecione uma nação", 'class' => 'select-search-on js-field-required nationality-sensitive no-br t-field-select__input select2-container js-edcenso_nation_fk', 'disabled' => 'disabled')
                                );
                                ?>
                                <?php echo $form->error($model, 'edcenso_nation_fk'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'cpf', ['class' => 't-field-text__label']); ?>
                            <?php echo $form->textField($model, 'cpf', ['size' => 14, 'maxlength' => 14, 'class' => 't-field-text__input js-cpf-mask']); ?>
                            <?php echo $form->error($model, 'cpf'); ?>
                        </div>

                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'sex', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->DropDownList(
                                $model,
                                'sex',
                                [null => 'Selecione o sexo', '1' => 'Masculino', '2' => 'Feminino'],
                                ['class' => 'select-search-off t-field-select__input select2-container js-field-required']
                            ); ?>
                            <?php echo $form->error($model, 'sex'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'color_race', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            echo $form->DropDownList($model, 'color_race', [
                                null => 'Selecione a cor/raça',
                                '0' => 'Não declarada',
                                '1' => 'Branca',
                                '2' => 'Preta',
                                '3' => 'Parda',
                                '4' => 'Amarela',
                                '5' => 'Indígena'
                            ], ['class' => 'select-search-off t-field-select__input select2-container js-field-required']);
                            ?>
                            <?php echo $form->error($model, 'color_race'); ?>
                        </div>

                        <div class="t-field-text column">
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="column">
                            Deficiência
                        </h3>
                    </div>
                    <div class="row">
                        <div class="t-field-checkbox column">
                            <?php echo $form->checkBox($model, 'deficiency', ['class' => 't-field-checkbox__input']); ?>
                            <?php echo $form->labelEx($model, 'deficiency', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->error($model, 'deficiency'); ?>
                        </div>
                        <div class="column"></div>
                    </div>
                    <div class="row ">
                        <div
                            class="column t-field-checkbox-group control-group deficiencies-container js-change-required js-visibility-deficiencies">
                            <label
                                class="t-field-checkbox__label--required column t-margin-none--left"><?php echo Yii::t('default', 'Deficiency Type'); ?>
                            </label>
                            <div class="t-field-checkbox">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_blindness', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_blindness']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_blindness'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_low_vision', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_low_vision']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_low_vision'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_deafness', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_deafness']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_deafness'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_disability_hearing', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_disability_hearing'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_deafblindness', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_deafblindness']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_deafblindness'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_phisical_disability', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_phisical_disability'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_intelectual_disability', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_intelectual_disability'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_multiple_disabilities', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_multiple_disabilities']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_multiple_disabilities'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_autism', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_autism']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_autism'); ?>
                            </div>
                            <div class="t-field-text">
                                <label class="t-field-checkbox">
                                    <?php echo $form->checkBox($model, 'deficiency_type_gifted', ['value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency']); ?>
                                    <?php echo $model->attributeLabels()['deficiency_type_gifted']; ?>
                                </label>
                                <?php echo $form->error($model, 'deficiency_type_gifted'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="student-affiliation">
                    <div class="row">
                        <h3 class="column">
                            Filiação
                        </h3>
                    </div>
                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'responsable_name', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'responsable_name', ['size' => 60, 'maxlength' => 90, 'class' => 't-field-text__input js-field-required', 'placeholder' => 'Digite o Nome do Responsável']); ?>
                            <?php echo $form->error($model, 'responsable_name'); ?>
                        </div>

                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'responsable_cpf', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'responsable_cpf', ['size' => 14, 'maxlength' => 14, 'class' => 't-field-text__input  js-cpf-mask js-field-required']); ?>
                            <?php echo $form->error($model, 'responsable_cpf'); ?>
                        </div>
                    </div>

                    <div class="row">

                    </div>

                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'responsable_telephone', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'responsable_telephone', ['size' => 14, 'maxlength' => 15, 'class' => 't-field-text__input js-tel-mask js-field-required']); ?>
                            <?php echo $form->error($model, 'responsable_telephone'); ?>
                        </div>
                        <div class="t-field-text column">
                            <label class="t-field-select__label--required ">Filiação</label>
                            <?php
                            echo $form->DropDownList(
                                $model,
                                'filiation',
                                [

                                    null => 'Selecione a filiação',
                                    '0' => 'Não declarada/ignorada',
                                    '1' => 'Mãe e/ou Pai',
                                ],
                                ['class' => 'select-search-off t-field-select__input select2-container js-filiation-select js-field-required']
                            );
                            ?>
                        </div>
                    </div>
                    <div class="row js-hide-filiation" style="display:none;">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'filiation_1', ['class' => 't-field-text__label']); ?>
                            <?php echo $form->textField($model, 'filiation_1', ['size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input js-mother-name js-ignore-validation', 'placeholder' => 'Digite o Nome da Mãe']); ?>
                            <?php echo $form->error($model, 'filiation_1'); ?>
                        </div>

                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'filiation_2', ['class' => 't-field-text__label']); ?>
                            <?php echo $form->textField($model, 'filiation_2', ['size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input js-father-name js-ignore-validation', 'placeholder' => 'Digite o Nome do Pai']); ?>
                            <?php echo $form->error($model, 'filiation_2'); ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="student-address">
                    <div class="row">
                        <h3 class="column">
                            Endereço
                        </h3>
                    </div>
                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'cep', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'cep', ['size' => 9, 'maxlength' => 9, 'class' => 't-field-text__input js-cep-mask js-field-required']); ?>
                            <?php echo $form->error($model, 'cep'); ?>
                        </div>
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'residence_zone', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            echo $form->DropDownList(
                                $model,
                                'residence_zone',
                                [null => 'Selecione uma zona', '1' => 'URBANA', '2' => 'RURAL'],
                                ['class' => 'select-search-off t-field-select__input select2-container js-field-required']
                            ); ?>

                            <?php echo $form->error($model, 'residence_zone'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'address', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'address', ['size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input js-field-required', 'placeholder' => 'Digite o Endereço']); ?>
                            <?php echo $form->error($model, 'address'); ?>
                        </div>

                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'number', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'number', ['size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input js-field-required', 'placeholder' => 'Digite o Número']); ?>
                            <?php echo $form->error($model, 'number'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'complement', ['class' => 't-field-text__label']); ?>
                            <?php echo $form->textField($model, 'complement', ['size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Complemento']); ?>
                            <?php echo $form->error($model, 'complement'); ?>
                        </div>

                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'neighborhood', ['class' => 't-field-text__label--required']); ?>
                            <?php echo $form->textField($model, 'neighborhood', ['size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input js-field-required', 'placeholder' => 'Digite o Bairro / Povoado']); ?>
                            <?php echo $form->error($model, 'neighborhood'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'edcenso_uf_fk', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            echo $form->dropDownList(
                                $model,
                                'edcenso_uf_fk',
                                CHtml::listData(EdcensoUf::model()->findAll(['order' => 'name']), 'id', 'name'),
                                [
                                    'prompt' => 'Selecione um estado',
                                    'class' => 'select-search-on t-field-select__input select2-container js-uf js-field-required'
                                ]
                            );
                            ?>
                            <?php echo $form->error($model, 'edcenso_uf_fk'); ?>
                        </div>
                        <?php echo $form->hiddenField($model, 'edcenso_city_fk', ['id' => 'edcenso-city-fk-hidden']); ?>
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'edcenso_city_fk', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            echo $form->dropDownList(
                                $model,
                                'edcenso_city_fk',
                                [],
                                ['prompt' => 'Selecione uma cidade', 'class' => 'select-search-on t-field-select__input select2-container js-field-required js-cities', 'disabled' => 'disabled']
                            );
                            ?>
                            <?php echo $form->error($model, 'edcenso_city_fk'); ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="student-enrollment">
                    <div class="row">
                        <h3 class="column">
                            Matrícula
                        </h3>
                    </div>
                    <div class="row">
                        <div class="column t-field-text">
                            <?php echo $form->labelEx($model, 'pre_enrollment_event_fk', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            echo $form->dropDownList(
                                $model,
                                'pre_enrollment_event_fk',
                                CHtml::listData(
                                    EnrollmentOnlinePreEnrollmentEvent::model()->findAll([
                                        // CURDATE() pega a data atual do banco de dados (YYYY-MM-DD)
                                        'condition' => 'CURDATE() BETWEEN start_date AND end_date',
                                        'order' => 'name'
                                    ]),
                                    'id',
                                    'name'
                                ),
                                [
                                    'prompt' => 'Selecione um evento de pré-matrícula',
                                    'class' => 'select-search-on t-field-select__input select2-container js-pre-enrollment-event js-field-required'
                                ]
                            );
                            ?>
                            <?php echo $form->error($model, 'pre_enrollment_event_fk'); ?>
                        </div>
                        <div class="t-field-text column">
                            <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', ['class' => 't-field-text__label--required']); ?>
                            <?php
                            echo $form->dropDownList(
                                $model,
                                'edcenso_stage_vs_modality_fk',
                                CHtml::listData(EdcensoStageVsModality::model()->findAll(['order' => 'name']), 'id', 'name'),
                                [
                                    'prompt' => 'Selecione uma etapa',
                                    'class' => 'select-search-on t-field-select__input select2-container js-stage js-field-required',
                                    'disabled' => 'disabled'
                                ]
                            );
                            ?>
                            <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="t-field-text column is-half">
                            <label for="" class="t-field-text__label--required">Priemira opção matrícula</label>
                            <?php
                            echo CHtml::dropDownList(
                                'school_1',
                                $studentSolicitations[0]['school_inep_id_fk'],
                                $schools,
                                [
                                    'prompt' => 'Selecione uma opção de matrícula',
                                    'class' => 'select-search-on t-field-select__input select2-container js-school-1 js-field-required',
                                    'disabled' => 'disabled'
                                ]
                            );
                            ?>
                        </div>
                        <div class="t-field-text column is-half">
                            <label for="" class="t-field-text__label">Segunda opção matrícula</label>
                            <?php
                            echo CHtml::dropDownList(
                                'school_2',
                                $studentSolicitations[1]->school_inep_id_fk,
                                $schools,
                                [
                                    'prompt' => 'Selecione uma opção de matrícula',
                                    'class' => 'select-search-on t-field-select__input select2-container js-school-2  js-ignore-validation',
                                    'disabled' => 'disabled'
                                ]
                            );
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="t-field-text column">
                            <label for="" class="t-field-text__label">Terceira opção matrícula</label>
                            <?php
                            echo CHtml::dropDownList(
                                'school_3',
                                $studentSolicitations[2]->school_inep_id_fk,
                                $schools,
                                [
                                    'prompt' => 'Selecione uma opção de matrícula',
                                    'class' => 'select-search-on t-field-select__input select2-container js-school-3  js-ignore-validation',
                                    'disabled' => 'disabled'
                                ]
                            );
                            ?>
                        </div>
                        <div class="column"></div>
                    </div>
                    <?php if ($adminOrManager && !$model->student_fk && !$isRejected): ?>
                        <div class="row show--desktop  js-hide-buttons-enrollment">
                            <div class="column"></div>
                            <div class="column">
                                <div class="row justify-content--end">
                                    <a class="t-button-danger column t-margin-none--right js-rejected-enrollment">
                                        Rejeitar Matrícula
                                    </a>
                                    <a class="t-button-success column t-margin-none--right  js-confirm-enrollment">
                                        Confirmar Matrícula
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row show--tablet  js-hide-buttons-enrollment">
                            <div class="column">
                                <a
                                    class="t-button-danger  t-margin-none--bottom  t-margin-none--right  js-rejected-enrollment">
                                    Rejeitar Matrícula
                                </a>

                            </div>
                            <div class="column">
                                <a
                                    class="t-button-success  t-margin-none--top  t-margin-none--right  js-confirm-enrollment">
                                    Confirmar Matrícula
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row reverse t-margin-large--top reverse show--tablet">
                <div class="t-buttons-container">
                    <div class="column"></div>
                    <div class="column"></div>
                    <div class="column">
                        <a data-toggle="tab" class='t-button-secondary t-margin-none--right t-padding-small--all prev'
                            style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
                    </div>
                    <div class="column">
                        <?= $model->isNewRecord ? "<a data-toggle='tab' class='t-button-primary t-margin-none--right t-padding-small--all nofloat next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>
                        <button class="t-button-primary t-padding-small--all t-margin-none--right last save-student"
                            type="submit" style="display:none;width:100%;">
                            <?= Yii::t('default', 'Save') ?>
                        </button>
                    </div>


                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div><!-- form -->
<style>
    #content.enrolment-online-content {
        margin-top: 80px;
    }

    span.required {
        display: none;
    }
</style>