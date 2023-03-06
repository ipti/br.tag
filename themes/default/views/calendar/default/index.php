<?php
/** 
 * @var DefaultController $this calendar.defaultController
 * @var Calendar $modelCalendar Calendar
 * @var CalendarEvent $modelEvent CalendarEvent
 * @var CClientScript $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.1');
$cs->registerScriptFile($baseScriptUrl . '/common/js/index.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$this->setPageTitle('TAG - ' . Yii::t('calendarModule.index', 'Calendar'));
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?= yii::t('calendarModule.index', 'Calendar') ?></h1>
            <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                <div class="t-buttons-container">
                    <a class="t-button-primary new-calendar-button">
                        <?= yii::t('calendarModule.index', "New Calendar") ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="tag-inner home">
        <?php
        $this->widget('calendar.components.calendarWidget', [
            'calendar' => $calendar
        ]);
        ?>
    </div>
</div>