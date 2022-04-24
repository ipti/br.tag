<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Question'));


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'question-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>  
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($question->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save_question_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
            <?php 
                if(!$question->isNewRecord){
                    echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), array('id' => 'delete_question_button', 'class' => 'btn btn-icon btn-primary last glyphicons delete', 'type' => 'button'));
                }
            ?>
        </div>
    </div>
</div>

<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success') && (!$question->isNewRecord)): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error') && (!$question->isNewRecord)): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head  hidden-print">
            <ul class="tab-classroom">
                <li id="tab-question" class="active" ><a class="glyphicons adress_book" href="#question" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Question') ?></a></li>
                <?php if(!$question->isNewRecord &&  in_array($question->type, $question->getEnableOption())): ?>
                    <li id="tab-option"><a class="glyphicons book" href="#option" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Option') ?></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                    
                <div class="tab-pane active" id="question">
                        <div class="row-fluid">
                            <div class=" span8">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($question, 'description', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->textField($question, 'description', array('size' => 60, 'maxlength' => 255)); ?>
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('default', 'Question Description'); ?>"><i></i></span>
                                        <?php echo $form->error($question, 'description'); ?>
                                    </div>
                                </div> <!-- .control-group -->
                                <div class="control-group">
                                    <?php echo $form->labelEx($question, 'type', array('class' => 'control-label required')); ?>
                                    <div class="controls">
                                    <?php
                                        $quizs = Quiz::model()->findAll(
                                            "status = :status AND final_date >= :final_date",
                                            [
                                                ':status' => 1,
                                                ':final_date' => date('Y-m-d'),
                                            ]
                                        );

                                        echo $form->dropDownList($question, 'type',$question->getTypes(),
                                            array("prompt" => "Selecione o Tipo", 'class' => 'select-search-on')); ?>
                                        <?php echo $form->error($question, 'type'); ?>
                                    </div>
                                </div><!-- .control-group -->
                                <div class="control-group">
                                    <?php echo $form->labelEx($question, 'status', array('class' => 'control-label required')); ?>
                                    <div class="controls">
                                        <?php
                                        echo $form->DropDownList($question, 'status', array(
                                            null => 'Selecione o status',
                                            '1' => 'Ativo',
                                            '0' => 'Inativo'), array('class' => 'select-search-off'));
                                        ?>
                                        <?php echo $form->error($question, 'status'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!$question->isNewRecord && in_array($question->type, $question->getEnableOption())): ?>
                    <div class="tab-pane" id="option">
                        <div class="row-fluid">
                            <div class="span5">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($option, 'description', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->textField($option, 'description', array('size' => 60, 'maxlength' => 255)); ?>
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('default', 'Option Description'); ?>"><i></i></span>
                                        <?php echo $form->error($option, 'description'); ?>
                                    </div>
                                </div> <!-- .control-group -->
                                <div class="control-group">                
                                    <?php echo $form->labelEx($option, 'answer', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->textField($option, 'answer', array('size' => 60, 'maxlength' => 255)); ?>
                                        <?php echo $form->error($option, 'answer'); ?>
                                    </div>
                                    <?php echo $form->hiddenField($option, 'question_id', array('size' => 60, 'maxlength' => 45, 'value' => $question->id)); ?>
                                    <?php echo $form->hiddenField($option, 'id', array('size' => 60, 'maxlength' => 45, 'value' => $option->id)); ?>
                                </div> <!-- .control-group -->
                                <div class="control-group">                
                                    <?php echo $form->labelEx($option, 'complement', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->checkBox($option, 'complement', array('size' => 60, 'maxlength' => 150)); ?>
                                        <?php echo $form->error($option, 'complement'); ?>
                                    </div>
                                </div> <!-- .control-group -->
								<div class="control-group">
									<div class="controls">
										<button id="save_option_button" class="btn btn-icon btn-primary last glyphicons circle_ok" type="button" name="yt0"><i></i>Salvar</button>
									</div>
								</div>
                            </div>

                            <div class="span6">
                                <table class="grade-table table table-bordered table-striped">
										<thead>
											<tr>
												<th width="15%">Nº</th>
												<th width="55%">Opção</th>
												<th width="30%">Ação</th>
											</tr>
										</thead>
										<tbody id="container_option"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</div>

<?php 

$form = $this->endWidget(); 

$dataOption = [];

foreach ($question->questionOptions as $value) {
    $dataOption[] = $value->getAttributes();
}


$script = "
var dataOption = ".json_encode($dataOption).";
Option.init();";

$cs->registerScript('option' ,$script, CClientScript::POS_END);

?>