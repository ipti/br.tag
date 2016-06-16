<?php
	/* @var $this SiteController */

	$this->pageTitle = Yii::app()->name . '';
	$this->breadcrumbs = [
		'',
	];

	$year = Yii::app()->user->year;

	if (!function_exists('isInInterval')) {
		/**
		 * @param $start DateTime
		 * @param $end DateTime
		 * @param $day Integer
		 * @param $month Integer
		 * @return bool {True} If the $day and $month are in the interval.
		 */
		function isInInterval($start, $end, $day, $month) {
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

				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	/** @var Calendar $calendar */
	$calendar = Calendar::model()->find("school_fk = :school and actual = 1", [":school" => Yii::app()->user->school]);
	$start = new DateTime($calendar->start_date);
	$end = new DateTime($calendar->end_date);
	$events = [];
	for ($i = 1; $i <= 12; $i++) {
		$events[$i] = [];
	}
	foreach ($calendar->calendarEvents as $event) {
		$start_event = new DateTime($event->start_date);
		$end_event = new DateTime($event->end_date);
		$mStart = $start_event->format('n');
		$mEnd = $end_event->format('n');

		for ($i = $mStart; $i <= $mEnd; $i++) {
			$events[$i][] = $event;
		}
	}
?>


<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic">Página Inicial</h3>
	</div>
</div>

<div class="innerLR home">
	<?php
		date_default_timezone_set("America/Recife");
		$date = new DateTime();
		$date->setDate($date->format("Y"), $date->format("m"), 1);
		$month = $date->format("F");
		$m = $date->format("n");
		$y = $date->format("Y");
	?>
	<div class="row-fluid">
		<div class="span9"></div>
		<div class="span3">
			<div class="img-polaroid">
				<div class="row-fluid">
					<div class="span12 center">
						<h4><?= yii::t('default', $month) ?></h4>
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
								$beforeContent = "<span class='change-event' data-toggle='modal' data-target='#myChangeEvent' data-id='-1' data-year='$y'  data-month='$m' data-day='$day' >";
								if ($day < 10) {
									$content = "0";
								}
								if (isInInterval($start, $start, $day, $m) || isInInterval($end, $end, $day, $m)) {
									$class .= " calendar-black ";
									$beforeContent .= "<i class=' calendar-icon fa fa-circle'></i>";
								}
								foreach ($events[$m] as $event) {
									/** @var $event CalendarEvent */
									$start_event = new DateTime($event->start_date);
									$end_event = new DateTime($event->end_date);
									//Verifica se esta dentro do intervalo de datas
									if (isInInterval($start_event, $end_event, $day, $m)) {
										$beforeContent = "<span class='change-event' data-toggle='modal' data-target='#myChangeEvent' data-year='$y'  data-id='$event->id' data-month='$m' data-day='$day' >";
										$class .= " calendar-" . $event->calendarEventTypeFk->color . " ";
										$beforeContent .= "<i class=' calendar-icon fa " . $event->calendarEventTypeFk->icon . "'></i>";
										break;
									}
								}

								$content .= $day++;
								$afterContent = "</span>";
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
			<div class="eggs">
				<?php
					foreach ($events[$m] as $event) {
						//puxar as dodjera de events
						echo $event->name;
						echo $event->calendarEventTypeFk->color;
						echo $event->calendarEventTypeFk->icon;
						echo $event->start_date;
						echo $event->end_date;
						echo "<br>";
					}
				?>
			</div>
		</div>
	</div>
</div>
