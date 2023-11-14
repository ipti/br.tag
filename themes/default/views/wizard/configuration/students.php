<?php
/* @var $this ClassroomConfigurationControler */
/* @var $form CActiveForm */
/* @var $title String */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js', CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
));

$this->breadcrumbs = array(
    Yii::t('default', 'Student Configuration'),
);
$this->setPageTitle("TAG - " . Yii::t('default', 'Student Configuration'));
$lastYear = (Yii::app()->user->year - 1);
$year = (Yii::app()->user->year);

$model = new StudentEnrollment();
?>
<div class="main">
    <div class="mobile-row ">
        <div class="column clearleft">
            <h1><?php echo $title; ?></h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop" id="save">
            <?php echo CHtml::htmlButton(Yii::t('default', 'Save'), array('class' => 't-button-primary  last', 'type' => 'submit')); ?>
        </div>
    </div>
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
        <div class="widget widget-tabs border-bottom-none">
            <div class="widget-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="student">
                        <div class="row">
                            <div class="column is-two-fifths clearleft">
                                <div class="t-field-select t-multiselect clear-margin--top" id="classrooms-select">
                                    <?php echo Chtml::label('Turma de ' . $lastYear . ':', '', array('class' => 't-field-select__label--required')); ?>
                                    <?php echo chtml::dropDownList('Classrooms', "", CHtml::listData(Classroom::model()->findAll(
                                        "school_year = :sy AND school_inep_fk = :si order by name",
                                        array("sy" => (Yii::app()->user->year - 1), "si" => yii::app()->user->school)
                                    ), 'id', 'name'), array(
                                        'class' => 'select-search-on t-field-select__input select2-container',
                                        'multiple' => 'multiple',
                                        'placeholder' => Yii::t('default', 'Select Classrooms'),
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="column clearleft--on-mobile is-two-fifths">
                                <div class="t-field-select" id="oneClassrom-select">
                                    <?php echo Chtml::label('Turma de ' . Yii::app()->user->year . ':', '', array('class' => 't-field-select__label--required')); ?>
                                    <?php echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll(
                                        "school_year = :sy AND school_inep_fk = :si order by name",
                                        array("sy" => (Yii::app()->user->year), "si" => yii::app()->user->school)
                                    ), 'id', 'name'), array("prompt" => "Selecione uma Turma", 'class' => 'select-search-on t-field-select__input select2-container')); ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row show--tablet">
                            <div class="t-buttons-container">
                                <div class="column clearfix" >
                                    <?php echo CHtml::htmlButton(Yii::t('default', 'Save'), array('class' => 't-button-primary full--width last', 'type' => 'submit')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#Students").select2({
            width: 'resolve',
            maximumSelectionSize: 13,
            minimumInputLength: 5
        });
    });
    var formEnrollment = '#StudentEnrollment_';
    var updateDependenciesURL = "<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>";
</script>

<style>
    .select2-choice {
        height: 44px !important;
    }
    .select2-chosen {
        margin-top: 7px !important;
    }
    .select2-choice .select2-arrow b {
        margin-top: 8px !important;
    }
</style>
