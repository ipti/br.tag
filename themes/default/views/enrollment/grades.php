<?php
/** 
* @var ClassesController $this ClassesController
* @var CActiveDataProvider $dataProvider CActiveDataProvider 
*
*/

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/grades/_initialization.js', CClientScript::POS_END);

$script = "var getGradesUrl = '" . Yii::app()->createUrl('enrollment/getGrades') . "';";

$cs->registerScript('variables', $script, CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/grades.css');
$this->setPageTitle('TAG - ' . Yii::t('default', 'Grades'));
$cs->registerCssFile($themeUrl . '/css/template2.css');
?>

<div class="main">
    <?php 
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'classes-form',
            'enableAjaxValidation' => false,
            'action' => CHtml::normalizeUrl(array('enrollment/saveGrades')),
        ));
    ?>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Grades'); ?></h1>
            <div class="buttons span9">
                <button id="save"
                   class='t-button-primary  hidden-print no-show'><?php echo Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <div class="filter-bar margin-bottom-none">
        <div>
            <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'small-label control-label required', 'style' => 'width: 53px;')); ?>
            <?php
            echo CHtml::dropDownList('classroom', '', $classrooms, array(
                'key' => 'id',
                'class' => 'select-search-on control-input classroom-input',
                'prompt' => 'Selecione...',
                ));
            ?>
        </div>
        <div>
            <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'discipline', array('class' => 'control-label required', 'style' => 'width: 75px;')); ?>
            <?php
            echo CHtml::dropDownList('discipline', '', array(), array(
                'key' => 'id',
                'class' => 'select-search-on control-input discipline-input',
                'prompt' => 'Selecione...',
            ));
            ?>
        </div>
        <i class="js-grades-loading fa fa-spin fa-spinner"></i>
        <div class="no-disciplines-guide" style="display: none;">Algumas Disciplinas não aparecem na tabela? <span class="no-disciplines-link">Saiba mais</span>.</div>
    </div>
    <br>
    <div class="js-grades-alert alert"></div>
    <div class="js-grades-container"></div>
    <div class="js-save-grades-loading-gif">
        <i class="fa fa-spin fa-spinner fa-3x"></i>
    </div>
<?php $this->endWidget(); ?>

</div>