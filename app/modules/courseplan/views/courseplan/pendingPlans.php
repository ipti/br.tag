<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

$this->menu=array(
	array('label'=>'Create CoursePlan', 'url'=>array('index')),
	array('label'=>'List ClassPlan', 'url'=>array('admin')),
);

?>

<?php
/* @var $this CoursePlanController */
/* @var $dataProvider CActiveDataProvider */


$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));

$this->menu = array(
    array('label' => 'List Courseplan', 'url' => array('index')),
    array('label' => '')
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v='.TAG_VERSION, CClientScript::POS_END);

?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Pending Course Plan') ?></h1>
            <div class="t-buttons-container">
                <a  href="<?php echo Yii::app()->createUrl('courseplan/courseplan/index') ?>"
                class="t-button-primary"><?= Yii::t('default', 'Course Plan') ?></a>
            </div>
        </div>
    </div>

    <div id="select-container" class="tablet-row align-items--center-on-desktop">
        <div class="mobile-row">
            <div class="column clearleft">
                <div class="t-field-select instructor-container">
                    <?php echo CHtml::label(yii::t('default', 'Instructor'), 'instructor', array('class' => 't-field-select__label--required'));?>
                    <select class="select-search-on t-field-select__input" id="instructor" style="min-width: 185px;">
                        <option value="">Selecione o Professor</option>
                        <?php
                            $instructors = array_map("unserialize", array_unique(array_map("serialize", $instructors)));
                            foreach ($instructors as $instructor) :
                        ?>
                            <option value="<?=$instructor['id']?>"><?=$instructor['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="column clearleft">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Stage'), 'stage', array('class' => 't-field-select__label--required'));?>
                        <select class="select-search-on t-field-select__input" id="stage" style="min-width: 185px;">
                            <option value="">Selecione a etapa</option>
                            <?php
                                $stages = array_map("unserialize", array_unique(array_map("serialize", $stages)));
                                foreach ($stages as $stage) :
                            ?>
                                <option value="<?=$stage['id']?>"><?=$stage['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <!-- diciplina -->
                </div>
                <div class="column discipline-container hide">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Discipline'),
                            'month', array('class' => 't-field-select__label--required')); ?>
                        <select class="select-search-on t-field-select__input" id="discipline"
                                style="min-width: 185px;">
                        </select>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <div class="tag-inner">
        <div class="columnone" style="padding-right: 1em">
            <div class="widget clearmargin">
                <div class="alert courseplan-alert <?= Yii::app()->user->hasFlash('success') ? "alert-success" : "no-show" ?>"><?php echo Yii::app()->user->getFlash('success') ?></div>
                    <div class="pending_courseplan_div"></div>
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>
</div>

