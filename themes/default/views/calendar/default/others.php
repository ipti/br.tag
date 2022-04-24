<?php
/* @var $this calendar.defaultController
 * @var $calendars Calendar[]
 * @var $modelEvent CalendarEvent
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$setActiveUrl = Yii::app()->createUrl("/calendar/default/setActive");

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/others.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('calendarModule.others', 'Other Calendars'));
?>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        </div>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= yii::t('calendarModule.others', 'Calendars') ?></h3>

        <div class="buttons span9">
            <a href="<?= yii::app()->createUrl("calendar/default/index") ?>"
               class="btn btn-primary btn-icon glyphicons calendar">
                <i></i><?= yii::t('calendarModule.others', "Actual Calendar") ?>
            </a>
            <button data-toggle="modal" data-target="#myNewCalendar"
                    class="btn btn-primary btn-icon glyphicons circle_plus">
                <i></i><?= yii::t('calendarModule.index', "New Calendar") ?>
            </button>
        </div>
    </div>
</div>
<div class="innerLR home">
    <div class="accordion" id="calendars">
        <?php
        foreach ($calendars as $calendar):
            ?>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <div class="accordion-toggle">
                        <a class="span12" data-toggle="collapse" data-parent="#calendars"
                           href="#collapse<?= $calendar->id ?>">
                            <?= $calendar->title ?>
                        </a>
                        <span class="text-right pull-right change-active"
                            <?php if (!$calendar->actual): ?>
                                data-toggle="modal"
                                data-target="#setActual"
                            <?php endif; ?>
                              data-id="<?= $calendar->id ?>">

                            <?= $calendar->actual ? "<i class='fa fa-check'></i>" : "<i class='fa fa-history'></i>" ?>
                        </span>
                        <?php if (!$calendar->actual): ?>
                            <span class="text-right pull-right remove-calendar" data-toggle="modal"
                                  data-target="#removeCalendar" data-id="<?= $calendar->id ?>">
                                <i class="fa fa-remove"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="collapse<?= $calendar->id ?>" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <?php
                        $this->widget('calendar.components.calendarWidget', [
                            'calendar' => $calendar,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>


<!-- Modals -->
<div class="modal fade" id="setActual" tabindex="-1" role="dialog" aria-labelledby="Set Actual Calendar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel"><?= yii::t("calendarModule.others", "Set Actual Calendar") ?></h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <?= yii::t("calendarModule.others", "Are you sure?") ?>
                        <input type="hidden" name="id" id="calendar_id" value="-1"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                    <?= CHtml::button(yii::t("calendarModule.others", "Confirm"), array('class' => 'btn btn-primary', 'submit' => array('/calendar/default/setActual'))); ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="removeCalendar" tabindex="-1" role="dialog" aria-labelledby="Remove Calendar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel"><?= yii::t("calendarModule.others", "Remove Calendar") ?></h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <?= yii::t("calendarModule.others", "Are you sure?") ?>
                        <input type="hidden" name="calendar_removal_id" id="calendar_removal_id" value="-1"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                    <?= CHtml::button(yii::t("calendarModule.others", "Confirm"), array('class' => 'btn btn-primary', 'submit' => array('/calendar/default/removeCalendar'))); ?>
                </div>
            </form>
        </div>
    </div>
</div>