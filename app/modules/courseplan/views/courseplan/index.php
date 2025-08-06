<?php
/** @var $this DefaultController */

$this->breadcrumbs = [
    $this->module->id,
];

$this->menu = [
    ['label' => 'Create CoursePlan', 'url' => ['index']],
    ['label' => 'List Pending ClassPlan', 'url' => ['admin']],
];

?>

<?php
/** @var $this CoursePlanController */
/** @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));

$this->menu = [
    ['label' => 'List Pending Courseplan', 'url' => ['index']],
];

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
// $cs->registerScriptFile($baseScriptUrl . '/_initialization.js');

// $baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
// $themeUrl = Yii::app()->theme->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);

?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Course Plan'); ?></h1>
            <div class="t-buttons-container">
                <?php if (!Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id) && !TagUtils::isManager()): ?>
                    <a href="<?php echo Yii::app()->createUrl('courseplan/courseplan/create') ?>"
                        class="t-button-primary"><?= Yii::t('default', 'Create Plan'); ?> </a>
                    <br />
                <?php endif ?>
                <?php if (!Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)): ?>
                    <a href="<?php echo Yii::app()->createUrl('courseplan/courseplan/pendingPlans') ?>"
                        class="t-button-primary"><?= Yii::t('default', 'Pendent Plan') ?></a>
                    <br />
                <?php endif ?>
            </div>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    <div id="select-container" class="tablet-row align-items--center-on-desktop">
        <div class="mobile-row">
            <div class="column clearleft">
                <div class="t-field-select">
                    <?php echo CHtml::label(Yii::t('default', 'Stage'), 'stage', ['class' => 't-field-select__label--required']); ?>
                    <select class="select-search-on t-field-select__input" id="stage" style="min-width: 185px;">
                        <option value="">Selecione a etapa</option>
                        <?php
                        $stages = array_map('unserialize', array_unique(array_map('serialize', $stages)));
foreach ($stages as $stage):
    ?>
                            <option value="<?= $stage['id'] ?>"><?= $stage['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- diciplina -->
            </div>
            <div class="column discipline-container hide">
                <div class="t-field-select">
                    <?php echo CHtml::label(
        Yii::t('default', 'Discipline'),
        'month',
        ['class' => 't-field-select__label--required']
    ); ?>
                    <select class="select-search-on t-field-select__input" id="discipline" style="min-width: 185px;">
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="columnone" style="padding-right: 1em">
            <div class="widget clearmargin">
                <div
                    class="alert courseplan-alert <?= Yii::app()->user->hasFlash('success') ? 'alert-success' : 'no-show' ?>">
                    <?php echo Yii::app()->user->getFlash('success') ?></div>
                <div class="courseplan_table_div"></div>
            </div>
        </div>
        <div class="columntwo"></div>
    </div>
</div>

