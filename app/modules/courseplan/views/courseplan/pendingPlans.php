<?php
/** @var $this DefaultController */

$this->breadcrumbs = [
    $this->module->id,
];

$this->menu = [
    ['label' => 'Create CoursePlan', 'url' => ['index']],
    ['label' => 'List ClassPlan', 'url' => ['admin']],
];

?>

<?php
/** @var $this CoursePlanController */
/** @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));

$this->menu = [
    ['label' => 'List Courseplan', 'url' => ['index']],
    ['label' => '']
];

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);

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
        <div class="row">
            <div class="column clearleft">
                <div class="t-field-select instructor-container">
                    <?php echo CHtml::label(Yii::t('default', 'Instructor'), 'instructor', ['class' => 't-field-select__label--required']); ?>
                    <select class="select-search-on t-field-select__input" id="instructor" style="min-width: 185px;">
                        <option value="">Selecione o Professor</option>
                        <?php
                            $instructors = array_map('unserialize', array_unique(array_map('serialize', $instructors)));
foreach ($instructors as $instructor) :
    ?>
                            <option value="<?=$instructor['id']?>"><?=$instructor['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="t-field-select row">
                    <div class="t-field-select">
                        <?php echo CHtml::label(Yii::t('default', 'Stage'), 'stage', ['class' => 't-field-select__label--required']); ?>
                        <select class="select-search-on t-field-select__input" id="stage" style="min-width: 185px;">
                            <option value="">Selecione a etapa</option>
                            <?php
            $stages = array_map('unserialize', array_unique(array_map('serialize', $stages)));
foreach ($stages as $stage) :
    ?>
                                <option value="<?=$stage['id']?>"><?=$stage['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- diciplina -->
                    <div class="t-field-select discipline-container hide">
                        <?php echo CHtml::label(
        Yii::t('default', 'Discipline'),
        'month',
        ['class' => 't-field-select__label--required']
    ); ?>
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
                <div class="alert courseplan-alert <?= Yii::app()->user->hasFlash('success') ? 'alert-success' : 'no-show' ?>"><?php echo Yii::app()->user->getFlash('success') ?></div>
                    <div class="pending_courseplan_table_div"></div>
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>
</div>

