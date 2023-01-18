<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/grades/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/grades/functions.js', CClientScript::POS_END);

$script = "var getGradesUrl = '" . Yii::app()->createUrl('enrollment/getGrades') . "';";

$cs->registerScript('variables', $script, CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/grades.css');

$this->setPageTitle('TAG - ' . Yii::t('default', 'Grades'));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(array('enrollment/saveGrades')),
));
$cs->registerCssFile($themeUrl . '/css/template2.css');
?>

    <div class="row-fluid hidden-print">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Grades'); ?></h3>
            <div class="buttons span9">
                <button id="save"
                   class='tag-button small-button hidden-print no-show'><?php echo Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="innerLR">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>

        <div class="filter-bar margin-bottom-none">
            <div>
                <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'small-label control-label required')); ?>
                <?php
                echo CHtml::dropDownList('classroom', '', $classrooms, array(
                    'key' => 'id',
                    'class' => 'select-search-on control-input',
                    'prompt' => 'Selecione a turma',
                    ));
                ?>
            </div>
            <div class="no-disciplines-guide">Algumas Disciplinas não aparecem na tabela? <span class="no-disciplines-link">Saiba mais</span>.</div>
        </div>
        <br>
        <div class="alert-no-students alert alert-warning">Não há estudantes cadastrados na turma.</div>
        <div class="alert-no-disciplines alert alert-warning"></div>
        <div class="classroom widget widget-tabs widget-tabs-vertical row row-merge hide">
            <div class="students widget-head span3">
                <ul></ul>
            </div>
            <div class="grades widget-body span9">
                <div class="tab-content"></div>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>