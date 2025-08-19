<?php
/**
* @var ClassesController $this ClassesController
* @var CActiveDataProvider $dataProvider CActiveDataProvider
*
*/

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/grades/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/grades/functions.js?v='.TAG_VERSION, CClientScript::POS_END);

$script = "var getGradesUrl = '" . Yii::app()->createUrl('enrollment/getGrades') . "';";

$cs->registerScript('variables', $script, CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/grades.css');
$this->setPageTitle('TAG - ' . Yii::t('default', 'Grades'));

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
            <div class="buttons row grades-buttons">
                <!--<button class='t-button-primary calculate-media'>Calcular Média</button>-->
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
    <div class="js-grades-alert alert"></div>
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
            <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'discipline', array('class' => 'control-label required', 'style' => 'width: 100%;')); ?>
            <?php
            echo CHtml::dropDownList('discipline', '', array(), array(
                'key' => 'id',
                'class' => 'select-search-on control-input discipline-input',
                'prompt' => 'Selecione...',
            ));
            ?>
        </div>
        <img class="js-grades-loading"  style="display:none;margin: 10px 20px;overflow-y: auto;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
    </div>
    <br>
    <div class="js-grades-container"></div>
<?php $this->endWidget(); ?>

</div>
