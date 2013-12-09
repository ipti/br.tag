<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'school',
	'enableAjaxValidation'=>false,
)); ?>
    
<div class="heading-buttons">
    <?php echo $form->errorSummary($modelSchoolIdentification); ?>
    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>
    <div class="buttons pull-right">
        <button type="button" class="btn btn-icon btn-default glyphicons unshare"><i></i>Voltar</button>
        <?php echo CHtml::submitButton($modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary glyphicons circle_ok')); ?>
    </div>
    <div class="clearfix"></div>
</div>
            
<div class="innerLR">
    
    <div class="widget widget-tabs border-bottom-none">
    
        <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons edit" href="#school-indentify" data-toggle="tab"><i></i>Identificação</a></li>
                <li><a class="glyphicons settings" href="#school-structure" data-toggle="tab"><i></i>Infraestrutura</a></li>
                <li><a class="glyphicons imac" href="#school-equipament" data-toggle="tab"><i></i>Equipamentos</a></li>
                <li><a class="glyphicons parents" href="#school-humans" data-toggle="tab"><i></i>Recursos Humanos</a></li>
                <li><a class="glyphicons cutlery" href="#school-feeding" data-toggle="tab"><i></i>Alimentação</a></li>
                <li><a class="glyphicons book" href="#school-education" data-toggle="tab"><i></i>Dados Educacionais</a></li>
            </ul>
        </div>
        
        <div class="widget-body form-horizontal">
        
            <div class="tab-content">

        <!-- Tab content -->
        <div class="tab-pane active" id="school-indentify">
            <div class="row-fluid">
                <div class=" span6">
                    
                    <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                         
                    <div class="separator"></div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'name', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'name', array('size' => 60, 'maxlength' => 100, 'class' => 'span10')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'name'); ?>
                        </div>
                    </div>
                        
                    <div class="separator"></div>

                    <!--

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'latitude',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'latitude',array('size'=>20,'maxlength'=>20)); ?>
                            <?php echo $form->error($modelSchoolIdentification,'latitude'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php //echo $form->labelEx($modelSchoolIdentification,'longitude',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php //echo $form->textField($modelSchoolIdentification,'longitude',array('size'=>20,'maxlength'=>20)); ?>
                            <?php //echo $form->error($modelSchoolIdentification,'longitude'); ?>
                        </div>
                    </div>

                    -->

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'cep', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'cep', array('size' => 8, 'maxlength' => 8, 'class' => 'span5')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'cep'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'address', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'address', array('size' => 60, 'maxlength' => 100, 'class' => 'span10')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'address'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'address_number', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'address_number', array('size' => 10, 'maxlength' => 10, 'class' => 'span2')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'address_number'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'address_complement', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'address_complement', array('size' => 20, 'maxlength' => 20, 'class' => 'span10')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'address_complement'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'address_neighborhood', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'address_neighborhood', array('size' => 50, 'maxlength' => 50, 'class' => 'span10')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'address_neighborhood'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                'prompt' => 'Select State'
                                ,'ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('schoolIdentification/getcities'),
                                    'update' => '#SchoolIdentification_edcenso_city_fk'
                                )));?>      
                            <?php echo $form->error($modelSchoolIdentification, 'edcenso_uf_fk'); ?>
                        </div>
                    </div>
                        
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php
                            echo $form->dropDownList($modelSchoolIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelSchoolIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array('ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('schoolIdentification/getdistricts'),
                                    'update' => '#SchoolIdentification_edcenso_district_fk',
                                    )));
                            ?>  
                            <?php echo $form->error($modelSchoolIdentification, 'edcenso_city_fk'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_district_fk', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_district_fk', CHtml::listData(EdcensoDistrict::model()->findAllByAttributes(array('edcenso_city_fk' => $modelSchoolIdentification->edcenso_city_fk), array('order' => 'name')), 'code', 'name'));
                            ?>  
                            <?php echo $form->error($modelSchoolIdentification, 'edcenso_district_fk'); ?>
                        </div>
                    </div>

                    <?php // @TODO Campo de DDD tem que estar junto com o campo de Telefone ?> 

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'ddd', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'ddd', array('size' => 2, 'maxlength' => 2)); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'ddd'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'phone_number', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'phone_number', array('size' => 9, 'maxlength' => 9)); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'phone_number'); ?>
                        </div>
                    </div>
                    <!--

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'public_phone_number',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'public_phone_number',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($modelSchoolIdentification,'public_phone_number'); ?>
                        </div>
                    </div>

                    -->
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'other_phone_number', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'other_phone_number', array('size' => 9, 'maxlength' => 9)); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'other_phone_number'); ?>
                        </div>
                    </div>

                    <!--

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'fax_number',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'fax_number',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($modelSchoolIdentification,'fax_number'); ?>
                        </div>
                    </div>

                    -->
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'email', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'email', array('size' => 50, 'maxlength' => 50)); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'email'); ?>
                        </div>
                    </div>
                </div>

                <div class="span6">
                    
                    <div class="separator"></div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'inep_id', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'inep_id', array('size' => 8, 'maxlength' => 8, 'class' => 'span10')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'inep_id'); ?>
                        </div>
                    </div>
                        
                    <div class="separator"></div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'situation', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->DropDownList($modelSchoolIdentification, 'situation', array(1 => 'Em Atividade', 2 => 'paralisada', 3 => 'extinta')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'situation'); ?>
                        </div>
                    </div>
                        
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'initial_date', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'initial_date', array('size' => 10, 'maxlength' => 10)); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'initial_date'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'final_date', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification, 'final_date', array('size' => 10, 'maxlength' => 10)); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'final_date'); ?>
                        </div>
                    </div>
                        
                    <div class="separator"></div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($modelSchoolIdentification, 'edcenso_regional_education_organ_fk', CHtml::listData(EdcensoRegionalEducationOrgan::model()->findAll(array('order' => 'name')), 'id', 'name'));
                            ?>
                            <?php echo $form->error($modelSchoolIdentification, 'edcenso_regional_education_organ_fk'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'administrative_dependence', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($modelSchoolIdentification, 'administrative_dependence', array(1 => 'Federal', 2 => 'Estadual', 3 => 'Municipal', 4 => 'Privada')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'administrative_dependence'); ?>
                        </div>
                    </div>
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'location', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->DropDownList($modelSchoolIdentification, 'location', array(1 => 'Urbano', 2 => 'Rural')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'location'); ?>
                        </div>
                    </div>

                    <!--

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'private_school_category',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'private_school_category'); ?>
                            <?php echo $form->error($modelSchoolIdentification,'private_school_category'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'public_contract',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'public_contract'); ?>
                            <?php echo $form->error($modelSchoolIdentification,'public_contract'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'private_school_maintainer_fk',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php //echo $form->textField($modelSchoolIdentification,'private_school_maintainer_fk'); ?>
                            <?php //echo $form->error($modelSchoolIdentification,'private_school_maintainer_fk'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'private_school_maintainer_cnpj',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'private_school_maintainer_cnpj',array('size'=>14,'maxlength'=>14)); ?>
                            <?php echo $form->error($modelSchoolIdentification,'private_school_maintainer_cnpj'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification,'private_school_cnpj',array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($modelSchoolIdentification,'private_school_cnpj',array('size'=>14,'maxlength'=>14)); ?>
                            <?php echo $form->error($modelSchoolIdentification,'private_school_cnpj'); ?>
                        </div>
                    </div>

                    -->
                        
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolIdentification, 'regulation', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($modelSchoolIdentification, 'regulation', array(0 => 'Não', 1 => 'Sim', 2 => 'Em tramitação')); ?>
                            <?php echo $form->error($modelSchoolIdentification, 'regulation'); ?>
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
    
            
        <div class="tab-pane" id="school-structure">
            <div class="row-fluid">
                <div class=" span6">
                    <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                </div>
                  
                <div class="separator"></div>
                            
<?php /*
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'register_type'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'register_type',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($modelSchoolStructure,'register_type'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'school_inep_id_fk'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'school_inep_id_fk'); ?>
                    </div>
*/ ?>
                        <div class="separator"></div>
                            
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'manager_cpf'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'manager_cpf', array('size' => 11, 'maxlength' => 11)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'manager_cpf'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'manager_name'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'manager_name', array('size' => 60, 'maxlength' => 100)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'manager_name'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'manager_role'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'manager_role', array(null => "Selecione o cargo", "1" => "Diretor", "2" => "Outro Cargo")); ?>
                            <?php echo $form->error($modelSchoolStructure, 'manager_role'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'manager_email'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'manager_email', array('size' => 50, 'maxlength' => 50)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'manager_email'); ?>
                        </div>
                        
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_building'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_building', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_building'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_temple'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_temple', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_temple'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_businness_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_businness_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_businness_room'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_instructor_house'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_instructor_house', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_instructor_house'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_other_school_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other_school_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_other_school_room'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_barracks'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_barracks', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_barracks'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_socioeducative_unity'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_socioeducative_unity', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_socioeducative_unity'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_prison_unity'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_prison_unity', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_prison_unity'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'operation_location_other'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'operation_location_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'operation_location_other'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'building_occupation_situation'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'building_occupation_situation', array(null => "Selecione a forma de ocupação", "1" => "Próprio", "2" => "Alugado", "3" => "Cedido")); ?>
                            <?php echo $form->error($modelSchoolStructure, 'building_occupation_situation'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'shared_building_with_school'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'shared_building_with_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'shared_building_with_school'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'shared_school_inep_id_1'); ?>
                            <?php echo $form->dropDownList($modelSchoolStructure, 'shared_school_inep_id_1', CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'), array('multiple' => true, 'key' => 'inep_id'));?>
                            <?php echo $form->error($modelSchoolStructure, 'shared_school_inep_id_1'); ?>
                        </div>

                        <?php /*
                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'shared_school_inep_id_1'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'shared_school_inep_id_1',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'shared_school_inep_id_1'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'shared_school_inep_id_2'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'shared_school_inep_id_2',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'shared_school_inep_id_2'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'shared_school_inep_id_3'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'shared_school_inep_id_3',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'shared_school_inep_id_3'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'shared_school_inep_id_4'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'shared_school_inep_id_4',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'shared_school_inep_id_4'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'shared_school_inep_id_5'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'shared_school_inep_id_5',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'shared_school_inep_id_5'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($modelSchoolStructure,'shared_school_inep_id_6'); ?>
                        <?php echo $form->textField($modelSchoolStructure,'shared_school_inep_id_6',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($modelSchoolStructure,'shared_school_inep_id_6'); ?>
                    </div>
*/ ?>
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'consumed_water_type'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'consumed_water_type', array(null => "Selecione o tipo de água", "1" => "Não filtrada", "2" => "Filtrada")); ?>
                            <?php echo $form->error($modelSchoolStructure, 'consumed_water_type'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'water_supply_public'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'water_supply_public'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'water_supply_artesian_well'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_artesian_well', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'water_supply_artesian_well'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'water_supply_well'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_well'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'water_supply_well'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'water_supply_river'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_river', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'water_supply_river'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'water_supply_inexistent'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'water_supply_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'water_supply_inexistent'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'energy_supply_public'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'energy_supply_public'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'energy_supply_generator'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_generator', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'energy_supply_generator'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'energy_supply_other'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'energy_supply_other'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'energy_supply_inexistent'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'energy_supply_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'energy_supply_inexistent'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'sewage_public'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'sewage_public', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'sewage_public'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'sewage_fossa'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'sewage_fossa', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'sewage_fossa'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'sewage_inexistent'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'sewage_inexistent', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'sewage_inexistent'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'garbage_destination_collect'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_collect', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'garbage_destination_collect'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'garbage_destination_burn'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_burn', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'garbage_destination_burn'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'garbage_destination_throw_away'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_throw_away', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'garbage_destination_throw_away'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'garbage_destination_recycle'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_recycle', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'garbage_destination_recycle'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'garbage_destination_bury'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_bury', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'garbage_destination_bury'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'garbage_destination_other'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'garbage_destination_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'garbage_destination_other'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_principal_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_principal_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_principal_room'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_instructors_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructors_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_instructors_room'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_secretary_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_secretary_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_secretary_room'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_info_lab'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_info_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_info_lab'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_science_lab'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_science_lab', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_science_lab'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_aee_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_aee_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_aee_room'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_indoor_sports_court'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_indoor_sports_court', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_indoor_sports_court'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_outdoor_sports_court'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_outdoor_sports_court', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_outdoor_sports_court'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_kitchen'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_kitchen', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_kitchen'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_library'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_library', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_library'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_reading_room'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_reading_room', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_reading_room'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_playground'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_playground', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_playground'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_nursery'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_nursery', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_nursery'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_outside_bathroom'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_outside_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_outside_bathroom'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_inside_bathroom'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_inside_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_inside_bathroom'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_child_bathroom'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_child_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_child_bathroom'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_prysical_disability_bathroom'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_prysical_disability_bathroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_prysical_disability_bathroom'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_physical_disability_support'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_physical_disability_support', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_physical_disability_support'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_bathroom_with_shower'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_bathroom_with_shower', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_bathroom_with_shower'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_refectory'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_refectory', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_refectory'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_storeroom'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_storeroom', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_storeroom'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_warehouse'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_warehouse', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_warehouse'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_auditorium'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_auditorium', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_auditorium'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_covered_patio'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_covered_patio', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_covered_patio'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_uncovered_patio'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_uncovered_patio', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_uncovered_patio'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_student_accomodation'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_student_accomodation', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_student_accomodation'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_instructor_accomodation'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_instructor_accomodation', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_instructor_accomodation'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_green_area'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_green_area', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_green_area'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_laundry'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_laundry', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_laundry'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'dependencies_none'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'dependencies_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'dependencies_none'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'classroom_count'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'classroom_count'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'classroom_count'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'used_classroom_count'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'used_classroom_count'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'used_classroom_count'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_tv'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_tv'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_tv'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_vcr'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_vcr'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_vcr'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_dvd'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_dvd'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_dvd'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_satellite_dish'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_satellite_dish'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_satellite_dish'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_copier'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_copier'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_copier'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_overhead_projector'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_overhead_projector'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_overhead_projector'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_printer'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_printer'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_printer'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_stereo_system'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_stereo_system'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_stereo_system'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_data_show'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_data_show'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_data_show'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_fax'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_fax'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_fax'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_camera'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_camera'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_camera'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'equipments_computer'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'equipments_computer'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'equipments_computer'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'administrative_computers_count'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'administrative_computers_count'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'administrative_computers_count'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'student_computers_count'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'student_computers_count'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'student_computers_count'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'internet_access'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'internet_access', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'internet_access'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'bandwidth'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'bandwidth', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'bandwidth'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'employees_count'); ?>
                            <?php echo $form->textField($modelSchoolStructure, 'employees_count'); ?>
                            <?php echo $form->error($modelSchoolStructure, 'employees_count'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'feeding'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'feeding', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Oferece")); ?>
                            <?php echo $form->error($modelSchoolStructure, 'feeding'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'aee'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'aee', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Não exclusivamente", "2" => "Exclusivamente")); ?>
                            <?php echo $form->error($modelSchoolStructure, 'aee'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'complementary_activities'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'complementary_activities', array(null => "Selecione o valor", "0" => "Não oferece", "1" => "Não exclusivamente", "2" => "Exclusivamente")); ?>
                            <?php echo $form->error($modelSchoolStructure, 'complementary_activities'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'modalities_regular'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'modalities_regular', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'modalities_regular'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'modalities_especial'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'modalities_especial', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'modalities_especial'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'modalities_eja'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'modalities_eja', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'modalities_eja'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_creche'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_creche', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_creche'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_preschool'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_preschool', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_preschool'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_fundamental_eigth_years'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_fundamental_eigth_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_fundamental_eigth_years'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_fundamental_nine_years'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_fundamental_nine_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_fundamental_nine_years'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_high_school'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_high_school'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_high_school_integrated'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school_integrated', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_high_school_integrated'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_high_school_normal_mastership'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school_normal_mastership', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_high_school_normal_mastership'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_regular_education_high_school_preofessional_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_regular_education_high_school_preofessional_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_regular_education_high_school_preofessional_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_creche'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_creche', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_creche'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_preschool'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_preschool', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_preschool'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_fundamental_eigth_years'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_fundamental_eigth_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_fundamental_eigth_years'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_fundamental_nine_years'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_fundamental_nine_years', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_fundamental_nine_years'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_high_school'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_high_school'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_high_school_integrated'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school_integrated', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_high_school_integrated'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_high_school_normal_mastership'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school_normal_mastership', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_high_school_normal_mastership'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_high_school_professional_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_high_school_professional_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_high_school_professional_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_eja_fundamental_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_eja_fundamental_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_eja_fundamental_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_special_education_eja_high_school_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_special_education_eja_high_school_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_special_education_eja_high_school_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_education_eja_fundamental_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_education_eja_fundamental_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_education_eja_fundamental_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_education_eja_fundamental_education_projovem'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_education_eja_fundamental_education_projovem', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_education_eja_fundamental_education_projovem'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'stage_education_eja_high_school_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'stage_education_eja_high_school_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'stage_education_eja_high_school_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'basic_education_cycle_organized'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'basic_education_cycle_organized', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'basic_education_cycle_organized'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'different_location'); ?>
                            <?php
                            echo $form->DropDownList($modelSchoolStructure, 'different_location', array(null => "Selecione a localização",
                                "1" => "Área de assentamento",
                                "2" => "Terra indígena",
                                "3" => "Área remanescente de quilombos",
                                "4" => "Unidade de uso sustentável",
                                "5" => "Unidade de uso sustentável em terra indígena",
                                "6" => "Unidade de uso sustentável em área remanescente de quilombos",
                                "7" => "Não se aplica",
                            ));
                            ?>
                        <?php echo $form->error($modelSchoolStructure, 'different_location'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'sociocultural_didactic_material_none'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'sociocultural_didactic_material_none'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'sociocultural_didactic_material_quilombola'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_quilombola', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'sociocultural_didactic_material_quilombola'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'sociocultural_didactic_material_native'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'sociocultural_didactic_material_native', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'sociocultural_didactic_material_native'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'native_education'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'native_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'native_education'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'native_education_language_native'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_native', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'native_education_language_native'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'native_education_language_portuguese'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'native_education_language_portuguese', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'native_education_language_portuguese'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'edcenso_native_languages_fk'); ?>
                            <?php echo $form->DropDownList($modelSchoolStructure, 'edcenso_native_languages_fk', CHtml::listData(EdcensoNativeLanguages::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione a língua indígena"));
                            ?>
                            <?php echo $form->error($modelSchoolStructure, 'edcenso_native_languages_fk'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'brazil_literate'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'brazil_literate', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'brazil_literate'); ?>
                        </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'open_weekend'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'open_weekend', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'open_weekend'); ?>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelSchoolStructure, 'pedagogical_formation_by_alternance'); ?>
                            <?php echo $form->checkBox($modelSchoolStructure, 'pedagogical_formation_by_alternance', array('value' => 1, 'uncheckValue' => 0)); ?>
                            <?php echo $form->error($modelSchoolStructure, 'pedagogical_formation_by_alternance'); ?>
                        </div>

                                    
            </div>
        </div>
        
        <div class="tab-pane" id="school-equipament">
            <div class="row-fluid">
                <div class=" span6">
                    <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                </div>
                      
                <div class="separator"></div>
            </div>
        </div>
            
        <div class="tab-pane" id="school-humans">
            <div class="row-fluid">
                <div class=" span6">
                    <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                </div>
                      
                <div class="separator"></div>
            </div>
        </div>
            
        <div class="tab-pane" id="school-feeding">
            <div class="row-fluid">
                <div class=" span6">
                    <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                </div>
                      
                <div class="separator"></div>
            </div>
        </div>
            
        <div class="tab-pane" id="school-education">
            <div class="row-fluid">
                <div class=" span6">
                      
                    <?php echo Yii::t('default', 'Campos com * são obrigatórios.') ?>
                </div>
                      
                <div class="separator"></div>
            </div>
        </div>
                
            
        <?php $this->endWidget(); ?>
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
            initial_date = stringToDate($(formIdentification+'initial_date').val());    
            if(!validateDate($(formIdentification+'initial_date').val()) 
                || !(initial_date.year >= actual_year - 1 
                && initial_date.year <= actual_year)) 
                $(formIdentification+'initial_date').attr('value','');
        });
        
        $(formIdentification+'final_date').mask("99/99/9999");
        $(formIdentification+'final_date').focusout(function() {
            final_date = stringToDate($(formIdentification+'final_date').val());
            if(!validateDate($(formIdentification+'final_date').val())
                || !(final_date.year >= actual_year 
                && final_date.year <= actual_year + 1)
                || !(final_date.asianStr > initial_date.asianStr)
        ) 
                $(formIdentification+'final_date').attr('value','');
        });
        
        $(formIdentification+'name').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateSchoolName($(this).val())) 
                $(this).attr('value','');
        });
        
        $(formIdentification+'cep').focusout(function() { 
            if(!validateCEP($(this).val())) 
                $(this).attr('value','');
        });
        
        $(formIdentification+'address').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateAddress($(this).val(),100)) 
                $(this).attr('value','');
        });
        $(formIdentification+'address_number').focusout(function() {
            $(this).val($(this).val().toUpperCase()); 
            if(!validateAddress($(this).val(),10)) 
                $(this).attr('value','');
        });
        $(formIdentification+'address_complement').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateAddress($(this).val(),20)) 
                $(this).attr('value','');
        });
        $(formIdentification+'address_neighborhood').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateAddress($(this).val(),50)) 
                $(this).attr('value','');
        });
        
        $(formIdentification+'phone_number').focusout(function() { 
            if(!validatePhone($(this).val(),9)) 
                $(this).attr('value','');
        });
        $(formIdentification+'public_phone_number').focusout(function() { 
            if(!validatePhone($(this).val(),8)) 
                $(this).attr('value','');
        });
        $(formIdentification+'other_phone_number').focusout(function() { 
            if(!validatePhone($(this).val(),9)) 
                $(this).attr('value','');
        });
        
        $(formIdentification+'email').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateEmail($(this).val())) 
                $(this).attr('value','');
        });
        
        $(formStructure+'manager_cpf').focusout(function() { 
            if(!validateCPF($(this).val())) 
                $(this).attr('value','');
        });
        
        $(formStructure+'manager_name').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            var ret = validateNamePerson(($(this).val()));
            if(!ret[0]) 
                $(this).attr('value','');
        });
        
        $(formStructure+'manager_email').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateEmail($(this).val())) 
                $(this).attr('value','');
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
            if(!validateCount($(this).val())) 
                $(this).attr('value','');
        });
        
        
        
    </script>
