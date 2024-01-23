<?php
/**
 * @var AdminController $this UsersController
 * @var CModel $model Users
 * @var $form CActiveForm
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/form/validations.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/form/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/form/_functions.js?v='.TAG_VERSION, CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'users-createUser-form',
    'enableAjaxValidation' => false,
));

/**
 * @var CActiveForm $form
 */

?>
<div class="mobile-row">
    <div class="column clearleft">
        <h1><?php echo $title; ?></h1>
    </div>
    <div class="column clearfix align-items--center justify-content--end show--desktop">
        <div id="createUser">
            <?php echo CHtml::htmlButton(($model->isNewRecord
                    ? Yii::t('default', 'Create')
                    : Yii::t('default', 'Save')),
                array('type' => 'submit', 'class' => 't-button-primary last')
            );
            ?>
        </div>
    </div>
</div>

<div class="form">
    <div class="tag-inner">
        <div class="widget widget-tabs border-bottom-none">
            <div class="t-tabs">
                <ul class="t-tabs__list tab-classroom">
                    <li id="tab-classroom" class="t-tabs__item active">
                        <a class="t-tabs__link first">
                            <span class="t-tabs__numeration">1</span>
                            <?php echo Yii::t('default', 'User') ?>
                        </a>
                    </li>
                </ul>
            </div>

            <?php echo $form->errorSummary($model); ?>
            <div class="alert alert-error no-show"></div>

            <div class="widget-body form-horizontal">
                <div class="tab-content">
                    <!-- Tab content -->
                    <div class="tab-pane active" id="User">
                        <div class="row">
                            <h3>Dados Básicos</h3>
                        </div>
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-select">
                                        <?php echo CHtml::label(Yii::t('default', 'Role'), 's2id_Role', array('class' => 't-field-select__label required', 'required' => true)); ?>
                                        <?php
                                        $roles = CHtml::listData(AuthItem::model()->findAll('type=2 order by name'), 'name', 'name');
                                        foreach ($roles as $key => $value) {
                                            $roles[$key] = Yii::t('default', $value);
                                        }
                                        echo CHtml::dropDownList(
                                            'Role',
                                            $actual_role,
                                            $roles,
                                            array(
                                                'class' => 'select-search-off t-field-select__input select2-container js-show-instructor-input'
                                            )
                                        ); ?>
                                </div>
                                <div class="t-field-select js-instructor-input hide">
                                    <?php echo CHtml::label(Yii::t('default', 'Instructor'), 'instructor', array('class' => 't-field-select__label'))?>
                                    <?=
                                        CHtml::dropDownList(
                                            'instructor',
                                            $selectedInstructor->id,
                                            $instructors,
                                            array(
                                                'prompt' => 'Selecione o professor',
                                                'class' => 'select-search-on t-field-select__input js-instructor-select'
                                            )
                                        );
                                    ?>
                                </div>
                                <div class="t-field-text">
                                        <?php echo $form->labelEx($model, 'name', array('class' => 't-field-text__label')); ?>
                                        <?php echo $form->textField($model, 'name', array('size' => 100, 'maxlength' => 150, 'class' => 't-field-text__input js-chage-name')); ?>
                                        <?php echo $form->error($model, 'name'); ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-select">
                                        <?php echo CHtml::label(Yii::t('default', 'Schools'), 'schools', array('class' => 't-field-select__label')); ?>
                                        <?php
                                            echo CHtml::dropDownList(
                                                'schools',
                                                $userSchools,
                                                CHtml::listData(SchoolIdentification::model()->findAll('situation=1 order by name'), 'inep_id', 'name'),
                                                array(
                                                    'multiple' => 'multiple',
                                                    'class' => 'select-search-on t-multiselect t-field-select__input'
                                                )
                                            ); ?>
                                </div>
                                <div class="t-field-checkbox">
                                        <?php echo CHtml::activeCheckbox($model, 'active', array('class'=>'t-field-checkbox__input')) ?>
                                        <?php echo CHtml::label(Yii::t('default', 'Active'), 'active', array('class' => 't-field-checkbox__label', 'id' => 'active-label')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h3>Dados de login</h3>
                        </div>
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-text">
                                    <?php echo $form->labelEx($model, 'username', array('class' => 't-field-text__label')); ?>
                                    <?php echo $form->textField($model, 'username', array('class' => 't-field-text__input')); ?>
                                    <?php echo $form->error($model, 'username'); ?>
                                </div>
                                <div class="t-field-text password-container">
                                    <?php echo $form->labelEx($model, 'password', array('class' => 't-field-text__label')); ?>
                                    <?php echo $form->passwordField($model, 'password', array('size' => 32, 'maxlength' => 32, 'class' => 't-field-text__input')); ?>
                                    <span class="t-icon-eye show-password-icon" id="showPassword"></span>
                                    <?php echo $form->error($model, 'password'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $this->endWidget(); ?>
                </div>
                <div class="row show--tablet">
                    <div class="t-buttons-container">
                        <div class="column clearfix">
                            <div id="createUser">
                                <a class="t-button-primary last save-school-button" type="submit">
                                    <?= $modelSchoolIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var form = '#Users_';
</script>

<style>
    #active-label {
        width: 40px !important;
    }

    input[type="checkbox"] {
        height: 30px !important;
    }

    .select2-container .select2-choices {
        max-height: 500px !important;
    }

    #s2id_schools .select2-choices {
        height: 100px !important;
    }

    .show-password-icon {
        cursor: pointer;
        font-size: 18px;
        position: absolute;
        left: calc(100% - 25px);
        top: 28px;
    }
    .password-container {
        position: relative;
    }
</style>
