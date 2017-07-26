<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$baseUrl = Yii::app()->baseUrl;
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
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Grades'); ?></h3>  
        <div class="buttons span9">
            <a id="save" class='btn btn-icon btn-primary glyphicons circle_ok hidden-print'><?php echo Yii::t('default', 'Save') ?><i></i></a>
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
            <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 'control-label')); ?>
            <?php
            echo CHtml::dropDownList('classroom', '', $classrooms, array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Selecione a turma',
                'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('classes/getDisciplines'),
                    'update' => '#disciplines',
            )));
            ?>
        </div>
    </div>
    <br>
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