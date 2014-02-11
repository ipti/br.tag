<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'users-createUser-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>        
                </div>
                <div class="span4">
                    <div class="buttons">
                        <?php echo CHtml::htmlButton('<i></i>' . ($model->isNewRecord 
                                ? Yii::t('default', 'Create') 
                                : Yii::t('default', 'Save')), 
                              array('id' => 'enviar_essa_bagaca', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
                        ?>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul class="tab-classroom">
                <li id="tab-classroom" class="active" ><a class="glyphicons user" href="#User" data-toggle="tab"><i></i><?php echo Yii::t('default', 'User') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content">
                <!-- Tab content -->
                <div class="tab-pane active" id="User">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'name',array('size'=>100,'maxlength'=>150, 'class' => 'span10')); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'User Name'); ?>"><i></i></span>
                                    <?php echo $form->error($model, 'name'); ?>
                                </div>

                            </div>
                            
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'username', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'username'); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Login'); ?>"><i></i></span>
                                    <?php echo $form->error($model, 'username'); ?>
                                </div>

                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Password'); ?>"><i></i></span>
                                    <?php echo $form->error($model, 'password'); ?>
                                </div>

                            </div>
                            <div class="control-group">
                                <?php echo CHtml::label('Confirm Password', 'Confirm', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::passwordField('Confirm','',array('size'=>32,'maxlength'=>32)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Password'); ?>"><i></i></span>
                                </div>

                            </div>
                        </div>
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo CHtml::label('Role', 'Role', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList('Role','',array(),array('class'=>'select-search-off')); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Password'); ?>"><i></i></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo CHtml::label('Schools', 'schools', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList('schools','',array(),array('multiple'=>'multiple', 'class'=>'select-search-on')); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Password'); ?>"><i></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>