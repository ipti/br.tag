<?php
/**
 * @var ClassesController $this ClassesController
 * @var CActiveDataProvider $dataProvider CActiveDataProvider
 *
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl.'/js/grades/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl.'/js/grades/functions.js', CClientScript::POS_END);

$script = "var getGradesUrl = '".Yii::app()->createUrl('grades/getGrades')."';";

$cs->registerScript('variables', $script, CClientScript::POS_END);
$cs->registerCssFile($baseUrl.'/css/grades.css');
$this->setPageTitle('TAG - '.Yii::t('default', 'Grades'));

?>

<div class="main">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
        'action' => CHtml::normalizeUrl(array('grades/saveGrades')),
    ));
    ?>
    <div class="row">
        <div class="column clearleft">
            <h1>
                <?php echo Yii::t('default', 'Grades'); ?>
            </h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end">
            <div class="row justify-content--end">
                <button type="button" id="close-grades-diary" class='t-button-secondary calculate-media'>Calc. média anual</button>
            </div>
        </div>
    </div>
    <?php if(Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="js-grades-alert alert"></div>
    <hr class="row t-separator" />
    <div class="row justify-content--space-between">
        <div class="column clearleft  is-four-fifths row">
            <div class="column  clearleft ">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 't-field-select__label--required')); ?>
                    <?php
                    echo CHtml::dropDownList('classroom', '', $classrooms, array(
                        'key' => 'id',
                        'class' => 'select-search-on t-field-select__input select2-container',
                        'prompt' => 'Selecione...',
                    )
                    );
                    ?>
                </div>
            </div>
            <div class="column">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline', array('class' => 't-field-select__label--required')); ?>
                    <?php
                    echo CHtml::dropDownList('discipline', '', array(), array(
                        'key' => 'id',
                        'class' => 'select-search-on t-field-select__input select2-container',
                        'prompt' => 'Selecione...',
                    )
                    );
                    ?>
                </div>
            </div>
            <div class="column ">
                <img class="js-grades-loading" style="display:none;margin: 10px 20px;" height="30px" width="30px"
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
            </div>
        </div>
        <button id="save" class='t-button-primary column hidden-print no-show'>
                    <?php echo Yii::t('default', 'Save') ?>
        </button>

    </div>
    <hr class="row t-separator" />
    <br>
    <div class="js-grades-container"></div>
    <?php $this->endWidget(); ?>

</div>
