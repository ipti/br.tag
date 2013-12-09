<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student',
	'enableAjaxValidation'=>false,
)); ?>
<div class="heading-buttons">
    <?php echo $form->errorSummary($modelStudentIdentification); ?>
    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.')?></span></h3>
    <div class="buttons pull-right">
        <button type="button" class="btn btn-icon btn-default glyphicons unshare"><i></i>Voltar</button>
        <?php echo CHtml::submitButton($modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'btn btn-icon btn-primary glyphicons circle_ok')); ?>
    </div>
    <div class="clearfix"></div>
</div>
            
<div class="innerLR">

<div class="widget widget-tabs border-bottom-none">

    <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons edit" href="#student-indentify" data-toggle="tab"><i></i>Identificação</a></li>
                <li><a class="glyphicons settings" href="#student-documents" data-toggle="tab"><i></i>Documentos</a></li>
                <li><a class="glyphicons imac" href="#student-address" data-toggle="tab"><i></i>Endereço</a></li>
            </ul>
    </div>

    <div class="widget-body form-horizontal">

        <div class="tab-content">
            <!-- Tab content -->
            <!-- Tab Student Identify -->
            <div class="tab-pane active" id="student-indentify">
                <div class="row-fluid">
                    <div class=" span6">
                        <?php echo Yii::t('default', 'Campos com * são obrigatórios.')?>
                    </div>
                    <div class="separator"></div>
                    <div class="separator"></div>
<?php /*
                        <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'register_type'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'register_type',array('size'=>2,'maxlength'=>2)); ?>
                                <?php echo $form->error($modelStudentIdentification,'register_type'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'school_inep_id_fk'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
                                <?php echo $form->error($modelStudentIdentification,'school_inep_id_fk'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'inep_id'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
                                <?php echo $form->error($modelStudentIdentification,'inep_id'); ?>
                            </div>
                        </div>
*/ ?>
                        <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'name'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'name',array('size'=>60,'maxlength'=>100)); ?>
                                <?php echo $form->error($modelStudentIdentification,'name'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'nis'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'nis',array('size'=>11,'maxlength'=>11)); ?>
                                <?php echo $form->error($modelStudentIdentification,'nis'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'birthday'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'birthday',array('size'=>10,'maxlength'=>10)); ?>
                                <?php echo $form->error($modelStudentIdentification,'birthday'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'sex'); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($modelStudentIdentification,'sex', array(null=>"Selecione o sexo", "1"=>"Masculino", "2"=>"Feminino")); ?>
                                <?php echo $form->error($modelStudentIdentification,'sex'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'color_race'); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($modelStudentIdentification,'color_race', array(null => "Selecione a cor/raça",
                                "0" => "Não declarada",
                                "1" => "Branca",
                                "2" => "Preta",
                                "3" => "Parda",
                                "4" => "Amarela",
                                "5" => "Indígena"
                            )); ?>
                                <?php echo $form->error($modelStudentIdentification,'color_race'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'filiation'); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($modelStudentIdentification,'filiation', array(null=>"Selecione a filiação", "0"=>"Não declarado/Ignorado", "1"=>"Pai e/ou Mãe")); ?>
                                <?php echo $form->error($modelStudentIdentification,'filiation'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'mother_name'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'mother_name',array('size'=>60,'maxlength'=>100,"disabled"=> "disabled")); ?>
                                <?php echo $form->error($modelStudentIdentification,'mother_name'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'father_name'); ?>
                            <div class="controls">
                                <?php echo $form->textField($modelStudentIdentification,'father_name',array('size'=>60,'maxlength'=>100,"disabled"=> "disabled")); ?>
                                <?php echo $form->error($modelStudentIdentification,'father_name'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'nationality'); ?>
                            <div class="controls">
                                <?php echo $form->dropDownList($modelStudentIdentification,'nationality', array(null=>"Selecione a nacionalidade", "1"=>"Brasileira", "2"=>"Brasileira: Nascido no exterior ou Naturalizado","3"=>"Estrangeira")); ?>
                                <?php echo $form->error($modelStudentIdentification,'nationality'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'edcenso_nation_fk'); ?>
                            <div class="controls">
                                <?php echo $form->dropDownList($modelStudentIdentification, 'edcenso_nation_fk', 
                                        CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt"=>"Selecione uma nação","disabled"=>"disabled")); ?>
                                <?php echo $form->error($modelStudentIdentification,'edcenso_nation_fk'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'edcenso_uf_fk'); ?>
                            <div class="controls">
                                <?php echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', 
                                        CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), 
                                        array(
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl('studentIdentification/getcities'),
                                                'update' => '#StudentIdentification_edcenso_city_fk'
                                            ),
                                            "prompt"=>"Selecione um estado",
                                            "disabled"=>"disabled")); ?>
                                <?php echo $form->error($modelStudentIdentification,'edcenso_uf_fk'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'edcenso_city_fk'); ?>
                            <div class="controls">
                                <?php echo $form->dropDownList($modelStudentIdentification, 'edcenso_city_fk', 
                                        CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), 
                                        array("prompt"=>"Selecione uma cidade",
                                            "disabled"=>"disabled")); ?>
                                <?php echo $form->error($modelStudentIdentification,'edcenso_city_fk'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_blindness'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_blindness'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_low_vision'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_low_vision'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_deafness'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_deafness'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_disability_hearing'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_disability_hearing'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_deafblindness'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_deafblindness'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_phisical_disability'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_phisical_disability'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_intelectual_disability'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_intelectual_disability'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_multiple_disabilities'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_multiple_disabilities', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_multiple_disabilities'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_autism'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_autism', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_autism'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_aspenger_syndrome'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_aspenger_syndrome', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_aspenger_syndrome'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_rett_syndrome'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_rett_syndrome', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_rett_syndrome'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_childhood_disintegrative_disorder'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_childhood_disintegrative_disorder', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_childhood_disintegrative_disorder'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'deficiency_type_gifted'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'deficiency_type_gifted'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_aid_lector'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_aid_lector', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_aid_lector'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_aid_transcription'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_aid_transcription', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_aid_transcription'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_interpreter_guide'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_interpreter_guide', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_interpreter_guide'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_interpreter_libras'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_interpreter_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_interpreter_libras'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_lip_reading'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_lip_reading', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_lip_reading'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_zoomed_test_16'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_zoomed_test_16', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_zoomed_test_16'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_zoomed_test_20'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_zoomed_test_20', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_zoomed_test_20'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_zoomed_test_24'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_zoomed_test_24', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_zoomed_test_24'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_braille_test'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_braille_test', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_braille_test'); ?>
                            </div>
                        </div>

                                            <div class="control-group">
                            <?php echo $form->labelEx($modelStudentIdentification,'resource_none'); ?>
                            <div class="controls">
                                <?php echo $form->checkBox($modelStudentIdentification,'resource_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                <?php echo $form->error($modelStudentIdentification,'resource_none'); ?>
                            </div>
                        </div>
                </div>
            </div>   
            
            
            <!-- Tab Student Documents -->
            <div class="tab-pane" id="student-documents">
                <div class="row-fluid">
                    <div class=" span6">
                        <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                    </div>

                    <div class="separator"></div>
                    <div class="separator"></div>

                    <?php /*
                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'register_type'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'register_type', array('size' => 2, 'maxlength' => 2)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'register_type'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'school_inep_id_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'school_inep_id_fk', array('size' => 8, 'maxlength' => 8)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'school_inep_id_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'student_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'student_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'student_fk'); ?>
                        </div>
                    </div>
                    */ ?>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number', array('size' => 20, 'maxlength' => 20)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_complement'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_complement', array('size' => 4, 'maxlength' => 4)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_complement'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('size' => 10, 'maxlength' => 10)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('size' => 8, 'maxlength' => 8)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('size' => 4, 'maxlength' => 4)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_book', array('size' => 8, 'maxlength' => 8)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_date', array('size' => 10, 'maxlength' => 10)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('size' => 32, 'maxlength' => 32)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cpf'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 11)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'cpf'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('size' => 20, 'maxlength' => 20)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'nis'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'nis', array('size' => 11, 'maxlength' => 11)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'nis'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'document_failure_lack'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'document_failure_lack'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'document_failure_lack'); ?>
                        </div>
                    </div>

                </div>
            </div>   
            
            <!-- Tab Student Address -->
            <div class="tab-pane" id="student-address">
                <div class="row-fluid">
                    <div class=" span6">
                        <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                    </div>

                    <div class="separator"></div>
                    <div class="separator"></div>
                    
                    
                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cep'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cep', array('size' => 8, 'maxlength' => 8)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'cep'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'address'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'address', array('size' => 60, 'maxlength' => 100)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'address'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'number', array('size' => 10, 'maxlength' => 10)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'number'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'complement'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'complement', array('size' => 20, 'maxlength' => 20)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'complement'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'neighborhood', array('size' => 50, 'maxlength' => 50)); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>

    <script type="text/javascript">
        var formIdentification = '#StudentIdentification_';
        var formDocumentsAndAddress = '#StudentDocumentsAndAddress_';
        
        $(formIdentification+'name').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            var ret = validateNamePerson(($(this).val()));
            if(!ret[0]) 
                $(this).attr('value','');
        });
        
        $(formIdentification+'nis').focusout(function() { 
            if(!validateCPF($(this).val())) 
                $(this).attr('value','');
        });
        
        var date = new Date();
        $(formIdentification+'birthday').mask("99/99/9999");
        $(formIdentification+'birthday').focusout(function() {
            birthday = stringToDate($(formIdentification+'birthday').val());    
            if(!validateDate($(formIdentification+'birthday').val())) 
                $(formIdentification+'birthday').attr('value','');
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
            $(this).val($(this).val().toUpperCase());
            var ret = validateNamePerson(($(this).val()));
            if(!ret[0]) 
                $(this).attr('value','');
            
            if($(formIdentification+'mother_name').val() == $(formIdentification+'father_name').val()){
                $(formIdentification+'father_name').attr('value','');
            } 
        });
        
          $(formIdentification+'nationality').change(function(){
              
            $(formIdentification+'edcenso_nation_fk').attr("disabled", "disabled");
            $(formIdentification+'edcenso_uf_fk').attr("disabled", "disabled");
            $(formIdentification+'edcenso_city_fk').attr("disabled", "disabled");
            
              if($(this).val() == 3){
                $(formIdentification+'edcenso_nation_fk').removeAttr("disabled");
                $(formIdentification+'edcenso_uf_fk').val("");
                $(formIdentification+'edcenso_city_fk').val("");
                
              }else{
                $(formIdentification+'edcenso_nation_fk').val("76");
                $(formIdentification+'edcenso_uf_fk').removeAttr("disabled");
                $(formIdentification+'edcenso_city_fk').removeAttr("disabled");
                  
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
                
              }else{
              }
              
          });
          
    </script>
