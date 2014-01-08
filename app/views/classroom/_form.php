<?php 
//@todo 21 - A turma precisa de um periodo letivo senão ela fica atemporal.
//@todo 23 - Lembra de associar o professor a turma.
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'classroom-form',
	'enableAjaxValidation'=>false,
)); 
?>
    
<div class="heading-buttons">
    <?php echo $form->errorSummary($model); ?>
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
                <li id="tab-disciplines"><a class="glyphicons edit" href="#disciplines" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Disciplines') ?></a></li>
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
                                    echo $form->hiddenField($model,'school_inep_fk',array('value'=>Yii::app()->user->school));
                                    ?>  
                                </div>
                            </div>
                            <div class="control-group">
                                <?php 
                                //@todo 09 - O Campo nome deve possuir uma mascara e seguir um padrão a ser definido.
                                echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 80)); ?>
                                    <?php echo $form->error($model, 'name'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'initial_hour', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->hiddenField($model, 'initial_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo $form->hiddenField($model, 'initial_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo CHtml::textField('Classroom_initial_time', $model->initial_hour . ':' . $model->initial_minute, array('size' => 5, 'maxlength' => 5)); ?>
                                    <?php echo $form->error($model, 'initial_hour'); ?>
                                    <?php echo $form->error($model, 'initial_minute'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'final_hour', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->hiddenField($model, 'final_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo $form->hiddenField($model, 'final_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo CHtml::textField('Classroom_final_time', $model->final_hour . ':' . $model->final_minute, array('size' => 5, 'maxlength' => 5)); ?>
                                    <?php echo $form->error($model, 'final_hour'); ?>
                                    <?php echo $form->error($model, 'final_minute'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">
                            <label class="control-label"><?php echo Yii::t('default', 'Week Days'); ?></label>
                            <div class="uniformjs margin-left" id="Classroom_week_days">
                                <label class="checkbox">
                                    <?php 
                                    //@done s1-08-08 - Os Valores deste campo são definidos de forma global e pode vim preenchidos default
                                    echo Classroom::model()->attributeLabels()['week_days_sunday'];
                                    echo $form->checkBox($model, 'week_days_sunday', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_monday']; ?>
                                    <?php echo $form->checkBox($model, 'week_days_monday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_tuesday']; ?>
                                    <?php echo $form->checkBox($model, 'week_days_tuesday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_wednesday']; ?>
                                    <?php echo $form->checkBox($model, 'week_days_wednesday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_thursday']; ?>
                                    <?php echo $form->checkBox($model, 'week_days_thursday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_friday']; ?>
                                    <?php echo $form->checkBox($model, 'week_days_friday', array("checked"=>"checked",'value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['week_days_saturday']; ?>
                                    <?php echo $form->checkBox($model, 'week_days_saturday', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'assistance_type', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($model, 'assistance_type', array(null => 'Selecione o tipo de assistencia'), array('ajax' => array(
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
                                    <?php echo $form->error($model, 'assistance_type'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'mais_educacao_participator', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'mais_educacao_participator'); ?>
                                    <?php echo $form->error($model, 'mais_educacao_participator'); ?>
                                </div>
                            </div>
                                
                            <!-- dar uma olhada no http://mind2soft.com/labs/jquery/multiselect/ -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'complementary_activity_type_1', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'complementary_activity_type_1', CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'), array('multiple' => true, 'key' => 'id')); ?>
                                    <?php echo $form->error($model, 'complementary_activity_type_1'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">
                            <label class="control-label"><?php echo Yii::t('default', 'Aee'); ?></label>
                            <div class="uniformjs margin-left">
                                <label class="checkbox">
                                    <?php 
                                    echo Classroom::model()->attributeLabels()['aee_braille_system_education'];
                                    echo $form->checkBox($model, 'aee_braille_system_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_optical_and_non_optical_resources']; ?>
                                    <?php echo $form->checkBox($model, 'aee_optical_and_non_optical_resources', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_mental_processes_development_strategies']; ?>
                                    <?php echo $form->checkBox($model, 'aee_mental_processes_development_strategies', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_mobility_and_orientation_techniques']; ?>
                                    <?php echo $form->checkBox($model, 'aee_mobility_and_orientation_techniques', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_libras']; ?>
                                    <?php echo $form->checkBox($model, 'aee_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_caa_use_education']; ?>
                                    <?php echo $form->checkBox($model, 'aee_caa_use_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_curriculum_enrichment_strategy']; ?>
                                    <?php echo $form->checkBox($model, 'aee_curriculum_enrichment_strategy', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_soroban_use_education']; ?>
                                    <?php echo $form->checkBox($model, 'aee_soroban_use_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_usability_and_functionality_of_computer_accessible_education']; ?>
                                    <?php echo $form->checkBox($model, 'aee_usability_and_functionality_of_computer_accessible_education', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_teaching_of_Portuguese_language_written_modality']; ?>
                                    <?php echo $form->checkBox($model, 'aee_teaching_of_Portuguese_language_written_modality', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                <label class="checkbox">
                                    <?php echo Classroom::model()->attributeLabels()['aee_strategy_for_school_environment_autonomy']; ?>
                                    <?php echo $form->checkBox($model, 'aee_strategy_for_school_environment_autonomy', array('value' => 1, 'uncheckValue' => 0)); ?>
                                </label>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'modality', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'modality', array(null => 'Selecione a modalidade',
                                        '1' => 'Ensino Regular',
                                        '2' => 'Educação Especial - Modalidade Substitutiva',
                                        '3' => 'Educação de Jovens e Adultos (EJA)')); ?>
                                    <?php echo $form->error($model, 'modality'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => '(Select Stage vs Modality)')); ?>
                                    <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'edcenso_professional_education_course_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'edcenso_professional_education_course_fk', CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o curso',)); ?>
                                    <?php echo $form->error($model, 'edcenso_professional_education_course_fk'); ?>
                                </div>
                            </div>
                                
                            <?php $instructorSituationEnum = array(null => 'Selecione a situação', "0" => "Turma com docente", "1" => "Turma sem docente"); ?>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'instructor_situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'instructor_situation', $instructorSituationEnum); ?>
                                    <?php echo $form->error($model, 'instructor_situation'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group buttonWizardBar nextBar">
                        <a href="#disciplines" data-toggle="tab" class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('deafult','Next') ?><i></i></a>
                    </div>
                </div>
                <div class="tab-pane" id="disciplines">
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="separator"></div>
                            <?php 
                            //@todo 10 - Melhorar a forma com é feita esta seleção pode ser feita através de uma tabela, lembrando que eles vão precisar fazer isso para varias turmas no inicio do ano. 
                            $disciplinesEnum = array(null => 'Selecione a oferta da disciplina', "0" => "Não oferece disciplina", "1" => "Sim, oferece disciplina com docente vinculado", "2" => "Sim, oferece disciplina sem docente vinculado"); ?>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_chemistry', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_chemistry', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_chemistry'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_physics', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_physics', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_physics'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_mathematics', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_mathematics', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_mathematics'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_biology', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_biology', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_biology'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_science', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_science', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_science'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_language_portuguese_literature', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_language_portuguese_literature', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_language_portuguese_literature'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_foreign_language_english', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_foreign_language_english', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_foreign_language_english'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_foreign_language_spanish', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_foreign_language_spanish', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_foreign_language_spanish'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_foreign_language_franch', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_foreign_language_franch', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_foreign_language_franch'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_foreign_language_other', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_foreign_language_other', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_foreign_language_other'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_arts', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_arts', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_arts'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_physical_education', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_physical_education', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_physical_education'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_history', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_history', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_history'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_geography', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_geography', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_geography'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_philosophy', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_philosophy', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_philosophy'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_social_study', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_social_study', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_social_study'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_sociology', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_sociology', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_sociology'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_informatics', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_informatics', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_informatics'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_professional_disciplines', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_professional_disciplines', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_professional_disciplines'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_special_education_and_inclusive_practices', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_special_education_and_inclusive_practices', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_special_education_and_inclusive_practices'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_sociocultural_diversity', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_sociocultural_diversity', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_sociocultural_diversity'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_libras', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_libras', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_libras'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_pedagogical', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_pedagogical', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_pedagogical'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_religious', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_religious', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_religious'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_native_language', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_native_language', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_native_language'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'discipline_others', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'discipline_others', $disciplinesEnum); ?>
                                    <?php echo $form->error($model, 'discipline_others'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group buttonWizardBar nextBar">
                        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon next btn-primary')); ?>
                    </div>
                    <?php $this->endWidget(); ?>
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
        $(form+"complementary_activity_type_1").val(jQuery.parseJSON('<?php echo json_encode($complementary_activities); ?>'));
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
</script>