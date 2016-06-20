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
	if (isset($calendar)) {
		foreach ($calendar->calendarEvents as $event) {
			$start_event = new DateTime($event->start_date);
			$end_event = new DateTime($event->end_date);
			$mStart = $start_event->format('n');
			$mEnd = $end_event->format('n');

			for ($i = $mStart; $i <= $mEnd; $i++) {
				$events[$i][] = $event;
			}
		}
	}

	$logs = Log::model()->findAll("school_fk = :school order by date desc limit 5", [':school' => Yii::app()->user->school]);
	$logCount = count(Log::model()->findAll("school_fk = :school", [':school' => Yii::app()->user->school]));
?>


<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic">Página Inicial</h3>
	</div>
</div>
<div class="innerLR eggs">
	<div class="row-fluid">
		<div class="span9">
			<div class="widget widget-scroll widget-gray margin-bottom-none"
			     data-toggle="collapse-widget" data-scroll-height="223px"
			     data-collapse-closed="false">
				<div class="widget-head"><h5 class="heading glyphicons calendar"><i></i>Atividades Recentes</h5>
				</div>
				<div class="widget-body logs in" style="height: auto;">
					<?php
						if (count($logs) > 0) {
						foreach ($logs as $log) :
							$text = $icon = $crud = "";

							switch ($log->crud) {
								case "C" :
									$crud = "criado(a)";
									break;
								case "U" :
									$crud = "atualizado(a)";
									break;
								case "D":
									$crud = "excluído(a)";
									break;
							}

							switch ($log->reference) {
								case "class":
									$icon = "notes_2";
									break;
								case "frequency":
									$icon = "check";
									break;
								case "classroom": //done
									$text = 'Turma "' . $log->additional_info . '" foi ' . $crud . ".";
									$icon = "adress_book";
									break;
								case "courseplan": //done
									$text = 'Plano de aula "' . $log->additional_info . '" foi ' . $crud . ".";
									$icon = "book_open";
									break;
								case "enrollment":
									$icon = "book";
									break;
								case "instructor": //done
									$text = 'Professor(a) "' . $log->additional_info . '" foi ' . $crud . ".";
									$icon = "nameplate";
									break;
								case "school": //done
									$text = 'Escola "' . $log->additional_info . '" foi ' . $crud . ".";
									$icon = "building";
									break;
								case "student": //done
									$text = 'Aluno(a) "' . $log->additional_info . '" foi ' . $crud . ".";
									$icon = "parents";
									break;
								case "grade":
									$icon = "list";
									break;
								case "calendar": //done
									$text = 'Calendário de ' . $log->additional_info . ' foi ' . $crud . ".";
									$icon = "calendar";
									break;
								case "curricular_matrix":
									$icon = "stats";
									break;
								case "lunch_received":
									$icon = "upload";
									break;
								case "lunch_spent":
									$icon = "download";
									break;
								case "lunch_menu":
									$icon = "notes";
									break;
								case "lunch_meal":
									$icon = "cutlery";
									break;
								case "timesheet": //done
									$text = 'Quadro de Horário da turma "' . $log->additional_info . '" foi gerado.';
									$icon = "signal";
									break;
								case "wizard_classroom":
									$icon = "unchecked";
									break;
								case "wizard_student":
									$icon = "unchecked";
									break;
							}
							$date = date("d-m-Y H:i:s", strtotime($log->date));
							?>
							<li class="log">
								<span class="glyphicons <?= $icon ?>"><i></i><?= $text ?></span>
								<span class="log-date"><?= $date ?></span>
								<span class="log-author"><?= $log->userFk->name ?> - </span>
							</li>
						<?php endforeach; } else { ?>
							<li class="log">
								<span class="glyphicons notes"><i></i> Não há atividades recentes.</span>
							</li>
						<?php }  ?>

				</div>
			</div>
			<?php if (count($logs) < $logCount) : ?>
				<span class="load-more fa fa-plus-circle"> Carregar mais</span>
			<?php endif; ?>
		</div>
		<div class="span3">
			<?php
				date_default_timezone_set("America/Recife");
				$date = new DateTime();
				$date->setDate($date->format("Y"), $date->format("m"), $date->format("d"));
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
				<div class="next-events widget widget-scroll widget-gray margin-bottom-none"
				     data-toggle="collapse-widget" data-scroll-height="223px"
				     data-collapse-closed="false">
					<div class="widget-head"><h5 class="heading glyphicons calendar"><i></i>Eventos do mês</h5>
					</div>
					<div class="widget-body in" style="height: auto;">
						<span class="actual-date"><strong>Data atual:</strong> <?= $date->format("d/m") ?></span>
						<?php
							if ($start->format("n") == $m && $start != $end) :
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
							if ($end->format("n") == $m && $start != $end) :
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
