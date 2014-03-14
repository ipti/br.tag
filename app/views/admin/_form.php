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
        <h3 class="heading-mosaic"><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></h3>  
        <div class="buttons">
            <div class="buttons">
                 <div class="buttons">
                        <?php echo CHtml::htmlButton('<i></i>' . ($model->isNewRecord 
                                ? Yii::t('default', 'Create') 
                                : Yii::t('default', 'Save')), 
                              array( 'type' => 'submit', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok'));
                        ?>
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
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full Name'); ?>"><i></i></span>
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
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Min length')."4"; ?>"><i></i></span>
                                    <?php echo $form->error($model, 'username'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Min length')."6"; ?>"><i></i></span>
                                    <?php echo $form->error($model, 'password'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo CHtml::label(Yii::t('default', 'Confirm'), 'Confirm', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::passwordField('Confirm','',array('size'=>32,'maxlength'=>32)); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Confirm Password'); ?>"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo CHtml::label( Yii::t('default','Role'), 'Role', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList('Role','',  CHtml::listData(AuthItem::model()->findAll('type=2 order by name'), 'name','name'),array('class'=>'select-search-off')); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo CHtml::label( Yii::t('default','Schools'), 'schools', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList('schools','',CHtml::listData(SchoolIdentification::model()->findAll('situation=1 order by name'), 'inep_id', 'name'),array('multiple'=>'multiple', 'class'=>'select-search-on')); ?>
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
<script>
    var form = '#Users_';
    
    $(form+'name').focusout(function() { 
        var id = '#'+$(this).attr("id");
        $(id).val($(id).val().toUpperCase());
        if(!validateSchoolName($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
    
    $(form+'username').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validateLogin($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });

    $(form+'password').focusout(function() { 
        var id = '#'+$(this).attr("id");
        if(!validatePassword($(id).val())) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });

    $('#Confirm').focusout(function() { 
        var id = '#'+$(this).attr("id");
        var passId = form+'password';
        
        if($(id).val() != $(passId).val()) {
            $(id).attr('value','');
            addError(id, "Campo não está dentro das regras.");
        }else{
            removeError(id);
        }
    });
</script>