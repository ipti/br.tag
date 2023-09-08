<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Edit Password'));
    $title = Yii::t('default', 'Edit Password');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu = array(
        array(
            'label' => Yii::t('default', 'List User'),
            'url' => array('index'),
            'description' => Yii::t('default', 'This action list all User, you can search, delete and update')
        ),
    );
    ?>

    <?php

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/admin/form/validations.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl . '/js/admin/form/_initialization.js', CClientScript::POS_END);

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'users-createUser-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <?php echo $form->errorSummary($model); ?>


    <div class="row-fluid hidden-print">
        <div class="span12">
            <h1><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></h1>
            <div class="buttons">
                <div class="buttons">
                    <div class="buttons">
                        <?php echo CHtml::htmlButton(
                            '<i></i>' . ($model->isNewRecord
                                ? Yii::t('default', 'Create')
                                : Yii::t('default', 'Save')),
                            array('type' => 'submit', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok')
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tag-inner">
        <div class="widget widget-tabs border-bottom-none">

            <div class="t-tabs">
                <ul class="tab-classroom t-tabs__list">
                    <li id="tab-classroom" class="active t-tabs__item"><a class="glyphicons user t-tabs__link" href="#User" data-toggle="tab"><span class="t-tabs__numeration">1</span><?php echo Yii::t('default', 'User') ?></a></li>
                </ul>
            </div>

            <div class="widget-body form-horizontal">
                <div class="tab-content form-content">

                    <!-- Tab content -->
                    <div class="tab-pane active" id="User">
                        <div class="row">
                            <div class="column is-two-fifths ">
                                <div class="column is-two-fifths ">
                                    <div class="t-field-text">
                                        <?php echo $form->labelEx($model, 'password', array('class' => 't-field-text__label--required')); ?>
                                        <?php echo $form->passwordField($model, 'password', array('size' =>     32, 'maxlength' => 32, 'class' => 't-field-text__input password-input',)); ?>
                                        <span class="t-icon-eye show-password-icon" id="showPassword"></span>
                                        <?php echo $form->error($model, 'password'); ?>
                                    </div>
                                    <div class="t-field-text">
                                        <?php echo CHtml::label(Yii::t('default', 'Confirm'), 'Confirm', array('class' => 't-field-text__label--required')); ?>
                                        <?php echo CHtml::passwordField('Confirm', '', array('size' => 32, 'maxlength' => 32, 'class' => 't-field-text__input password-input',)); ?>
                                        <span class="t-icon-eye show-password-icon" id="showPasswordConfirm"></span>
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

</div>

<style>
    .show-password-icon {
        cursor: pointer;
        font-size: 16px;
        position: relative;
        left: 200px;
        bottom: 26px;
    }
</style>