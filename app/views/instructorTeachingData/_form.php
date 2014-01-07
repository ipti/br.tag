<?php 
//@todo 27 - InstructorTeachingData é o processo na turma e não a turma no professor.
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'instructor-teaching-data-form',
	'enableAjaxValidation'=>false,
)); ?>


<div class="heading-buttons">
    <?php
    $isModel = isset($model);
    echo $form->errorSummary($model);
    echo isset($error) ? $error : '';
    ?>
    
    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>
    <div class="buttons pull-right">
        <button type="button" class="btn btn-icon btn-default glyphicons unshare"><i></i>Voltar</button>
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary glyphicons circle_ok')); ?><div class="controls">
    </div>
    <div class="clearfix"></div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul>
                <li><a class="glyphicons cutlery" href="#instructor-teaching" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Teaching Data') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">

                    <div class="tab-pane active" id="instructor-teaching">
                    <div class="row-fluid">
                        <div class=" span6">
                            <?php echo Yii::t('default', 'Fields with * are required.') ?>

                        <div class="separator"></div>
                        <div class="separator"></div>
<!--                      </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'instructor_inep_id'); ?>
                            <?php echo $form->textField($model, 'instructor_inep_id', array('size' => 12, 'maxlength' => 12)); ?>
                            <?php echo $form->error($model, 'instructor_inep_id'); ?>
                        </div></div>

<!--                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'classroom_inep_id'); ?>
                            <?php echo $form->textField($model, 'classroom_inep_id', array('size' => 8, 'maxlength' => 8)); ?>
                            <?php echo $form->error($model, 'classroom_inep_id'); ?>
                        </div></div>-->

                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'school_inep_id_fk', array('class' => 'control-label')); ?><div class="controls">
                             <?php
                            echo $form->DropDownList($model, 'school_inep_id_fk', CHtml::listData(
                                            SchoolIdentification::model()->findAll(), 'inep_id', 'name'), array(
                                'prompt' => 'Select School',
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('InstructorTeachingData/getClassroom'),
                                    'update' => '#InstructorTeachingData_classroom_id_fk',
                                    )));
                            ?>
                            <?php echo $form->error($model, 'school_inep_id_fk'); ?>
                        </div></div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'classroom_id_fk', array('class' => 'control-label')); ?><div class="controls">
                            <?php echo $form->DropDownList($model, 'classroom_id_fk', CHtml::listData(
                            Classroom::model()->findAllByAttributes(array('school_inep_fk'=>$model->school_inep_id_fk)), 'id', 'name'),
                                    array(
                                        'prompt' =>'Primeiro Selecione uma Escola'
                                    )); ?>
                            <?php echo $form->error($model, 'classroom_id_fk'); ?>
                        </div></div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'role', array('class' => 'control-label')); ?><div class="controls">
                            <?php
                            echo $form->DropDownlist($model, 'role', array(1 => 'Docente', 2 => 'Auxiliar/Assistente Educacional',
                                3 => 'Profissional/Monitor de Atividade Complementar',
                                4 => 'Tradutor Intérprete de LIBRAS'));
                            ?>                    
                            <?php echo $form->error($model, 'role'); ?>
                        </div></div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'contract_type', array('class' => 'control-label')); ?><div class="controls">        
                            <?php
                            echo $form->DropDownlist($model, 'contract_type', array(1 => 'Concursado/efetivo/estável', 2 => 'Contrato temporário',
                                3 => 'Contrato terceirizado',
                                4 => 'Contrato CLT'));
                            ?>  
                            <?php echo $form->error($model, 'contract_type'); ?>
                        </div></div>
                        </div>
                        <div class="span6">
                        <div class="separator"></div>
                        <div class="separator"></div>
                        <div class="control-group">
                            <?php echo $form->labelEx($model, 'discipline_1_fk', array('class' => 'control-label')); ?>
                            <div class="controls">
                            <?php
                            echo $form->DropDownlist($model, 'discipline_1_fk', CHtml::listData(
                                            EdcensoDiscipline::model()->findAll(), 'id', 'name')
                                    , array('multiple'=>true, 'key'=>'id'));
                            ?>
                            <?php echo $form->error($model, 'discipline_1_fk'); ?>
                        </div></div>
                       <div class="control-group">
                        <div class="controls">
                            <?php echo $form->hiddenField($model,'instructor_fk',array('value'=>$instructor_id)); ?>
                            <?php echo $form->error($model,'instructor_fk'); ?>
                        </div>
                    </div>

                    </div>
                </div>    
               

                <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>

        
        <script language="javascript" type="text/javascript">
        
        $(document).ready(function() {
        //==============INSTRUCTOR TEACHING DATA
        var formInstructorTeachingData = '#InstructorTeachingData_';
            $(formInstructorTeachingData+"discipline_1_fk").change(function(){
            while($(this).val().length > 13){
                $(formInstructorTeachingData+"discipline_1_fk").val($(formInstructorTeachingData+"discipline_1_fk"));
                alert('Máximo de disciplinas: 13')
            }
        });
        
        var compAct = [];
        
        $(formInstructorTeachingData+"discipline_1_fk").mousedown(function(){
            compAct = $(this).val();
        });
        
        $(formInstructorTeachingData+"discipline_1_fk").mouseup(function(e){
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
        
        $(formInstructorTeachingData+"discipline_1_fk").keypress(function(e) {
            console.log();
        });
        //=============================================
        });
        
        </script>
