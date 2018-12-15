<?php
/* @var $model SchoolConfiguration */
/* @var $this SchoolConfigurationControler */
/* @var $form CActiveForm */

$this->breadcrumbs = array(
    Yii::t('app', 'School Configurarion') => array('index'),
    Yii::t('app', 'Create'),
);

$title = Yii::t('app', 'School Configurarion');

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
));
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?>
            <span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span>
        </h3>

        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'submit')); ?></div>
    </div>
</div>
<div class="innerLR">
    <div class="widget widget-tabs border-bottom-none">
        <?php echo $form->errorSummary($model); ?>

        <div class="widget-head">
            <ul class="tab-sorcerer">
                <li id="tab-time" class="active">
                    <a class="glyphicons vcard" href="#time" data-toggle="tab">
                        <i></i> <?php echo Yii::t('wizardModule.labels', 'Schedule') ?>
                    </a>
                </li>
                <li id="tab-exams">
                    <a class="glyphicons vcard" href="#exams" data-toggle="tab">
                        <i></i> <?php echo Yii::t('wizardModule.labels', 'Exam dates') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="time">
                    <div class="row-fluid">
                        <?php echo $form->hiddenField($model, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'morning_initial', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'morning_initial'); ?>
                                </div>
                                <?php echo $form->error($model, 'morning_initial'); ?>
                            </div>
                            <!-- row -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'afternoom_initial', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'afternoom_initial'); ?>
                                </div>
                                <?php echo $form->error($model, 'afternoom_initial'); ?>
                            </div>
                            <!-- row -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'night_initial', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'night_initial'); ?>
                                </div>
                                <?php echo $form->error($model, 'night_initial'); ?>
                            </div>
                            <!-- row -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'allday_initial', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'allday_initial'); ?>
                                </div>
                                <?php echo $form->error($model, 'allday_initial'); ?>
                            </div>
                            <!-- row -->
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'morning_final', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'morning_final'); ?>
                                </div>
                                <?php echo $form->error($model, 'morning_final'); ?>
                            </div>
                            <!-- row -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'afternoom_final', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'afternoom_final'); ?>
                                </div>
                                <?php echo $form->error($model, 'afternoom_final'); ?>
                            </div>
                            <!-- row -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'night_final', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'night_final'); ?>
                                </div>
                                <?php echo $form->error($model, 'night_final'); ?>
                            </div>
                            <!-- row -->
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'allday_final', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->timeField($model, 'allday_final'); ?>
                                </div>
                                <?php echo $form->error($model, 'allday_final'); ?>
                            </div>
                            <!-- row -->
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="exams">
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'exam1', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'exam1'); ?>
                                </div>
                                <?php echo $form->error($model, 'exam1'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'exam2', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'exam2'); ?>
                                </div>
                                <?php echo $form->error($model, 'exam2'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'exam3', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'exam3'); ?>
                                </div>
                                <?php echo $form->error($model, 'exam3'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'exam4', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'exam4'); ?>
                                </div>
                                <?php echo $form->error($model, 'exam4'); ?>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'recovery1', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'recovery1'); ?>
                                </div>
                                <?php echo $form->error($model, 'recovery1'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'recovery2', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'recovery2'); ?>
                                </div>
                                <?php echo $form->error($model, 'recovery2'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'recovery3', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'recovery3'); ?>
                                </div>
                                <?php echo $form->error($model, 'recovery3'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'recovery4', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'recovery4'); ?>
                                </div>
                                <?php echo $form->error($model, 'recovery4'); ?>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'recovery_final', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dateField($model, 'recovery_final'); ?>
                                </div>
                                <?php echo $form->error($model, 'recovery_final'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<!-- form -->