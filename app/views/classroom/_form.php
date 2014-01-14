<?php 
//@done S1 - 21 - A turma precisa de um periodo letivo senão ela fica atemporal.
//@done S1 - 23 - Lembrar de associar o professor a turma.
//@done S1 - Organizar os campos do form_classroom
//@todo S1 - Retirar aba Disciplinas. Add disciplinas em teachingData e vincular as disciplinas(checkbox) com os prof (dropdown) 
//@later S2 - Add validação para os campos que esão faltando


$form=$this->beginWidget('CActiveForm', array(
	'id'=>'classroom-form',
	'enableAjaxValidation'=>false,
)); 
?>
    
<div class="heading-buttons">
    <?php echo $form->errorSummary($modelClassroom); ?>
    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
    
<div class="innerLR">
    
    <div class="widget widget-tabs border-bottom-none">
        
        <div class="widget-head">
            <ul>
                <li id="tab-classroom" class="active" ><a class="glyphicons edit" href="#classroom" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Classroom') ?></a></li>
                <li id="tab-instructor-teaching"><a class="glyphicons edit" href="#instructor-teaching" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Instructor Teaching Data') ?></a></li> 
                <li id="tab-relations-classroom-teaching"><a class="glyphicons edit" href="#relations-classroom-teaching" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Relations Classroom Teaching') ?></a></li> 
            </ul>
        </div>
            
        <div class="widget-body form-horizontal">
            
            <div class="tab-content">
                
                <!-- Tab content -->
                <div class="tab-pane active" id="classroom">
                    <div class="row-fluid">
                        <div class=" span5">
                            
                            <div class="separator"></div>
                            <div class="control-group">
                                <?php //@done S1 - 08 - 07 - A Criação da turma é feita dentro de uma escola, não precisa ser necessário selecionar uma?>
                                <div class="controls">
                                    <?php
                                    echo $form->hiddenField($modelClassroom,'school_inep_fk',array('value'=>Yii::app()->user->school));
                                    ?>  
                                </div>
                            </div>
                            <div class="control-group">
                                <?php 
                                //@todo S1 - 09 - O Campo nome deve possuir uma mascara e seguir um padrão a ser definido.
                                echo $form->labelEx($modelClassroom, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelClassroom, 'name', array('size' => 60, 'maxlength' => 80)); ?>
                                    <?php echo $form->error($modelClassroom, 'name'); ?>
                                </div>
                            </div>
                            <div class="control-group">

                               <?php 
                                echo $form->labelEx($modelClassroom, 'school_year', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelClassroom, 'school_year', array('size' => 5, 'maxlength' => 5)); ?>
                                    <?php echo $form->error($modelClassroom, 'school_year'); ?>
                                </div>
                            </div>
                          
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'initial_hour', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->hiddenField($modelClassroom, 'initial_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo $form->hiddenField($modelClassroom, 'initial_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo CHtml::textField('Classroom_initial_time', $modelClassroom->initial_hour . ':' . $modelClassroom->initial_minute, array('size' => 5, 'maxlength' => 5)); ?>
                                    <?php echo $form->error($modelClassroom, 'initial_hour'); ?>
                                    <?php echo $form->error($modelClassroom, 'initial_minute'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'final_hour', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->hiddenField($modelClassroom, 'final_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo $form->hiddenField($modelClassroom, 'final_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo CHtml::textField('Classroom_final_time', $modelClassroom->final_hour . ':' . $modelClassroom->final_minute, array('size' => 5, 'maxlength' => 5)); ?>
                                    <?php echo $form->error($modelClassroom, 'final_hour'); ?>
                                    <?php echo $form->error($modelClassroom, 'final_minute'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">
                            <label class="control-label"><?php echo Yii::t('default', 'Week Days'); ?></label>
                            <div class="uniformjs margin-left" id="Classroom_week_days">
                                <label class="checkbox">
                                    <?php 
                                    //@done S1 - 08 - 08 - Os Valores deste campo são definidos de forma global e pode vim preenchidos default
                                    echo Classroom::model()->attributeLabels()['week_days_sunday'];
                                    echo $form->checkBox($modelClassroom, 'week_days_sunday', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_monday']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'week_days_monday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_tuesday']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'week_days_tuesday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_wednesday']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'week_days_wednesday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_thursday']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'week_days_thursday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_friday']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'week_days_friday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_saturday']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'week_days_saturday', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'assistance_type', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelClassroom, 'assistance_type', array(null => 'Selecione o tipo de assistencia'), array('ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('classroom/updateassistancetypedependencies'),
                                            'success' => "function(data){
                                                    data = jQuery.parseJSON(data);
                                                    $('#Classroom_mais_educacao_participator').prop('disabled', data.MaisEdu);
                                                    $('#Classroom_aee_braille_system_education').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_optical_and_non_optical_resources').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_mental_processes_development_strategies').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_mobility_and_orientation_techniques').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_libras').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_caa_use_education').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_curriculum_enrichment_strategy').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_soroban_use_education').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_usability_and_functionality_of_computer_accessible_education').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_teaching_of_Portuguese_language_written_modality').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_aee_strategy_for_school_environment_autonomy').prop('disabled', data.AeeActivity);
                                                    $('#Classroom_edcenso_stage_vs_modality_fk').html(data.Stage);
                                                    $('#Classroom_edcenso_stage_vs_modality_fk').prop('disabled', data.StageEmpty);
                                                    $('#Classroom_modality').html(data.Modality);
                                                }",
                                            )));
                                    ?> 
                                    <?php echo $form->error($modelClassroom, 'assistance_type'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'mais_educacao_participator', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelClassroom, 'mais_educacao_participator'); ?>
                                    <?php echo $form->error($modelClassroom, 'mais_educacao_participator'); ?>
                                </div>
                            </div>
                                
                            <!-- dar uma olhada no http://mind2soft.com/labs/jquery/multiselect/ -->
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'complementary_activity_type_1', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelClassroom, 'complementary_activity_type_1', CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'), array('multiple' => true, 'key' => 'id')); ?>
                                    <?php echo $form->error($modelClassroom, 'complementary_activity_type_1'); ?>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="span5">
                            <div class="separator"></div>
                             <div class="separator"></div>
                            <div class="control-group">
                            <label class="control-label"><?php echo Yii::t('default', 'Aee'); ?></label>
                            <div class="uniformjs margin-left">
                                <label class="checkbox">
                                    <?php 
                                    echo Classroom::model()->attributeLabels()['aee_braille_system_education'];
                                    echo $form->checkBox($modelClassroom, 'aee_braille_system_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_optical_and_non_optical_resources']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_optical_and_non_optical_resources', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_mental_processes_development_strategies']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_mental_processes_development_strategies', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_mobility_and_orientation_techniques']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_mobility_and_orientation_techniques', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_libras']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_caa_use_education']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_caa_use_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_curriculum_enrichment_strategy']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_curriculum_enrichment_strategy', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_soroban_use_education']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_soroban_use_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_usability_and_functionality_of_computer_accessible_education']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_usability_and_functionality_of_computer_accessible_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_teaching_of_Portuguese_language_written_modality']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_teaching_of_Portuguese_language_written_modality', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_strategy_for_school_environment_autonomy']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'aee_strategy_for_school_environment_autonomy', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'modality', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'modality', array(null => 'Selecione a modalidade',
                                        '1' => 'Ensino Regular',
                                        '2' => 'Educação Especial - Modalidade Substitutiva',
                                        '3' => 'Educação de Jovens e Adultos (EJA)')); ?>
                                    <?php echo $form->error($modelClassroom, 'modality'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => '(Select Stage vs Modality)')); ?>
                                    <?php echo $form->error($modelClassroom, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'edcenso_professional_education_course_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'edcenso_professional_education_course_fk', CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o curso',)); ?>
                                    <?php echo $form->error($modelClassroom, 'edcenso_professional_education_course_fk'); ?>
                                </div>
                            </div>
                                
                            <?php $instructorSituationEnum = array(null => 'Selecione a situação', "0" => "Turma com docente", "1" => "Turma sem docente"); ?>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'instructor_situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'instructor_situation', $instructorSituationEnum); ?>
                                    <?php echo $form->error($modelClassroom, 'instructor_situation'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group buttonWizardBar nextBar">
                        <a href="#disciplines" data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('deafult','Next') ?><i></i></a>
                    </div>
                </div>
                  <div class="tab-pane" id="instructor-teaching">
                    <div class="row-fluid">
                        <div class=" span6">
                        <div class="separator"></div>
                            
                        <div>
                        <div class="control-group">
                            <?php echo CHtml::label("Instructors", "Instructors", array('class' => 'control-label'))?>
                            <div class="controls">
                                <?php echo CHtml::DropDownList("Instructors", '', CHtml::listData(InstructorIdentification::model()->findAll(array('order' => 'name')), 'id', 'name')); ?>
                                <button class="btn btn-icon btn-primary next glyphicons circle_plus" id="addInstructor"><i>Incluir</i></button>

                            </div>
                        </div>
                        
                        <div class="control-group">
                            <div class="controls">
                            <ul>
                                <?php 
                                 foreach ($modelTeachingData as $key => $model) {
                                     echo "<li><span id='instructorSelect' instructor='".$key."'>".$model->instructorFk->name."</span></li>";
                                 }
                                ?>
                            </ul>
                            </div>
                        </div>
                        </div>
                        
                        <?php 
                        //@done S1 - Retirar campos de Escola e Turma TD
                        //@done S1 - Adicionar campo para selecionar o instrutor TD
                        //@done S1 - Mudar o TeachingData para a view do ClassRoom e o seu controler tbm.
                        //@done S1 - Criar lista de professores TD
                        //@todo S1 - Quando associar uma disciplina a um prof numa determinada turma essa disciplina não pode ser escolhida novamente naquela turma(desabilitar disciplina) TD
                        //@todo S1 - Edição de teaching data - excluir o professor TD
                        
                        
                        foreach ($modelTeachingData as $key => $model) {
                            
                            ?>
                           <div id="teachingData[<?php echo $key ?>]">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'instructor_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->textField($model, 'instructor_fk');
                                    echo $model->instructorFk->name;
                                    ?> 
                                    <?php echo $form->error($model, 'instructor_fk'); ?>
                                </div>
                            </div>
                            </div>
                        <?php 
                        
                        } 
                        ?>
                        
<?php /*             
                         <div class="checkbox">
                            <?php echo "Without Teacher?"; ?> 
                            <?php echo CHtml::checkBox('without_teacher'); ?>
                            </div>
                         <div class="control-group">
                            <?php echo $form->labelEx($modelTeachingData, 'instructor_fk', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($modelTeachingData,'instructor_fk', CHtml::listData(InstructorIdentification::model()->findAll(), 'id', 'name'),array('prompt'=>'Selecione o Professor'));
                            ?>
                            <?php echo $form->error($modelTeachingData,'instructor_fk'); ?>
                        </div>
                    </div>
                        
                        <div class="control-group">
                            <?php echo $form->labelEx($modelTeachingData, 'role', array('class' => 'control-label')); ?><div class="controls">
                            <?php echo $form->DropDownlist($modelTeachingData, 'role', array(1 => 'Docente', 2 => 'Auxiliar/Assistente Educacional',
                                3 => 'Profissional/Monitor de Atividade Complementar',
                                4 => 'Tradutor Intérprete de LIBRAS'));
                            ?>                    
                            <?php echo $form->error($modelTeachingData, 'role'); ?>
                        </div></div>

                        <div class="control-group">
                            <?php echo $form->labelEx($modelTeachingData, 'contract_type', array('class' => 'control-label')); ?><div class="controls">        
                            <?php
                            echo $form->DropDownlist($modelTeachingData, 'contract_type', array(1 => 'Concursado/efetivo/estável', 2 => 'Contrato temporário',
                                3 => 'Contrato terceirizado',
                                4 => 'Contrato CLT'));
                            ?>  
                            <?php echo $form->error($modelTeachingData, 'contract_type'); ?>
                        </div></div>
                        <div class="control-group">
                        <?php echo $form->labelEx($modelTeachingData, 'Disciplinas', array('class' => 'control-label')); ?>
                        </div>
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_chemistry']; ?>
                               <?php  echo $form->checkBox($modelClassroom, 'discipline_chemistry', array('value' => 1, 'uncheckValue' => 0)); ?>
                               <?php  echo $form->error($modelClassroom, 'discipline_chemistry'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_physics']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_physics', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_physics'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_mathematics']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_mathematics', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_mathematics'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_biology']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_biology', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_biology'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_science']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_science', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_science'); ?>
                            </div>
                                
                            <div class="checkbox">
                                <?php  echo Classroom::model()->attributeLabels()['discipline_language_portuguese_literature']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_language_portuguese_literature', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_language_portuguese_literature'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_foreign_language_english']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_foreign_language_english', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_foreign_language_english'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_foreign_language_spanish']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_foreign_language_spanish', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_foreign_language_spanish'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_foreign_language_franch']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_foreign_language_franch', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_foreign_language_franch'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_foreign_language_other']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_foreign_language_other', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_foreign_language_other'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_arts']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_arts', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_arts'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_physical_education']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_physical_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_physical_education'); ?>
                            </div>
                        
                        </div>
                        <div class="span6">
                        <div class="separator"></div>
                        <div class="separator"></div> 
                        <div class="control-group">
                   
                        <div>
                             
                             <?php 
                            //@todo S1 - 10 - Melhorar a forma com é feita esta seleção pode ser feita através de uma tabela, lembrando que eles vão precisar fazer isso para varias turmas no inicio do ano. 
                            $disciplinesEnum = array(null => 'Selecione a oferta da disciplina', "0" => "Não oferece disciplina", "1" => "Sim, oferece disciplina com docente vinculado", "2" => "Sim, oferece disciplina sem docente vinculado"); ?>
                           
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_history']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_history', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_history'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_geography']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_geography', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_geography'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_philosophy']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_philosophy', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_philosophy'); ?>
                            </div>
                                
                            <div class="checkbox">
                                <?php  echo Classroom::model()->attributeLabels()['discipline_social_study']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_social_study', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_social_study'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_sociology']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_sociology', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_sociology'); ?>
                            </div>
                                
                            <div class="checkbox">
                             <?php  echo Classroom::model()->attributeLabels()['discipline_informatics']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_informatics', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_informatics'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_professional_disciplines']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_professional_disciplines', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_professional_disciplines'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_special_education_and_inclusive_practices']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_special_education_and_inclusive_practices', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_special_education_and_inclusive_practices'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_sociocultural_diversity']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_sociocultural_diversity', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_sociocultural_diversity'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_libras']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_libras'); ?>
                            </div>
                                
                            <div class="checkbox">
                              <?php  echo Classroom::model()->attributeLabels()['discipline_pedagogical']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_pedagogical', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_pedagogical'); ?>
                            </div>
                                
                            <div class="checkbox">
                             <?php  echo Classroom::model()->attributeLabels()['discipline_religious']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_religious', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_religious'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_native_language']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_native_language', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_native_language'); ?>
                            </div>
                                
                            <div class="checkbox">
                               <?php  echo Classroom::model()->attributeLabels()['discipline_others']; ?>
                                    <?php echo $form->checkBox($modelClassroom, 'discipline_others', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelClassroom, 'discipline_others'); ?>
                            </div>
                        </div>
                        
                       <div class="control-group">
                        <div class="controls">
                            <?php echo $form->hiddenField($modelTeachingData,'instructor_fk',array('value'=>$instructor_id)); ?>
                            <?php echo $form->error($modelTeachingData,'instructor_fk'); ?>
                        </div>
                    </div>

                    </div>
                </div>    
               

                <div class="formField buttonWizardBar">
                    <?php //echo CHtml::submitButton($modelTeachingData->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); 
                    echo CHtml::button('Relate',array('id'=>'bt_relate'));
                    ?>
                
                </div>
                */?>
                <?php $this->endWidget(); ?>
            </div>
                
            </div>
                <div class="tab-pane" id="relations-classroom-teaching">
                    <div class="row-fluid">
                        <div class=" span6">
                        <div class="separator"></div>
                        <div class="separator"></div>
                        <div id="relations">
                            
                        </div>
                        
                        
                        </div>
                        </div>
                    </div>
        </div>
    </div>
</div>
    
<script type="text/javascript">
    
    var form = '#Classroom_';
    jQuery(function($) {
        jQuery.ajax({
            'type':'POST',
            'url':'/tag/index.php?r=classroom/getassistancetype',
            'cache':false,
            'data':jQuery('#Classroom_school_inep_fk').parents("form").serialize(),
            'success':function(html){
                jQuery("#Classroom_assistance_type").html(html); 
                jQuery("#Classroom_assistance_type").trigger('change');
            }});
        $(form+"complementary_activity_type_1").val(jQuery.parseJSON('<?php echo json_encode($complementaryActivities); ?>'));
    }); 
    
    $(form+"complementary_activity_type_1").change(function(){
        while($(this).val().length > 6){
            $(form+"complementary_activity_type_1").val($(form+"complementary_activity_type_1").val().slice(0,-1));
        }
    });
    
    
    //multiselect
    var compAct = [];
    $(form+"complementary_activity_type_1").mousedown(function(){
        compAct = $(this).val();
    });
    
    $(form+"complementary_activity_type_1").mouseup(function(e){
        if (!e.shiftKey){
            value = $(this).val()[0];
            
            remove = 0;
            compAct = jQuery.grep(compAct, function( a ) {
                if(a === value) remove++;
                return a !== value;
            });
            
            if(remove == 0) compAct.push(value);
            $(this).val(compAct);
        }
    });
    //multiselect
  
    $(form+'name').focusout(function() {
        var id = '#'+$(this).attr("id");
        
        $(id).val($(id).val().toUpperCase());
        
        if(!validateClassroomName($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo Nome não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    
    $(form+'initial_time').mask("99:99");
    $(form+'initial_time').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        var hour = form+'initial_hour';
        var minute = form+'initial_minute';
        
        if(!validateTime($(id).val())) {
            $(id).attr('value','');
            $(hour).attr('value','');
            $(minute).attr('value','');
            addError(id, "Campo Hora Inicial não está dentro das regras.");
        }
        else {
            var time = $(id).val().split(":");
            time[1] = Math.floor(time[1]/5) * 5;
            $(hour).attr('value',time[0]=='0'?'00':time[0]);
            $(minute).attr('value',time[1]=='0'?'00':time[1]);
            removeError(id);
        }
    });
    
    $(form+'final_time').mask("99:99");
    $(form+'final_time').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        var hour = form+'final_hour';
        var minute = form+'final_minute';
        
        if(!validateTime($(id).val()) || $(form+'final_time').val() <= $(form+'initial_time').val()) {
            $(id).attr('value','');
            $(hour).attr('value','');
            $(minute).attr('value','');
            addError(id, "Campo Hora Final não está dentro das regras.");
        }
        else {
            var time = $(id).val().split(":"); 
            time[1] = Math.floor(time[1]/5) * 5;
            $(hour).attr('value',time[0]=='0'?'00':time[0]);
            $(minute).attr('value',time[1]=='0'?'00':time[1]);
            removeError(id);
        }
    });
    
    
    $(form+'week_days input[type=checkbox]').change(function(){
        var id = '#'+$(form+'week_days').attr("id");
        if($('#Classroom_week_days input[type=checkbox]:checked').length == 0){
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    $(form+'week_days').focusout(function(){
        var id = '#'+$(this).attr("id");
        if($('#Classroom_week_days input[type=checkbox]:checked').length == 0){
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $('.next').click(function(){
        $('li[class="active"]').removeClass("active");
        var id = '#tab-'+($(this).attr("href")).substring(1);
        $(id).addClass("active");
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });
    
    var form_teaching = '#InstructorTeachingData_';
    
    $('#without_teacher').on('change', function(){
        window.alert($('#without_teacher').is(':checked'));
        
        if( $('#without_teacher').is(':checked') ){
            $(form_teaching+'id').add().attr('disabled','disabled');
            $(form_teaching+'role').add().attr('disabled','disabled');
            $(form_teaching+'contract_type').add().attr('disabled','disabled');
        }else{
            $(form_teaching+'id').removeAttr('disabled');
            $(form_teaching+'role').removeAttr('disabled');
            $(form_teaching+'contract_type').removeAttr('disabled');
        }
            
       
        

                                    
    });
    
       // Classe Teachind_Data
       
     function teaching_data() {
        var id;
        var instructor_fk;
        var classroom_id_fk;
        var discipline_1_fk;
        var discipline_2_fk;
        var discipline_3_fk;
        var discipline_4_fk;
        var discipline_5_fk;
        var discipline_6_fk;
        var discipline_7_fk;
        var discipline_8_fk;
        var discipline_9_fk;
        var discipline_10_fk;
        var discipline_11_fk;
        var discipline_12_fk;
        var discipline_13_fk;
        var role;
        var contract_type;
        
        this.setFields = function(id_val, instructor_fk_val, classroom_id_fk_val,
        role_val, contract_type_val,
         discipline_1_fk_val, discipline_2_fk_val, discipline_3_fk_val,
         discipline_4_fk_val, discipline_5_fk_val, discipline_6_fk_val,
         discipline_7_fk_val, discipline_8_fk_val, discipline_9_fk_val,
         discipline_10_fk_val, discipline_11_fk_val,   discipline_12_fk_val,
         discipline_13_fk_val){
             
         id = id_val;
         instructor_fk = instructor_fk_val;
         classroom_id_fk = classroom_id_fk_val;
         role = role_val;
         contract_type =contract_type_val;
         discipline_1_fk = discipline_1_fk_val;
         discipline_2_fk = discipline_2_fk_val;
         discipline_3_fk = discipline_3_fk_val;
         discipline_4_fk = discipline_4_fk_val;
         discipline_5_fk = discipline_5_fk_val;
         discipline_6_fk = discipline_6_fk_val;
         discipline_7_fk = discipline_7_fk_val;
         discipline_8_fk = discipline_8_fk_val;
         discipline_9_fk = discipline_9_fk_val;
         discipline_10_fk = discipline_10_fk_val;
         discipline_11_fk = discipline_11_fk_val;
         discipline_12_fk = discipline_12_fk_val;
         discipline_13_fk = discipline_13_fk_val;    
        }
        this.isvalidate = function(){
        
            if(isset(instructor_fk) && instructor_fk != '' 
               && isset(role) && role == 1){
                //Se existe professor é obrigatório a existência de pelo menos 1 disciplina
                if(isset(discipline_1_fk)  || isset(discipline_2_fk)|| isset(discipline_3_fk)||
                    isset(discipline_4_fk)|| isset(discipline_5_fk)|| isset(discipline_6_fk)|| isset(discipline_7_fk)||
                    isset(discipline_8_fk)|| isset(discipline_9_fk)|| isset(discipline_10_fk)|| isset(discipline_11_fk)||
                    isset(discipline_12_fk)|| isset(discipline_13_fk)){
                       return true;
                }else{
                    return "Selecione Pelo Menos Uma Matéria";
                }      
            }else{
                return "Professor ou Função que Exerce NÃO selecionado(s)";
            }
            
            
        }
        
       }
       
       //=======================
    
        
        var teachingDatas= [];
        $('#bt_relate').click(function(){
            
        var id = 0;
        var instructor_fk = $(form_teaching+'instructor_fk').val();
        var classroom_id_fk = $(form_teaching+'classroom_id_fk').val();
        var discipline_1_fk= $(form_teaching+'discipline_1_fk').val();
        var discipline_2_fk= $(form_teaching+'discipline_2_fk').val();
        var discipline_3_fk= $(form_teaching+'discipline_3_fk').val();
        var discipline_4_fk= $(form_teaching+'discipline_4_fk').val();
        var discipline_5_fk= $(form_teaching+'discipline_5_fk').val();
        var discipline_6_fk= $(form_teaching+'discipline_6_fk').val();
        var discipline_7_fk= $(form_teaching+'discipline_7_fk').val();
        var discipline_8_fk= $(form_teaching+'discipline_8_fk').val();
        var discipline_9_fk= $(form_teaching+'discipline_9_fk').val();
        var discipline_10_fk= $(form_teaching+'discipline_10_fk').val();
        var discipline_11_fk= $(form_teaching+'discipline_11_fk').val();
        var discipline_12_fk= $(form_teaching+'discipline_12_fk').val();
        var discipline_13_fk= $(form_teaching+'discipline_13_fk').val();
        var role= $(form_teaching+'role').val();
        var contract_type= $(form_teaching+'contract_type').val();
        var newTeachingData = new teaching_data();
        newTeachingData.setFields(id, instructor_fk, classroom_id_fk,
        role, contract_type,
         discipline_1_fk, discipline_2_fk, discipline_3_fk,
         discipline_4_fk, discipline_5_fk, discipline_6_fk,
         discipline_7_fk, discipline_8_fk, discipline_9_fk,
         discipline_10_fk, discipline_11_fk,   discipline_12_fk,
         discipline_13_fk);
         
            window.alert(newTeachingData.isvalidate());
            
            
            $('#relations').append('Novo Professor<br>');
                        var idTeacher = $(form_teaching+'id').val(); 
                        
                        
            
        });
        
    
</script>
