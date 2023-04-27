<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('Group', 'default'));
$cs->registerCssFile($baseUrl . 'sass/css/main.css');

Yii::app()->clientScript->registerMetaTag('unsafe-url', 'referrer');
Yii::app()->clientScript->registerMetaTag('origin', 'referrer');
Yii::app()->clientScript->registerMetaTag('origin-when-cross-origin', 'referrer');
Yii::app()->clientScript->registerMetaTag('no-referrer-when-downgrade', 'referrer');


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'questiongroup-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h1><?php echo $title; ?></h1>
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($questionGroup->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save_question_group_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
            <?php
            if (!$questionGroup->isNewRecord) {
                echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), array('id' => 'delete_question_group_button', 'class' => 'btn btn-icon btn-primary last glyphicons delete', 'type' => 'button'));
            }
            ?>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('success') && (!$questionGroup->isNewRecord)) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error')) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>

    <div class="widget widget-tabs border-bottom-none">
        <div class="t-tabs js-tab-control">
            <ul class="tab-classroom t-tabs__list">
                <li id="tab-group" class="t-tabs__item active">
                    <a class="t-tabs__link" href="#group" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Group') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
        <div class="tab-content form-content">

                <div class="tab-pane active" id="group">
                    <div class="row">
                        <div class="column helper">
                            <div class="t-field-select">
                                <?php echo $form->labelEx($questionGroup, 'question_group_id', array('class' => 't-field-text__label--required')); ?>
                                <?php
                                $questionGroups = QuestionGroup::model()->findAll();

                                echo $form->dropDownList(
                                    $questionGroup,
                                    'question_group_id',
                                    CHtml::listData(
                                        $questionGroups,
                                        'id',
                                        'name'
                                    ),
                                    array("prompt" => "Selecione um Grupo", 'class' => 'select-search-on t-field-select__input')
                                ); ?>
                                <?php echo $form->error($questionGroup, 'question_group_id'); ?>
                            </div>
                            <!-- .control-group -->
                            <div class="t-field-select">
                                <?php echo $form->labelEx($questionGroup, 'question_id', array('class' => 't-field-text__label--required')); ?>
                                <?php
                                $questions = Question::model()->findAll();
                                echo $form->dropDownList(
                                    $questionGroup,
                                    'question_id',
                                    CHtml::listData(
                                        $questions,
                                        'id',
                                        'description'
                                    ),
                                    array("prompt" => "Selecione uma Questão", 'class' => 'select-search-on t-field-select__input')
                                ); ?>
                                <?php echo $form->error($questionGroup, 'question_id'); ?>
                            </div>
                        </div>
                        <div class="column"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php $form = $this->endWidget(); ?>