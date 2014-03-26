<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'school',
	'enableAjaxValidation'=>false,
)); ?>



<?php echo $form->errorSummary($modelSchoolIdentification); ?>
<?php echo $form->errorSummary($modelSchoolStructure); ?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></h3>  
        <div class="buttons">
                    <a  data-toggle="tab" class='btn btn-icon btn-default prev glyphicons circle_arrow_left' style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
                        <a  data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default', 'Next') ?><i></i></a>
                        <?php echo CHtml::htmlButton('<i></i>' . ($modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'style' => 'display:none', 'type' => 'submit'));
                        ?>
                        <?php //echo CHtml::submitButton($modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary last', 'style' => 'display:none')); ?>
        </div>
    </div>
</div>
            
<div class="innerLR">
    
    <div class="widget widget-tabs border-bottom-none">
    
        <div class="widget-head">
            <ul class="tab-school">
                <li id="tab-school-indentify" class="active"><a class="glyphicons edit" href="#school-indentify" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Identification') ?></a></li>
                <li id="tab-school-structure"   ><a class="glyphicons settings" href="#school-structure" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Structure') ?></a></li>
                <li id="tab-school-equipment"  ><a class="glyphicons imac"     href="#school-equipment" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Equipments') ?></a></li>
                <li id="tab-school-education"   ><a class="glyphicons book"     href="#school-education" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Educational Data') ?></a></li>
            </ul>
        </div>
        
        <div class="widget-body form-horizontal">

            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="school-indentify">
                    <div class="row-fluid">
                        <?php // @done S1 - Alinhar o campo nome, o span12 não esta funcionando devidamente(remover o style e corrigir no css) ?>
                        <div class="span12">

                            <!--//@done S1 - 09 - O Campo de nome está muito pequeno, aumentar -->   
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'name', array('size' => 100, 'maxlength' => 100, 'class' => 'span10')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.') . " " . Yii::t('help', 'Min length') . '4'; ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'name'); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row-fluid">    
                        <div class=" span6">

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'inep_id', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'inep_id', array('size' => 8, 'maxlength' => 8, 'class' => 'span10')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'School code in the registration INEP'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'inep_id'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php //@done s1 - Tem que filtrar de acordo com o estado e cidade, no momento está listando todos ?>
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', CHtml::listData(EdcensoRegionalEducationOrgan::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o órgão', 'class' => 'select-search-on'));
                                    ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_regional_education_organ_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'administrative_dependence', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'administrative_dependence', array(null => 'Selecione a dependencia administrativa', 1 => 'Federal', 2 => 'Estadual', 3 => 'Municipal', 4 => 'Privada'), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'administrative_dependence'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="span6">



                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                            <?php echo $form->DropDownList($modelSchoolIdentification, 'situation', array(null => 'Selecione a situação', 1 => 'Em Atividade', 2 => 'Paralisada', 3 => 'Extinta'), array('class' => 'select-search-off')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Current situation school run'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'situation'); ?>
                                </div>
                            </div>


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'initial_date', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'initial_date', array('size' => 10, 'maxlength' => 10)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Initial Date Help'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'initial_date'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'final_date', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'final_date', array('size' => 10, 'maxlength' => 10)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Final Date Help'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'final_date'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'regulation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolIdentification, 'regulation', array(null => 'Selecione a situação de regulamentação', 0 => 'Não', 1 => 'Sim', 2 => 'Em tramitação'), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'regulation'); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row-fluid">    
                        <div class=" span6">

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'manager_cpf', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'manager_cpf', array('size' => 11, 'maxlength' => 11)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'CPF school manager. Numbers only.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'manager_cpf'); ?>
                                </div></div> 

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'manager_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'manager_name', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full name of school manager'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'manager_name'); ?>
                                </div></div>

                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'manager_role', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'manager_role', array(null => "Selecione o cargo", "1" => "Diretor", "2" => "Outro Cargo"), array('class' => 'select-search-off')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Role of the school manager'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'manager_role'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'manager_email', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'manager_email', array('size' => 50, 'maxlength' => 50)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'E-mail'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'manager_email'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">

                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address', array('size' => 60, 'maxlength' => 100, 'class' => 'span10')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'address'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address_number', array('size' => 10, 'maxlength' => 10, 'class' => 'span2')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'address_number'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address_complement', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address_complement', array('size' => 20, 'maxlength' => 20, 'class' => 'span10')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'address_complement'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'address_neighborhood', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'address_neighborhood', array('size' => 50, 'maxlength' => 50, 'class' => 'span10')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'address_neighborhood'); ?>
                                </div>
                            </div>

                            <?php // @done S1 -10 - Campo de DDD tem que estar junto com o campo de Telefone (cancelado) ?> 

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'ddd', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'ddd', array('size' => 2, 'maxlength' => 2, 'class' => 'span2')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'ddd'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'phone_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'phone_number', array('size' => 9, 'maxlength' => 9)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Phone'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'phone_number'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'other_phone_number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'other_phone_number', array('size' => 9, 'maxlength' => 9)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Phone'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'other_phone_number'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'email', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolIdentification, 'email', array('size' => 50, 'maxlength' => 50)); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'E-mail'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolIdentification, 'email'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="span6">

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'cep', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->textField($modelSchoolIdentification, 'cep', array('size' => 8, 'maxlength' => 8,
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
                                    )));
                                    ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Valid Cep') . " " . Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '8.'; ?>"><i></i></span>

                                    <?php echo $form->error($modelSchoolIdentification, 'cep'); ?>
                                </div>
                            </div>




                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    //@done s1 - Atualizar a lista de Orgão Regional de Educação também.
                                    echo $form->dropDownList($modelSchoolIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'prompt' => 'Selecione um estado',
                                        'class' => 'select-search-on',
                                        'ajax' => array(
                                            'type' => 'POST',
//                                    'url' => CController::createUrl('school/getcities'),
//                                    'update' => '#SchoolIdentification_edcenso_city_fk',
                                            'url' => CController::createUrl('school/updateUfDependencies'),
                                            'success' => "function(data){
                                            data = jQuery.parseJSON(data);
                                            $('#SchoolIdentification_edcenso_regional_education_organ_fk').html(data.Organ);
                                            $('#SchoolIdentification_edcenso_city_fk').html(data.City);
                                        }",
                                    )));
                                    ?>      
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_uf_fk'); ?>
                                </div>
                            </div>


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelSchoolIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione uma cidade',
                                        'class' => 'select-search-on',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('school/getdistricts'),
                                            'update' => '#SchoolIdentification_edcenso_district_fk',
                                    )));
                                    ?>  
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_city_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_district_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelSchoolIdentification, 'edcenso_district_fk', CHtml::listData(EdcensoDistrict::model()->findAllByAttributes(array('edcenso_city_fk' => $modelSchoolIdentification->edcenso_city_fk), array('order' => 'name')), 'code', 'name'), array('prompt' => 'Selecione um distrito', 'class' => 'select-search-on'));
                                    ?>  
                                    <?php echo $form->error($modelSchoolIdentification, 'edcenso_district_fk'); ?>
                                </div>
                            </div>



                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolIdentification, 'location', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolIdentification, 'location', array(null => 'Selecione a localização', 1 => 'Urbano', 2 => 'Rural'), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolIdentification, 'location'); ?>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>

                <div class="tab-pane" id="school-structure">

                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'classroom_count', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'classroom_count'); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'classroom_count'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'used_classroom_count', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'used_classroom_count'); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'used_classroom_count'); ?>
                                </div>
                            </div>    
                        </div>
                        <div class="span6">

                            <?php
                            //@done S1 - 09 - Recursos humanos só tem um campo, não faz sentido manter essa aba com um campo apenas 
                            //@done S1 - 09 - Apenas uma campo inseri em uma outra aba  
                            //@done S1 - 09 - Remover abas, corrigir botão de next.
                            ?>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'employees_count', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'employees_count'); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'employees_count'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'feeding', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'feeding', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Oferece"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'feeding'); ?>
                                </div>                             
                            </div>
                        </div>


                    </div>

                    <div class="row-fluid">
                        <div class=" span6">      
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Operation Location'); ?></label>
                                <div class="uniformjs margin-left" id="SchoolStructure_operation_location">
                                    <label class="checkbox">
                                        <?php
                                        echo SchoolStructure::model()->attributeLabels()['operation_location_building'];
                                        echo $form->checkBox($modelSchoolStructure, 'operation_location_building', array('value' => 1, 'uncheckValue' => 0));
                                        ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_temple']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_temple', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_businness_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_businness_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_instructor_house']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_instructor_house', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other_school_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other_school_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_barracks']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_barracks', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_socioeducative_unity']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_socioeducative_unity', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_prison_unity']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_prison_unity', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['operation_location_other']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>                    
                        </div>
                        <div class=" span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'building_occupation_situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'building_occupation_situation', array(null => "Selecione a forma de ocupação", "1" => "Próprio", "2" => "Alugado", "3" => "Cedido"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'building_occupation_situation'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'shared_building_with_school', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'shared_building_with_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'shared_building_with_school'); ?>
                                </div>
                            </div>
                            <!-- //@done s1 - Lista de Escolas muito pequena, aumentar -->
                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'shared_school_inep_id_1', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelSchoolStructure, 'shared_school_inep_id_1', CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'), array('multiple' => true, 'key' => 'inep_id', 'class' => 'select-schools multiselect')); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'shared_school_inep_id_1'); ?>
                                </div>
                            </div>            
                        </div>
                    </div>

                    <div class="row-fluid"> 
                        <div class=" span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Dependencies'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_principal_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_principal_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructors_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructors_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_secretary_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_secretary_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_info_lab']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_info_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_science_lab']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_science_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_aee_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_aee_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_indoor_sports_court']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_indoor_sports_court', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_outdoor_sports_court']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_outdoor_sports_court', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_kitchen']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_kitchen', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_library']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_library', array('value' => 1, 'uncheckValue' => 0)); ?>                                            Biblioteca
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_reading_room']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_reading_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class=" span3">

                            <div class="control-group">
                                <div class="uniformjs select-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_playground']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_playground', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_nursery']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_nursery', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_outside_bathroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_outside_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_inside_bathroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_inside_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_child_bathroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_child_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_prysical_disability_bathroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_prysical_disability_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_physical_disability_support']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_physical_disability_support', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_bathroom_with_shower']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_bathroom_with_shower', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_refectory']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_refectory', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_storeroom']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_storeroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>


                            </div>

                        </div>
                        <div class=" span4">
                            <div class="control-group">
                                <div class="uniformjs select-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_warehouse']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_warehouse', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_auditorium']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_auditorium', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_covered_patio']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_covered_patio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_uncovered_patio']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_uncovered_patio', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_student_accomodation']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_student_accomodation', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_instructor_accomodation']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructor_accomodation', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_green_area']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_green_area', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_laundry']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_laundry', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['dependencies_none']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Water Supply'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_public']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_artesian_well']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_artesian_well', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_well']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_well'); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_river']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_river', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['water_supply_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Energy Supply'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_public']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_generator']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_generator', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_other']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['energy_supply_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Sewage'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_public']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_fossa']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_fossa', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sewage_inexistent']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sewage_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Garbage Destination'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_collect']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_collect', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_burn']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_burn', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_throw_away']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_throw_away', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_recycle']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_recycle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_bury']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_bury', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['garbage_destination_other']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="school-equipment">
                    <!--//@done S1 - 09 - equipmento é uma quantidade, não faz sentido os campos serem tão grandes. -->
                    <div class="row-fluid">
                        <div class=" span4">



                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_tv', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_tv', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_tv'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_vcr', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_vcr', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_vcr'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_dvd', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_dvd', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_dvd'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_satellite_dish', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_satellite_dish', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_satellite_dish'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_copier', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_copier', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_copier'); ?>
                                </div></div>


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'internet_access', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'internet_access', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'internet_access'); ?>
                                </div></div>
                        </div>
                        <div class=" span4">


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_overhead_projector', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_overhead_projector', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_overhead_projector'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_printer', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_printer', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_printer'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_stereo_system', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_stereo_system', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_stereo_system'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_data_show', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_data_show', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_data_show'); ?>
                                </div></div>


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_fax', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_fax', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                            <?php echo $form->error($modelSchoolStructure, 'equipments_fax'); ?>
                                </div></div>


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'bandwidth', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'bandwidth', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'bandwidth'); ?>
                                </div></div>
                        </div>

                        <div class=" span4">


                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_camera', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_camera', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_camera'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'equipments_computer', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'equipments_computer', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Count') . " " . Yii::t('help', 'School equipment'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'equipments_computer'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'administrative_computers_count', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'administrative_computers_count', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'administrative_computers_count'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'student_computers_count', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelSchoolStructure, 'student_computers_count', array('size' => 4, 'maxlength' => 4, 'class' => 'equipments_input')); ?>
                                    <span class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Only Numbers') . " " . Yii::t('help', 'Count'); ?>"><i></i></span>
                                    <?php echo $form->error($modelSchoolStructure, 'student_computers_count'); ?>
                                </div></div>

                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="school-education">
                    <div class="row-fluid">
                        <div class=" span5">



                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'aee', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'aee', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Não exclusivamente", "2" => "Exclusivamente"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'aee'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'complementary_activities', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'complementary_activities', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Não exclusivamente", "2" => "Exclusivamente"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'complementary_activities'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'basic_education_cycle_organized', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'basic_education_cycle_organized', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'basic_education_cycle_organized'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'different_location', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelSchoolStructure, 'different_location', array(null => "Selecione a localização",
                                        "1" => "Área de assentamento",
                                        "2" => "Terra indígena",
                                        "3" => "Área remanescente de quilombos",
                                        "4" => "Unidade de uso sustentável",
                                        "5" => "Unidade de uso sustentável em terra indígena",
                                        "6" => "Unidade de uso sustentável em área remanescente de quilombos",
                                        "7" => "Não se aplica",
                                            ), array('class' => 'select-search-off'));
                                    ?>
                                    <?php echo $form->error($modelSchoolStructure, 'different_location'); ?>
                                </div></div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Sociocultural Didactic Material'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_none']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_quilombola']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_quilombola', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['sociocultural_didactic_material_native']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_native', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'native_education', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'native_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'native_education'); ?>
                                </div></div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Native Education Language'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_native']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_native', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['native_education_language_portuguese']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_portuguese', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk', CHtml::listData(EdcensoNativeLanguages::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione a língua indígena", 'class' => 'select-search-on'));
                                    ?>
                                    <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'brazil_literate', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'brazil_literate', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'brazil_literate'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'open_weekend', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'open_weekend', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'open_weekend'); ?>
                                </div></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelSchoolStructure, 'pedagogical_formation_by_alternance', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelSchoolStructure, 'pedagogical_formation_by_alternance', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelSchoolStructure, 'pedagogical_formation_by_alternance'); ?>
                                </div></div>   
                        </div>
                        <div class="span7">



                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Modalities'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_regular']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_regular', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_especial']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_especial', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['modalities_eja']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'modalities_eja', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Stage'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_creche']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_creche', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_preschool']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_preschool', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_fundamental_eigth_years']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_fundamental_eigth_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_fundamental_nine_years']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_fundamental_nine_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_high_school']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_high_school_integrated']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school_integrated', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_high_school_normal_mastership']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school_normal_mastership', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_regular_education_high_school_preofessional_education']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school_preofessional_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_creche']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_creche', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_preschool']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_preschool', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_fundamental_eigth_years']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_fundamental_eigth_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_fundamental_nine_years']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_fundamental_nine_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_high_school']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_high_school_integrated']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school_integrated', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_high_school_normal_mastership']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school_normal_mastership', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_high_school_professional_education']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school_professional_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_eja_fundamental_education']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_eja_fundamental_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_special_education_eja_high_school_education']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_eja_high_school_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_education_eja_fundamental_education']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_education_eja_fundamental_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_education_eja_fundamental_education_projovem']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_education_eja_fundamental_education_projovem', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo SchoolStructure::model()->attributeLabels()['stage_education_eja_high_school_education']; ?>
                                        <?php echo $form->checkBox($modelSchoolStructure, 'stage_education_eja_high_school_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>                                
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




<script type="text/javascript">
    var formIdentification = '#SchoolIdentification_';
    var formStructure = '#SchoolStructure_';
    var date = new Date();
    var actual_year = date.getFullYear();
    var initial_date = stringToDate($(formIdentification+'initial_date').val());    
    var final_date = stringToDate($(formIdentification+'final_date').val());
        
    $(formIdentification+'initial_date').mask("99/99/9999");
    $(formIdentification+'initial_date').focusout(function() {
        var id = '#'+$(this).attr("id");
        initial_date = stringToDate($(formIdentification+'initial_date').val());    
        if(!validateDate($(formIdentification+'initial_date').val()) 
            || !(initial_date.year >= actual_year - 1 
            && initial_date.year <= actual_year)) {
            $(formIdentification+'initial_date').attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formIdentification+'final_date').mask("99/99/9999");
    $(formIdentification+'final_date').focusout(function() {
        var id = '#'+$(this).attr("id");
        final_date = stringToDate($(formIdentification+'final_date').val());
        if(!validateDate($(formIdentification+'final_date').val())
            || !(final_date.year >= actual_year 
            && final_date.year <= actual_year + 1)
            || !(final_date.asianStr > initial_date.asianStr)) {
            $(formIdentification+'final_date').attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formIdentification+'name').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateSchoolName($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formIdentification+'cep, '+formIdentification+'inep_id').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateCEP($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
        
    $(formIdentification+'ddd').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateDDD($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    }); 
        
    $(formIdentification+'address').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateAddress($(id).val(),100)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    $(formIdentification+'address_number').focusout(function() {
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase()); 
        if(!validateAddress($(id).val(),10)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    /*$(formIdentification+'address_complement').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateAddress($(id).val(),20)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });*/
    $(formIdentification+'address_neighborhood').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateAddress($(id).val(),50)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formIdentification+'phone_number').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validatePhone($(id).val(),9)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    $(formIdentification+'public_phone_number').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validatePhone($(id).val(),8)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    $(formIdentification+'other_phone_number').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validatePhone($(id).val(),9)) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formIdentification+'email').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateEmail($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formStructure+'manager_cpf').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateCpf($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formStructure+'manager_name').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        var ret = validateNamePerson(($(id).val()));
        if(!ret[0]) {
            $(id).attr('value','');
            addError(id, "Campo Endereço não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formStructure+'manager_email').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateEmail($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formStructure+'used_classroom_count, '
        + formStructure+'classroom_count, '
        + formStructure+'equipments_tv , '
        + formStructure+'equipments_vcr , '
        + formStructure+'equipments_dvd , '
        + formStructure+'equipments_satellite_dish , '
        + formStructure+'equipments_copier , '
        + formStructure+'equipments_overhead_projector , '
        + formStructure+'equipments_printer , '
        + formStructure+'equipments_stereo_system , '
        + formStructure+'equipments_data_show , '
        + formStructure+'equipments_fax , '
        + formStructure+'equipments_camera , '
        + formStructure+'equipments_computer, '
        + formStructure+'administrative_computers_count, '
        + formStructure+'student_computers_count, '
        + formStructure+'employees_count '
    ).focusout(function() {
        var id = '#'+$(this).attr("id");
        if(!validateCount($(id).val()))  {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
        
    $(formStructure+'operation_location input[type=checkbox]').change(function(){
        var id = '#'+$(formStructure+'operation_location').attr("id");
        if($('#SchoolStructure_operation_location input[type=checkbox]:checked').length == 0){
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    })
        
    $(formStructure+'operation_location').focusout(function(){
        var id = '#'+$(this).attr("id");
        if($('#SchoolStructure_operation_location input[type=checkbox]:checked').length == 0){
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    })
        
        
    //multiselect
    var sharedSchool = [];
    $(formStructure+"shared_school_inep_id_1").mousedown(function(){
        sharedSchool = $(this).val();
    });
        
    $(formStructure+"shared_school_inep_id_1").mouseup(function(e){
        if (!e.shiftKey){
            value = $(this).val()[0];
                
            remove = 0;
            sharedSchool = jQuery.grep(sharedSchool, function( a ) {
                if(a === value) remove++;
                return a !== value;
            });
                
            if(remove == 0) sharedSchool.push(value);
            $(this).val(sharedSchool);
        }
    });
    //multiselect
        
//        $("#SchoolIdentification_edcenso_regional_education_organ_fk").select2({ width: 'resolve' });
//        $("#SchoolIdentification_administrative_dependence").select2({ width: 'resolve' });
//        $("#SchoolStructure_shared_school_inep_id_1").select2({ width: 'resolve' , placeholder: '<?php echo Yii::t('default', 'Select School'); ?>'});
        
    $('.tab-school li a').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-school-indentify';
        var li2 = 'tab-school-structure';
        var li3 = 'tab-school-equipment';
        var li4 = 'tab-school-education';
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
                $('.next').show();
                $('.last').hide(); break;
            case li4 : tab = li4; 
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
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    }) 

    $('.next').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-school-indentify';
        var li2 = 'tab-school-structure';
        var li3 = 'tab-school-equipment';
        var li4 = 'tab-school-education';
        var next = '';
        switch(classActive.attr('id')) {
            case li1 : next = li2; 
                $('.prev').show(); break;
            case li2 : next = li3; break;
            case li3 : next = li4;
                $('.next').hide();
                $('.last').show(); break;
            case li4 : next = li4; break;
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
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-school-indentify';
        var li2 = 'tab-school-structure';
        var li3 = 'tab-school-equipment';
        var li4 = 'tab-school-education';
        var previous = '';
        switch(classActive.attr('id')) {
            case li1 : previous = li1; break;
            case li2 : previous = li1; 
                $('.prev').hide(); break;
            case li3 : previous = li2; break;
            case li4 : previous = li3; 
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
</script>
