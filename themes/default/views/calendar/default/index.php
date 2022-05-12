<?php
/* @var $this calendar.defaultController
 * @var $modelCalendar Calendar
 * @var $modelEvent CalendarEvent
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.1');
$cs->registerScriptFile($baseScriptUrl . '/common/js/index.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('calendarModule.index', 'Calendar'));
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= yii::t('calendarModule.index', 'Calendar') ?></h3>
        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
            <div class="buttons span9">
                <button class="btn btn-primary btn-icon glyphicons circle_plus new-calendar-button">
                    <i></i><?= yii::t('calendarModule.index', "New Calendar") ?>
                </button>
            </div>
        <?php } ?>
    </div>
</div>
<div class="innerLR home">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
            </div>
        </div>
    <?php endif ?>
    <?php
    $this->widget('calendar.components.calendarWidget', [
        'calendar' => $calendar
    ]);
    ?>

</div>