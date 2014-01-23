<?php 
//@done S1 - 21 - A turma precisa de um periodo letivo senão ela fica atemporal.
//@done S1 - 23 - Lembrar de associar o professor a turma.
//@done S1 - Organizar os campos do form_classroom
//@done S1 - Modificar aba disciplinas para Teaching Data.
//@todo S1 - Vincular disciplinas do classroom com as do teachingdata
//@later S2 - Add validação para os campos que esão faltando


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
                <li id="tab-classroom" class="active" ><a class="glyphicons edit" href="#classroom" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Classroom') ?></a></li>
                <li id="tab-instructor-teaching"><a class="glyphicons edit" href="#instructor-teaching" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Instructor') ?></a></li> 
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

                //@todo S1 - Edição de teaching data - excluir o professor TD

                //@done s1 - criar estutura da tela de TeachingData no Classroom 
                
                ?>
                <div class="tab-pane" id="instructor-teaching">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="separator"></div>
                                
                            <div>
                                <div class="control-group">
                                    <?php echo CHtml::label("Instructors", "Instructors", array('class' => 'control-label')) ?>
                                    <div class="controls">
                                        <?php echo CHtml::DropDownList("Instructors", '', CHtml::listData(InstructorIdentification::model()->findAll('school_inep_id_fk=:school order by name', array(':school' => Yii::app()->user->school)), 'id', 'name'),array('prompt'=>'Sem Instrutor','class' => 'select-search-on')); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo CHtml::label("Disciplines", "Disciplines", array('class' => 'control-label')) ?>
                                    <div class="controls">
                                        <?php echo CHtml::DropDownList("Disciplines", '', ClassroomController::classroomDisciplineLabelArray(),array('multiple'=>'multiple')); ?>
                                            
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo CHtml::label("Role", "Role", array('class' => 'control-label')) ?>
                                    <div class="controls">
                                        <?php echo CHtml::DropDownList("Role", '', array(
                                            null=>'Selecione um Cargo',
                                            1=>'Docente',
                                            2=>'Auxiliar',
                                            3=>'Monitor',
                                            4=>'Intérprete',
                                            ),array('class' => 'select-search-off')); ?>
                                            
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo CHtml::label("ContractType", "ContractType", array('class' => 'control-label')) ?>
                                    <div class="controls">
                                        <?php echo CHtml::DropDownList("ContractType", '', array(
                                            null=>'Selecione tipo de Contrato',
                                            1=>'Concursado/Efetivo',
                                            2=>'Temporário',
                                            3=>'Terceirizado',
                                            4=>'CLT',
                                            ),array('class' => 'select-search-off')); ?> 
                                        
                            <div class="separator"></div>
                                        <a href="#" class="btn btn-icon btn-primary add glyphicons circle_plus" id="addInstructor"><i>Add</i></a>
                                    </div>
                                </div>
                                    
                            </div>
                        </div>
                        
                        <div class=" span6">
                            <div class="separator"></div>
                                
                            <?php
                            
                                $teachingDataList = "<ul>"
                                                    ."<li><span><b>Disciplinas com Instrutores</b></span>"
                                                    ."<ul id='DisciplinesWithInstructors'>";
                                $teachingDataArray = array();
                                $teachingDataDisciplines = array();
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
                                    
                                    foreach ($disciplines as $discipline) {
                                        $teachingDataList .= "<li discipline='".$discipline->id."'>".$discipline->name
                                            .'<a href="#" class="deleteTeachingData delete" title="Excluir">
                                              </a>'
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
                                foreach ($disciplinesWithoutInstructor as $disciplineId => $value) {
                                    if($value == 2){
                                        $labels = ClassroomController::classroomDisciplineLabelArray();
                                        $teachingDataList .= "<li discipline='".$disciplineId."'><span>" . $labels[$disciplineId] ."</span>"
                                                .'<a href="#" class="deleteTeachingData delete" title="Excluir"></a>';
                                    }
                                }
                                $teachingDataList .= "</ul></ul>";
                                
                            ?>
                                            
                                            
                                            
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $teachingDataList; ?>
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
    <?php //@done s1 - defini como ficaram os arrays de Disciplinas e TeachingData para salvar no banco ?>
    var teachingData = <?php echo json_encode($teachingDataArray); ?>;
    var disciplines =  <?php echo json_encode($disciplinesArray); ?>; 
                                    
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
    
    
    
    //@todo remover Disciplinas das arrays ao clicar em excluir
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
        
    $(document).on('click','.deleteTeachingData',removeTeachingData);
    
    
    <?php //@done s1 - Criar função addInstructor que adiciona o instrutor e suas disciplinas na array ?>
    $("#addInstructor").on('click', function(){
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
    
    var form_teaching = '#InstructorTeachingData_';

    $('.tab-classroom li a').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-classroom';
        var li2 = 'tab-instructor-teaching';
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
        var next_content = next.substring(4);
        next_content = next_content.toString();
        $('#'+tab).addClass("active");
        $('#'+next_content).addClass("active");
        $('html, body').animate({ scrollTop: 85 }, 'fast');
    });
     
    $('.next').click(function(){
        var classActive = $('li[class="active"]');
        var divActive = $('div .active');
        var li1 = 'tab-classroom';
        var li2 = 'tab-instructor-teaching';
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
        var li2 = 'tab-instructor-teaching';
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
    
    
    
    $('#enviar_essa_bagaca').click(function() { 
        $('#teachingData').val(JSON.stringify(teachingData)); 
        $('#disciplines').val(JSON.stringify(disciplines));
        $('form').submit();
    });
</script>
