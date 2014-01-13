<?php 
//@done S1 - 27 - InstructorTeachingData é o processo na turma e não no professor.

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
