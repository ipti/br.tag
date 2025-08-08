<?php
/**
 * @var ClassesController $this ClassesController
 * @var CActiveDataProvider $dataProvider CActiveDataProvider
 *
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/grades/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/grades/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

$script = "var getGradesUrl = '" . Yii::app()->createUrl('grades/getGrades') . "';";

$cs->registerScript('variables', $script, CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/grades.css?v=' . TAG_VERSION);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Grades'));

?>

<div class="main">
    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
        'action' => CHtml::normalizeUrl(['grades/saveGrades']),
    ]);
?>
    <div class="row">
        <div class="column clearleft">
            <h1>
                <?php echo Yii::t('default', 'Grades'); ?>
            </h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end">
                    <button type="button" id="close-grades-diary" class='t-button-secondary mobile-width calculate-media'>
                        Calc. média anual
                    </button>
        </div>
    </div>
    <div class="row">
    <div class="column clearfix align-items--center justify-content--start js-print-grades" style="display:none;">
                    <a href="" class='t-button-secondary mobile-width calculate-media'>
                    <span class="t-icon-printer"></span>
                    Imprimir
                    </a>
        </div>

    </div>
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="js-grades-alert alert"></div>
    <div class="js-grades-alert-multi alert hide"></div>
    <hr class="row t-separator" />
    <div class="row justify-content--space-between">
        <div class="column clearleft  is-four-fifths row wrap">
            <div class="column clearleft">
                <div class="t-field-select">
                    <?php echo CHtml::label(Yii::t('default', 'Classroom'), 'classroom', ['class' => 't-field-select__label--required no-wrap']); ?>
                    <select name="classroom" id="classroom" class="select-search-on t-field-select__input select2-container">
                    <option value="" selected>Selecione...</option>
                    <?php foreach ($classrooms as $classroom): ?>
                        <option data-isMulti="<?= TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk) ? '1' : '0' ?>" value="<?= $classroom['id'] ?>"><?= htmlspecialchars($classroom['name']) ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="column clearleft">
                <div class="t-field-select">
                    <?php echo CHtml::label(Yii::t('default', 'Discipline'), 'discipline', ['class' => 't-field-select__label--required no-wrap']); ?>
                    <?php
                echo CHtml::dropDownList(
    'discipline',
    '',
    [],
    [
        'key' => 'id',
        'class' => 'select-search-on t-field-select__input select2-container',
        'prompt' => 'Selecione...',
    ]
);
?>
                </div>
            </div>
            <div class="column clearleft js-stage-select js-hide-stage">
                    <?php echo CHtml::label(Yii::t('default', 'Etapa'), 'stage', ['class' => 't-field-select__label--required no-wrap'])?>
                    <?php
echo CHtml::dropDownList(
    'stage',
    '',
    [],
    [
        'key' => 'id',
        'class' => 'select-search-on t-field-select__input select2-container ',
        'prompt' => 'Selecione...',
    ]
);
?>
            </div>
            <div class="column clearleft">
                    <?php echo CHtml::label(Yii::t('default', 'Unidade'), 'unities', ['class' => 't-field-select__label--required no-wrap'])?>
                    <?php
echo CHtml::dropDownList(
    'unities',
    '',
    [],
    [
        'key' => 'id',
        'class' => 'select-search-on t-field-select__input select2-container',
        'prompt' => 'Selecione...',
    ]
);
?>
            </div>
            <div class="column clearleft">
                <img class="js-grades-loading" style="margin: 10px 20px;" height="30px" width="30px"
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
            </div>
        </div>
        <button id="save" class='t-button-primary mobile-width column hidden-print no-show'>
                    <?php echo Yii::t('default', 'Save') ?>
        </button>

    </div>
    <hr class="row t-separator" />
    <br>
    <h2 class="js-unity-title"></h2>
    <div class="js-grades-container"></div>
    <?php $this->endWidget(); ?>

</div>
<style>
    .js-hide-stage {
        display: none;
    }
    .no-wrap {
        white-space: nowrap;
    }
    .wrap {
        flex-wrap: wrap;
    }
</style>
