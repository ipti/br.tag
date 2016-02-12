<?php
/**
 * @var $calendar Calendar
 */

$start = new DateTime($calendar->start_date);
$end = new DateTime($calendar->end_date);

$interval = $start->diff($end);

$total = $interval->m;
$date = $start;
?>

<?php for ($i = 0; $i < $total / 4; $i++): ?>
    <div class="row-fluid">
        <div class="span12">
            <?php for ($j = 0; $j < 4; $j++):
                if($date->diff($end)->invert) break;
                $month = $date->format("F");
                ?>
                <div class="span3 img-polaroid">
                    <span><?= yii::t('app', $month) ?></span>
                    <hr>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="span1">D</div>
                            <div class="span2">S</div>
                            <div class="span2">T</div>
                            <div class="span2">Q</div>
                            <div class="span2">Q</div>
                            <div class="span2">S</div>
                            <div class="span1">S</div>
                        </div>
                    </div>
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
    <div class="span12">
        <div class="span12 img-polaroid">teste</div>
    </div>
</div>