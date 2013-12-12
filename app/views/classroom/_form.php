 <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'classroom-form',
	'enableAjaxValidation'=>false,
)); 


?>
        <div class="panelGroup form">
            <?php echo $form->errorSummary($model); ?>
            <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
</div></div>
            <div class="panelGroupBody">
                <div class="panelGroupAbout">
                     <?php echo Yii::t('default', 'Fields with * are required.')?></div>

                                   

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'school_inep_fk'); ?>
                        <?php echo $form->dropDownList($model, 'school_inep_fk', 
                                    CHtml::listData(SchoolIdentification::model()->findAll(), 'inep_id', 'name'),
                                        array(
                                            'prompt'=>'(Select School)',
                                            'ajax' => array(
                                            'type' => 'POST', 
                                            'url' => CController::createUrl('classroom/getassistancetype'),
                                            'update' => '#Classroom_assistance_type',
                                            'success' => 'function(html){
                                                $("#Classroom_assistance_type").html(html); 
                                                $("#Classroom_assistance_type").trigger("change");                                                
                                            }'
                                            
                                            ))); ?>  
                           <?php echo $form->error($model,'school_inep_fk'); ?>
                    </div>
<!--
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'inep_id'); ?>
                        <?php echo $form->textField($model,'inep_id',array('size'=>10,'maxlength'=>10)); ?>
                        <?php echo $form->error($model,'inep_id'); ?>
                    </div>
-->

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'name'); ?>
                        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>80)); ?>
                        <?php echo $form->error($model,'name'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'initial_hour'); ?>
                        <?php echo $form->hiddenField($model,'initial_hour',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->hiddenField($model,'initial_minute',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo CHtml::textField('Classroom_initial_time',$model->initial_hour.':'.$model->initial_minute,array('size'=>5,'maxlength'=>5)); ?>
                        <?php echo $form->error($model,'initial_hour'); ?>
                        <?php echo $form->error($model,'initial_minute'); ?>
                    </div>
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'final_hour'); ?>
                        <?php echo $form->hiddenField($model,'final_hour',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->hiddenField($model,'final_minute',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo CHtml::textField('Classroom_final_time',$model->final_hour.':'.$model->final_minute,array('size'=>5,'maxlength'=>5)); ?>
                        <?php echo $form->error($model,'final_hour'); ?>
                        <?php echo $form->error($model,'final_minute'); ?>
                    </div>
<!--
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'initial_hour'); ?>
                        <?php echo $form->textField($model,'initial_hour',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($model,'initial_hour'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'initial_minute'); ?>
                        <?php echo $form->textField($model,'initial_minute',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($model,'initial_minute'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'final_hour'); ?>
                        <?php echo $form->textField($model,'final_hour',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($model,'final_hour'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'final_minute'); ?>
                        <?php echo $form->textField($model,'final_minute',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($model,'final_minute'); ?>
                    </div>
-->

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_sunday'); ?>
                        <?php echo $form->checkBox($model,'week_days_sunday'); ?>
                        <?php echo $form->error($model,'week_days_sunday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_monday'); ?>
                        <?php echo $form->checkBox($model,'week_days_monday'); ?>
                        <?php echo $form->error($model,'week_days_monday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_tuesday'); ?>
                        <?php echo $form->checkBox($model,'week_days_tuesday'); ?>
                        <?php echo $form->error($model,'week_days_tuesday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_wednesday'); ?>
                        <?php echo $form->checkBox($model,'week_days_wednesday'); ?>
                        <?php echo $form->error($model,'week_days_wednesday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_thursday'); ?>
                        <?php echo $form->checkBox($model,'week_days_thursday'); ?>
                        <?php echo $form->error($model,'week_days_thursday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_friday'); ?>
                        <?php echo $form->checkBox($model,'week_days_friday'); ?>
                        <?php echo $form->error($model,'week_days_friday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'week_days_saturday'); ?>
                        <?php echo $form->checkBox($model,'week_days_saturday'); ?>
                        <?php echo $form->error($model,'week_days_saturday'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'assistance_type'); ?>
                        <?php echo $form->DropDownList($model,'assistance_type',
                                array('null' => '(Select Assistance Type)'),
                                array('ajax' => array(
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
                                ))); ?> 
                        <?php echo $form->error($model,'assistance_type'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'mais_educacao_participator'); ?>
                        <?php echo $form->checkBox($model,'mais_educacao_participator'); ?>
                        <?php echo $form->error($model,'mais_educacao_participator'); ?>
                    </div>

<!-- dar uma olhada no http://mind2soft.com/labs/jquery/multiselect/ -->
                                       <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_1'); ?>
                        <?php echo $form->dropDownList($model, 'complementary_activity_type_1', 
                                CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'),
                                array('multiple'=>true,'key'=>'id')
                            );?>
                        <?php echo $form->error($model,'complementary_activity_type_1'); ?>
                    </div>

<?php /*
<!--                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_1'); ?>
                        <?php echo $form->DropDownList($model,'complementary_activity_type_1', 
                                CHtml::listData(EdcensoComplementaryActivityType::model()->findAll(), 'id', 'name'),
                                array('prompt'=>'(Select Complementary Activity)',
                                    'ajax' => array(
                                    'type' => 'POST', 
                                    'url' => CController::createUrl('classroom/updatecomplementaryactivity'), 
                                    'update' => "#Classroom_complementary_activity_type_2",
                                ))); ?>
                        <?php echo $form->error($model,'complementary_activity_type_1'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_2'); ?>
                        <?php echo $form->DropDownList($model,'complementary_activity_type_2',
                                array(),
                                array('prompt'=>'(Select Complementary Activity)',
                                    'ajax' => array(
                                    'type' => 'POST', 
                                    'url' => CController::createUrl('classroom/updatecomplementaryactivity'), 
                                    'update' => "#Classroom_complementary_activity_type_3",
                                ))); ?>
                        <?php echo $form->error($model,'complementary_activity_type_2'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_3'); ?>
                        <?php echo $form->DropDownList($model,'complementary_activity_type_3',
                                array(),
                                array('prompt'=>'(Select Complementary Activity)',
                                    'ajax' => array(
                                    'type' => 'POST', 
                                    'url' => CController::createUrl('classroom/updatecomplementaryactivity'), 
                                    'update' => "#Classroom_complementary_activity_type_4",
                                ))); ?>
                        <?php echo $form->error($model,'complementary_activity_type_3'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_4'); ?>
                        <?php echo $form->DropDownList($model,'complementary_activity_type_4',
                                array(),
                                array('prompt'=>'(Select Complementary Activity)',
                                    'ajax' => array(
                                    'type' => 'POST', 
                                    'url' => CController::createUrl('classroom/updatecomplementaryactivity'), 
                                    'update' => "#Classroom_complementary_activity_type_5",
                                ))); ?>
                        <?php echo $form->error($model,'complementary_activity_type_4'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_5'); ?>
                        <?php echo $form->DropDownList($model,'complementary_activity_type_5',
                                array(),
                                array('prompt'=>'(Select Complementary Activity)',
                                    'ajax' => array(
                                    'type' => 'POST', 
                                    'url' => CController::createUrl('classroom/updatecomplementaryactivity'), 
                                    'update' => "#Classroom_complementary_activity_type_6",
                                ))); ?>
                        <?php echo $form->error($model,'complementary_activity_type_5'); ?>
                    </div>
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complementary_activity_type_6'); ?>
                        <?php echo $form->DropDownList($model,'complementary_activity_type_6',
                                array('null'=>'(Select Complementary Activity)')); ?>
                        <?php echo $form->error($model,'complementary_activity_type_6'); ?>
                    </div>

-->
 */ ?>
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_braille_system_education'); ?>
                        <?php echo $form->checkBox($model,'aee_braille_system_education', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_braille_system_education'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_optical_and_non_optical_resources'); ?>
                        <?php echo $form->checkBox($model,'aee_optical_and_non_optical_resources', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_optical_and_non_optical_resources'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_mental_processes_development_strategies'); ?>
                        <?php echo $form->checkBox($model,'aee_mental_processes_development_strategies', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_mental_processes_development_strategies'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_mobility_and_orientation_techniques'); ?>
                        <?php echo $form->checkBox($model,'aee_mobility_and_orientation_techniques', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_mobility_and_orientation_techniques'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_libras'); ?>
                        <?php echo $form->checkBox($model,'aee_libras', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_libras'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_caa_use_education'); ?>
                        <?php echo $form->checkBox($model,'aee_caa_use_education', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_caa_use_education'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_curriculum_enrichment_strategy'); ?>
                        <?php echo $form->checkBox($model,'aee_curriculum_enrichment_strategy', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_curriculum_enrichment_strategy'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_soroban_use_education'); ?>
                        <?php echo $form->checkBox($model,'aee_soroban_use_education', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_soroban_use_education'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_usability_and_functionality_of_computer_accessible_education'); ?>
                        <?php echo $form->checkBox($model,'aee_usability_and_functionality_of_computer_accessible_education', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_usability_and_functionality_of_computer_accessible_education'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_teaching_of_Portuguese_language_written_modality'); ?>
                        <?php echo $form->checkBox($model,'aee_teaching_of_Portuguese_language_written_modality', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_teaching_of_Portuguese_language_written_modality'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'aee_strategy_for_school_environment_autonomy'); ?>
                        <?php echo $form->checkBox($model,'aee_strategy_for_school_environment_autonomy', array('disabled'=>'disabled')); ?>
                        <?php echo $form->error($model,'aee_strategy_for_school_environment_autonomy'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'modality'); ?>
                        <?php echo $form->DropDownList($model,'modality',
                                array('null' => '(Select Modality)',
                                    '1' => '(Ensino Regular)',
                                    '2' => '(Educação Especial - Modalidade Substitutiva)',
                                    '3' => '(Educação de Jovens e Adultos (EJA))')); ?>
                        <?php echo $form->error($model,'modality'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_stage_vs_modality_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_stage_vs_modality_fk',
                                CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order'=>'name')), 'id', 'name'),
                                array('prompt' => '(Select Stage vs Modality)'));?>
                        <?php echo $form->error($model,'edcenso_stage_vs_modality_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_professional_education_course_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_professional_education_course_fk', 
                                CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order'=>'name')), 'id', 'name'),
                                        array('prompt'=>'(Select Course)',)); ?>
                        <?php echo $form->error($model,'edcenso_professional_education_course_fk'); ?>
                    </div>

                    <?php $disciplinesEnum = array("0" => "Não oferece disciplina", "1" => "Sim, oferece disciplina com docente vinculado", "2" => "Sim, oferece disciplina sem docente vinculado"); ?>
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_chemistry'); ?>
                        <?php echo $form->DropDownList($model,'discipline_chemistry', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_chemistry'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_physics'); ?>
                        <?php echo $form->DropDownList($model,'discipline_physics', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_physics'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_mathematics'); ?>
                        <?php echo $form->DropDownList($model,'discipline_mathematics', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_mathematics'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_biology'); ?>
                        <?php echo $form->DropDownList($model,'discipline_biology', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_biology'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_science'); ?>
                        <?php echo $form->DropDownList($model,'discipline_science', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_science'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_language_portuguese_literature'); ?>
                        <?php echo $form->DropDownList($model,'discipline_language_portuguese_literature', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_language_portuguese_literature'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_foreign_language_english'); ?>
                        <?php echo $form->DropDownList($model,'discipline_foreign_language_english', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_foreign_language_english'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_foreign_language_spanish'); ?>
                        <?php echo $form->DropDownList($model,'discipline_foreign_language_spanish', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_foreign_language_spanish'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_foreign_language_franch'); ?>
                        <?php echo $form->DropDownList($model,'discipline_foreign_language_franch', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_foreign_language_franch'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_foreign_language_other'); ?>
                        <?php echo $form->DropDownList($model,'discipline_foreign_language_other', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_foreign_language_other'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_arts'); ?>
                        <?php echo $form->DropDownList($model,'discipline_arts', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_arts'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_physical_education'); ?>
                        <?php echo $form->DropDownList($model,'discipline_physical_education', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_physical_education'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_history'); ?>
                        <?php echo $form->DropDownList($model,'discipline_history', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_history'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_geography'); ?>
                        <?php echo $form->DropDownList($model,'discipline_geography', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_geography'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_philosophy'); ?>
                        <?php echo $form->DropDownList($model,'discipline_philosophy', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_philosophy'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_social_study'); ?>
                        <?php echo $form->DropDownList($model,'discipline_social_study', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_social_study'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_sociology'); ?>
                        <?php echo $form->DropDownList($model,'discipline_sociology', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_sociology'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_informatics'); ?>
                        <?php echo $form->DropDownList($model,'discipline_informatics', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_informatics'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_professional_disciplines'); ?>
                        <?php echo $form->DropDownList($model,'discipline_professional_disciplines', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_professional_disciplines'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_special_education_and_inclusive_practices'); ?>
                        <?php echo $form->DropDownList($model,'discipline_special_education_and_inclusive_practices', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_special_education_and_inclusive_practices'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_sociocultural_diversity'); ?>
                        <?php echo $form->DropDownList($model,'discipline_sociocultural_diversity', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_sociocultural_diversity'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_libras'); ?>
                        <?php echo $form->DropDownList($model,'discipline_libras', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_libras'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_pedagogical'); ?>
                        <?php echo $form->DropDownList($model,'discipline_pedagogical', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_pedagogical'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_religious'); ?>
                        <?php echo $form->DropDownList($model,'discipline_religious', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_religious'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_native_language'); ?>
                        <?php echo $form->DropDownList($model,'discipline_native_language', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_native_language'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'discipline_others'); ?>
                        <?php echo $form->DropDownList($model,'discipline_others', $disciplinesEnum); ?>
                        <?php echo $form->error($model,'discipline_others'); ?>
                    </div>

                        <?php $instructorSituationEnum = array("0" => "Turma com docente", "1" => "Turma sem docente"); ?>
                                        <div class="formField">
                        <?php echo $form->labelEx($model,'instructor_situation'); ?>
                        <?php echo $form->DropDownList($model,'instructor_situation',$instructorSituationEnum); ?>
                        <?php echo $form->error($model,'instructor_situation'); ?>
                    </div>

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>


    <script type="text/javascript">
        
        var form = '#Classroom_';
        jQuery(function($) {
            //consertar isso urgentemente!
            //não pode ficar assim!! u.u
            //mude essa desgraça!
            jQuery('body').on('change','#Classroom_school_inep_fk',
                function(){jQuery.ajax({
                        'type':'POST',
                        'url':'/tag/index.php?r=classroom/getassistancetype',
                        'cache':false,
                        'data':jQuery(this).parents("form").serialize(),
                        'success':function(html){
                            jQuery("#Classroom_assistance_type").html(html); 
                            jQuery("#Classroom_assistance_type").trigger('change');
                        }});
                    return false;}
            );
            $(form+'school_inep_fk').trigger('change');
            
            $(form+"complementary_activity_type_1").val(jQuery.parseJSON('<?php echo json_encode($complementary_activities); ?>'));
            
            
        }); 
        
        
        $(form+"complementary_activity_type_1").change(function(){
            while($(this).val().length > 6){
                $(form+"complementary_activity_type_1").val($(form+"complementary_activity_type_1").val().slice(0,-1));
            }
        });
        
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
        
        $(form+"complementary_activity_type_1").keypress(function(e) {
            console.log();
        });


        

        
        $(form+'name').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateClassroomName($(this).val())) 
                $(this).attr('value','');
        });
        
        
        $(form+'initial_time').mask("99:99");
        $(form+'initial_time').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            var hour = form+'initial_hour';
            var minute = form+'initial_minute';
            
            if(!validateTime($(this).val())) {
                $(this).attr('value','');
                $(hour).attr('value','');
                $(minute).attr('value','');
            }
            else {
                var time = $(this).val().split(":");
                time[1] = Math.floor(time[1]/5) * 5;
                $(hour).attr('value',time[0]=='0'?'00':time[0]);
                $(minute).attr('value',time[1]=='0'?'00':time[1]);
            }
        });
               
        $(form+'final_time').mask("99:99");
        $(form+'final_time').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            var hour = form+'final_hour';
            var minute = form+'final_minute';
            
            if(!validateTime($(this).val()) || $(form+'final_time').val() <= $(form+'initial_time').val()) {
                $(this).attr('value','');
                $(hour).attr('value','');
                $(minute).attr('value','');
            }
            else {
                var time = $(this).val().split(":"); 
                time[1] = Math.floor(time[1]/5) * 5;
                $(hour).attr('value',time[0]=='0'?'00':time[0]);
                $(minute).attr('value',time[1]=='0'?'00':time[1]);
            }
        });
    </script>