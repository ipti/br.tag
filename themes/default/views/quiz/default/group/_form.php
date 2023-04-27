<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Group'));
$cs->registerCssFile($baseUrl . 'sass/css/main.css');


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'group-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h1><?php echo $title; ?></h1>
        <div class="tag-buttons-container buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($group->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save_group_button', 'class' => 't-button-primary  next', 'type' => 'button'));
            ?>
            <?php
            if (!$group->isNewRecord) {
                echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), array('id' => 'delete_group_button', 'class' => 't-button-primary  next', 'type' => 'button'));
            }
            ?>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('success') && (!$group->isNewRecord)) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error') && (!$group->isNewRecord)) : ?>
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
                            <div class="t-field-text">
                                <?php echo $form->labelEx($group, 'name', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php echo $form->textField($group, 'name', array('size' => 60, 'maxlength' => 150, 'class' => "t-field-text__input")); ?>
                                <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('default', 'Group Name'); ?>"><i></i></span> -->
                                <?php echo $form->error($group, 'name'); ?>
                            </div>
                            <!-- .control-group -->
                            <div class="control-group">
                                <?php echo $form->labelEx($group, 'quiz_id', array('class' => 'control-label required')); ?>
                                <?php
                                $quizs = Quiz::model()->findAll(
                                    "status = :status AND final_date >= :final_date",
                                    [
                                        ':status' => 1,
                                        ':final_date' => date('Y-m-d'),
                                    ]
                                );

                                echo $form->dropDownList(
                                    $group,
                                    'quiz_id',
                                    CHtml::listData(
                                        $quizs,
                                        'id',
                                        'name'
                                    ),
                                    array("prompt" => "Selecione um Questionário", 'class' => 'select-search-on t-field-select__input')
                                ); ?>
                                <?php echo $form->error($group, 'quiz_id'); ?>
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