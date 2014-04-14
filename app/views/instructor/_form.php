<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'instructor-form',
    'enableAjaxValidation' => false,
        ));

$isModel = isset($modelInstructorIdentification->id); // Corrigir se precisar acessar os atributos
echo $form->errorSummary($modelInstructorIdentification);
echo $form->errorSummary($modelInstructorDocumentsAndAddress);
echo isset($error['documentsAndAddress']) ? $error['documentsAndAddress'] : '';
echo $form->errorSummary($modelInstructorVariableData);
echo isset($error['variableData']) ? $error['variableData'] : '';
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></h3>  
        <div class="buttons">
            <a  data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            <a  data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default', 'Next') ?><i></i></a>
            <?php echo CHtml::htmlButton('<i></i>' . ($modelInstructorIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'style' => 'display:none', 'type' => 'submit'));
            ?>
        </div>
    </div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul class="tab-instructor">
                <li id="tab-instructor-identify"class="active"><a class="glyphicons vcard" href="#instructor-identify" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Identification') ?></a></li>
                <li id="tab-instructor-address"><a class="glyphicons home" href="#instructor-address" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Address') ?></a></li>
                <li id="tab-instructor-data"><a class="glyphicons certificate" href="#instructor-data" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Variable Data') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="instructor-identify">
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="separator"></div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorIdentification, 'name', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorIdentification, 'name'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'cpf', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 11)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'email', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorIdentification, 'email', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Email'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorIdentification, 'email'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'nis', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorIdentification, 'nis', array('size' => 11, 'maxlength' => 11)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '11'; ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorIdentification, 'nis'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'birthday_date', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorIdentification, 'birthday_date', array('size' => 10, 'maxlength' => 10)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Date'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorIdentification, 'birthday_date'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'sex', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownlist($modelInstructorIdentification, 'sex', array(null => "Selecione um sexo", 1 => 'Masculino', 2 => 'Feminino'), array("class" => "select-search-off")); ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'sex'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'color_race', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelInstructorIdentification, 'color_race', array(null => "Selecione uma raça", 0 => "Não Declarada",
                                        1 => "Branca", 2 => "Preta", 3 => "Parda", 4 => "Amarela", 5 => "Indígena"), array("class" => "select-search-off"));
                                    ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'color_race'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'mother_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorIdentification, 'mother_name', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('default', 'Full name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorIdentification, 'mother_name'); ?>
                                </div>
                            </div>
                        </div>

                        <div class=" span5">
                            <div class="separator"></div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'nationality', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelInstructorIdentification, 'nationality', array(null => "Selecione uma nacionalidade", 1 => "Brasileira",
                                        2 => "Brasileira nascido no Exterior ou Naturalizado", 3 => "Estrangeira"), array("class" => "select-search-off"));
                                    ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'nationality'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'edcenso_nation_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo ($isModel && isset($modelInstructorIdentification->edcenso_nation_fk) ) ?
                                            $form->DropDownList($modelInstructorIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array("prompt" => "Selecione um país", "class" => "select-search-on"), array('options' => array(76 => array('selected' => true)))) : $form->DropDownList($modelInstructorIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array("prompt" => "Selecione um país", "class" => "select-search-on"), array('options' => array(76 => array('selected' => true))
                                                , 'disabled' => 'true'));
                                    ?>

                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_nation_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelInstructorIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array("order" => "name")), 'id', 'name'), array(
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('Instructor/getcity'),
                                            'update' => '#InstructorIdentification_edcenso_city_fk',
                                            'data' => array('edcenso_uf_fk' => 'js:this.value'),
                                        ),
                                    ));
                                    ?>                    
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelInstructorIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelInstructorIdentification->edcenso_uf_fk)), 'id', 'name'), array("prompt" => "Selecione uma cidade", "class" => "select-search-on")); ?>                  
                                    <?php echo $form->error($modelInstructorIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorIdentification, 'deficiency', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo ($isModel && isset($modelInstructorIdentification->deficiency)) ? CHtml::activeCheckBox($modelInstructorIdentification, 'deficiency') : CHtml::activeCheckBox($modelInstructorIdentification, 'deficiency');
                                    ?>
                                    <?php echo $form->error($modelInstructorIdentification, 'deficiency'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Deficiency Type'); ?></label>
                                <div class="uniformjs margin-left" id="InstructorIdentification_deficiencies">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorIdentification::model()->attributeLabels()['deficiency_type_blindness'];
                                        echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                                        <?php echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                                        <?php echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                        <?php echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                                        <?php echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                        <?php echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo InstructorIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                        <?php echo $form->checkBox($modelInstructorIdentification, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0, 'disabled' => true)); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->hiddenField($modelInstructorIdentification, 'deficiency_type_multiple_disabilities'); ?>
                                <?php echo $form->error($modelInstructorIdentification, 'deficiency_type_multiple_disabilities'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="instructor-address">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="separator"></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'area_of_residence', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownlist($modelInstructorDocumentsAndAddress, 'area_of_residence', array(null => "Selecione uma localização", 1 => 'URBANA', 2 => 'RURAL'), array("class" => "select-search-off")); ?>                 
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'area_of_residence'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'cep', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->textField($modelInstructorDocumentsAndAddress, 'cep', array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('Instructor/getcitybycep'),
                                            'data' => array('cep' => 'js:this.value'),
                                            'success' => "function(data){
                                     data = jQuery.parseJSON(data);
                                     if(data.UF == null) $(formDocumentsAndAddress+'cep').val('').trigger('focusout');
                                     $(formDocumentsAndAddress+'edcenso_uf_fk').val(data['UF']).trigger('change').select2('readonly',data.UF != null);
                                     setTimeout(function(){
                                        $(formDocumentsAndAddress+'edcenso_city_fk').val(data['City']).trigger('change').select2('readonly',data.City != null);
                                        }, 500);
                                    }"
                                    )));
                                    ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Valid Cep') . " " . Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '8.'; ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'cep'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'address', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorDocumentsAndAddress, 'address', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'address'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'address_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorDocumentsAndAddress, 'address_number', array('size' => 10, 'maxlength' => 10)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'address_number'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'complement', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorDocumentsAndAddress, 'complement', array('size' => 20, 'maxlength' => 20)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'complement'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'neighborhood', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelInstructorDocumentsAndAddress, 'neighborhood', array('size' => 50, 'maxlength' => 50)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'neighborhood'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelInstructorDocumentsAndAddress, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array("order" => "name")), 'id', 'name'), array(
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('Instructor/getcity'),
                                            'update' => '#InstructorDocumentsAndAddress_edcenso_city_fk',
                                            'data' => array('edcenso_uf_fk' => 'js:this.value'),
                                    )));
                                    ?>                    
                                    <?php echo $form->error($modelInstructorDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelInstructorDocumentsAndAddress, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelInstructorDocumentsAndAddress, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAll(array("order" => "name")), 'id', 'name'), array("prompt" => "Selecione uma cidade", "class" => "select-search-on")); ?>

                                    <div class="controls">                    
                                        <?php echo $form->error($modelInstructorDocumentsAndAddress, 'edcenso_city_fk'); ?>
                                    </div>
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
                                <?php echo $form->labelEx($modelInstructorVariableData, 'scholarity', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownlist($modelInstructorVariableData, 'scholarity', array(null => "Selecione uma escolaridade", 1 => 'Fundamental Incompleto', 2 => 'Fundamental Completo',
                                        3 => 'Ensino Médio - Normal/Magistério', 4 => 'Ensino Médio - Normal/Magistério Indígena',
                                        5 => 'Ensino Médio', 6 => 'Superior'), array("class" => "select-search-off"));
                                    ?>
                                    <?php echo $form->error($modelInstructorVariableData, 'scholarity'); ?>
                                </div>
                            </div>

                            <div class="separator"></div>

                            <div class="control-group">
                                <label class="control-label">Pos-Graduação</label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_specialization'];
                                        $validate = $isModel && isset($modelInstructorVariableData->post_graduation_specialization);
                                        echo $form->checkBox($modelInstructorVariableData, 'post_graduation_specialization', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_master'];
                                        $validate = $isModel && isset($modelInstructorVariableData->post_graduation_master);
                                        echo $form->checkBox($modelInstructorVariableData, 'post_graduation_master', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_doctorate'];
                                        $validate = $isModel && isset($modelInstructorVariableData->post_graduation_doctorate);
                                        echo $form->checkBox($modelInstructorVariableData, 'post_graduation_doctorate', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['post_graduation_none'];
                                        $validate = $isModel && isset($modelInstructorVariableData->post_graduation_none);
                                        echo $form->checkBox($modelInstructorVariableData, 'post_graduation_none', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Outros Cursos</label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_nursery'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_nursery);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_nursery', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_pre_school'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_pre_school);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_pre_school', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_basic_education_initial_years'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_basic_education_initial_years);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_basic_education_initial_years', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_basic_education_final_years'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_basic_education_final_years);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_basic_education_final_years', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_high_school'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_high_school);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_high_school', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_education_of_youth_and_adults'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_education_of_youth_and_adults);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_education_of_youth_and_adults', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_special_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_special_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_special_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_native_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_native_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_native_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_field_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_field_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_field_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_environment_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_environment_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_environment_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_human_rights_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_human_rights_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_human_rights_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_sexual_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_sexual_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_sexual_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_child_and_teenage_rights'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_child_and_teenage_rights);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_child_and_teenage_rights', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_ethnic_education'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_ethnic_education);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_ethnic_education', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_other'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_other);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_other', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php
                                        echo InstructorVariableData::model()->attributeLabels()['other_courses_none'];
                                        $validate = $isModel && isset($modelInstructorVariableData->other_courses_none);
                                        echo $form->checkBox($modelInstructorVariableData, 'other_courses_none', array('disabled' => !$validate, 'value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                </div>
                            </div>
                        </div>    

                        <div class="span6">

                            <div class="separator"></div>
                            <div class="innerLR" id="instructorVariableData">
                                <div class="widget widget-tabs border-bottom-none">
                                    <div class="widget-head">
                                        <ul class="tab-instructordata">
                                            <li id="tab-instructor-data1" class="sub-active"><a class="glyphicons certificate" href="#instructor-data1" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Data') . ' 1' ?></a></li>
                                            <li id="tab-instructor-data2"><a class="glyphicons certificate" href="#instructor-data2" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Data') . ' 2' ?></a></li>
                                            <li id="tab-instructor-data3"><a class="glyphicons certificate" href="#instructor-data3" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Data') . ' 3' ?></a></li>
                                        </ul>
                                    </div>

                                    <div class="widget-body form-horizontal">
                                        <div class="tab-content">

                                            <!-- Tab content -->
                                            <div class="sub-active" id="instructor-data1">
                                                <div class="row-fluid">
                                                    <div class=" span6">
                                                        <div class="separator"></div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_situation_1', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php echo ($isModel && isset($modelInstructorVariableData->high_education_situation_1)) ?
                                                                        $form->DropDownlist($modelInstructorVariableData, 'high_education_situation_1', array(null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em andamento'), array('class' => 'select-search-off')) : $form->DropDownlist($modelInstructorVariableData, 'high_education_situation_1', array(null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em andamento'), array('class' => 'select-search-off', 'disabled' => 'disabled'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_situation_1'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_formation_1', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php echo ($isModel && isset($modelInstructorVariableData->high_education_formation_1)) ?
                                                                        CHtml::activeCheckBox($modelInstructorVariableData, 'high_education_formation_1') : CHtml::activeCheckBox($modelInstructorVariableData, 'high_education_formation_1', array('disabled' => 'disabled'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_formation_1'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo CHtml::label(Yii::t('default', 'Area'), 'high_education_course_area1', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo CHtml::DropDownList('high_education_course_area1', '', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(array('group' => 'cod')), 'cod', 'area'), array(
                                                                    'class' => 'select-search-off',
                                                                    'prompt' => 'Selecione a Área de Atuação',
                                                                    'ajax' => array(
                                                                        'type' => 'POST',
                                                                        'url' => CController::createUrl('instructor/getCourses&tdid=1'),
                                                                        'update' => '#InstructorVariableData_high_education_course_code_1_fk',
                                                                )));
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php
                                                            echo $form->labelEx($modelInstructorVariableData, 'high_education_course_code_1_fk', array('class' => 'control-label'));
                                                            ?>
                                                            <div class="controls">
                                                                <?php
                                                                $validate = $isModel && isset($modelInstructorVariableData->high_education_course_code_1_fk);
                                                                echo $form->DropDownlist($modelInstructorVariableData, 'high_education_course_code_1_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                                                    'prompt' => 'Selecione o curso 1',
                                                                    "class" => "select-search-on",
                                                                    'disabled' => !$validate
                                                                ));
                                                                echo $form->error($modelInstructorVariableData, 'high_education_course_code_1_fk');
                                                                ?>
                                                            </div>
                                                        </div>
                                                            
                                                        <div class="control-group">
                                                                <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_initial_year_1', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_initial_year_1)) ?
                                                                        $form->textField($modelInstructorVariableData, 'high_education_initial_year_1', array('size' => 4, 'maxlength' => 4)) : $form->textField($modelInstructorVariableData, 'high_education_initial_year_1', array('size' => 4, 'maxlength' => 4, 'disabled' => 'disabled'));
                                                                ?>
                                                                <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_initial_year_1'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_final_year_1', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_final_year_1)) ?
                                                                        $form->textField($modelInstructorVariableData, 'high_education_final_year_1', array('size' => 4, 'maxlength' => 4)) : $form->textField($modelInstructorVariableData, 'high_education_final_year_1', array('size' => 4, 'maxlength' => 4, 'disabled' => 'disabled'));
                                                                ?>
                                                                <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_final_year_1'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_type_1', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                $validate = $isModel && isset($modelInstructorVariableData->high_education_institution_type_1);
                                                                echo $form->DropDownList($modelInstructorVariableData, 'high_education_institution_type_1', array(null => "Selecione o tipo", 1 => "Pública", 2 => "Privada"), array('class' => 'select-search-off'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_institution_type_1'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_code_1_fk', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                $validate = $isModel && isset($modelInstructorVariableData->high_education_institution_code_1_fk);
                                                                echo $form->textField($modelInstructorVariableData, 'high_education_institution_code_1_fk', array("style" => "width:220px;", "disabled" => !$validate));
                                                                ?>                                                            
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
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_situation_2', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_situation_2)) ?
                                                                        $form->DropDownList($modelInstructorVariableData, 'high_education_situation_2', array(
                                                                            null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em Andamento'
                                                                                ), array("class" => "select-search-off")) : $form->DropDownList($modelInstructorVariableData, 'high_education_situation_2', array(
                                                                            null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em Andamento'
                                                                                ), array('disabled' => 'disabled', "class" => "select-search-off"));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_situation_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_formation_2', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_formation_2)) ?
                                                                        CHtml::activeCheckBox($modelInstructorVariableData, 'high_education_formation_2') : CHtml::activeCheckBox($modelInstructorVariableData, 'high_education_formation_2', array('disabled' => true));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_formation_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo CHtml::label(Yii::t('default', 'Area'), 'high_education_course_area2', array('class' => 'control-label'));
                                                            ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo CHtml::DropDownList('high_education_course_area2', '', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(array('group' => 'cod')), 'cod', 'area'), array(
                                                                    'class' => 'select-search-off',
                                                                    'prompt' => 'Selecione a Área de Atuação',
                                                                    'ajax' => array(
                                                                        'type' => 'POST',
                                                                        'url' => CController::createUrl('instructor/getCourses&tdid=2'),
                                                                        'update' => '#InstructorVariableData_high_education_course_code_2_fk',
                                                                )));
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_course_code_2_fk', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_course_code_2_fk)) ?
                                                                        $form->DropDownList($modelInstructorVariableData, 'high_education_course_code_2_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name')
                                                                                , array('prompt' => 'Selecione o curso 2', 'class' => 'select-search-on')) : $form->DropDownList($modelInstructorVariableData, 'high_education_course_code_2_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name')
                                                                                , array('prompt' => 'Selecione o curso 2', 'class' => 'select-search-on', 'disabled' => 'disabled'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_course_code_2_fk'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_initial_year_2', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_initial_year_2)) ?
                                                                        $form->textField($modelInstructorVariableData, 'high_education_initial_year_2', array('size' => 4, 'maxlength' => 4)) : $form->textField($modelInstructorVariableData, 'high_education_initial_year_2', array('size' => 4, 'maxlength' => 4, 'disabled' => 'disabled'));
                                                                ?>
                                                                <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_initial_year_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_final_year_2', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_initial_year_2)) ?
                                                                        $form->textField($modelInstructorVariableData, 'high_education_initial_year_2', array('size' => 4, 'maxlength' => 4)) : $form->textField($modelInstructorVariableData, 'high_education_initial_year_2', array('size' => 4, 'maxlength' => 4, 'disabled' => 'disabled'));
                                                                ?>
                                                                <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_final_year_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_type_2', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                $validate = $isModel && isset($modelInstructorVariableData->high_education_institution_type_2);
                                                                echo $form->DropDownList($modelInstructorVariableData, 'high_education_institution_type_2', array(null => "Selecione o tipo", 1 => "Pública", 2 => "Privada"), array('class' => 'select-search-off'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_institution_type_2'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_code_2_fk', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                            <?php
                                                            $validate = $isModel && isset($modelInstructorVariableData->high_education_institution_code_1_fk);
                                                            echo $form->textField($modelInstructorVariableData, 'high_education_institution_code_1_fk', array("style" => "width:220px;", "disabled" => !$validate));
                                                            ?>   
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_institution_code_2_fk'); ?>
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
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_situation_3', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_situation_3)) ?
                                                                        $form->DropDownList($modelInstructorVariableData, 'high_education_situation_3', array(
                                                                            null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em Andamento'
                                                                                ), array('class' => 'select-search-off')) : $form->DropDownList($modelInstructorVariableData, 'high_education_situation_3', array(
                                                                            null => "Selecione a situação", 1 => 'Concluído', 2 => 'Em Andamento'
                                                                                ), array('disabled' => 'disabled', 'class' => 'select-search-off'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_situation_3'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_formation_3', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_formation_3)) ?
                                                                        CHtml::activeCheckBox($modelInstructorVariableData, 'high_education_formation_3') : CHtml::activeCheckBox($modelInstructorVariableData, 'high_education_formation_3', array('disabled' => true));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_formation_3'); ?>
                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <?php echo CHtml::label(Yii::t('default', 'Area'), 'high_education_course_area3', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo CHtml::DropDownList('high_education_course_area3', '', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(array('group' => 'cod')), 'cod', 'area'), array(
                                                                    'class' => 'select-search-off',
                                                                    'prompt' => 'Selecione a Área de Atuação',
                                                                    'ajax' => array(
                                                                        'type' => 'POST',
                                                                        'url' => CController::createUrl('instructor/getCourses&tdid=3'),
                                                                        'update' => '#InstructorVariableData_high_education_course_code_3_fk',
                                                                )));
                                                                ?>
                                                            </div>
                                                        </div>



                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_course_code_3_fk', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_course_code_3_fk)) ?
                                                                        $form->DropDownList($modelInstructorVariableData, 'high_education_course_code_3_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name')
                                                                                , array('prompt' => 'Selecione o curso 3', 'class' => 'select-search-on')) : $form->DropDownList($modelInstructorVariableData, 'high_education_course_code_3_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name')
                                                                                , array('prompt' => 'Selecione o curso 3', 'disabled' => 'disabled', 'class' => 'select-search-on'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_course_code_3_fk'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_initial_year_3', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_initial_year_3)) ?
                                                                        $form->textField($modelInstructorVariableData, 'high_education_initial_year_3', array('size' => 4, 'maxlength' => 4)) : $form->textField($modelInstructorVariableData, 'high_education_initial_year_3', array('size' => 4, 'maxlength' => 4, 'disabled' => 'disabled'));
                                                                ?>
                                                                <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_initial_year_3'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                                <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_final_year_3', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                echo ($isModel && isset($modelInstructorVariableData->high_education_final_year_3)) ?
                                                                        $form->textField($modelInstructorVariableData, 'high_education_final_year_3', array('size' => 4, 'maxlength' => 4)) : $form->textField($modelInstructorVariableData, 'high_education_final_year_3', array('size' => 4, 'maxlength' => 4, 'disabled' => 'disabled'));
                                                                ?>
                                                                <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_final_year_3'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_type_3', array('class' => 'control-label')); ?>
                                                            <div class="controls">
                                                                <?php
                                                                $validate = $isModel && isset($modelInstructorVariableData->high_education_institution_type_3);
                                                                echo $form->DropDownList($modelInstructorVariableData, 'high_education_institution_type_3', array(null => "Selecione o tipo", 1 => "Pública", 2 => "Privada"), array('class' => 'select-search-off'));
                                                                ?>
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_institution_type_3'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <?php echo $form->labelEx($modelInstructorVariableData, 'high_education_institution_code_3_fk', array('class' => 'control-label')); ?>
                                                            <div class="controls"> 
                                                                <?php
                                                                $validate = $isModel && isset($modelInstructorVariableData->high_education_institution_code_1_fk);
                                                                echo $form->textField($modelInstructorVariableData, 'high_education_institution_code_1_fk', array("style" => "width:220px;", "disabled" => !$validate));
                                                                ?>   
                                                                <?php echo $form->error($modelInstructorVariableData, 'high_education_institution_code_3_fk'); ?>
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

<script type="text/javascript">
    var formInstructorIdentification = '#InstructorIdentification_';
    var formInstructorvariableData = "#InstructorVariableData_";  
    
    var filter = {1:0, 2:0, 3:0};
    var actualFilter = 0;
    
    var id = { 1: formInstructorvariableData+"high_education_institution_code_1_fk", 
               2: formInstructorvariableData+"high_education_institution_code_2_fk",
               3: formInstructorvariableData+"high_education_institution_code_3_fk"};
                
    $(formInstructorIdentification +'name,'+formInstructorIdentification +'mother_name').on('focusout', function(){
        var id = '#'+$(this).attr("id");
        
        $(this).val($(this).val().toUpperCase());
        
        if(!validateNamePerson(this.value)[0]){ 
            $(id).attr('value','');
            addError(id, "Campo Nome não está dentro das regras.");
        }else{
            removeError(id);
        }
        
    });
    $(formInstructorIdentification+'email').on('focusout', function(){
        var id = '#'+$(this).attr("id");
        
        $(id).val($(id).val().toUpperCase());
        
        if(!validateEmail($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo Email não está dentro das regras.");
        }else{
            removeError(id);
        }
        
    });
    
    $(formInstructorIdentification+'birthday_date').on('focusout', function(){
        var id = '#'+$(this).attr("id");
        
        $(id).val($(id).val().toUpperCase());
        
        if(!validateBirthdayPerson($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo Data não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    <?php 
    ?>
        
    $(formInstructorIdentification+'nationality').on('change', function(){
        var nationality = $(this).val();
        var nation = $(formInstructorIdentification+'edcenso_nation_fk');
        var uf = $(formInstructorIdentification+'edcenso_uf_fk');
        var city = $(formInstructorIdentification+'edcenso_city_fk');
        
        if(nationality == 2 || nationality == 1) {
            nation.val(76).trigger('change').add().select2('readonly', true);
            uf.val('').trigger('change').select2('enable', true);
            city.val('').trigger('change').select2('enable', true);
        }else if(nationality==3){
            nation.val('').trigger('change').select2("enable", true);
            nation.val('').trigger('change').select2('readonly', false);
            uf.val('').trigger('change').add().select2('disable', true);
            city.val('').trigger('change').add().select2('disable', true);
        }else{
            nation.val('').trigger('change').select2("disable", true);
            uf.val('').trigger('change').select2("disable", true);
            city.val('').trigger('change').select2("disable", true);
        }        
    });
    
    $(formInstructorIdentification+'deficiency').on('change', function(){
        if($(this).is(':checked')) { 
            $("#InstructorIdentification_deficiencies input").removeAttr('disabled');
        }else{
            $("#InstructorIdentification_deficiencies input").removeAttr('checked').add().attr('disabled','disabled');
        }
    }); 
    
    $(formInstructorIdentification+'deficiency_type_blindness').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }         
        
        if($(this).is(':checked')) { 
            $(formInstructorIdentification+'deficiency_type_low_vision').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_deafness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafness').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafblindness').add().attr('disabled','disabled');
        }else{
            $(formInstructorIdentification+'deficiency_type_low_vision').removeAttr('disabled');
            $(formInstructorIdentification+'deficiency_type_deafness').removeAttr('disabled');
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('disabled'); 
        }
    });
    
    $(formInstructorIdentification+'deficiency_type_low_vision').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }   
        if($(this).is(':checked')) { 
            $(formInstructorIdentification+'deficiency_type_blindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_blindness').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafblindness').add().attr('disabled','disabled'); 
        }else{
            $(formInstructorIdentification+'deficiency_type_blindness').removeAttr('disabled');
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('disabled');
        }
    });
    
    $(formInstructorIdentification+'deficiency_type_deafness').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }   
        if($(this).is(':checked')) { 
            $(formInstructorIdentification+'deficiency_type_blindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_blindness').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_disability_hearing').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_disability_hearing').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafblindness').add().attr('disabled','disabled');  
        }else{
            $(formInstructorIdentification+'deficiency_type_blindness').removeAttr('disabled');
            $(formInstructorIdentification+'deficiency_type_disability_hearing').removeAttr('disabled');      
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('disabled');
        }
    });
    
    $(formInstructorIdentification+'deficiency_type_disability_hearing').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }      
        if($(this).is(':checked')) { 
            $(formInstructorIdentification+'deficiency_type_deafness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafness').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafblindness').add().attr('disabled','disabled');
        }else{
            $(formInstructorIdentification+'deficiency_type_deafness').removeAttr('disabled');    
            $(formInstructorIdentification+'deficiency_type_deafblindness').removeAttr('disabled');
            
        }
    }); 
    $(formInstructorIdentification+'deficiency_type_deafblindness').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }       
        
        if($(this).is(':checked')) { 
            $(formInstructorIdentification+'deficiency_type_blindness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_blindness').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_low_vision').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_low_vision').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_deafness').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_deafness').add().attr('disabled','disabled');
            $(formInstructorIdentification+'deficiency_type_disability_hearing').removeAttr('checked','checked');
            $(formInstructorIdentification+'deficiency_type_disability_hearing').add().attr('disabled','disabled'); 
        }else{
            $(formInstructorIdentification+'deficiency_type_blindness').removeAttr('disabled');    
            $(formInstructorIdentification+'deficiency_type_low_vision').removeAttr('disabled'); 
            $(formInstructorIdentification+'deficiency_type_deafness').removeAttr('disabled');    
            $(formInstructorIdentification+'deficiency_type_disability_hearing').removeAttr('disabled');
        }
    });
    
    $(formInstructorIdentification+'deficiency_type_phisical_disability').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }    
    });
    
    $(formInstructorIdentification+'deficiency_type_intelectual_disability').on('click', function(){
        if(($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked')) 
            || ($(formInstructorIdentification+'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification+'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification+'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification+'deficiency_type_intelectual_disability').is(':checked'))
    ) {
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').add().attr('checked','checked');
        }else{
            $(formInstructorIdentification+'deficiency_type_multiple_disabilities').removeAttr('checked','checked');
        }    
    });
    
    var formDocumentsAndAddress = '#InstructorDocumentsAndAddress_';
    
    $(formDocumentsAndAddress+'cpf').on('change',function(){
        var id = '#'+$(this).attr("id");
        
        $(id).val($(id).val().toUpperCase());
        
        if(!validateCpf($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo CPF não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'cep').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        
        if(!validateCEP($(id).val())){
            $(this).attr('value',''); 
            addError(id, "Campo CEP não está dentro das regras.");
        }else{
            removeError(id);
            
        } 
        if ($(id).val().length == 0){
            $(formDocumentsAndAddress+'edcenso_uf_fk').val("").trigger('change').select2("readonly",false);
            $(formDocumentsAndAddress+'edcenso_city_fk').val("").trigger('change').select2("readonly",false);
        }
        
    });
    
    $(formDocumentsAndAddress+'address').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        
        if(!validateInstructorAddress($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo Endereço não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'address_number').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        
        if(!validateInstructorAddressNumber($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    $(formDocumentsAndAddress+'neighborhood').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        
        if(!validateInstructorAddressNeighborhood($(this).val())) { 
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });

    
    $('#InstructorVariableData_high_education_initial_year_1, \n\
    #InstructorVariableData_high_education_initial_year_2,\n\
    #InstructorVariableData_high_education_initial_year_3').on('change',function(){
        if(this.value.length == 4){
            var data = new Date();
            if(!anoMinMax(2002,data.getFullYear(),this.value)){
                $(this).attr('value','');
            }
        }
    });   
    $('#InstructorVariableData_high_education_final_year_1,\n\
    #InstructorVariableData_high_education_final_year_2,\n\
    #InstructorVariableData_high_education_final_year_3').on('change',function(){
        if(this.value.length == 4){
            var data = new Date();
            if(!anoMinMax(1941,data.getFullYear(),this.value)) {
                $(this).attr('value','');
            }
        } 
    }); 
    
    $(formInstructorvariableData+'high_education_institution_type_1').on('change', function(){
        filter[1] = $(this).val();
        actualFilter = filter[1];
    });
    $(formInstructorvariableData+'high_education_institution_type_2').on('change', function(){
        filter[2] = $(this).val();
        actualFilter = filter[2];
    });
    $(formInstructorvariableData+'high_education_institution_type_3').on('change', function(){
        filter[3] = $(this).val();
        actualFilter = filter[3];
    });
    
    $(formInstructorvariableData+'scholarity').on('change', function(){
        if($(this).val() == 6) { 
            $("#instructorVariableData").show();
            $("#tab-instructor-data1").attr('class','active sub-active');
            $("#instructor-data1").attr('class','active sub-active');
            $("#instructor-data2").hide();
            $("#instructor-data3").hide();
            $(formInstructorvariableData+'high_education_situation_1').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_formation_1').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_course_code_1_fk').removeAttr('disabled');
            
            $(formInstructorvariableData+'high_education_final_year_1').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_institution_type_1').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_institution_code_1_fk').removeAttr('disabled');
            
            $(formInstructorvariableData+'high_education_situation_2').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_formation_2').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_course_code_2_fk').removeAttr('disabled');
            
            $(formInstructorvariableData+'high_education_final_year_2').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_institution_type_2').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_institution_code_2_fk').removeAttr('disabled');
            
            $(formInstructorvariableData+'high_education_situation_3').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_formation_3').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_course_code_3_fk').removeAttr('disabled');
            
            $(formInstructorvariableData+'high_education_final_year_3').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_institution_type_3').removeAttr('disabled');
            $(formInstructorvariableData+'high_education_institution_code_3_fk').removeAttr('disabled');
            
            $(formInstructorvariableData+'post_graduation_specialization').removeAttr('disabled');
            $(formInstructorvariableData+'post_graduation_master').removeAttr('disabled');
            $(formInstructorvariableData+'post_graduation_doctorate').removeAttr('disabled');
            $(formInstructorvariableData+'post_graduation_none').removeAttr('disabled');
            
            $(formInstructorvariableData+'other_courses_nursery').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_pre_school').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_basic_education_initial_years').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_basic_education_final_years').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_high_school').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_education_of_youth_and_adults').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_special_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_native_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_field_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_environment_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_human_rights_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_sexual_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_child_and_teenage_rights').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_ethnic_education').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_other').removeAttr('disabled');
            $(formInstructorvariableData+'other_courses_none').removeAttr('disabled');
           
        }else{
            
            $("#instructorVariableData").hide();
            $(formInstructorvariableData+'high_education_situation_1').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_formation_1').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_course_code_1_fk').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_initial_year_1').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_final_year_1').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_institution_type_1').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_institution_code_1_fk').add().attr('disabled','disabled');
            
            $(formInstructorvariableData+'high_education_situation_2').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_formation_2').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_course_code_2_fk').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_initial_year_2').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_final_year_2').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_institution_type_2').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_institution_code_2_fk').add().attr('disabled','disabled');
            
            $(formInstructorvariableData+'high_education_situation_3').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_formation_3').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_course_code_3_fk').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_initial_year_3').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_final_year_3').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_institution_type_3').add().attr('disabled','disabled');
            $(formInstructorvariableData+'high_education_institution_code_3_fk').add().attr('disabled','disabled');
            
            $(formInstructorvariableData+'post_graduation_specialization').add().attr('disabled','disabled');
            $(formInstructorvariableData+'post_graduation_master').add().attr('disabled','disabled');
            $(formInstructorvariableData+'post_graduation_doctorate').add().attr('disabled','disabled');
            $(formInstructorvariableData+'post_graduation_none').add().attr('disabled','disabled');
            
            $(formInstructorvariableData+'other_courses_nursery').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_pre_school').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_basic_education_initial_years').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_basic_education_final_years').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_high_school').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_education_of_youth_and_adults').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_special_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_native_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_field_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_environment_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_human_rights_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_sexual_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_child_and_teenage_rights').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_ethnic_education').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_other').add().attr('disabled','disabled');
            $(formInstructorvariableData+'other_courses_none').add().attr('disabled','disabled');
            
            
        }
        
        $(formInstructorvariableData + 'high_education_situation_1').on('change', function(){
            if($(this).val() == 1) { // Concluído
                $(formInstructorvariableData + 'high_education_initial_year_1').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'high_education_final_year_1').removeAttr('disabled');
            }else{ // Em Andamento
                $(formInstructorvariableData + 'high_education_initial_year_1').removeAttr('disabled');     
                $(formInstructorvariableData + 'high_education_final_year_1').add().attr('disabled','disabled');
            }
            
        });
        
        $(formInstructorvariableData + 'high_education_situation_2').on('change', function(){
            if($(this).val() == 1) { // Concluído
                $(formInstructorvariableData + 'high_education_initial_year_2').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'high_education_final_year_2').removeAttr('disabled');
            }else{ // Em Andamento
                $(formInstructorvariableData + 'high_education_initial_year_2').removeAttr('disabled');     
                $(formInstructorvariableData + 'high_education_final_year_2').add().attr('disabled','disabled');
            }
            
        });
        
        $(formInstructorvariableData + 'high_education_situation_3').on('change', function(){
            if($(this).val() == 1) { // Concluído
                $(formInstructorvariableData + 'high_education_initial_year_3').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'high_education_final_year_3').removeAttr('disabled');
            }else{ // Em Andamento
                $(formInstructorvariableData + 'high_education_initial_year_3').removeAttr('disabled');     
                $(formInstructorvariableData + 'high_education_final_year_3').add().attr('disabled','disabled');
            }
            
        });
        
        $(formInstructorvariableData + 'high_education_course_code_1_fk').on('change',function(){
            var course = $(formInstructorvariableData + 'high_education_course_code_1_fk option:selected').text();
            course = course.toUpperCase();
            var beforelicenciatura = course.split('LICENCIATURA')[0];
            if(course != beforelicenciatura) {
                // Se é diferente então encontrou a palavra Licenciatura
                $(formInstructorvariableData + 'high_education_formation_1').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'high_education_formation_1').removeAttr('disabled');
            }
        }); 
        
        $(formInstructorvariableData + 'high_education_course_code_2_fk').on('change',function(){
            var course = $(formInstructorvariableData + 'high_education_course_code_2_fk option:selected').text();
            course = course.toUpperCase();
            var beforelicenciatura = course.split('LICENCIATURA')[0];
            if(course != beforelicenciatura) {
                // Se é diferente então encontrou a palavra Licenciatura
                $(formInstructorvariableData + 'high_education_formation_2').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'high_education_formation_2').removeAttr('disabled');
            }
        });
        
        $(formInstructorvariableData + 'high_education_course_code_3_fk').on('change',function(){
            var course = $(formInstructorvariableData + 'high_education_course_code_3_fk option:selected').text();
            course = course.toUpperCase();
            var beforelicenciatura = course.split('LICENCIATURA')[0];
            if(course != beforelicenciatura) {
                // Se é diferente então encontrou a palavra Licenciatura
                $(formInstructorvariableData + 'high_education_formation_3').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'high_education_formation_3').removeAttr('disabled');
            }
        });
        
        
        
        $(formInstructorvariableData + 'post_graduation_specialization').on('change',function(){           
            if($(this).is(':checked')) {
                $(formInstructorvariableData + 'post_graduation_master').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_doctorate').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_none').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'post_graduation_master').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_doctorate').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_none').removeAttr('disabled');
            } 
        });
        $(formInstructorvariableData + 'post_graduation_master').on('change',function(){           
            if($(this).is(':checked')) {
                $(formInstructorvariableData + 'post_graduation_specialization').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_doctorate').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_none').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'post_graduation_specialization').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_doctorate').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_none').removeAttr('disabled');
            } 
        });
        $(formInstructorvariableData + 'post_graduation_doctorate').on('change',function(){           
            if($(this).is(':checked')) {
                $(formInstructorvariableData + 'post_graduation_specialization').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_master').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_none').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'post_graduation_specialization').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_master').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_none').removeAttr('disabled');
            } 
        });
        $(formInstructorvariableData + 'post_graduation_none').on('change',function(){           
            if($(this).is(':checked')) {
                $(formInstructorvariableData + 'post_graduation_specialization').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_master').add().attr('disabled','disabled');
                $(formInstructorvariableData + 'post_graduation_doctorate').add().attr('disabled','disabled');
            }else{
                $(formInstructorvariableData + 'post_graduation_specialization').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_master').removeAttr('disabled');
                $(formInstructorvariableData + 'post_graduation_doctorate').removeAttr('disabled');
            } 
        });
        
        
        
        
        
    }); 
    
    $('.tab-instructor li a').click(function(){
        var classActive = $('ul.tab-instructor li[class="active"]');
        var divActive = $('div.active');
        var li1 = 'tab-instructor-identify';
        var li2 = 'tab-instructor-address';
        var li3 = 'tab-instructor-data';
        var tab = '';
        switch($(this).parent().attr('id')) {
            case li1 : tab = li1; 
                $('.prev').hide();
                $('.next').show();
                $('.last').hide(); break;
            case li2 : tab = li2;
                $('.prev').show();
                $('.next').show();
                $('.last').hide(); break;
            case li3 : tab = li3; 
                $('.prev').show();
                $('.next').hide();
                $('.last').show();  
                $(formInstructorvariableData+'scholarity').trigger('change'); break;
        }
        classActive.removeClass("active");
        divActive.removeClass("active");
        var next_content = tab.substring(4);
        next_content = next_content.toString();
        $('#'+tab).addClass("active");
        $('#'+next_content).addClass("active");
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });
    
    $('.next').click(function(){
        var classActive = $('ul.tab-instructor li[class="active"]');
        var divActive = $('div.active');
        var li1 = 'tab-instructor-identify';
        var li2 = 'tab-instructor-address';
        var li3 = 'tab-instructor-data';
        var next = '';
        switch(classActive.attr('id')) {
            case li1 : next = li2; 
                $('.prev').show(); break;
            case li2 : next = li3;
                $('.next').hide();
                $('.last').show(); 
                $('#instructorVariableData').hide();break;
            case li3 : next = li3;break;
        }
        
        classActive.removeClass("active");
        divActive.removeClass("active");
        var next_content = next.substring(4);
        next_content = next_content.toString();
        $('#'+next).addClass("active");
        $('#'+next_content).addClass("active");
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });
    
    $('.prev').click(function(){
        var classActive = $('ul.tab-instructor li[class="active"]');
        var divActive = $('div.active');
        var li1 = 'tab-instructor-identify';
        var li2 = 'tab-instructor-address';
        var li3 = 'tab-instructor-data';
        var previous = '';
        switch(classActive.attr('id')) {
            case li1 : previous = li1; break;
            case li2 : previous = li1; 
                $('.prev').hide(); break;
            case li3 : previous = li2; 
                $('.last').hide();
                $('.next').show(); break;
        }
        
        classActive.removeClass("active");
        divActive.removeClass("active");
        var previous_content = previous.substring(4);
        previous = previous.toString();
        $('#'+previous).addClass("active");
        $('#'+previous_content).addClass("active");
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });
    
    $('.heading-buttons').css('width', $('#content').width());

    $('.tab-instructordata li a').click(function(){
        var classActive = $('li[class="sub-active"]');
        var divActive = $('div.sub-active');
        var li1 = 'tab-instructor-data1';
        var li2 = 'tab-instructor-data2';
        var li3 = 'tab-instructor-data3';
        var tab = '';
        switch($(this).parent().attr('id')) {
            case li1 : tab = li1; 
                $('#instructor-data1').show();
                $('#instructor-data2').hide();
                $('#instructor-data3').hide(); 
                actualFilter = filter[1];break;
            case li2 : tab = li2;
                $('#instructor-data1').hide();
                $('#instructor-data2').show();
                $('#instructor-data3').hide(); 
                actualFilter = filter[2];break;
            case li3 : tab = li3; 
                $('#instructor-data1').hide();
                $('#instructor-data2').hide();
                $('#instructor-data3').show(); 
                actualFilter = filter[3];break;
        }
        classActive.removeClass("sub-active");
        divActive.removeClass("sub-active")
        var next_content = tab.substring(4);
        next_content = next_content.toString();
        $('#'+next_content).addClass("sub-active");
        $('#'+tab).addClass("sub-active");
    });
    
    $(document).ready(function(){
        $(formInstructorvariableData+'scholarity').trigger('change');
        $(formInstructorIdentification+'birthday_date').mask("99/99/9999");
        
        $(formInstructorvariableData+"high_education_institution_code_1_fk, "
            +formInstructorvariableData+"high_education_institution_code_2_fk, "
            +formInstructorvariableData+"high_education_institution_code_3_fk").select2({
            
            
            placeholder: "Selecione a Instituição",
            minimumInputLength: 3,
            ajax: {
                url: "<?php echo CController::createUrl('instructor/getInstitutions'); ?>",
                dataType: 'json',
                quietMillis: 500,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        f: actualFilter,
                        
                        page: page // page number
                    };
                },
                results: function (data, page) {
                    var more = (page * 10) < data.total; // whether or not there are more results available

                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.ies, more: more};
                }
            },
            formatResult: function(institution) {
                var markup = "<table class='institution-result'><tr>";
                markup += "<td class='institution-info'><div class='institution-name'>" + institution.name + "</div>";
                markup += "</td></tr></table>";
                return markup;
            },
            formatSelection:function(institution) {
                return institution.name;
            },
            dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
            escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
        });       
        $(formInstructorvariableData+"high_education_institution_code_1_fk, "
            +formInstructorvariableData+"high_education_institution_code_2_fk, "
            +formInstructorvariableData+"high_education_institution_code_3_fk").select2('enable', true);
        $(formInstructorIdentification+"edcenso_nation_fk").select2("disable","true");
        $(formInstructorIdentification+"edcenso_uf_fk").select2("disable","true");
        $(formInstructorIdentification+"edcenso_city_fk").select2("disable","true");
    });     
</script>
