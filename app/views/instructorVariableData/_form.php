<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'instructor-variable-data-form',
    'enableAjaxValidation' => false,
        ));
?>

<?php
//Yii::app()->clientScript->registerScript('register_script_name', "
//    $('#InstructorVariableData_high_education_initial_year_1').click(function(){
//       alert('edit');
//       return false;
//    });
//", CClientScript::POS_READY);
?>


<div class="panelGroup form">
    <?php echo $form->errorSummary($model); ?>
    <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
        </div></div>
    <div class="panelGroupBody">
        <div class="panelGroupAbout">
            <?php echo Yii::t('default', 'Fields with * are required.') ?></div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'register_type'); ?>
            <?php echo $form->textField($model, 'register_type', array('size' => 2, 'maxlength' => 2)); ?>
            <?php echo $form->error($model, 'register_type'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'school_inep_id_fk'); ?>
            <?php echo $form->textField($model, 'school_inep_id_fk', array('size' => 8, 'maxlength' => 8)); ?>
            <?php echo $form->error($model, 'school_inep_id_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'inep_id'); ?>
            <?php echo $form->textField($model, 'inep_id', array('size' => 12, 'maxlength' => 12)); ?>
            <?php echo $form->error($model, 'inep_id'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'scholarity'); ?>
            <?php
            echo $form->DropDownlist($model, 'scholarity', array(1 => 'Fundamental Incompleto', 2 => 'Fundamental Completo',
                3 => 'Ensino Médio - Normal/Magistério', 4 => 'Ensino Médio - Normal/Magistério Indígena',
                5 => 'Ensino Médio', 6 => 'Superior'));
            ?>
            <?php echo $form->error($model, 'scholarity'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_situation_1'); ?>
            <?php echo $form->DropDownlist($model, 'high_education_situation_1', array(1 => 'Concluído', 2 => 'Em andamento'));
            ?>
            <?php echo $form->error($model, 'high_education_situation_1'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_formation_1'); ?>
            <?php echo $form->DropDownlist($model, 'high_education_formation_1', array(0 => 'Não', 1 => 'Sim')); ?>
            <?php echo $form->error($model, 'high_education_formation_1'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_course_code_1_fk'); ?>
            <?php echo $form->DropDownlist($model, 'high_education_course_code_1_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name'));
            ?>
            <?php echo $form->error($model, 'high_education_course_code_1_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_initial_year_1'); ?>
            <?php echo $form->textField($model, 'high_education_initial_year_1', array('size' => 4, 'maxlength' => 4)); ?>
            <?php echo $form->error($model, 'high_education_initial_year_1'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_final_year_1'); ?>
            <?php echo $form->textField($model, 'high_education_final_year_1', array('size' => 4, 'maxlength' => 4)); ?>
            <?php echo $form->error($model, 'high_education_final_year_1'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_institution_type_1'); ?>
            <?php echo $form->DropDownList($model, 'high_education_institution_type_1', array(1 => "Pública", 2 => "Privada"));
            ?>
            <?php echo $form->error($model, 'high_education_institution_type_1'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_institution_code_1_fk'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_institution_code_1_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name'));
            ?>
            <?php echo $form->error($model, 'high_education_institution_code_1_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_situation_2'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_situation_2', array(
                1 => 'Concluído', 2 => 'Em Andamento'
            ));
            ?>
            <?php echo $form->error($model, 'high_education_situation_2'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_formation_2'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_formation_2', array(
                0 => 'Não', 1 => 'Sim'
            ));
            ?>
            <?php echo $form->error($model, 'high_education_formation_2'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_course_code_2_fk'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_course_code_2_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name'));
            ?>
            <?php echo $form->error($model, 'high_education_course_code_2_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_initial_year_2'); ?>
            <?php echo $form->textField($model, 'high_education_initial_year_2', array('size' => 4, 'maxlength' => 4)); ?>
            <?php echo $form->error($model, 'high_education_initial_year_2'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_final_year_2'); ?>
            <?php echo $form->textField($model, 'high_education_final_year_2', array('size' => 4, 'maxlength' => 4)); ?>
            <?php echo $form->error($model, 'high_education_final_year_2'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_institution_type_2'); ?>
            <?php echo $form->DropDownList($model, 'high_education_institution_type_2', array(1 => "Pública", 2 => "Privada"));
            ?>
            <?php echo $form->error($model, 'high_education_institution_type_2'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_institution_code_2_fk'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_institution_code_2_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name'));
            ?>
            <?php echo $form->error($model, 'high_education_institution_code_2_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_situation_3'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_situation_3', array(
                1 => 'Concluído', 2 => 'Em Andamento'
            ));
            ?>
            <?php echo $form->error($model, 'high_education_situation_3'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_formation_3'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_formation_3', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'high_education_formation_3'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_course_code_3_fk'); ?>
            <?php
            echo $form->DropDownList($model, 'high_education_course_code_3_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name'));
            ?>
            <?php echo $form->error($model, 'high_education_course_code_3_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_initial_year_3'); ?>
            <?php echo $form->textField($model, 'high_education_initial_year_3', array('size' => 4, 'maxlength' => 4)); ?>
            <?php echo $form->error($model, 'high_education_initial_year_3'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_final_year_3'); ?>
            <?php echo $form->textField($model, 'high_education_final_year_3', array('size' => 4, 'maxlength' => 4)); ?>
            <?php echo $form->error($model, 'high_education_final_year_3'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_institution_type_3'); ?>
            <?php echo $form->DropDownList($model, 'high_education_institution_type_3', array(1 => "Pública", 2 => "Privada")); ?>
            <?php echo $form->error($model, 'high_education_institution_type_3'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'high_education_institution_code_3_fk'); ?> 
            <?php
            echo $form->DropDownList($model, 'high_education_institution_code_3_fk', CHtml::listData(EdcensoCourseOfHigherEducation::model()->findAll(), 'id', 'name'));
            ?>
            <?php echo $form->error($model, 'high_education_institution_code_3_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'post_graduation_specialization'); ?>
            <?php
            echo $form->DropDownList($model, 'post_graduation_specialization', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'post_graduation_specialization'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'post_graduation_master'); ?>
            <?php
            echo $form->DropDownList($model, 'post_graduation_master', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'post_graduation_master'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'post_graduation_doctorate'); ?>
            <?php
            echo $form->DropDownList($model, 'post_graduation_doctorate', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'post_graduation_doctorate'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'post_graduation_none'); ?>
            <?php
            echo $form->DropDownList($model, 'post_graduation_none', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'post_graduation_none'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_nursery'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_nursery', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_nursery'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_pre_school'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_pre_school', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_pre_school'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_basic_education_initial_years'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_basic_education_initial_years', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_basic_education_initial_years'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_basic_education_final_years'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_basic_education_final_years', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_basic_education_final_years'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_high_school'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_high_school', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_high_school'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_education_of_youth_and_adults'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_education_of_youth_and_adults', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_education_of_youth_and_adults'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_special_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_special_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_special_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_native_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_native_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_native_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_field_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_field_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_field_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_environment_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_environment_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_environment_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_human_rights_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_human_rights_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_human_rights_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_sexual_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_sexual_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_sexual_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_child_and_teenage_rights'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_child_and_teenage_rights', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_child_and_teenage_rights'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_ethnic_education'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_ethnic_education', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_ethnic_education'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_other'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_other', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_other'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'other_courses_none'); ?>
            <?php
            echo $form->DropDownList($model, 'other_courses_none', array(
                0 => 'Não', 1 => 'Sim'));
            ?>
            <?php echo $form->error($model, 'other_courses_none'); ?>
        </div>

        <div class="formField buttonWizardBar">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'buttonLink button')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.min.js"> </script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js"> </script>
<script type="text/javascript">
    $(document).ready( 
    function(){
        $('#InstructorVariableData_high_education_initial_year_1, \n\
#InstructorVariableData_high_education_initial_year_2,\n\
#InstructorVariableData_high_education_initial_year_3').on('change',function(){
            if(this.value.length == 4){
                var data = new Date();
                alert(anoMinMax(2002,data.getFullYear(),this.value));
            }
        });   
        $('#InstructorVariableData_high_education_final_year_1,\n\
#InstructorVariableData_high_education_final_year_2,\n\
#InstructorVariableData_high_education_final_year_3').on('change',function(){
            if(this.value.length == 4){
                var data = new Date();
                alert(anoMinMax(1941,data.getFullYear(),this.value));
            }
        });  
   
   
    });
        
  

</script>  
