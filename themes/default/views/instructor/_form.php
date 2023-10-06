<?php

/**
 * @var $cs CClientScript
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/instructor/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/instructor/form/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/instructor/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/instructor/form/pagination.js', CClientScript::POS_END);

$cs->registerScript("VARS", "
    var GET_INSTITUTIONS = '" . $this->createUrl('instructor/getInstitutions') . "';
", CClientScript::POS_BEGIN);


$form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'instructor-form',
        'enableAjaxValidation' => false,
    )
);

$isModel = isset($modelInstructorIdentification->id); // Corrigir se precisar acessar os atributos
?>

<div class="row-fluid">
    <div class="span12" style="height: 63px; margin-left: 3px">
        <h1><?php echo $title; ?></h1>
        <span class="subtitle"><?php echo Yii::t('default', 'Fields with * are required.') ?></span>
        <div class="tag-buttons-container buttons hide-responsive">
            <a data-toggle="tab" class='t-button-secondary  prev' style="display:none;">
                <?php echo Yii::t('default', 'Previous') ?><i></i>
            </a>
            <?= $modelInstructorIdentification->isNewRecord ?
                "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>
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
            '<i></i>' . ($modelInstructorIdentification->isNewRecord ? Yii::t(
                'default',
                'Create'
            ) : Yii::t('default', 'Save')),
            array(
                'class' => 'btn btn-icon btn-primary last glyphicons circle_ok pull-right',
                'style' => 'display:none',
                'type' => 'submit'
            )
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
                <li id="tab-instructor-identify" class="active t-tabs__item">
                    <a href="#instructor-identify" data-toggle="tab" class="t-tabs__link">
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
                </li>
            </ul>
        </div>

        <div class="widget-body form-instructor form-horizontal">
            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="instructor-identify">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'name',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'name',
                                        array(
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' =>
                                            'Digite o Nome de Apresentação'
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'name'); ?>
                                </div>
                            </div>
                            <div class="controls">
                                <label class="checkbox show-instructor-civil-name-box">
                                    Esse é um nome social?
                                    <input type="checkbox" id="show-instructor-civil-name" <?php
                                                                                            if ($modelInstructorIdentification->civil_name != null) {
                                                                                                echo "checked";
                                                                                            }
                                                                                            ?>>
                                </label>
                            </div>
                            <div class="control-group instructor-civil-name" style="display: none;">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'civil_name',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'civil_name',
                                        array(
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Nome Civil'
                                        )
                                    ); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="
                                    <?php echo Yii::t(
                                        'help',
                                        'Instructor Full Civil Name'
                                    ); ?>"><i></i>
                                    </span>
                                    <?php echo $form->error($modelInstructorIdentification, 'civil_name'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'cpf',
                                        array('class' => 'control-label required')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'cpf',
                                        array('size' => 14, 'maxlength' => 14)
                                    ); ?>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'email',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'email',
                                        array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Digite o Email')
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'email'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'nis',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'nis',
                                        array('size' => 11, 'maxlength' => 11, 'placeholder' => 'Digite o NIS')
                                    ); ?>
                                    <?php echo $form->error(
                                        $modelInstructorIdentification,
                                        'nis'
                                    ); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'birthday_date',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'birthday_date',
                                        array(
                                            'size' => 10,
                                            'maxlength' => 10,
                                        )
                                    );
                                    ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'birthday_date'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'sex',
                                        array('class' => 'control-label ')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownlist(
                                        $modelInstructorIdentification,
                                        'sex',
                                        array(null => "Selecione um sexo", 1 => 'Masculino', 2 => 'Feminino'),
                                        array("class" => 'select-search-off control-input')
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'sex'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'color_race',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'color_race',
                                        array(
                                            null => "Selecione uma raça",
                                            0 => "Não Declarada",
                                            1 => "Branca",
                                            2 => "Preta",
                                            3 => "Parda",
                                            4 => "Amarela",
                                            5 => "Indígena"
                                        ),
                                        array("class" => 'select-search-off control-input')
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'color_race'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'filiation',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'filiation',
                                        array(
                                            null => "Selecione uma opção",
                                            0 => "Não declarado",
                                            1 => "Declarado"
                                        ),
                                        array("class" => 'select-search-off control-input')
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'filiation'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'filiation_1',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'filiation_1',
                                        array(
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Nome Completo da Filiação 1'
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'filiation_1'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'filiation_2',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorIdentification,
                                        'filiation_2',
                                        array(
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Nome Completo da Filiação 2'
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'filiation_2'); ?>
                                </div>
                            </div>
                        </div>

                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'nationality',
                                        array(
                                            'class' => 'control-label'
                                        )
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'nationality',
                                        array(
                                            null => "Selecione uma nacionalidade",
                                            1 => "Brasileira",
                                            2 => "Brasileira nascido no Exterior ou Naturalizado",
                                            3 => "Estrangeira"
                                        ),
                                        array(
                                            "class" => 'select-search-on control-input'
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'nationality'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'edcenso_nation_fk',
                                        array(
                                            'class' => 'control-label'
                                        )
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'edcenso_nation_fk',
                                        CHtml::listData(EdcensoNation::model()->findAll(
                                            array('order' => 'name ASC')
                                        ), 'id', 'name'),
                                        array(
                                            "prompt" => "Selecione um país", "class" => 'select-search-on control-input'
                                        ),
                                        array(
                                            'options' => array(76 => array('selected' => true))
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_nation_fk'); ?>
                                </div>
                            </div>
                            <!-- Estado de origem-->
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'edcenso_uf_fk',
                                        array(
                                            'class' => 'control-label'
                                        )
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'edcenso_uf_fk',
                                        CHtml::listData(EdcensoUf::model()->findAll(array(
                                            "order" => "name"
                                        )), 'id', 'name'),
                                        array(
                                            'prompt' => 'Selecione um estado',
                                            'class' => 'select-search-on control-input'
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>
                            <!-- Cidade de origem-->
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorIdentification,
                                        'edcenso_city_fk',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorIdentification,
                                        'edcenso_city_fk',
                                        CHtml::listData(EdcensoCity::model()->findAllByAttributes(array(
                                            'edcenso_uf_fk' => $modelInstructorIdentification->edcenso_uf_fk
                                        )), 'id', 'name'),
                                        array(
                                            "prompt" => "Selecione uma cidade",
                                            "class" => 'select-search-on control-input'
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label required">
                                        <?php echo $form->labelEx(
                                            $modelInstructorIdentification,
                                            'deficiency',
                                            array('class' => 'control-label')
                                        ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo CHtml::activeCheckBox($modelInstructorIdentification, 'deficiency'); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'deficiency'); ?>
                                </div>
                            </div>

                            <div class="control-group deficiencies-container">
                                <div class="controls">
                                    <label class="control-label required">
                                        <?php echo Yii::t('default', 'Deficiency Type'); ?>
                                        *
                                    </label>
                                </div>
                                <div class="controls" id="InstructorIdentification_deficiencies">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorIdentification::model()->attributeLabels()['deficiency_type_blindness'];
                                        echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_blindness', array(
                                            'value' => 1, 'uncheckValue' => 0
                                        ));
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array(
                                                'value' => 1, 'uncheckValue' => 0
                                            )
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                            array('value' => 1, 'uncheckValue' => 0)
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
                                    echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'cep',
                                        array('class' => 'control-label')
                                    );
                                    ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'cep',
                                        array(
                                            'placeholder' => 'Digite o CEP',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl(
                                                    'Instructor/getcitybycep'
                                                ),
                                                'data' => array('cep' => 'js:this.value'),
                                                'success' => "function(data){
                                                updateCep(data);
                                                }"
                                            ),
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'cep'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php
                                    echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'address',
                                        array('class' => 'control-label')
                                    );
                                    ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'address',
                                        array(
                                            'size' => 60,
                                            'maxlength' => 100,
                                            'placeholder' => 'Digite o Endereço'
                                        )
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
                                    <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'address_number', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'address_number',
                                        array(
                                            'size' => 10,
                                            'maxlength' => 10,
                                            'placeholder' => 'Digite o Número'
                                        )
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
                                    echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'complement',
                                        array(
                                            'class' => 'control-label'
                                        )
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'complement',
                                        array(
                                            'size' => 20, 'maxlength' => 20, 'placeholder' => 'Digite o Complemento'
                                        )
                                    ); ?>

                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'complement'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'neighborhood',
                                        array(
                                            'class' => 'control-label'
                                        )
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField(
                                        $modelInstructorDocumentsAndAddress,
                                        'neighborhood',
                                        array(
                                            'size' => 50,
                                            'maxlength' => 50,
                                            'placeholder' => 'Digite o Bairro ou Povoado'
                                        )
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
                                    echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_uf_fk',
                                        array(
                                            'class' => 'control-label'
                                        )
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_uf_fk',
                                        CHtml::listData(EdcensoUf::model()->findAll(
                                            array("order" => "name")
                                        ), 'id', 'name'),
                                        array(
                                            'prompt' => 'Selecione um estado',
                                            'class' => 'select-search-on control-input',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl('Instructor/getcity'),
                                                'update' => '#InstructorDocumentsAndAddress_edcenso_city_fk',
                                                'data' => array('edcenso_uf_fk' => 'js:this.value'),
                                            )
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_city_fk',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorDocumentsAndAddress,
                                        'edcenso_city_fk',
                                        CHtml::listData(
                                            EdcensoCity::model()->findAllByAttributes(array(
                                                'edcenso_uf_fk' => $modelInstructorDocumentsAndAddress->edcenso_uf_fk
                                            )),
                                            'id',
                                            'name'
                                        ),
                                        array(
                                            "prompt" => "Selecione uma cidade",
                                            "class" => "select-search-on control-input"
                                        )
                                    ); ?>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'edcenso_city_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'diff_location',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $modelInstructorDocumentsAndAddress,
                                        'diff_location',
                                        array(
                                            null => 'Selecione a localização',
                                            7 => 'Não reside em área de localização diferenciada',
                                            3 => 'Área onde se localiza comunidade remanescente de quilombos',
                                            2 => 'Terra indígena', 1 => 'Área de assentamento',
                                            8 => 'Área onde se localiza povos e comunidades tradicionais'
                                        ),
                                        array("class" => "select-search-on control-input")
                                    ); ?>
                                    <div class="controls">
                                        <?php echo $form->error(
                                            $modelInstructorDocumentsAndAddress,
                                            'diff_location'
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx(
                                        $modelInstructorDocumentsAndAddress,
                                        'area_of_residence',
                                        array('class' => 'control-label required')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownlist(
                                        $modelInstructorDocumentsAndAddress,
                                        'area_of_residence',
                                        array(null => "Selecione uma localização", 1 => 'URBANA', 2 => 'RURAL'),
                                        array("class" => "select-search-off control-input")
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
                                    <?php echo $form->labelEx(
                                        $modelInstructorVariableData,
                                        'scholarity',
                                        array('class' => 'control-label')
                                    ); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->DropDownlist($modelInstructorVariableData, 'scholarity', array(
                                        null => "Selecione uma escolaridade",
                                        1 => 'Fundamental Incompleto',
                                        2 => 'Fundamental Completo',
                                        3 => 'Ensino Médio - Normal/Magistério',
                                        4 => 'Ensino Médio - Normal/Magistério Indígena',
                                        7 => 'Ensino Médio',
                                        6 => 'Superior'
                                    ), array("class" => "select-search-off control-input")); ?>
                                    <?php echo $form->error($modelInstructorVariableData, 'scholarity'); ?>
                                </div>
                            </div>

                            <div class="separator"></div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label">Pos-Graduação</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_specialization'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'post_graduation_specialization',
                                            array(
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_master'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'post_graduation_master',
                                            array(
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_doctorate'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'post_graduation_doctorate',
                                            array('value' => 1, 'uncheckValue' => 0)
                                        ); ?>
                                    </label>
                                    <label class="checkbox" style="display:none;">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_none'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'post_graduation_none',
                                            array(
                                                'checked' => true,
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label">Outros Cursos</label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_nursery'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_nursery',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_pre_school'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_pre_school',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_basic_education_initial_years'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_basic_education_initial_years',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_basic_education_final_years'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_basic_education_final_years',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_high_school'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_high_school',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_education_of_youth_and_adults'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_education_of_youth_and_adults',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_special_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_special_education',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_native_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_native_education',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_field_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_field_education',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_environment_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_environment_education',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_human_rights_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_human_rights_education',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_bilingual_education_for_the_deaf'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_bilingual_education_for_the_deaf',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_education_and_tic'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_education_and_tic',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorVariableData::model()->attributeLabels()['other_courses_sexual_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_sexual_education',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,

                                                'uncheckValue' => 0
                                            )
                                        ); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorVariableData::model()->attributeLabels()['other_courses_child_and_teenage_rights'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_child_and_teenage_rights',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorVariableData::model()->attributeLabels()['other_courses_ethnic_education'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_ethnic_education',
                                            array(
                                                'class' => 'other_courses', 'value' => 1, 'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_other'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_other',
                                            array(
                                                'class' => 'other_courses',
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
                                        );
                                        ?>
                                    </label>
                                    <label class="checkbox" style="display:none;">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_none'];
                                        echo $form->checkBox(
                                            $modelInstructorVariableData,
                                            'other_courses_none',
                                            array(
                                                'value' => 1,
                                                'uncheckValue' => 0
                                            )
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
                                            <li id="tab-instructor-data1" class="sub-active">
                                                <a class="glyphicons certificate" href="#instructor-data1" data-toggle="tab">
                                                    <?php
                                                    echo Yii::t('default', 'Course') . ' 1'
                                                    ?>
                                                </a>
                                            </li>
                                            <li id="tab-instructor-data2">
                                                <a class="glyphicons certificate" href="#instructor-data2" data-toggle="tab">
                                                    <?php
                                                    echo Yii::t('default', 'Course') . ' 2'
                                                    ?>
                                                </a>
                                            </li>
                                            <li id="tab-instructor-data3">
                                                <a class="glyphicons certificate" href="#instructor-data3" data-toggle="tab">
                                                    <?php
                                                    echo Yii::t('default', 'Course') . ' 3'
                                                    ?>
                                                </a>
                                            </li>
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_1',
                                                                    array(
                                                                        'class' => 'control-label required indicator'
                                                                    )
                                                                );
                                                                ?>

                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->DropDownlist($modelInstructorVariableData, 'high_education_situation_1', array(
                                                                    null => "Selecione a situação",
                                                                    1 => 'Concluído',
                                                                    2 => 'Em andamento'
                                                                ), array(
                                                                    'class' => 'select-search-off control-input'
                                                                ));
                                                                ?>
                                                                <?php
                                                                echo $form->error(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_1'
                                                                );
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_formation_1',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->CheckBox(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_formation_1',
                                                                    array(
                                                                        'value' => 1, 'uncheckValue' => 0
                                                                    )
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
                                                                ), 'high_education_course_area1', array(
                                                                    'class' => 'control-label'
                                                                ));
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::DropDownList(
                                                                    'high_education_course_area1',
                                                                    '',
                                                                    CHtml::listData(
                                                                        EdcensoCourseOfHigherEducation::model()->findAll(
                                                                            array(
                                                                                'group' => 'cod, area',
                                                                                'select' => 'cod, area'
                                                                            )
                                                                        ),
                                                                        'cod',
                                                                        'area'
                                                                    ),
                                                                    array(
                                                                        'class' => 'select-search-off control-input',
                                                                        'prompt' => 'Selecione a Área de Atuação',
                                                                        'ajax' => array(
                                                                            'type' => 'POST',
                                                                            'url' => CController::createUrl('instructor/getCourses&tdid=1'),
                                                                            'success' => "function(data){
                                                                            val = $('#InstructorVariableData_high_education_course_code_1_fk').val();
                                                                            $('#InstructorVariableData_high_education_course_code_1_fk').html(data).val(val).trigger('change');
                                                                        }",
                                                                        )
                                                                    )
                                                                ); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_1_fk',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->DropDownlist(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_1_fk',
                                                                    CHtml::listData(
                                                                        EdcensoCourseOfHigherEducation::model()->findAll(
                                                                            array(
                                                                                'order' => 'name'
                                                                            )
                                                                        ),
                                                                        'id',
                                                                        'name'
                                                                    ),
                                                                    array(
                                                                        'prompt' => 'Selecione o curso 1',
                                                                        "class" => "select-search-on control-input",
                                                                    )
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_initial_year_1',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_initial_year_1',
                                                                    array(
                                                                        'size' => 4, 'maxlength' => 4
                                                                    )
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
                                                                <?php echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_final_year_1',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_final_year_1',
                                                                    array(
                                                                        'size' => 4, 'maxlength' => 4
                                                                    )
                                                                );
                                                                ?>
                                                                <?php echo $form->error(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_final_year_1'
                                                                ); ?>
                                                            </div>
                                                        </div>
                                                        <!-- Estado -->
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                Estado
                                                            </div>
                                                            <div class="controls">
                                                                <select id="IES">
                                                                    <?php
                                                                    $ufs = [
                                                                        "00" => "Selecione um estado",
                                                                        "11" => "Rondônia",
                                                                        "12" => "Acre",
                                                                        "13" => "Amazonas",
                                                                        "14" => "Roraima",
                                                                        "15" => "Pará",
                                                                        "16" => "Amapá",
                                                                        "17" => "Tocantins",
                                                                        "21" => "Maranhão",
                                                                        "22" => "Piauí",
                                                                        "23" => "Ceará",
                                                                        "24" => "Rio Grande do Norte",
                                                                        "25" => "Paraíba",
                                                                        "26" => "Pernambuco",
                                                                        "27" => "Alagoas",
                                                                        "28" => "Sergipe",
                                                                        "29" => "Bahia",
                                                                        "31" => "Minas Gerais",
                                                                        "32" => "Espírito Santo",
                                                                        "33" => "Rio de Janeiro",
                                                                        "35" => "São Paulo",
                                                                        "41" => "Paraná",
                                                                        "42" => "Santa Catarina",
                                                                        "43" => "Rio Grande do Sul",
                                                                        "50" => "Mato Grosso do Sul",
                                                                        "51" => "Mato Grosso",
                                                                        "52" => "Goiás",
                                                                        "53" => "Distrito Federal"
                                                                    ];

                                                                    foreach ($ufs as $k => $uf) {
                                                                        if ($k == $modelInstructorVariableData->highEducationInstitutionCode1Fk->edcenso_uf_fk) {
                                                                            echo "<option value=" . $k . " selected>" . $uf . "</option>";
                                                                        } else {
                                                                            echo "<option value=" . $k . " >" . $uf . "</option>";
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <?php echo $form->error($modelInstructorIdentification, 'edcenso_uf_fk'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_code_1_fk', array('class' => 'control-label required indicator')); ?>

                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->DropDownList($modelInstructorVariableData, 'high_education_institution_code_1_fk', [$modelInstructorVariableData->high_education_institution_code_1_fk  =>  $modelInstructorVariableData->high_education_institution_code_1_fk->name] , array("style" => "width:425px;", 'class' => 'select-search-on control-input')); ?>
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
                                                        <!-- Curso 2 -->
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_situation_2', array('class' => 'control-label required indicator')); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->DropDownList($modelInstructorVariableData, 'high_education_situation_2', array(null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em Andamento'), array("class" => "select-search-off control-input")); ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_situation_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_formation_2', array('class' => 'control-label')); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo $form->CheckBox($modelInstructorVariableData, 'high_education_formation_2'); ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_formation_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php echo CHtml::label(Yii::t('default', 'Area'), 'high_education_course_area2', array('class' => 'control-label')); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::DropDownList(
                                                                    'high_education_course_area2',
                                                                    '', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(array('group' => 'cod, area', 'select' => 'cod, area')), 'cod', 'area'),
                                                                    array(
                                                                        'class' => 'select-search-off control-input',
                                                                        'prompt' => 'Selecione a Área de Atuação',
                                                                        'ajax' => array(
                                                                            'type' => 'POST',
                                                                            'url' => CController::createUrl('instructor/getCourses&tdid=2'),
                                                                            'success' => "function(data){
                                                                            val = $('#InstructorVariableData_high_education_course_code_2_fk').val();
                                                                            $('#InstructorVariableData_high_education_course_code_2_fk').html(data).val(val).trigger('change');
                                                                        }",
                                                                        )
                                                                    )
                                                                ); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_2_fk',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
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
                                                                    array(
                                                                        'prompt' => 'Selecione o curso 2',
                                                                        'class' => 'select-search-on control-input'
                                                                    )
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
                                                                <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_initial_year_2', array('class' => 'control-label')); ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_initial_year_2',
                                                                    array(
                                                                        'size' => 4, 'maxlength' => 4
                                                                    )
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
                                                                echo $form->labelEx($modelInstructorVariableData, 'high_education_final_year_2', array('class' => 'control-label'));
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_final_year_2',
                                                                    array('size' => 4, 'maxlength' => 4)
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_institution_code_2_fk',
                                                                    array(
                                                                        'class' => 'control-label required indicator'
                                                                    )
                                                                );
                                                                ?>


                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_institution_code_2_fk',
                                                                    array(
                                                                        "style" => "width:425px;"
                                                                    )
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_3',
                                                                    array(
                                                                        'class' => 'control-label required indicator'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->DropDownList(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_situation_3',
                                                                    array(
                                                                        null => "Selecione a situação",
                                                                        1 => 'Concluído',
                                                                        2 => 'Em Andamento'
                                                                    ),
                                                                    array(
                                                                        'class' => 'select-search-off control-input'
                                                                    )
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_formation_3',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
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
                                                                ), 'high_education_course_area3', array(
                                                                    'class' => 'control-label'
                                                                ));
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php echo CHtml::DropDownList(
                                                                    'high_education_course_area3',
                                                                    '',
                                                                    CHtml::listData(
                                                                        EdcensoCourseOfHigherEducation::model()->findAll(
                                                                            array(
                                                                                'group' => 'cod, area',
                                                                                'select' => 'cod, area'
                                                                            )
                                                                        ),
                                                                        'cod',
                                                                        'area'
                                                                    ),
                                                                    array(
                                                                        'class' => 'select-search-off control-input',
                                                                        'prompt' => 'Selecione a Área de Atuação',
                                                                        'ajax' => array(
                                                                            'type' => 'POST',
                                                                            'url' => CController::createUrl('instructor/getCourses&tdid=3'),
                                                                            'success' => "function(data){
                                                                            val = $('#InstructorVariableData_high_education_course_code_3_fk').val();
                                                                            $('#InstructorVariableData_high_education_course_code_3_fk').html(data).val(val).trigger('change');
                                                                        }",
                                                                        )
                                                                    )
                                                                ); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_course_code_3_fk',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
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
                                                                    array(
                                                                        'prompt' => 'Selecione o curso 3',
                                                                        'class' => 'select-search-on control-input'
                                                                    )
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_initial_year_3',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_initial_year_3',
                                                                    array(
                                                                        'size' => 4,
                                                                        'maxlength' => 4
                                                                    )
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
                                                                <?php echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_final_year_3',
                                                                    array(
                                                                        'class' => 'control-label'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_final_year_3',
                                                                    array(
                                                                        'size' => 4, 'maxlength' => 4
                                                                    )
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
                                                                echo $form->labelEx(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_institution_code_3_fk',
                                                                    array(
                                                                        'class' => 'control-label required indicator'
                                                                    )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="controls">
                                                                <?php
                                                                echo $form->textField(
                                                                    $modelInstructorVariableData,
                                                                    'high_education_institution_code_3_fk',
                                                                    array(
                                                                        "style" => "width:425px;"
                                                                    )
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
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
    $this->widget('application.widgets.AlertCensoWidget', array('prefix' => 'instructor', 'dataId' => $_GET['id']));
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
