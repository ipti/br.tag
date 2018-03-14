<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('Group', 'default'));


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'questiongroup-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>  
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($questionGroup->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save_question_group_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
            <?php 
                if(!$questionGroup->isNewRecord){
                    echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), array('id' => 'delete_question_group_button', 'class' => 'btn btn-icon btn-primary last glyphicons delete', 'type' => 'button'));
                }
            ?>
        </div>
    </div>
</div>

<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success') && (!$questionGroup->isNewRecord)): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error') && (!$questionGroup->isNewRecord)): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head  hidden-print">
            <ul class="tab-classroom">
                <li id="tab-group" class="active" ><a class="glyphicons adress_book" href="#group" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Group') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                    
                <div class="tab-pane active" id="group">
                        <div class="row-fluid">
                            <div class=" span5">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($questionGroup, 'question_group_id', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                    <?php
                                        $questionGroups = QuestionGroup::model()->findAll();

                                        echo $form->dropDownList($questionGroup, 'question_group_id',
                                            CHtml::listData(
                                                $questionGroups, 'id', 'name'),
                                            array("prompt" => "Selecione um Grupo", 'class' => 'select-search-on')); ?>
                                        <?php echo $form->error($questionGroup, 'question_group_id'); ?>
                                    </div>
                                </div> 
                                <!-- .control-group -->
                                <div class="control-group">
                                    <?php echo $form->labelEx($questionGroup, 'question_id', array('class' => 'control-label required')); ?>
                                    <div class="controls">
                                    <?php
                                        $questions = Question::model()->findAll();

                                        echo $form->dropDownList($questionGroup, 'question_id',
                                            CHtml::listData(
                                                $questions, 'id', 'description'),
                                            array("prompt" => "Selecione uma Questão", 'class' => 'select-search-on')); ?>
                                        <?php echo $form->error($questionGroup, 'question_id'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</div>

<?php $form = $this->endWidget(); ?>