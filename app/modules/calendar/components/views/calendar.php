<?php
/**
 * @var $calendar Calendar
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$getEventUrl = Yii::app()->createUrl("/calendar/default/event");

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/common/js/calendar.js?v=1.0', CClientScript::POS_END);
$cs->registerScript("vars", "
var GET_EVENT_URL = '$getEventUrl';
", CClientScript::POS_BEGIN);


if (!function_exists('isInInterval')) {
    /**
     * @param $start DateTime
     * @param $end DateTime
     * @param $day Integer
     * @param $month Integer
     * @return bool {True} If the $day and $month are in the interval.
     */
    function isInInterval($start, $end, $day, $month)
    {
        $sd = $start->format('d');
        $sm = $start->format('n');
        $ed = $end->format('d');
        $em = $end->format('n');

        if ($month >= $sm && $month <= $em) {
            if ($month == $sm && $month == $em) {
                return ($day >= $sd && $day <= $ed);
            }
            if ($month == $sm) {
                return ($day >= $sd);
            }
            if ($month == $em) {
                return ($day <= $ed);
            }
            return true;
        } else {
            return false;
        }
    }
}

function sortTranslatedName($a, $b)
{
    return strcmp(yii::t('calendarModule.labels', $a->name), yii::t('calendarModule.labels', $b->name));
}

$types = CalendarEventType::model()->findAll();
usort($types, "sortTranslatedName");

$calendars = Calendar::model()->findAll("YEAR(start_date) = :year", [":year" => Yii::app()->user->year]);

?>

<div class="no-calendars-alert alert alert-warning <?= $calendars == null ? "" : "no-show" ?>">Nenhum Calendário cadastrado para <?= Yii::app()->user->year ?>.</div>
<div class="accordion" id="calendars">
    <?php foreach ($calendars as $calendar): ?>
        <?php
        $start = new DateTime($calendar->start_date);
        $end = new DateTime($calendar->end_date);

        $interval = $start->diff($end);

        $total = $interval->m;
        $date = new DateTime($start->format("Y-m-d"));

        $events = [];
        for ($i = 1; $i <= 12; $i++) $events[$i] = [];
        foreach ($calendar->calendarEvents as $event) {
            if ($event->school_fk == null || $event->school_fk == Yii::app()->user->school) {
                $start_event = new DateTime($event->start_date);
                $end_event = new DateTime($event->end_date);
                $mStart = $start_event->format('n');
                $mEnd = $end_event->format('n');

                for ($i = $mStart; $i <= $mEnd; $i++) {
                    $events[$i][] = $event;
                }
            }
        }
        ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <div class="accordion-toggle" data-toggle="collapse" data-parent="#calendars"
                     href="#collapse<?= $calendar->id ?>">
                    <a class="accordion-title span12"><?= $calendar->title ?></a>
                    <span class="text-right pull-right remove-calendar" data-toggle="tooltip" data-placement="top"
                          data-original-title="Remover Calendário" data-id="<?= $calendar->id ?>">
                                <i class="fa fa-remove"></i>
                            </span>
                    <span class="text-right pull-right edit-calendar-title" data-toggle="tooltip" data-placement="top"
                          data-original-title="<?= yii::t('index', 'Editar Título do Calendário') ?>"
                          data-id="<?= $calendar->id ?>">
                                <i class="fa fa-edit"></i>
                            </span>
                    <span class="text-right pull-right change-calendar-status" data-toggle="tooltip"
                          data-placement="top"
                          data-original-title="<?= $calendar->available ? "Indisponibilizar Calendário" : "Disponibilizar Calendário" ?>"
                          data-id="<?= $calendar->id ?>">
                                <i class="fa fa-eye<?= $calendar->available ? "" : "-slash" ?>"></i>
                            </span>
                    <span class="text-right pull-right show-stages" data-toggle="tooltip" data-placement="top"
                          data-original-title="Visualizar Etapas do Calendário" data-id="<?= $calendar->id ?>">
                                <i class="fa fa-question-circle-o"></i>
                            </span>
                </div>
            </div>
            <div id="collapse<?= $calendar->id ?>" class="accordion-body collapse">
                <div class="accordion-inner">
                    <div class="centered-loading-gif">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 calendar-container" data-id="<?= $calendar->id ?>">
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="row-fluid calendar"
                                     data-year="<?= date('Y', strtotime($calendar->start_date)) ?>"
                                     data-id="<?= $calendar->id ?>">
                                    <div class="span12">
                                        <?php for ($j = 0; $j < 4; $j++):
                                            if ($date->diff($end)->invert) break;
                                            $month = $date->format("F");
                                            $m = $date->format("n");
                                            $y = $date->format("Y");
                                            ?>
                                            <div class="span3 img-polaroid">

                                                <div class="row-fluid">
                                                    <div class="span12 center">
                                                        <h4><?= yii::t('calendarModule.labels', $month) ?></h4>
                                                    </div>
                                                </div>
                                                <div class="row-fluid">
                                                    <div class="span12 center calendar-header">
                                                        <div class="span1-7">D</div>
                                                        <div class="span1-7">S</div>
                                                        <div class="span1-7">T</div>
                                                        <div class="span1-7">Q</div>
                                                        <div class="span1-7">Q</div>
                                                        <div class="span1-7">S</div>
                                                        <div class="span1-7">S</div>
                                                    </div>
                                                </div>
                                                <?php
                                                $monthDays = new DateTime(date("d-m-Y", mktime(0, 0, 0, $date->format('m'), 1, $date->format('Y'))));
                                                $nextMonth = new DateTime(date("d-m-Y", mktime(0, 0, 0, (int)$date->format('m') + 1, 1, $date->format('Y'))));
                                                $firstWeekDay = $monthDays->format('w');
                                                $totalDays = $monthDays->diff($nextMonth)->days;
                                                $day = 1;
                                                $html = "";
                                                for ($week = 0; $week < 6; $week++) {
                                                    $html .= '<div class="row-fluid"> <div class="span12 center">';
                                                    for ($weekDay = 0; $weekDay < 7; $weekDay++) {
                                                        $content = "";
                                                        $beforeContent = "";
                                                        $afterContent = "";
                                                        $class = "";

                                                        if (($week == 0 && $weekDay < $firstWeekDay) || $day > $totalDays) {
                                                            $content = "--";
                                                        } else {
                                                            $beforeContent = "<a class='change-event' data-toggle='modal' data-target='#myChangeEvent' data-id='-1' data-year='$y'  data-month='$m' data-day='$day' >";
                                                            if ($day < 10) {
                                                                $content = "0";
                                                            }
                                                            foreach ($events[$m] as $event) {
                                                                /** @var $event CalendarEvent */
                                                                $start_event = new DateTime($event->start_date);
                                                                $end_event = new DateTime($event->end_date);
                                                                //Verifica se esta dentro do intervalo de datas
                                                                if (isInInterval($start_event, $end_event, $day, $m)) {
                                                                    $beforeContent = "<a class='change-event' data-toggle='tooltip' data-placement='top' data-original-title='" . $event->name . "' data-year='$y'  data-id='$event->id' data-month='$m' data-day='$day' >";
                                                                    $class .= " calendar-" . $event->calendarEventTypeFk->color . " ";
                                                                    $beforeContent .= "<i class=' calendar-icon fa " . $event->calendarEventTypeFk->icon . "'></i>";
                                                                    break;
                                                                }
                                                            }

                                                            $content .= $day++;
                                                            $afterContent = "</a>";
                                                        }

                                                        if ($weekDay == 0) {
                                                            $class .= "sunday ";
                                                        }
                                                        $class .= "span1-7 ";
                                                        $html .= "<div class='$class'>$beforeContent<div class='calendar-text '>$content</div>$afterContent</div>";

                                                    }
                                                    $html .= '</div> </div>';
                                                }
                                                echo $html;
                                                ?>
                                            </div>
                                            <?php
                                            $date->add(date_interval_create_from_date_string("1 month"));
                                        endfor; ?>
                                    </div>
                                </div>
                                <br>
                            <?php
                            endfor; ?>
                        </div>
                    </div>
                    <br>
                    <div class="row-fluid">
                        <div class="span12 img-polaroid">

                            <div class="calendar-subtitles-title">
                                <h4><?= yii::t('calendarModule.labels', "Subtitles") ?></h4>
                            </div>
                            <?php
                            $html = '';
                            foreach ($types as $type) {
                                /**@var $type CalendarEventType */
                                $html .= '<div class="span3 calendar-subtitles calendar-' . $type->color . '">'
                                    . '<i class="fa ' . $type->icon . '"></i>&nbsp;'
                                    . '<span>' . yii::t('calendarModule.labels', $type->name) . '</span>'
                                    . '</div>';


                            }
                            echo $html; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="modal fade" id="myNewCalendar" tabindex="-1" role="dialog" aria-labelledby="New Calendar">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?= yii::t("calendarModule.index", "New Calendar") ?></h4>
                </div>
                <?php
                /* @var $form CActiveForm */
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'createCalendar',
                    'action' => '?r=/calendar/default/create',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-vertical',
                    ),
                ));
                ?>
                <div class="centered-loading-gif">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div class="modal-body">
                    <div class="error-calendar-event alert alert-error no-show"></div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?= chtml::label(yii::t("calendarModule.labels", "Title Required"), "title", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <input type="text" class="create-calendar-title span12">
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid stages-container">
                        <div class="span12">
                            <?= chtml::label(yii::t("calendarModule.labels", "Stages"), "stages", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <?= CHtml::dropDownList("stages", [], CHtml::listData(EdcensoStageVsModality::model()->findAll(), "id", "name"), [
                                    "multiple" => "multiple", "class" => "select-search-on span12"
                                ]) ?>
                                <div class="add-stages-options">Atalho: <span class="add-fundamental-menor">Fundamental Menor</span>
                                    | <span class="add-fundamental-maior">Fundamental Maior</span> | <span
                                            class="remove-stages">Remover</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?= chtml::label(yii::t("calendarModule.labels", "Copy From"), "copy", array('class' => 'control-label')); ?>
                            <div class="form-control">
                                <?= chtml::dropDownList("copy", "",
                                    chtml::listData(Calendar::model()->findAll(), "id", "title"),
                                    array('prompt' => yii::t("calendarModule.labels", 'Select calendar base'), 'class' => 'span9')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                    <button type="button"
                            class="btn btn-primary create-calendar"><?= yii::t("calendarModule.index", "Save") ?></button>
                </div>
                <?php
                $this->endWidget();
                ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-calendar-title-modal" tabindex="-1" role="dialog"
         aria-labelledby="Edit Calendar Title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?= yii::t("calendarModule.index", "Edit Calendar Title") ?></h4>
                </div>
                <?php
                /* @var $form CActiveForm */
                $editCalendar = new Calendar();
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'createCalendar',
                    'action' => '?r=/calendar/default/editCalendarTitle',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-vertical',
                    ),
                ));
                ?>
                <div class="centered-loading-gif">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div class="modal-body">
                    <div class="error-calendar-event alert alert-error no-show"></div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?= chtml::label(yii::t("calendarModule.labels", "Title Required"), "copy", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <?= $form->hiddenField($editCalendar, "id") ?>
                                <?= $form->textField($editCalendar, "title", ['class' => 'span12']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                    <button type="button"
                            class="btn btn-primary edit-calendar-title-button"><?= yii::t("calendarModule.index", "Save") ?></button>
                </div>
                <?php
                $this->endWidget();
                ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myChangeEvent" tabindex="-1" role="dialog" aria-labelledby="Change Event">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?= yii::t("calendarModule.index", "Change Event") ?></h4>
                </div>
                <?php
                /* @var $form CActiveForm */
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'createEvent',
                    'action' => '?r=/calendar/default/changeEvent',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-vertical',
                    ),
                ));
                ?>
                <div class="centered-loading-gif">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div class="modal-body">
                    <div class="error-calendar-event alert alert-error no-show"></div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?= chtml::label(yii::t("calendarModule.labels", "Name"), "title", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <input type="hidden" class="selected-calendar-current-year">
                                <input type="hidden" id="CalendarEvent_id">
                                <input type="hidden" id="CalendarEvent_calendar_fk">
                                <input type="text" id="CalendarEvent_name" class="span12">
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <?= chtml::label(yii::t("calendarModule.labels", "Start Date"), "title", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <input type="date" id="CalendarEvent_start_date" class="span12">
                            </div>
                        </div>
                        <div class="span6">
                            <?= chtml::label(yii::t("calendarModule.labels", "End Date"), "title", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <input type="date" id="CalendarEvent_end_date" class="span12">
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?= chtml::label(yii::t("calendarModule.labels", "Event Type"), "title", array('class' => 'control-label required')); ?>
                            <div class="form-control">
                                <select class="span6" id="CalendarEvent_calendar_event_type_fk">
                                    <option value="">Selecione o tipo</option>
                                    <?php foreach ($types as $type) : ?>
                                        <option value="<?= $type->id ?>"><?= yii::t('calendarModule.labels', $type->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="span12 checkbox">
                                <input type="checkbox" id="CalendarEvent_copyable" value="1"
                                       checked><?= yii::t("calendarModule.labels", "Copyable"); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-danger pull-left remove-event-button"><?= yii::t("calendarModule.index", "Delete Event") ?></button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                    <button type="button"
                            class="btn btn-primary save-event"><?= yii::t("calendarModule.index", "Save") ?></button>
                </div>
                <?php
                $this->endWidget();
                ?>
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
                        id="myModalLabel"><?= yii::t("calendarModule.index", "Remove Calendar") ?></h4>
                </div>
                <form method="post">
                    <div class="centered-loading-gif">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-error no-show"></div>
                        <div class="row-fluid">
                            <?= yii::t("calendarModule.index", "Are you sure?") ?>
                            <input type="hidden" name="calendar_removal_id" id="calendar_removal_id" value="-1"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                        <button type="button"
                                class="btn btn-primary remove-calendar-button"><?= yii::t("calendarModule.index", "Confirm") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeCalendarStatus" tabindex="-1" role="dialog"
         aria-labelledby="Change Calendar Status">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabel"></h4>
                </div>
                <form method="post">
                    <div class="centered-loading-gif">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-error no-show"></div>
                        <div class="row-fluid">
                            <?= yii::t("calendarModule.index", "Are you sure?") ?>
                            <input type="hidden" name="calendar-change-status-id" id="calendar-change-status-id"
                                   value=""/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                        <button type="button"
                                class="btn btn-primary change-calendar-status-button"><?= yii::t("calendarModule.index", "Confirm") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>