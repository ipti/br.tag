<?php 

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'classroom-form',
	'enableAjaxValidation'=>false,
)); 
?>
<?php echo $form->errorSummary($modelClassroom); ?>

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
                         <?php echo CHtml::htmlButton('<i></i>' . ($modelClassroom->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')),
                                    array('id'=>'enviar_essa_bagaca', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'style' => 'display:none', 'type' => 'button'));?>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>


<div class="innerLR">
    
    <div class="widget widget-tabs border-bottom-none">
        
        <div class="widget-head">
            <ul class="tab-classroom">
                <li id="tab-classroom" class="active" ><a class="glyphicons adress_book" href="#classroom" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Classroom') ?></a></li>
                <li id="tab-classboard"><a class="glyphicons calendar" href="#classboard" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Class Board') ?></a></li> 
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
                                    echo CHtml::hiddenField("teachingData",'',array('id'=>'teachingData'));
                                    echo CHtml::hiddenField("disciplines",'',array('id'=>'disciplines'));
                                    ?>  
                                </div>
                            </div>
                            <div class="control-group">
                                <?php 
                                //@later Sx - 09 - O Campo nome deve possuir uma mascara e seguir um padrão a ser definido.
                                echo $form->labelEx($modelClassroom, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelClassroom, 'name', array('size' => 60, 'maxlength' => 80)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Classroom Name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelClassroom, 'name'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">

                               <?php
                                echo $form->labelEx($modelClassroom, 'turn', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'turn', array(null => 'Selecione o turno',
                                        'M' => 'Manhã',
                                        'T' => 'Tarde',
                                        'N' => 'Noite',
                                        'I' => 'Integral'),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelClassroom, 'turn'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">

                               <?php //@done s1 - colocar o ano atual como valor padrão
                                echo $form->labelEx($modelClassroom, 'school_year', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelClassroom, 'school_year', array('value'=>isset($modelClassroom->school_year)? $modelClassroom->school_year :date("Y"),'size' => 5, 'maxlength' => 5)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'School year'); ?>"><i></i></span>
                                    <?php echo $form->error($modelClassroom, 'school_year'); ?>
                                </div>
                            </div>
                          
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'initial_hour', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->hiddenField($modelClassroom, 'initial_hour', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo $form->hiddenField($modelClassroom, 'initial_minute', array('size' => 2, 'maxlength' => 2)); ?>
                                    <?php echo CHtml::textField('Classroom_initial_time', $modelClassroom->initial_hour . ':' . $modelClassroom->initial_minute, array('size' => 5, 'maxlength' => 5)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Time'); ?>"><i></i></span>
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
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Time'); ?>"><i></i></span>
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
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Week days'); ?>"><i></i></span>
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
                                    echo $form->DropDownList($modelClassroom, 'assistance_type', array(null => 'Selecione o tipo de assistencia'),array('class' => 'select-search-off'),
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
                                        '3' => 'Educação de Jovens e Adultos (EJA)'),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelClassroom, 'modality'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o estágio vs modalidade','class' => 'select-search-on')); ?>
                                    <?php echo $form->error($modelClassroom, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>
                                
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'edcenso_professional_education_course_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'edcenso_professional_education_course_fk', CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order' => 'name')), 'id', 'name'), array('prompt' => 'Selecione o curso','class' => 'select-search-on')); ?>
                                    <?php echo $form->error($modelClassroom, 'edcenso_professional_education_course_fk'); ?>
                                </div>
                            </div>
                                
                            <?php $instructorSituationEnum = array(null => 'Selecione a situação', "0" => "Turma com docente", "1" => "Turma sem docente"); ?>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelClassroom, 'instructor_situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelClassroom, 'instructor_situation', $instructorSituationEnum, array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelClassroom, 'instructor_situation'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                                         
                <?php
                //@done S1 - Retirar campos de Escola e Turma TD
                
                //@done S1 - Adicionar campo para selecionar o instrutor TD
                //@done S1 - Mudar o TeachingData para a view do ClassRoom e o seu controler tbm.

                //@done S1 - Edição de teaching data - excluir o professor TD

                //@done s1 - criar estutura da tela de TeachingData no Classroom 
                
                ?>
                <div class="tab-pane" id="classboard">
                    <div class="row-fluid">
                        <div class=" span8">
                            <div class="separator"></div>
                            <div id='loading' style='display:none'>loading...</div>
                            <div id='calendar'></div>
                        </div>

                        <div class=" span4">
                            <div class="separator"></div>
                                <a href="#" class="btn btn-icon btn-primary add glyphicons circle_plus" id="newDiscipline"><i></i><?php echo Yii::t('default', 'New Discipline') ?></a>
                            
                            <div class="separator"></div>
                                    <?php
                            
                                $teachingDataList = "<ul>"
                                                    ."<li><span><b>Disciplinas com Instrutores</b></span>"
                                                    ."<ul id='DisciplinesWithInstructors'>";
                                $teachingDataArray = array();
                                $teachingDataDisciplines = array();
                                $disciplinesLabels = array();
                                $i = 0;
                                foreach ($modelTeachingData as $key => $model) {
                                    $disciplines = ClassroomController::teachingDataDiscipline2array($model);

                                    $teachingDataList .= "<li instructor='".$model->instructor_fk."'><span>" . $model->instructorFk->name ."</span>"
                                            .'<a  href="#" class="deleteTeachingData delete" title="Excluir">
                                              </a>';
                                    $teachingDataList .= "<ul>";

                                    $teachingDataArray[$i] = array();
                                    $teachingDataArray[$i]['Instructor'] = $model->instructor_fk;
                                    $teachingDataArray[$i]['Classroom'] = $model->classroom_id_fk;
                                    $teachingDataArray[$i]['Role'] = $model->role;
                                    $teachingDataArray[$i]['ContractType'] = $model->contract_type;
                                    $teachingDataArray[$i]['Disciplines'] = array();
                                    
                                    //@done s2 - corrigir problema com nomes grandes de disciplina
                                    //@done s2 - colocar o botão de excluir na parte superior
                                    foreach ($disciplines as $discipline) {
                                        $teachingDataList .= "<li discipline='".$discipline->id."'>"
                                                .'<a href="#" class="deleteTeachingData delete" title="Excluir"></a>'
                                                ."<span class='disciplines-list'>".$discipline->name
                                            .'</span>'
                                            ."</li>";
                                        array_push($teachingDataDisciplines, $discipline->id);
                                        array_push( $teachingDataArray[$i]['Disciplines'], $discipline->id);
                                    }
                                    $teachingDataList .= "</ul></li>";
                                    $i++;
                                }
                                $teachingDataList .= "</ul></li>";
                                
                                //Pega a lista de disciplinas que possuem instrutores e tira as duplicatas
                                $teachingDataDisciplines = array_unique($teachingDataDisciplines);
                                //Pega a lista de disciplinas da turma
                                $disciplinesArray = ClassroomController::classroomDiscipline2array($modelClassroom);
                                
                                //Pega a diferença entre a lista de disciplinas com instrutores e a lista de disciplinas da turma
                                $disciplinesWithoutInstructor = array_diff($disciplinesArray, $teachingDataDisciplines);
                                
                                //monta a lista com as disciplinas que não possuem instrutor                                
                                $teachingDataList .= "<li><span><b>Disciplinas sem Instrutores</b></span>"
                                        ."<ul id='DisciplinesWithoutInstructors'>";
                                $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
                                foreach ($disciplinesWithoutInstructor as $disciplineId => $value) {
                                    if($value == 2){
                                        $teachingDataList .= "<li discipline='".$disciplineId."'><span>" . $disciplinesLabels[$disciplineId] ."</span>"
                                                .'<a href="#" class="deleteTeachingData delete" title="Excluir"></a>';
                                    }
                                }
                                $teachingDataList .= "</ul></ul>";
                                
                                echo $teachingDataList; ?>     
                        </div>
                    </div>
                        
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>




    <!-- Modal -->
    <div id="create-dialog-form" title="<?php echo Yii::t('default', 'Insert class'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label( Yii::t('default','Discipline'), 'discipline', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::dropDownList('discipline', '', array(),array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="update-dialog-form" title="<?php echo Yii::t('default', 'Update class'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label( Yii::t('default','Discipline'), 'update-discipline', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::dropDownList('update-discipline', '', array() ,array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="teachingdata-dialog-form" title="<?php echo Yii::t('default', 'New Discipline'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t("default", "Instructor"), "Instructors", array('class' => 'control-label')) ?>
                    <div class="controls">
                        <?php echo CHtml::DropDownList("Instructors", '', CHtml::listData(InstructorIdentification::model()->findAll('school_inep_id_fk=:school order by name', array(':school' => Yii::app()->user->school)), 'id', 'name'), array('prompt' => 'Sem Instrutor', 'class' => 'select-search-on')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t("default", "Disciplines"), "Disciplines", array('class' => 'control-label')) ?>
                    <div class="controls">
                        <?php echo CHtml::DropDownList("Disciplines", '', ClassroomController::classroomDisciplineLabelArray(), array('multiple' => 'multiple')); ?>

                    </div>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t("default", "Role"), "Role", array('class' => 'control-label')) ?>
                    <div class="controls">
                        <?php
                        echo CHtml::DropDownList("Role", '', array(
                            null => 'Selecione um Cargo',
                            1 => 'Docente',
                            2 => 'Auxiliar',
                            3 => 'Monitor',
                            4 => 'Intérprete',
                                ), array('class' => 'select-search-off'));
                        ?>

                    </div>
                </div>
                <div class="control-group">
                        <?php echo CHtml::label(Yii::t("default", "Contract Type"), "ContractType", array('class' => 'control-label')) ?>
                    <div class="controls">
                        <?php
                        echo CHtml::DropDownList("ContractType", '', array(
                            null => 'Selecione tipo de Contrato',
                            1 => 'Concursado/Efetivo',
                            2 => 'Temporário',
                            3 => 'Terceirizado',
                            4 => 'CLT',
                                ), array('class' => 'select-search-off'));
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




<script type="text/javascript">
    
    ////////////////////////////////////////////////
    // Variables and Initialization               //
    ////////////////////////////////////////////////
    <?php //@done s1 - defini como ficaram os arrays de Disciplinas e TeachingData para salvar no banco ?>
    var teachingData = <?php echo json_encode($teachingDataArray); ?>;
    var disciplines =  <?php echo json_encode($disciplinesArray); ?>; 
    var disciplinesLabels = <?php echo json_encode($disciplinesLabels); ?>; 
                                    
    var form = '#Classroom_';
    var formClassBoard = "#ClassBoard_";
    var form_teaching = '#InstructorTeachingData_';
    <?php //@done s2 - Criar modal ao clicar na tabela ?>
    <?php //@done s2 - Corrigir problemas do submit automático ?>
    <?php //@done s2 - Corrigir problemas do Layout ?>
    var lesson = {};
    var lessons = {};
    var lesson_id = 1;
    var lesson_start = 1;
    var lesson_end = 2;
    
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    var calendar;
    
    var myTeachingDataDialog;
    var myCreateDialog;
    var myUpdateDialog;
    
    var discipline = $("#discipline");
    var uDiscipline = $("#update-discipline");
    
    var classroomId = '<?php echo $modelClassroom->id; ?>';
    
    $()
    ////////////////////////////////////////////////
    // Document Ready                             //
    ////////////////////////////////////////////////
    $(document).ready(function() {
        ////////////////////////////////////////////////
        // Ajax Initialization                        //
        ////////////////////////////////////////////////
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
   
   
        ////////////////////////////////////////////////
        // Calendar                                   //
        ////////////////////////////////////////////////
        //Cria o calendário semanal de aulas
        calendar = $('#calendar').fullCalendar({
            <?php //@done s2 - Colocar data padrão        ?>
            year: 1996, //Porque eu nasci em 1993.
            month: 0,
            date: 1,
            theme: true,
            firstDay:1,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            allDayDefault: false,
            slotEventOverlap: true,
            disableResizing: true,
            editable: true,

            <?php //@done s2 - Limitar quantidade de slots que aparecem no Quadro de Horário        ?>
            firstHour: 1,
            minTime: 1,
            maxTime: 11,
            slotMinutes: 60,
            defaultEventMinutes: 60,
            axisFormat: "H'º' 'Horário'",
            timeFormat: { agenda: "" },
            columnFormat: { week: 'dddd', },

            <?php //@done s2 - Não é necessário colocar o mês (o quadro de aulas serve pro ano inteiro)         ?>
            header: { left: '', center: '', right: '', },
            titleFormat: { week: "MMMM", },

            <?php //@done s2 - Traduzir dias da semana e meses do fullCalendar        ?>
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            selectable: true,
            selectHelper: true,

            <?php //@done s2 - Criar o evento que importa os dados do banco        ?>
            <?php //@done s2 - Atualizar para a nova estrutura do bando o evento que importa os dados do banco        ?>
            events: '<?php echo CController::createUrl('classroom/getClassBoard&classroom_fk='.$modelClassroom->id); ?>',

            //Evento ao selecionar nos blocos de horários
            //Criar uma nova aula
            <?php //@done s2 - Criar tela de dialogo para CRIAR da aula        ?>
            select: function(start, end, allDay) {
                var id = formClassBoard+'classroom_fk';
                //if($(id).val().length != 0){
                lesson_start = start;
                lesson_end = end;
                
                //atualizar lista de disciplinas
                var listOfdisciplines = '<option value="">Selecione a disciplina</option>';
                $.each(disciplines,function(i,d){
                    if (d != 0)
                    listOfdisciplines += '<option value="'+i+'">'+disciplinesLabels[i]+'</option>';
                });
                discipline.html(listOfdisciplines);
                $("#create-dialog-form").dialog("open");

                <?php //@done s2 - Não permitir que crie eventos no mesmo horário
                      //@todo s2 - Verificar choque de horários  
                      //@todo s2 - Verificar se o professor já esta dando aula neste horário?>
                $(lessons).each(function(i, val){ 
                    v1 = val.start.getTime();
                    v2 = val.end == null ? v1 : val.end.getTime();
                    l1 = lesson_start.getTime();
                    l2 = lesson_end == null ? l1 : lesson_end.getTime();

                    if ((l1 < v1 && l2 <= v1)
                       || (l1 > v2 && l2 > v2)){
                    }else{
                        myCreateDialog.dialog('close');
                        //Pode-se criar um dialog para avisar o que ocorreu, mas acho que ficaria muito spam.
                    }
                });
                $('body').css('overflow','hidden');
                //}else{
                //    addError(id, "Selecione a Turma");
                //} 
                calendar.fullCalendar('unselect');
            },
            
            
            //Evento ao clicar nos blocos de horários existentes
            //Atualizar e Remover bloco
            eventClick: function(event){
                lesson = updateLesson(event);
                <?php //@done s2 - Criar tela de dialogo com opções de ALTERAR e REMOVER aula        ?>
                <?php //@done s2 - Criar função de REMOVER aula        ?>
                <?php //@done s2 - Criar função de ATUALIZAR aula        ?>
//                var id = formClassBoard+'classroom_fk';
//                if($(id).val().length != 0){

                    var listOfdisciplines = '<option value="">Selecione a disciplina</option>';
                    $.each(disciplines,function(i,d){
                        if (d != 0)
                        listOfdisciplines += '<option value="'+i+'">'+disciplinesLabels[i]+'</option>';
                    });
                    uDiscipline.html(listOfdisciplines);
                    uDiscipline.val(event.discipline).trigger('change');
                    $("#update-dialog-form").dialog("open");
                    calendar.fullCalendar('unselect');
                    $('body').css('overflow','hidden');
//                }else{
//                    addError(id, "Selecione a Turma");
//                } 
            },
                    
                    
            //Evento ao mover um bloco de horário
            //Atualizar o bloco
            <?php //@done s2 - criar o evento que ATUALIZAR os dados do banco ao mover a aula        
                  //@done s2 - Draggear evento grande apos renderizar e voltar pequeno nao funciona?>
            eventDrop: function(event, dayDelta, minuteDelta) {
                lesson = updateLesson(event);
                lesson.discipline = event.discipline;
                var l = lesson;  
                $.ajax({
                    type:'POST',
                    url:'<?php echo CController::createUrl('classroom/updateLesson'); ?>',
                    success:function(e){
                        var event = jQuery.parseJSON(e);
                        calendar.fullCalendar('removeEvents',event.id);
                        calendar.fullCalendar('renderEvent',event,true);
                        myUpdateDialog.dialog("close");
                    },
                    data:{'lesson': l , 'days': dayDelta, 'minutes': minuteDelta,classroom_fk:classroomId}
                });
                
            },
                    
                    
            //Evento de carregamento do calendário
            loading: function(bool) {
                if (bool) $('#loading').show();
                else $('#loading').hide();
            }

        });
        
        
        ////////////////////////////////////////////////
        // Modals                                     //
        ////////////////////////////////////////////////
        //Cria o Dialogo de TeachingData
        myTeachingDataDialog = $("#teachingdata-dialog-form").dialog({
            autoOpen: false,
            height: 430,
            width: 250,
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {
                "<?php echo Yii::t('default','Create'); ?>": function(){   
                    addTeachingData();
                    $(this).dialog("close");
                },
                <?php echo Yii::t('default','Cancel'); ?>: function() {
                    $(this).dialog("close");
                    $('body').css('overflow','scroll');
                }
            },
        });

        //Cria o Dialogo de CRIAÇÃO
        myCreateDialog = $("#create-dialog-form").dialog({
            autoOpen: false,
            height: 215,
            width: 230,
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {
                "<?php echo Yii::t('default','Create'); ?>": function(){                    
                    if(discipline.val().length != 0){
                        var l = createNewLesson();                    
                        <?php //@done s2 - Ajax da criação de lessons ?>
                        $.ajax({
                            type:'POST',
                            url:'<?php echo CController::createUrl('classroom/addLesson'); ?>',
                            success:function(e){
                                var event = jQuery.parseJSON(e);
                                calendar.fullCalendar('renderEvent',event,true);
                                myCreateDialog.dialog("close");
                                $('body').css('overflow','scroll');
                            },
                            data:{'lesson': l }
                        });
                    }else{
                        var id = '#discipline';
                        addError(id, "Selecione a Disciplina");              
                    }
                },
                <?php echo Yii::t('default','Cancel'); ?>: function() {
                    $(this).dialog("close");
                    $('body').css('overflow','scroll');
                }
            },
        });

        //Cria o Dialogo de ALTERAÇÃO e REMOÇÃO
        myUpdateDialog = $("#update-dialog-form").dialog({
            autoOpen: false,
            height: 215,
            width: 250,
            modal: true,
            draggable: false,
            resizable: false,
            create: function( event, ui ) {
                uDiscipline.val(lesson.discipline).trigger('change');
            },
            buttons: {
                "<?php echo Yii::t('default','Update'); ?>": function(){
                    if(uDiscipline.val().length != 0){
                        lesson.discipline = uDiscipline.val();
                        var l = lesson;  
                        <?php //@done s2 - Ajax da criação de lessons ?>
                        $.ajax({
                            type:'POST',
                            url:'<?php echo CController::createUrl('classroom/updateLesson'); ?>',
                            success:function(e){
                                var event = jQuery.parseJSON(e);
                                calendar.fullCalendar('removeEvents',event.id);
                                calendar.fullCalendar('renderEvent',event,true);
                                myUpdateDialog.dialog("close");
                                $('body').css('overflow','scroll');
                            },
                            data:{'lesson': l }
                        });
                    }else{
                        var id = '#update-discipline';
                        addError(id, "Selecione a Disciplina");              
                    }
                },
                <?php echo Yii::t('default','Delete'); ?>: function() {
                        lesson.discipline = uDiscipline.val();
                        var l = lesson;  
                        <?php //@done s2 - Ajax da criação de lessons ?>
                        $.ajax({
                            type:'POST',
                            url:'<?php echo CController::createUrl('classroom/deleteLesson'); ?>',
                            success:function(){
                                calendar.fullCalendar('removeEvents',l.id);
                                myUpdateDialog.dialog("close");
                                $('body').css('overflow','scroll');
                            },
                            data:{'lesson': l }
                        });
                },
                <?php echo Yii::t('default','Cancel'); ?>: function() {
                    myUpdateDialog.dialog("close");
                    $('body').css('overflow','scroll');
                }
            },
        });

    });

    
    ////////////////////////////////////////////////
    // Functions                                  //
    ////////////////////////////////////////////////
    <?php //@done s1 - Criar exclusão do teachingData e verificar a não exclusão de novos dados (vem undefined) ?>
    var removeTeachingData = function(){
        var instructor = $(this).parent().parent().parent().attr("instructor");
        var discipline = ($(this).parent().attr("discipline"));
        if (instructor == undefined){
            instructor = $(this).parent().attr("instructor");
            if (instructor == undefined){
                disciplines[discipline] = 0;
                $("#DisciplinesWithoutInstructors li[discipline = "+discipline+"]").remove();
            }else{
                removeInstructor(instructor);
            }
        }
        else{
            removeDiscipline(instructor,discipline);
        }
    
    }
    
    var removeInstructor = function(instructor){
        for(var i = 0; i < teachingData.length; i++){
            if(teachingData[i].Instructor == instructor){
                for(var j = 0; j < teachingData[i].Disciplines.length;j++){
                    removeDiscipline(instructor,teachingData[i].Disciplines[j])
                }
                
                teachingData.splice(i, 1);
            }
        }
        $("li[instructor = "+instructor+"]").remove();
        
    }
        
    var removeDiscipline = function(instructor, discipline){
        var count = 0;
        for(var i = 0; i < teachingData.length; i++){
            for(var j = 0; j < teachingData[i].Disciplines.length;j++){
                if(discipline == teachingData[i].Disciplines[j])
                    count++;
            }
        }
        
        for(var i = teachingData.length; i--;) {
            if(teachingData[i].Instructor == instructor) {
                for(var j = teachingData[i].Disciplines.length; j--;){
                    if(teachingData[i].Disciplines[j] == discipline)
                        teachingData[i].Disciplines.splice(j, 1);
                }
                
                if(teachingData[i].Disciplines.length == 0){
                    teachingData.splice(i, 1);
                    $("li[instructor = "+instructor+"]").remove();
                }
                if(count <= 1){
                    disciplines[discipline] = 0;
                }
            }
        }
        $("li[instructor = "+instructor+"] li[discipline = "+discipline+"]").remove();
    }

    var addTeachingData = function(){
        var instructorName = $('#s2id_Instructors span').text();
        var instructorId = $('#Instructors').val();

        var disciplineList = $("#Disciplines").val(); 
        var disciplineNameList = [];

        var role = $("#Role").val(); 
        var contract = $("#ContractType").val(); 

        $.each($("#s2id_Disciplines li.select2-search-choice"), function(i,v){
            disciplineNameList[i] = $(v).text();
        });

        //Se for uma string vazia
        if(instructorId.length == 0){
            $.each(disciplineNameList, function(i,name){
                if($("#DisciplinesWithoutInstructors li[discipline="+disciplineList[i]+"]").length == 0){
                    $("#DisciplinesWithoutInstructors").append(""
                        +"<li discipline='"+disciplineList[i]+"'><span>"+name+"</span>"
                        +"<a href='#' class='deleteTeachingData delete' title='Excluir'> </a> "
                        +"</li>");
                }
                disciplines[disciplineList[i]] = 2;
            });
        }else{
            var td = {
                Instructor : instructorId,
                Classroom : null,
                Role : role,
                ContractType : contract,
                Disciplines : []
            };
            var html = "";
            var tag = "";

            var hasInstructor = $("li[instructor = "+instructorId+"]").length != 0;
            var instructorIndex = -1;

            if (!hasInstructor){
                tag = "#DisciplinesWithInstructors";
                html = "<li instructor='"+instructorId+"'><span>"+instructorName+"</span>"
                    +"<a href='#' class='deleteTeachingData delete' title='Excluir'> </a>"
                    +"<ul>";
            }else{
                $.each(teachingData, function(i, data){
                    if(data.Instructor == instructorId)
                        instructorIndex = i;
                });
                tag = "#DisciplinesWithInstructors li[instructor = "+instructorId+"] ul";
            }

            $.each(disciplineNameList, function(i,name){
                var hasDiscipline = $("li[instructor = "+instructorId+"] li[discipline="+disciplineList[i]+"]").length != 0;
                if(!hasDiscipline){
                    html += "<li discipline='"+disciplineList[i]+"'>"+name
                        +"<a href='#' class='deleteTeachingData delete' title='Excluir'></a>"
                        +"</li>";
                    if(!hasInstructor)
                        td.Disciplines.push(disciplineList[i]);
                    else
                        teachingData[instructorIndex].Disciplines.push(disciplineList[i]);
                }
                disciplines[disciplineList[i]] = 1;
            });


            if (!hasInstructor){
                html += "</ul>"
                    +"</li>";
                teachingData.push(td);
            }
            $(tag).append(html);
        }
    }
    
    //@done S2 - Reduzir caracteres do evento
    //@done S2 - Comportar o horário na tabela de classboard
    //Cria estrutura de uma aula
    //Retorna um array
    //O Ajax da problema de recursividade se colocado aqui
    var createNewLesson = function() {
        lesson = {
            id: lesson_id++,
            id_db: 0,
            title: (discipline.find('option:selected').text().length > 40) ? discipline.find('option:selected').text().substring(0,37) + "..." : discipline.find('option:selected').text(),
            discipline: discipline.val(),
            start: lesson_start,
            end: lesson_end,
            classroom: classroomId,
            description: 'This is a cool event'
        };
        return lesson;
    }
    
    //Atualiza estrutura de uma aula
    //Retorna um array
    //O Ajax da problema de recursividade se colocado aqui
    var updateLesson = function(l) {
        lesson = {
            id: l.id,
            db: l.db,
            title: l.title,
            discipline: uDiscipline.val(),
            start: l.start,
            end: l.end,
            classroom: l.classroom,
            description: 'This is a cool event'
        };
        return lesson;
    }
    
    
    
    $(document).on('click','.deleteTeachingData',removeTeachingData);
    <?php //@done s1 - Criar função addInstructor que adiciona o instrutor e suas disciplinas na array ?>
    $("#addTeachingData").on('click', addTeachingData);
    
  
    ////////////////////////////////////////////////
    // Validations                                //
    ////////////////////////////////////////////////
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
    <?php //@done s1 - Validar se o ano é apenas número e mandar erro?>
    $(form+'school_year').focusout(function() {
        var id = '#'+$(this).attr("id");
        
        $(id).val($(id).val().toUpperCase());
        
        if(!validateYear($(id).val())){ 
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
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
   <?php //@done s2 - Validação da disciplina?>
    //Validação da disciplina
    $("#discipline").change(function(){
        var id = '#discipline';
        if($(id).val().length == 0){
            addError(id, "Selecione a Disciplina."); 
        }else{
            removeError(id);
        }
    });
   <?php //@done s2 - Validação da classroom?>
    //Validação da Classroom
    $(formClassBoard+'classroom_fk').change(function(){
        var id = formClassBoard+'classroom_fk';
        calendar.fullCalendar('removeEvents');
        if($(id).val().length == 0){
            addError(id, "Selecione a Turma."); 
        }else{
            removeError(id);
        }
    });


    ////////////////////////////////////////////////
    // Tabs and Pagination                        //
    ////////////////////////////////////////////////
    $('.tab-classroom li a').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-classroom';
        var li2 = 'tab-classboard';
        var tab = '';
        switch($(this).parent().attr('id')) {
            case li1 : tab = li1; 
                $('.prev').hide();
                $('.next').show();
                $('.last').hide(); break;
            case li2 : tab = li2;
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
    });
    $('.next').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-classroom';
        var li2 = 'tab-classboard';
        var next = '';
        switch(classActive.attr('id')) {
            case li1 : next = li2; 
                $('.prev').show();
                $('.next').hide();
                $('.last').show(); break;
            case li2 : next = li2; break;
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
        var li1 = 'tab-classroom';
        var li2 = 'tab-classboard';
        var previous = '';
        switch(classActive.attr('id')) {
            case li1 : previous = li1;  break;
            case li2 : previous = li1; 
                $('.prev').hide();
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
    
    
    ////////////////////////////////////////////////
    // Submit Form                                //
    ////////////////////////////////////////////////
    $('#enviar_essa_bagaca').click(function() { 
        $('#teachingData').val(JSON.stringify(teachingData)); 
        $('#disciplines').val(JSON.stringify(disciplines));
        $('form').submit();
    });

    
    ////////////////////////////////////////////////
    // Dialog Controls                            //
    ////////////////////////////////////////////////
    $("#newDiscipline").click(function(){
        $("#teachingdata-dialog-form").dialog('open');
    });    
    
    //Ao clicar ENTER no formulário adicionar aula
    $('#create-dialog-form, #teachingdata-dialog-form, #update-dialog-form').keypress(function(e) {
        if (e.keyCode == $.ui.keyCode.ENTER) {
            e.preventDefault();
        }
    });

    $('.heading-buttons').css('width', $('#content').width());
</script>
