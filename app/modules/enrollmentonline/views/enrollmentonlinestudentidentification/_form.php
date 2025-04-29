<?php
/* @var $this OnlineEnrollmentStudentIdentificationController */
/* @var $model OnlineEnrollmentStudentIdentification */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'online-enrollment-student-identification-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>
    <div class="main form-content">
        <div class="row">
            <div class="column">
                <h1>
                    <?php echo $title; ?>
                </h1>
            </div>
        </div>
        <div class="t-tabs js-tab-control">
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
                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'name', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome de Apresentação')); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'birthday', array('class' => 't-field-text__label')); ?>
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($model, 'birthday')); ?>
                        <?php echo $form->error($model, 'birthday'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'cpf', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'cpf', array('size' => 14, 'maxlength' => 14, 'class' => 't-field-text__input js-cpf-mask')); ?>
                        <?php echo $form->error($model, 'cpf'); ?>
                    </div>

                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'sex', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->DropDownList(
                            $model,
                            'sex',
                            array(null => "Selecione o sexo", "1" => "Masculino", "2" => "Feminino"),
                            array('class' => 'select-search-off t-field-select__input select2-container')
                        ); ?>
                        <?php echo $form->error($model, 'sex'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'color_race', array('class' => 't-field-text__label')); ?>
                        <?php
                        echo $form->DropDownList($model, 'color_race', array(
                            null => "Selecione a cor/raça",
                            "0" => "Não declarada",
                            "1" => "Branca",
                            "2" => "Preta",
                            "3" => "Parda",
                            "4" => "Amarela",
                            "5" => "Indígena"
                        ), array('class' => 'select-search-off t-field-select__input select2-container'));
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
                        <?php echo $form->checkBox($model, 'deficiency', array('class' => 't-field-checkbox__input')); ?>
                        <?php echo $form->labelEx($model, 'deficiency', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->error($model, 'deficiency'); ?>
                    </div>
                    <div class="column"></div>
                </div>
                <div class="row ">
                    <div class="column t-field-checkbox-group control-group deficiencies-container js-change-required js-visibility-deficiencies">
                        <label class="t-field-checkbox__label--required column t-margin-none--left"><?php echo Yii::t('default', 'Deficiency Type'); ?>
                        </label>
                        <div class="t-field-checkbox">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_blindness']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_blindness'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_low_vision']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_low_vision'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_deafness']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_deafness'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_disability_hearing']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_disability_hearing'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_deafblindness']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_deafblindness'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_phisical_disability']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_phisical_disability'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_intelectual_disability'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_multiple_disabilities', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_multiple_disabilities']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_multiple_disabilities'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_autism', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                                <?php echo $model->attributeLabels()['deficiency_type_autism']; ?>
                            </label>
                            <?php echo $form->error($model, 'deficiency_type_autism'); ?>
                        </div>
                        <div class="t-field-text">
                            <label class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
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
                        <?php echo $form->labelEx($model, 'responsable_name', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'responsable_name', array('size' => 60, 'maxlength' => 90, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Responsável')); ?>
                        <?php echo $form->error($model, 'responsable_name'); ?>
                    </div>

                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'responsable_cpf', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'responsable_cpf', array('size' => 11, 'maxlength' => 11, 'class' => 't-field-text__input  js-cpf-mask')); ?>
                        <?php echo $form->error($model, 'responsable_cpf'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'responsable_telephone', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'responsable_telephone', array('size' => 14, 'maxlength' => 15, 'class' => 't-field-text__input js-tel-mask')); ?>
                        <?php echo $form->error($model, 'responsable_telephone'); ?>
                    </div>
                    <div class="t-field-text column">
                        <label class="t-field-select__label--required ">Filiação</label>
                        <?php
                        echo CHtml::dropDownList(
                            'filiation',
                            '',
                            array(
                                null => "Selecione a filiação",
                                "0" => "Não declarada/ignorada",
                                "1" => "Mãe e/ou Pai",
                            ),
                            array('class' => 'select-search-off t-field-select__input select2-container js-filiation-select')
                        );
                        ?>
                    </div>
                </div>
                <div class="row js-hide-filiation" style="display:none;">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'mother_name', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'mother_name', array('size' => 60, 'maxlength' => 90, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome da Mãe')); ?>
                        <?php echo $form->error($model, 'mother_name'); ?>
                    </div>

                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'father_name', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'father_name', array('size' => 60, 'maxlength' => 90, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Pai')); ?>
                        <?php echo $form->error($model, 'father_name'); ?>
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
                        <?php echo $form->labelEx($model, 'cep', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'cep', array('size' => 8, 'maxlength' => 8, 'class' => 't-field-text__input js-cep-mask')); ?>
                        <?php echo $form->error($model, 'cep'); ?>
                    </div>
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'zone', array('class' => 't-field-text__label')); ?>
                        <?php
                        echo $form->DropDownList(
                            $model,
                            'zone',
                            array(null => "Selecione uma zona", "1" => "URBANA", "2" => "RURAL"),
                            array('class' => 'select-search-off t-field-select__input select2-container')
                        ); ?>

                        <?php echo $form->error($model, 'zone'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'address', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Endereço')); ?>
                        <?php echo $form->error($model, 'address'); ?>
                    </div>

                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'number', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'number', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input',  'placeholder' => 'Digite o Número')); ?>
                        <?php echo $form->error($model, 'number'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'complement', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'complement', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input',  'placeholder' => 'Digite o Complemento')); ?>
                        <?php echo $form->error($model, 'complement'); ?>
                    </div>

                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'neighborhood', array('class' => 't-field-text__label')); ?>
                        <?php echo $form->textField($model, 'neighborhood', array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input',  'placeholder' => 'Digite o Bairro / Povoado')); ?>
                        <?php echo $form->error($model, 'neighborhood'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'edcenso_uf_fk', array('class' => 't-field-text__label')); ?>
                        <?php
                        echo $form->dropDownList(
                            $model,
                            'edcenso_uf_fk',
                            CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'),
                            array(
                                "prompt" => "Selecione um estado",
                                "class" => "select-search-on t-field-select__input select2-container js-uf"
                            )
                        );
                        ?>
                        <?php echo $form->error($model, 'edcenso_uf_fk'); ?>
                    </div>
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'edcenso_city_fk', array('class' => 't-field-text__label')); ?>
                        <?php
                        echo $form->dropDownList(
                            $model,
                            'edcenso_city_fk',
                            [],
                            array("prompt" => "Selecione uma cidade", "class" => "select-search-on t-field-select__input select2-container js-cities", "disabled" => "disabled")
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
                    <div class="t-field-text column">
                        <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', array('class' => 't-field-text__label')); ?>
                        <?php
                        echo $form->dropDownList(
                            $model,
                            'edcenso_stage_vs_modality_fk',
                            CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'),
                            array(
                                "prompt" => "Selecione um estado",
                                "class" => "select-search-on t-field-select__input select2-container js-uf"
                            )
                        );
                        ?>
                        <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                    </div>
                    <div class="t-field-text column">
                    </div>
                </div>
            </div>
        </div>
        <div class="row buttons" style="width:106px;">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 't-button-primary')); ?>
        </div>


        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
