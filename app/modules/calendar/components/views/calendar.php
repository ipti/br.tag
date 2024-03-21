<?php
/**
 * @var $calendar Calendar
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$getEventUrl = Yii::app()->createUrl("/calendar/default/event");

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/common/js/calendar.js?v=1.1', CClientScript::POS_END);
$cs->registerScript("vars", "
var GET_EVENT_URL = '$getEventUrl';
", CClientScript::POS_BEGIN);


if (!function_exists('isInInterval')) {
    function isInInterval($start, $end, $day, $month, $year)
    {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $user_ts = strtotime($year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($day, 2, "0", STR_PAD_LEFT));

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
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

<div class="no-calendars-alert alert alert-warning <?= $calendars == null ? "" : "no-show" ?>">Nenhum Calendário
    cadastrado para <?= Yii::app()->user->year ?>.
</div>
<div class="accordion" id="calendars">
    <?php foreach ($calendars as $calendar): ?>
        <?php
        $start = new DateTime($calendar->start_date);
        $end = new DateTime($calendar->end_date);

        $interval = $start->diff($end);
        $rowsCount = (($interval->m + ($interval->y * 12)) + 1) / 4;

        $date = new DateTime($start->format("Y-m") . "-01");

        ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <div class="accordion-toggle" data-toggle="collapse" data-parent="#calendars"
                    href="#collapse<?= $calendar->id ?>">
                    <a class="accordion-title" style="margin-left:10px"><?= $calendar->title ?></a>
                    <?php if(Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)): ?>
                        <span class="text-right pull-right remove-calendar" data-toggle="tooltip" data-placement="top"
                            data-original-title="Remover Calendário" data-id="<?= $calendar->id ?>">
                                <i class="fa fa-remove"></i>
                        </span>

                        <span class="text-right pull-right edit-calendar" data-toggle="tooltip" data-placement="top"
                            data-original-title="<?= yii::t('index', 'Editar Calendário') ?>"
                            data-id="<?= $calendar->id ?>">
                                <i class="fa fa-edit"></i>
                        </span>
                        <span class="text-right pull-right manage-unity-periods" data-toggle="tooltip" data-placement="top"
                            data-original-title="Gerenciar Vigência das Unidades" data-id="<?= $calendar->id ?>">
                                <i class="fa fa-map-o"></i>
                        </span>
                        <span class="text-right pull-right show-stages" data-toggle="tooltip" data-placement="top"
                            data-original-title="Visualizar Etapas e Vigências" data-id="<?= $calendar->id ?>">
                                <i class="fa fa-question-circle-o"></i>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div id="collapse<?= $calendar->id ?>" class="accordion-body collapse">
                <div class="accordion-inner">
                    <div class="centered-loading-gif">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 calendar-container" data-id="<?= $calendar->id ?>">
                            <?php for ($i = 0; $i <= $rowsCount; $i++): ?>
                                <div class="row-fluid calendar"
                                     data-year="<?= date('Y', strtotime($calendar->school_year)) ?>"
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
                                                        <h4><?= yii::t('calendarModule.labels', $month) . "/" . $y ?></h4>
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
                                                            $isAdmin = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id);
                                                            if($isAdmin) {
                                                                $beforeContent = "<a class='change-event' data-toggle='modal' data-target='#myChangeEvent' data-id='-1' data-year='$y'  data-month='$m' data-day='$day' >";
                                                            }
                                                            if ($day < 10) {
                                                                $content = "0";
                                                            }
                                                            foreach ($calendar->calendarEvents as $event) {
                                                                if (isInInterval($event->start_date, $event->end_date, $day, $m, $y)) {
                                                                    if($isAdmin) {
                                                                        $beforeContent = "<a class='change-event' data-toggle='tooltip' data-placement='top' data-original-title='" . $event->name . "' data-year='$y'  data-id='$event->id' data-month='$m' data-day='$day' >";
                                                                    }
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

                                                        if ($start->format("Ymd") == ($y . $date->format("m") . $content)) {
                                                            $class .= "start-date ";
                                                        }
                                                        if ($end->format("Ymd") == ($y . $date->format("m") . $content)) {
                                                            $class .= "end-date ";
                                                        }

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
</div>

<div class="modal fade modal-content" id="myNewCalendar" tabindex="-1" role="dialog" aria-labelledby="New Calendar">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
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
                        <input type="text" class="create-calendar-title span12"
                               placeholder="Digite o Título do Calendário">
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="form-control">
                        <div class="row-fluid">
                            <div class="span6">
                                <?= chtml::label(yii::t("calendarModule.labels", "Start Date"), "title", array('class' => 'control-label required')); ?>
                                <div class="form-control">
                                    <input type="date" id="create-calendar-start-date" class="span12">
                                </div>
                            </div>
                            <div class="span6">
                                <?= chtml::label(yii::t("calendarModule.labels", "End Date"), "title", array('class' => 'control-label required')); ?>
                                <div class="form-control">
                                    <input type="date" id="create-calendar-end-date" class="span12">
                                </div>
                            </div>
                        </div>
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
                        <div class="add-stages-options">Atalho: <span
                                    class="add-fundamental-menor">Fundamental Menor</span>
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
                            array('prompt' => yii::t("calendarModule.labels", 'Select calendar base'), 'class' => 'span9', 'style' => 'width:100%')) ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                <button type="button"
                        class="btn btn-primary create-calendar"><?= yii::t("calendarModule.index", "Save") ?></button>
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>

<div class="modal fade modal-content" id="edit-calendar-modal" tabindex="-1" role="dialog"
     aria-labelledby="Edit Calendar">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"
                id="myModalLabel"><?= yii::t("calendarModule.index", "Edit Calendar") ?></h4>
        </div>
        <?php
        /* @var $form CActiveForm */
        $editCalendar = new Calendar();
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'createCalendar',
            'action' => '?r=/calendar/default/editCalendar',
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
                        <?= $form->textField($editCalendar, "title", ['class' => 'span12', 'placeholder' => 'Digite o Título do Calendário']) ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="form-control">
                        <div class="row-fluid">
                            <div class="span6">
                                <?= chtml::label(yii::t("calendarModule.labels", "Start Date"), "title", array('class' => 'control-label required')); ?>
                                <div class="form-control">
                                    <?= $form->dateField($editCalendar, "start_date", ['class' => 'span12', 'placeholder' => '']) ?>
                                </div>
                            </div>
                            <div class="span6">
                                <?= chtml::label(yii::t("calendarModule.labels", "End Date"), "title", array('class' => 'control-label required')); ?>
                                <div class="form-control">
                                    <?= $form->dateField($editCalendar, "end_date", ['class' => 'span12', 'placeholder' => '']) ?>
                                </div>
                            </div>
                        </div>
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
                        <div class="add-stages-options">Atalho: <span
                                    class="add-fundamental-menor">Fundamental Menor</span>
                            | <span class="add-fundamental-maior">Fundamental Maior</span> | <span
                                    class="remove-stages">Remover</span></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                <button type="button"
                        class="btn btn-primary edit-calendar-button"><?= yii::t("calendarModule.index", "Save") ?></button>
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>

<div class="modal fade modal-content" id="unity-periods-modal" tabindex="-1" role="dialog"
     aria-labelledby="Manage Unities Initial Date">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"
                id="myModalLabel"><?= yii::t("calendarModule.index", "Manage Unities Initial Date") ?></h4>
        </div>

        <div class="centered-loading-gif">
            <i class="fa fa-spin fa-spinner"></i>
        </div>
        <div class="modal-body">
            <div class="error-calendar-event alert alert-error no-show"></div>
            <div class="unity-periods-container"></div>
            <span class="replicate-periods">Replicar datas para etapas similares</span><i
                    class="fa fa-spin fa-spinner load-replication"></i>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                <button type="button"
                        class="btn btn-primary manage-unity-periods-button"><?= yii::t("calendarModule.index", "Save") ?></button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade modal-content" id="myChangeEvent" tabindex="-1" role="dialog" aria-labelledby="Change Event">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
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
        <div class="modal-body" style="overflow: hidden;">
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
                               style="margin-right: 10px;" checked><?= yii::t("calendarModule.labels", "Copyable"); ?>
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
        </div>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>

<div class="modal fade modal-content" id="removeCalendar" tabindex="-1" role="dialog" aria-labelledby="Remove Calendar">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
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


                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                    <button type="button"
                            class="btn btn-primary remove-calendar-button"><?= yii::t("calendarModule.index", "Confirm") ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
