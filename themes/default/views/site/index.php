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
<div class="innerLR home eggs">
	<div class="row-fluid">
		<div class="span4">
			<div class="widget widget-scroll widget-gray margin-bottom-none"
			     data-toggle="collapse-widget" data-scroll-height="223px"
			     data-collapse-closed="false">
				<div class="widget-head"><h5 class="heading glyphicons calendar"><i></i>Atividades Recentes</h5>
				</div>
				<div class="widget-body in" style="height: auto;">
					<!--
switch($reference) {
case "class":
break;
case "frequency":
break;
case "classroom":
break;
case "courseplan":
break;
case "enrollment":
break;
case "instructor":
break;
case "school":
break;
case "student":
break;
case "grade":
break;
case "calendar":
break;
case "curricular_matrix":
break;
case "lunch_received":
break;
case "lunch_spent":
break;
case "lunch_menu":
break;
case "lunch_meal":
break;
case "timesheet":
break;
case "wizard_classroom":
break;
case "wizard_student":
break;
}
-->
				</div>
			</div>
		</div>
		<div class="span5"></div>
		<div class="span3">
			<?php
				date_default_timezone_set("America/Recife");
				$date = new DateTime();
				$date->setDate($date->format("Y"), $date->format("m"), 1);
				$month = $date->format("F");
				$m = $date->format("n");
				$y = $date->format("Y");
			?>
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
			<div>
				<div class="widget widget-scroll widget-gray margin-bottom-none"
				     data-toggle="collapse-widget" data-scroll-height="223px"
				     data-collapse-closed="false">
					<div class="widget-head"><h5 class="heading glyphicons calendar"><i></i>Eventos do mês</h5>
					</div>
					<div class="widget-body in" style="height: auto;">
						<span class="actual-date"><strong>Data atual:</strong> <?= $date->format("d/m") ?></span>
						<?php
							if ($start->format("n") == $m) :
								?>
								<span class="calendar-event"><i
										class="fa fa-circle calendar-black"></i><?= $start->format("d/m") . ": Início do Período Letivo" ?></span>
								<?php
							endif;
							foreach ($events[$m] as $event) :
								$eventStart = new DateTime($event->start_date);
								$eventEnd = new DateTime($event->end_date);
								$eventStart = $eventStart->format("d/m");
								$eventEnd = $eventEnd->format("d/m");
								$eventDate = ($eventStart == $eventEnd ? $eventStart : $eventStart . " - " . $eventEnd);
								?>
								<span class="calendar-event"><i
										class="fa <?= $event->calendarEventTypeFk->icon . " calendar-" . $event->calendarEventTypeFk->color ?>"></i><?= $eventDate . ": " . $event->name ?></span>
								<?php
							endforeach;
							if ($end->format("n") == $m) :
								?>
								<span class="calendar-event"><i
										class="fa fa-circle calendar-black"></i><?= $end->format("d/m") . ": Término do Período Letivo" ?></span>
							<?php endif;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
