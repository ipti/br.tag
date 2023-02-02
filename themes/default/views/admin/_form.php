<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/form/validations.js', CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', [
    'id' => 'users-createUser-form',
    'enableAjaxValidation' => false,
]);
?>
<?php //echo $form->errorSummary($model);?>

<div class="row-fluid">
    <div class="span12" style="height: 70px;">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>
        <span class="subtitle"> <?php echo Yii::t('default', 'Fields with * are required.') ?>
            <div class="buttons">
                <div class="buttons">
                    <div class="buttons">
                        <?php echo CHtml::htmlButton(
    '<i></i>' . ($model->isNewRecord
                                ? Yii::t('default', 'Create')
                                : Yii::t('default', 'Save')),
    ['type' => 'submit', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok']
);
                        ?>
                    </div>
                </div>
            </div>
    </div>
</div>

<div class="tag-inner">
    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul class="tab-classroom">
                <li id="tab-classroom" class="active">
                    <a class="glyphicons user" href="#User" data-toggle="tab">
                        <?php echo Yii::t('default', 'User') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="User">
                    <div class="row-fluid">
                        <div class="span6">
                            <div>
                                <h5 class="titulos">Dados Básicos</h5>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($model, 'name', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'name', ['size' => 100, 'maxlength' => 150, ]); ?>
                                    <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Full Name'); ?>"><i></i></span> -->
                                    <?php echo $form->error($model, 'name'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::label(Yii::t('default', 'Role'), 'Role', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    $roles = CHtml::listData(AuthItem::model()->findAll('type=2 and name <> "instructor" order by name'), 'name', 'name');
                                    foreach ($roles as $key => $value) {
                                        $roles[$key] = Yii::t('default', $value);
                                    }
                                    echo CHtml::dropDownList('Role', '', $roles, ['class' => 'select-search-off control-input']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="separator"></div>
                        <div class="span6" >
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::label(Yii::t('default', 'Schools'), 'schools', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList('schools', '', CHtml::listData(SchoolIdentification::model()->findAll('situation=1 order by name'), 'inep_id', 'name'), ['multiple' => 'multiple', 'class' => 'select-search-on control-input']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class=" span6">
                            <div>
                                <h5 class="titulos">Dados de login

                                </h5>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($model, 'username', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'username'); ?>
                                    <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Min length') . '4'; ?>"><i></i></span> -->
                                    <?php echo $form->error($model, 'username'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->labelEx($model, 'password', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $form->passwordField($model, 'password', ['size' => 32, 'maxlength' => 32, 'style' => 'width: 412px;']); ?>
                                    <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Min length') . '6'; ?>"><i></i></span> -->
                                    <?php echo $form->error($model, 'password'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::label(Yii::t('default', 'Confirm'), 'Confirm', ['class' => 'control-label']); ?>
                                </div>
                                <div class="controls">
                                    <?php echo CHtml::passwordField('Confirm', '', ['size' => 32, 'maxlength' => 32, 'style' => 'width: 412px;']); ?>
                                    <!-- <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Confirm Password'); ?>"><i></i></span> -->
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
</script>