<?php
/* @var $this calendar.defaultController
 * @var $modelCalendar Calendar
 * @var $modelEvent CalendarEvent
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.1');
$cs->registerScriptFile($baseScriptUrl . '/common/js/index.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$this->setPageTitle('TAG - ' . Yii::t('calendarModule.index', 'Calendar'));
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= yii::t('calendarModule.index', 'Calendar') ?></h3>
        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
            <div class="buttons span9">
                <a class="tag-button medium-button new-calendar-button">
                    <?= yii::t('calendarModule.index', "New Calendar") ?>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
<div class="innerLR home">
    <?php
    $this->widget('calendar.components.calendarWidget', [
        'calendar' => $calendar
    ]);
    ?>

</div>