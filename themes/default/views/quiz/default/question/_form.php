<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Question'));
$cs->registerCssFile($baseUrl . 'sass/css/main.css');


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'question-form',
    'enableAjaxValidation' => false,
));
?>
<div class="row-fluid  hidden-print">
    <div class="span12">
        <h1><?php echo $title; ?></h1>
        <div class="tag-buttons-container buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($question->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save_question_button', 'class' => 't-button-primary  next', 'type' => 'button'));
            ?>
            <?php
            if (!$question->isNewRecord) {
                echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), array('id' => 'delete_question_button', 'class' => 't-button-primary  next', 'type' => 'button'));
            }
            ?>
        </div>
    </div>
</div>
<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('success') && (!$question->isNewRecord)) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error') && (!$question->isNewRecord)) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>

    <div class="widget widget-tabs border-bottom-none">
        <div class="t-tabs js-tab-control">
            <ul class=" tab-classroom t-tabs__list">
                <li id="tab-question" class="t-tabs__item active">
                    <a class="t-tabs__link" href="#question" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Question') ?>
                    </a>
                    
                </li>
                <?php if (!$question->isNewRecord &&  in_array($question->type, $question->getEnableOption())) : ?>
                    <li id="tab-option" class="t-tabs__item">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                        <a class="t-tabs__link" href="#option" data-toggle="tab">
                            <span class="t-tabs__numeration">2</span>
                            <?php echo Yii::t('default', 'Option') ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content form-content">
                <div class="tab-pane active" id="question">
                    <div class="row">
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($question, 'description', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php echo $form->textField($question, 'description', array('size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input')); ?>
                                <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('default', 'Question Description'); ?>"><i></i></span> -->
                                <?php echo $form->error($question, 'description'); ?>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($question, 'type', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php
                                $quizs = Quiz::model()->findAll(
                                    "status = :status AND final_date >= :final_date",
                                    [
                                        ':status' => 1,
                                        ':final_date' => date('Y-m-d'),
                                    ]
                                );
                                echo $form->dropDownList(
                                    $question,
                                    'type',
                                    $question->getTypes(),
                                    array("prompt" => "Selecione o Tipo", 'class' => 'select-search-on t-field-select__input')
                                ); ?>
                                <?php echo $form->error($question, 'type'); ?>
                            </div>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($question, 'status', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php
                                echo $form->DropDownList($question, 'status', array(
                                    null => 'Selecione o status',
                                    '1' => 'Ativo',
                                    '0' => 'Inativo'
                                ), array('class' => 'select-search-off t-field-select__input'));
                                ?>
                                <?php echo $form->error($question, 'status'); ?>
                            </div>
                        </div>
                        <div class="column"></div>
                    </div>
                </div>
                <?php if (!$question->isNewRecord && in_array($question->type, $question->getEnableOption())) : ?>
                    <div class="tab-pane" id="option">
                        <div class="row">
                            <div class="column">
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($option, 'description', array('class' => 't-field-text__label--required')); ?>
                                    <?php echo $form->textField($option, 'description', array('size' => 60, 'maxlength' => 255,  'class' => 't-field-text__input')); ?>
                                    <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('default', 'Option Description'); ?>"><i></i></span> -->
                                    <?php echo $form->error($option, 'description'); ?>
                                </div>
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($option, 'answer', array('class' => 't-field-text__label--required')); ?>
                                    <?php echo $form->textField($option, 'answer', array('size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input')); ?>
                                    <?php echo $form->error($option, 'answer'); ?>
                                    <?php echo $form->hiddenField($option, 'question_id', array('size' => 60, 'maxlength' => 45, 'value' => $question->id)); ?>
                                    <?php echo $form->hiddenField($option, 'id', array('size' => 60, 'maxlength' => 45, 'value' => $option->id)); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($option, 'complement', array('size' => 60, 'maxlength' => 150, 'class' => 't-field-checkbox__input')); ?>
                                    <?php echo $form->error($option, 'complement'); ?>
                                    <?php echo $form->labelEx($option, 'complement', array('class' => 't-field-text__label')); ?>

                                </div>
                                <div class="control-group">
                                    <button id="save_option_button" class="t-button-primary" type="button" name="yt0"><i></i>Salvar</button>
                                </div>
                            </div>
                            <div class="column">
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



<?php

$form = $this->endWidget();

$dataOption = [];

foreach ($question->questionOptions as $value) {
    $dataOption[] = $value->getAttributes();
}


$script = "
var dataOption = " . json_encode($dataOption) . ";
Option.init();";

$cs->registerScript('option', $script, CClientScript::POS_END);

?>