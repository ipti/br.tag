<?php
/**
 * @var $calendar Calendar
 */

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

$types = CalendarEventType::model()->findAll();

$start = new DateTime($calendar->start_date);
$end = new DateTime($calendar->end_date);

$interval = $start->diff($end);

$total = $interval->m;
$date = new DateTime($start->format("Y-m-d"));

$events = [];
for ($i = 1; $i <= 12; $i++) $events[$i] = [];
foreach ($calendar->calendarEvents as $event) {
    $start_event = new DateTime($event->start_date);
    $end_event = new DateTime($event->end_date);
    $mStart = $start_event->format('n');
    $mEnd = $end_event->format('n');

    for($i = $mStart; $i<= $mEnd; $i++){
        $events[$i][] = $event;
    }
}

?>

<?php for ($i = 0; $i < $total / 4; $i++): ?>
    <div class="row-fluid">
        <div class="span12">
            <?php for ($j = 0; $j < 4; $j++):
                if ($date->diff($end)->invert) break;
                $month = $date->format("F");
                $m = $date->format("n");
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
                            $class = "";

                            if (($week == 0 && $weekDay < $firstWeekDay) || $day > $totalDays) {
                                $content = "--";
                            } else {
                                if ($day < 10) {
                                    $content = "0";
                                }
                                if (isInInterval($start, $start, $day, $m) || isInInterval($end, $end, $day, $m)) {
                                    $class .= " calendar-black ";
                                    $beforeContent = "<i class=' calendar-icon fa fa-circle'></i>";
                                }
                                foreach ($events[$m] as $event) {
                                    /** @var $event CalendarEvent */
                                    $start_event = new DateTime($event->start_date);
                                    $end_event = new DateTime($event->end_date);
                                    //Verifica se esta dentro do intervalo de datas
                                    if (isInInterval($start_event, $end_event, $day, $m)) {
                                        $class .= " calendar-" . $event->calendarEventTypeFk->color . " ";
                                        $beforeContent = "<i class=' calendar-icon fa " . $event->calendarEventTypeFk->icon . "'></i>";

                                    }
                                }

                                $content .= $day++;
                            }

                            if ($weekDay == 0) {
                                $class .= "sunday ";
                            }
                            $class .= "span1-7 ";
                            $html .= "<div class='$class'>$beforeContent<div class='calendar-text '>$content</div></div>";

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
<div class="row-fluid">
    <div class="span12 img-polaroid">

        <div class="row-fluid">
            <div class="span12">
                <h4><?= yii::t('calendarModule.labels', "Subtitles") ?></h4>
            </div>
        </div>
        <?php
        $html = '<div class="span3 calendar-subtitles calendar-black">'
            .'<i class="fa fa-circle"></i>&nbsp;'
            .'<span>'.yii::t('calendarModule.labels', "Beginning and End of the School Year.").'</span>'
            .'</div>';
        foreach ($types as $type) {
            /**@var $type CalendarEventType */
            $html .= '<div class="span3 calendar-subtitles calendar-'. $type->color .'">'
                        .'<i class="fa '.$type->icon.'"></i>&nbsp;'
                        .'<span>'.yii::t('calendarModule.labels', $type->name).'</span>'
                    .'</div>';


        }
        echo $html;?>
    </div>
</div>