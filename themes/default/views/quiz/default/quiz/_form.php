<?php
/* @var $this calendar.defaultController
 * @var $modelCalendar Calendar
 * @var $modelEvent CalendarEvent
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('Quiz', 'Default'));


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'quiz-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>  
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($quiz->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'quiz_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
        </div>
    </div>
</div>

<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success') && (!$modelClassroom->isNewRecord)): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head  hidden-print">
            <ul class="tab-classroom">
                <li id="tab-quiz" class="active" ><a class="glyphicons adress_book" href="#quiz" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Quiz') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                    
                <div class="tab-pane active" id="quiz">
                        <div class="row-fluid">
                            <div class=" span5">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($quiz, 'name', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->textField($quiz, 'name', array('size' => 60, 'maxlength' => 150)); ?>
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Quiz Name'); ?>"><i></i></span>
                                        <?php echo $form->error($quiz, 'name'); ?>
                                    </div>
                                </div> 
                                <!-- .control-group -->
                                <div class="control-group" id="modality">
                                    <?php echo $form->labelEx($quiz, 'status', array('class' => 'control-label required')); ?>
                                    <div class="controls">
                                        <?php
                                        echo $form->DropDownList($quiz, 'status', array(
                                            null => 'Selecione o status',
                                            '1' => 'Ativo',
                                            '2' => 'Inativo'), array('class' => 'select-search-off'));
                                        ?>
                                        <?php echo $form->error($quiz, 'status'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class=" span5">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($quiz, 'init_date', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?= $form->dateField($quiz, "init_date") ?>
                                        <?php echo $form->error($quiz, 'init_date'); ?>
                                    </div>
                                </div> <!-- .control-group -->
                                <div class="control-group">
                                    <?php echo $form->labelEx($quiz, 'final_date', array('class' => 'control-label required')); ?>
                                    <div class="controls">
                                        <?= $form->dateField($quiz, "final_date") ?>
                                        <?php echo $form->error($quiz, 'final_date'); ?>
                                    </div>
                                </div> <!-- .control-group -->
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span12">
							<div class="control-group">                
                                    <?php echo $form->labelEx($quiz, 'description', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?= $form->textArea($quiz, "description", array('rows' => 5, 'cols'=> 110)) ?>
                                        <?php echo $form->error($quiz, 'description'); ?>
                                    </div>
                                </div> <!-- .control-group -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</div>

<?php $form = $this->endWidget(); ?>