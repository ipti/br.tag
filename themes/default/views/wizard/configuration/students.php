<?php
/* @var $this ClassroomConfigurationControler */
/* @var $form CActiveForm */
/* @var $title String */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js', CClientScript::POS_END);

$cs->registerCssFile($themeUrl . '/css/template2.css');
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

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic">
            <?php echo $title; ?>
        </h3>

    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="student">
                    <div class="row-fluid">	
                        <div class="span5">
                            <div class="control-group">
                                <?php echo Chtml::label('Turma de '.$lastYear.':','', array('class' => 'control-label ml-10 required')); ?>
                                <div class="controls">
                                    <?php echo chtml::dropDownList('Classrooms', "", CHtml::listData(Classroom::model()->findAll("school_year = :sy AND school_inep_fk = :si order by name",
                                            array("sy" => (Yii::app()->user->year-1), "si"=>yii::app()->user->school)), 'id', 'name'), array(
                                        'class' => 'select-search-on',
                                        'multiple' => 'multiple',
                                        'placeholder' => Yii::t('default', 'Select Classroom'),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="span5">
                            <div class="control-group">
                                <?php echo Chtml::label('Turma de '.Yii::app()->user->year.':','', array('class' => 'control-label ml-10 required')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll("school_year = " . Yii::app()->user->year . " AND school_inep_fk=".yii::app()->user->school." order by name", array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma Turma", 'class' => 'select-search-on')); ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span1">
                            <div class="tag-buttons-container buttons">
                                <div class="control-group">
                                    <?php echo CHtml::htmlButton( Yii::t('default', 'Save'), array('class' => 'tag-button small-button last', 'type' => 'submit')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span style="clear:both;display:block"></span>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#Students").select2({width: 'resolve', maximumSelectionSize: 13, minimumInputLength: 5});
    });
    var formEnrollment = '#StudentEnrollment_';
    var updateDependenciesURL = "<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>";

</script>