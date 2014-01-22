<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($modelStudentIdentification); ?>
<?php echo $form->errorSummary($modelStudentDocumentsAndAddress); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>        
                </div>
                <div class="span4">
                    <div class="buttons">
                         <a  data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display:none;"><?php echo Yii::t('default','Previous') ?><i></i></a>
                         <a  data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default','Next') ?><i></i></a>
                         <?php echo CHtml::htmlButton('<i></i>' . ($modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')),
                                    array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'style' => 'display:none', 'type' => 'submit'));?>
                         <?php //echo CHtml::submitButton($modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary last', 'style' => 'display:none')); ?>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
    
<div class="innerLR">
    
    <div class="widget widget-tabs border-bottom-none">
        
        <div class="widget-head">
            <ul class="tab-student">
                <li id="tab-student-identify" class="active"><a class="glyphicons edit" href="#student-identify" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Identification') ?></a></li>
                <li id="tab-student-documents"><a class="glyphicons settings" href="#student-documents" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Documents') ?></a></li>
                <li id="tab-student-address"><a class="glyphicons imac" href="#student-address" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Address') ?></a></li>
            </ul>
        </div>
            
        <div class="widget-body form-horizontal">
            
            <div class="tab-content">
                <!-- Tab content -->
                <!-- Tab Student Identify -->
                <div class="tab-pane active" id="student-identify">
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="separator"></div>
                            <div class="control-group">
                                <?php 
                                //@done S1 - 08 - 11 - Não precisar selecionar a escola, ele já estará em uma
                                ?>
                                <div class="controls">
                                     <?php
                                    echo $form->hiddenField($modelStudentIdentification,'school_inep_id_fk',array('value'=>Yii::app()->user->school));
                                    ?>
                                    
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'name', array('size' => 60, 'maxlength' => 100)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'name'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'nis', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'nis', array('size' => 11, 'maxlength' => 11)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'nis'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'birthday', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'birthday', array('size' => 10, 'maxlength' => 10)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'birthday'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'sex', array('class' => 'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($modelStudentIdentification,'sex', array(null=>"Selecione o sexo", "1"=>"Masculino", "2"=>"Feminino"),array('class' => 'select-search-off')); ?>
                                <?php echo $form->error($modelStudentIdentification,'sex'); ?>
                            </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'color_race', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelStudentIdentification, 'color_race', array(null => "Selecione a cor/raça",
                                        "0" => "Não declarada",
                                        "1" => "Branca",
                                        "2" => "Preta",
                                        "3" => "Parda",
                                        "4" => "Amarela",
                                        "5" => "Indígena"),array('class' => 'select-search-off'));
                                    ?>
                                <?php echo $form->error($modelStudentIdentification, 'color_race'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentIdentification, 'filiation', array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'mother_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'mother_name', array('size' => 60, 'maxlength' => 100, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'mother_name'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'father_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'father_name', array('size' => 60, 'maxlength' => 100, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'father_name'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'nationality', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'nationality', array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"),array('class' => 'select-search-off'), array('ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getnations'),
                                            'update' => '#StudentIdentification_edcenso_nation_fk'
                                            )));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'nationality'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_nation_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma nação",'class' => 'select-search-on'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_nation_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities&rt=0'),
                                            'update' => '#StudentIdentification_edcenso_city_fk'
                                        ),
                                        "prompt" => "Selecione um estado",
                                        "class" => "select-search-on",
                                        "disabled" => "disabled"));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma cidade",
                                        "disabled" => "disabled", 'class'=>'select-search-on'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'deficiency', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'deficiency', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'deficiency'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Deficiency Type'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_blindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0)); ?>
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
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_aspenger_syndrome']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_aspenger_syndrome', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_rett_syndrome']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_rett_syndrome', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_childhood_disintegrative_disorder']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_childhood_disintegrative_disorder', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_gifted']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Required Resources'); ?></label>
                                <div class="uniformjs margin-left">
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
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_16']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_16', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_20']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_20', array('value' => 1, 'uncheckValue' => 0)); ?>
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
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_none']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
                <!-- Tab Student Documents -->
                <div class="tab-pane" id="student-documents">
                    <div class="row-fluid">
                        <div class=" span5">
                            
                            <div class="separator"></div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number', array('size' => 20, 'maxlength' => 20, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_complement', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_complement', array('size' => 4, 'maxlength' => 4, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_complement'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    
                                    <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', CHtml::listData(EdcensoOrganIdEmitter::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um órgão emissor da identidade", "class"=>"select-search-on", "disabled" => "disabled"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um estado","class"=>"select-search-on", "disabled" => "disabled"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('size' => 10, 'maxlength' => 10, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'civil_certification', array(null => "Selecione o modelo", "1" => "Modelo Antigo", "2" => "Modelo Novo"), array("class"=>"select-search-off", "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                                </div>
                            </div>
                                
                            <?php //@done S1 - Alterar tipo de certidão civil para dropdown?>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_type', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'civil_certification_type', array(null => "Selecione o tipo", "1" => "Nascimento", "2" => "Casamento"), array("class"=>"select-search-off", "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('size' => 8, 'maxlength' => 8, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('size' => 4, 'maxlength' => 4, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_book', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_book', array('size' => 8, 'maxlength' => 8, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_date', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_date', array('size' => 10, 'maxlength' => 10, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities&rt=1'),
                                            'update' => '#StudentDocumentsAndAddress_notary_office_city_fk'
                                        ),
                                        "prompt" => "Selecione um estado",
                                        "class"=> "select-search-on",
                                        "disabled" => "disabled"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->notary_office_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getnotaryoffice'),
                                            'update' => '#StudentDocumentsAndAddress_edcenso_notary_office_fk'
                                        ),
                                        "prompt" => "Selecione uma cidade",
                                        "class" => "select-search-on",
                                        "disabled" => "disabled"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', CHtml::listData(EdcensoNotaryOffice::model()->findAllByAttributes(array('city' => $modelStudentDocumentsAndAddress->notary_office_city_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um cartório",
                                        "class"=>"select-search-on", "disabled" => "disabled"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('size' => 32, 'maxlength' => 32, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cpf', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 11, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'cpf'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('size' => 20, 'maxlength' => 20, "disabled" => "disabled")); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'document_failure_lack', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'document_failure_lack', array(null => "Selecione uma justificativa", "1" => "Aluno não possui documento", "2" => "Escola não possui informação de documento do aluno"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'document_failure_lack'); ?>
                                </div>
                            </div>
                                
                        </div>
                    </div>   
                </div>
                <!-- Tab Student Address -->
                <div class="tab-pane" id="student-address">
                    <div class="row-fluid">
                        <div class=" span6">
                            
                            <div class="separator"></div>
                                
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'residence_zone', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'residence_zone', array(null => "Selecione uma zona", "1" => "URBANA", "2" => "RURAL"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cep', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cep', array('size' => 8, 'maxlength' => 8)); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'cep'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'address', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'address', array('size' => 60, 'maxlength' => 100)); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'address'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'number', array('size' => 10, 'maxlength' => 10)); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'number'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'complement', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'complement', array('size' => 20, 'maxlength' => 20)); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'complement'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'neighborhood', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'neighborhood', array('size' => 50, 'maxlength' => 50)); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities&rt=2'),
                                            'update' => '#StudentDocumentsAndAddress_edcenso_city_fk'
                                        ),
                                        "prompt" => "Selecione um estado",
                                        "class" => "select-search-on"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma cidade","class"=>"select-search-on"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
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
    
<script type="text/javascript">
    var formIdentification = '#StudentIdentification_';
    var formDocumentsAndAddress = '#StudentDocumentsAndAddress_';
    
    
    jQuery(function($) {
        $(formIdentification+'filiation').trigger('change');
        $(formIdentification+'nationality').trigger('change');
    }); 
    
    
    $(formIdentification+'name').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        var ret = validateNamePerson(($(id).val()));
        if(!ret[0]) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'address').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateStudentAddress($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'number').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateStudentAddressNumber($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'complement').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateStudentAddressComplement($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'neighborhood').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateStudentAddressNeighborhood($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'cpf').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateCpf($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'cep').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateCEP($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formIdentification+'nis').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateNis($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'rg_number').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateRG($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    var date = new Date();
    $(formIdentification+'birthday').mask("99/99/9999");
    $(formIdentification+'birthday').focusout(function() {
        var id = '#'+$(this).attr("id");
        var birthday = stringToDate($(formIdentification+'birthday').val());    
        if(!validateDate($(formIdentification+'birthday').val()))  {
            $(formIdentification+'birthday').attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formDocumentsAndAddress+'rg_number_expediction_date, '+ formDocumentsAndAddress+'civil_certification_date').mask("99/99/9999");
    $(formDocumentsAndAddress+'rg_number_expediction_date, '+ formDocumentsAndAddress+'civil_certification_date').focusout(function() {
        var id = '#'+$(this).attr("id");
        var documentDate = stringToDate($(id).val());   
        var birthday = stringToDate($(formIdentification+'birthday').val());  
        if(!validateDate($(id).val()) || birthday.asianStr > documentDate.asianStr) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formIdentification+'filiation').change(function(){
        $(formIdentification+'mother_name').attr("disabled", "disabled");
        $(formIdentification+'father_name').attr("disabled", "disabled");
        
        if($(formIdentification+'filiation').val() == 1){
            $(formIdentification+'mother_name').removeAttr("disabled");
            $(formIdentification+'father_name').removeAttr("disabled");
        }
        else{
            $(formIdentification+'mother_name').val("");
            $(formIdentification+'father_name').val("");
        }
    });
    
    $(formIdentification+'mother_name, '
        +formIdentification+'father_name').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        var ret = validateNamePerson(($(id).val()));
        if(!ret[0]) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
        
        if($(formIdentification+'mother_name').val() == $(formIdentification+'father_name').val()){
            $(formIdentification+'father_name').attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(formIdentification+'nationality').change(function(){
        $(formIdentification+'edcenso_uf_fk').attr("disabled", "disabled");
        $(formIdentification+'edcenso_city_fk').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'rg_number').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'rg_number_complement').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'rg_number_edcenso_organ_id_emitter_fk').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'rg_number_edcenso_uf_fk').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'rg_number_expediction_date').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_certification').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_certification_type').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_certification_term_number').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_certification_sheet').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_certification_book').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_certification_date').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'notary_office_uf_fk').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'notary_office_city_fk').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'edcenso_notary_office_fk').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'civil_register_enrollment_number').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'cpf').attr("disabled", "disabled");
        $(formDocumentsAndAddress+'foreign_document_or_passport').attr("disabled", "disabled");
        
        if($(this).val() == 3){
            $(formIdentification+'edcenso_uf_fk').val("");
            $(formIdentification+'edcenso_city_fk').val("");
            $(formDocumentsAndAddress+'foreign_document_or_passport').removeAttr("disabled");
        }else{
            $(formIdentification+'edcenso_uf_fk').removeAttr("disabled");
            $(formIdentification+'edcenso_city_fk').removeAttr("disabled");
            $(formDocumentsAndAddress+'rg_number').removeAttr("disabled");
            $(formDocumentsAndAddress+'rg_number_complement').removeAttr("disabled");
            $(formDocumentsAndAddress+'rg_number_edcenso_organ_id_emitter_fk').removeAttr("disabled");
            $(formDocumentsAndAddress+'rg_number_edcenso_uf_fk').removeAttr("disabled");
            $(formDocumentsAndAddress+'rg_number_expediction_date').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_certification').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_certification_type').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_certification_term_number').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_certification_sheet').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_certification_book').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_certification_date').removeAttr("disabled");
            $(formDocumentsAndAddress+'notary_office_uf_fk').removeAttr("disabled");
            $(formDocumentsAndAddress+'notary_office_city_fk').removeAttr("disabled");
            $(formDocumentsAndAddress+'edcenso_notary_office_fk').removeAttr("disabled");
            $(formDocumentsAndAddress+'civil_register_enrollment_number').removeAttr("disabled");
            $(formDocumentsAndAddress+'cpf').removeAttr("disabled");
        }
    });
    
    $(formIdentification+"deficiency").change(function(){
        
        $(formIdentification+'deficiency_type_blindness').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_low_vision').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_deafness').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_disability_hearing').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_deafblindness').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_phisical_disability').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_intelectual_disability').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_multiple_disabilities').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_autism').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_aspenger_syndrome').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_rett_syndrome').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_childhood_disintegrative_disorder').attr("disabled", "disabled");
        $(formIdentification+'deficiency_type_gifted').attr("disabled", "disabled");
        $(formIdentification+'resource_aid_lector').attr("disabled", "disabled");
        $(formIdentification+'resource_aid_transcription').attr("disabled", "disabled");
        $(formIdentification+'resource_interpreter_guide').attr("disabled", "disabled");
        $(formIdentification+'resource_interpreter_libras').attr("disabled", "disabled");
        $(formIdentification+'resource_lip_reading').attr("disabled", "disabled");
        $(formIdentification+'resource_zoomed_test_16').attr("disabled", "disabled");
        $(formIdentification+'resource_zoomed_test_20').attr("disabled", "disabled");
        $(formIdentification+'resource_zoomed_test_24').attr("disabled", "disabled");
        $(formIdentification+'resource_braille_test').attr("disabled", "disabled");
        $(formIdentification+'resource_none').attr("disabled", "disabled");
        
        if($(this).is(':checked')){
            $(formIdentification+'deficiency_type_blindness').removeAttr("disabled");
            $(formIdentification+'deficiency_type_low_vision').removeAttr("disabled");
            $(formIdentification+'deficiency_type_deafness').removeAttr("disabled");
            $(formIdentification+'deficiency_type_disability_hearing').removeAttr("disabled");
            $(formIdentification+'deficiency_type_deafblindness').removeAttr("disabled");
            $(formIdentification+'deficiency_type_phisical_disability').removeAttr("disabled");
            $(formIdentification+'deficiency_type_intelectual_disability').removeAttr("disabled");
            $(formIdentification+'deficiency_type_multiple_disabilities').removeAttr("disabled");
            $(formIdentification+'deficiency_type_autism').removeAttr("disabled");
            $(formIdentification+'deficiency_type_aspenger_syndrome').removeAttr("disabled");
            $(formIdentification+'deficiency_type_rett_syndrome').removeAttr("disabled");
            $(formIdentification+'deficiency_type_childhood_disintegrative_disorder').removeAttr("disabled");
            $(formIdentification+'deficiency_type_gifted').removeAttr("disabled");
            $(formIdentification+'resource_aid_lector').removeAttr("disabled");
            $(formIdentification+'resource_aid_transcription').removeAttr("disabled");
            $(formIdentification+'resource_interpreter_guide').removeAttr("disabled");
            $(formIdentification+'resource_interpreter_libras').removeAttr("disabled");
            $(formIdentification+'resource_lip_reading').removeAttr("disabled");
            $(formIdentification+'resource_zoomed_test_16').removeAttr("disabled");
            $(formIdentification+'resource_zoomed_test_20').removeAttr("disabled");
            $(formIdentification+'resource_zoomed_test_24').removeAttr("disabled");
            $(formIdentification+'resource_braille_test').removeAttr("disabled");
            $(formIdentification+'resource_none').removeAttr("disabled");
            
            $(formIdentification+'deficiency_type_blindness').val("");
            $(formIdentification+'deficiency_type_low_vision').val("");
            $(formIdentification+'deficiency_type_deafness').val("");
            $(formIdentification+'deficiency_type_disability_hearing').val("");
            $(formIdentification+'deficiency_type_deafblindness').val("");
            $(formIdentification+'deficiency_type_phisical_disability').val("");
            $(formIdentification+'deficiency_type_intelectual_disability').val("");
            $(formIdentification+'deficiency_type_multiple_disabilities').val("");
            $(formIdentification+'deficiency_type_autism').val("");
            $(formIdentification+'deficiency_type_aspenger_syndrome').val("");
            $(formIdentification+'deficiency_type_rett_syndrome').val("");
            $(formIdentification+'deficiency_type_childhood_disintegrative_disorder').val("");
            $(formIdentification+'deficiency_type_gifted').val("");
            $(formIdentification+'resource_aid_lector').val("");
            $(formIdentification+'resource_aid_transcription').val("");
            $(formIdentification+'resource_interpreter_guide').val("");
            $(formIdentification+'resource_interpreter_libras').val("");
            $(formIdentification+'resource_lip_reading').val("");
            $(formIdentification+'resource_zoomed_test_16').val("");
            $(formIdentification+'resource_zoomed_test_20').val("");
            $(formIdentification+'resource_zoomed_test_24').val("");
            $(formIdentification+'resource_braille_test').val("");
            $(formIdentification+'resource_none').val("");
            
        }
    });
    
    $('.tab-student li a').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-student-identify';
        var li2 = 'tab-student-documents';
        var li3 = 'tab-student-address';
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
                $('.last').show(); break;
        }
         
        classActive.removeClass("active");
        divActive.removeClass("active");
        var next_content = tab.substring(4);
        next_content = next_content.toString();
        $('#'+tab).addClass("active");
        $('#'+next_content).addClass("active");
        $('html, body').animate({ scrollTop: 85 }, 'fast');
    })
    
    $('.next').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-student-identify';
        var li2 = 'tab-student-documents';
        var li3 = 'tab-student-address';
        var next = '';
        switch(classActive.attr('id')) {
            case li1 : next = li2; 
                $('.prev').show(); break;
            case li2 : next = li3;
                $('.next').hide();
                $('.last').show(); break;
            case li3 : next = li3; break;
        }
         
        classActive.removeClass("active");
        divActive.removeClass("active");
        var next_content = next.substring(4);
        next_content = next_content.toString();
        $('#'+next).addClass("active");
        $('#'+next_content).addClass("active");
        $('html, body').animate({ scrollTop: 85 }, 'fast');
    });
    
    $('.prev').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-student-identify';
        var li2 = 'tab-student-documents';
        var li3 = 'tab-student-address';
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
        $('html, body').animate({ scrollTop: 85 }, 'fast');
    });
    
    $('.heading-buttons').css('width', $('#content').width());
    
</script>