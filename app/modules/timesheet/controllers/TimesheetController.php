<?php

class TimesheetController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => [], 'users' => ['*'],
            ], [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'index', 'instructors', 'GetInstructorDisciplines', 'addInstructors', 'loadUnavailability',
                    'getTimesheet', 'generateTimesheet', "addinstructorsdisciplines", "changeSchedules", "ChangeInstructor"
                ], 'users' => ['@'],
            ], [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => [], 'users' => ['admin'],
            ], [
                'deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }


    public function actionIndex()
    {
        $daysPerMonth = [];
        for ($month = 1; $month <= 12; $month++) {
            $date = Yii::app()->user->year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
            $daysPerMonth[$month]["daysCount"] = date("t", strtotime($date));
            $daysPerMonth[$month]["monthName"] = date("F", strtotime($date));
            $daysPerMonth[$month]["weekDayOfTheFirstDay"] = date("w", strtotime($date));
        }
        $calendarTypes = CalendarEventType::model()->findAll();

        $this->render('index', array(
            "daysPerMonth" => $daysPerMonth,
            "dayNameFirstLetter" => ["D", "S", "T", "Q", "Q", "S", "S"],
            "calendarTypes" => $calendarTypes,
        ));
    }

    public function actionInstructors()
    {
        $this->render('instructors');
    }

    private function getUnavailableDays($classroomId, $fullDate, $level)
    {
        $unavailableDays = [];
        if ($level == "hard") {
            $firstDay = Yii::app()->db->createCommand("select DATE(ce.start_date) as start_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . " and calendar_event_type_fk = 1000;")->queryRow();
            $lastDay = Yii::app()->db->createCommand("select DATE(ce.end_date) as end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . " and calendar_event_type_fk  = 1001;")->queryRow();
            $unavailableEvents = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and ce.calendar_event_type_fk = 102 and YEAR(c.start_date) = " . Yii::app()->user->year . ";")->queryAll();
            $unavailableEventsArray = [];
            foreach ($unavailableEvents as $unavailableEvent) {
                $startDate = new DateTime($unavailableEvent["start_date"]);
                $endDate = new DateTime($unavailableEvent["end_date"]);
                $endDate->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($startDate, $interval, $endDate);
                foreach ($period as $date) {
                    if (!in_array($date->format("Y-m-d"), $unavailableEventsArray)) {
                        array_push($unavailableEventsArray, $date->format("Y-m-d"));
                    }
                }
            }
            $begin = new Datetime(Yii::app()->user->year . "-01-01");
            $end = new Datetime(Yii::app()->user->year . "-12-31");
            $end->modify('+1 day');
            for ($date = $begin; $date <= $end; $date->modify('+1 day')) {
                $dateStr = $date->format("Y-m-d");
                if ($dateStr < $firstDay["start_date"] || $dateStr > $lastDay["end_date"] || in_array($dateStr, $unavailableEventsArray)) {
                    if ($fullDate) {
                        if (!in_array($date->format("Y-m-d"), $unavailableDays)) {
                            array_push($unavailableDays, $date->format("Y-m-d"));
                        }
                    } else {
                        if (!isset($unavailableDays[$date->format("n")])) {
                            $unavailableDays[$date->format("n")] = [];
                        }
                        if (!in_array($date->format("j"), $unavailableDays[$date->format("n")])) {
                            array_push($unavailableDays[$date->format("n")], $date->format("j"));
                        }
                    }
                }
            }
        } else if ($level == "soft") {
            $unavailableEvents = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and ce.calendar_event_type_fk = 101 and YEAR(c.start_date) = " . Yii::app()->user->year . ";")->queryAll();
            foreach ($unavailableEvents as $unavailableEvent) {
                $startDate = new DateTime($unavailableEvent["start_date"]);
                $endDate = new DateTime($unavailableEvent["end_date"]);
                $endDate->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($startDate, $interval, $endDate);
                foreach ($period as $date) {
                    if ($fullDate) {
                        if (!in_array($date->format("Y-m-d"), $unavailableDays)) {
                            array_push($unavailableDays, $date->format("Y-m-d"));
                        }
                    } else {
                        if (!isset($unavailableDays[$date->format("n")])) {
                            $unavailableDays[$date->format("n")] = [];
                        }
                        if (!in_array($date->format("j"), $unavailableDays[$date->format("n")])) {
                            array_push($unavailableDays[$date->format("n")], $date->format("j"));
                        }
                    }
                }
            }
        }
        return $unavailableDays;
    }

    public function actionGetTimesheet($classroomId = NULL)
    {
        if ($classroomId == NULL) {
            $classroomId = $_POST["cid"];
        }
        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $classroomId]);

        $firstDay = Yii::app()->db->createCommand("select DATE(ce.start_date) as start_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . " and calendar_event_type_fk = 1000;")->queryRow();
        $lastDay = Yii::app()->db->createCommand("select DATE(ce.end_date) as end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . " and calendar_event_type_fk  = 1001;")->queryRow();

        if (is_array($firstDay) && is_array($lastDay)) {
            $calendarEvents = Yii::app()->db->createCommand("select ce.*, cet.* from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) inner join calendar_event_type as cet on cet.id = ce.calendar_event_type_fk join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . ";")->queryAll();
            $calendarEventsArray = [];
            foreach ($calendarEvents as $calendarEvent) {
                $startDate = new DateTime($calendarEvent["start_date"]);
                $endDate = new DateTime($calendarEvent["end_date"]);
                $endDate->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($startDate, $interval, $endDate);
                foreach ($period as $date) {
                    array_push($calendarEventsArray, [
                        "day" => $date->format("j"),
                        "month" => $date->format("n"),
                        "name" => yii::t('timesheetModule.timesheet', $calendarEvent["name"]),
                        "icon" => $calendarEvent["icon"],
                        "color" => $calendarEvent["color"]
                    ]);
                }
            }
            $response["calendarEvents"] = $calendarEventsArray;

            $curricularMatrix = TimesheetCurricularMatrix::model()->findAll("stage_fk = :stage and school_fk = :school", [
                ":stage" => $classroom->edcenso_stage_vs_modality_fk, ":school" => Yii::app()->user->school
            ]);
            $response["disciplines"] = [];
            foreach ($curricularMatrix as $cm) {
                array_push($response["disciplines"], ["disciplineId" => $cm->discipline_fk, "disciplineName" => $cm->disciplineFk->name, "workloadUsed" => 0, "workloadTotal" => $cm->workload]);
            }
            $hasMatrix = $curricularMatrix != null;

            if ($classroomId != "") {
                /** @var Schedule[] $schedules */
                $schedules = Schedule::model()->findAll("classroom_fk = :classroom", [":classroom" => $classroomId]);
                if (count($schedules) == 0) {
                    if ($hasMatrix) {
                        $response["valid"] = TRUE;
                        $response["hardUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "hard");
                        $response["softUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "soft");
                        $response["schedules"] = [];
                    } else {
                        $response["valid"] = FALSE;
                        $response["error"] = "curricularMatrix";
                    }
                } else {
                    $response["valid"] = TRUE;
                    $response["hardUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "hard");
                    $response["softUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "soft");

                    $lastClassFaultDay = Yii::app()->db->createCommand("select s.* from class_faults as cf join schedule as s on cf.schedule_fk = s.id where s.classroom_fk = " . $classroomId . " order by month DESC, day DESC limit 1")->queryRow();
                    if ($lastClassFaultDay != false) {
                        $response["frequencyUnavailableLastDay"] = ["month" => $lastClassFaultDay["month"], "day" => $lastClassFaultDay["day"]];
                    }

                    $response["schedules"] = [];
                    foreach ($schedules as $schedule) {
//                    if (!isset($response["schedules"][$schedule->month])) {
//                        $response["schedules"][$schedule->month] = [];
//                    }
//                    if ($schedule->instructor_fk != NULL) {
//                        /** @var TimesheetInstructor $instructor */
//                        $instructor = TimesheetInstructor::model()->find("id = :id", [":id" => $schedule->instructor_fk]);
//                        $unavailable = $instructor->isUnavailable($schedule->week_day, $schedule->turn, $schedule->schedule);
//                        $countConflicts = $instructor->countConflicts($schedule->week_day, $schedule->turn, $schedule->schedule);
//                        $instructorInfo = [
//                            "id" => $schedule->instructorFk->id,
//                            "name" => $schedule->instructorFk->name,
//                            "unavailable" => $unavailable,
//                            "countConflicts" => $countConflicts
//                        ];
//                    } else {
                        $instructorInfo = [
                            "id" => null,
                            "name" => "Sem Instrutor",
                            "unavailable" => false,
                            "countConflicts" => 0
                        ];
//                    }

                        if (!$schedule->unavailable) {
                            $cmKey = array_search($schedule["discipline_fk"], array_column($response["disciplines"], 'disciplineId'));
                            $response["disciplines"][$cmKey]["workloadUsed"]++;
                        }

                        $response["schedules"][$schedule->month][$schedule->schedule][$schedule->day] = [
                            "id" => $schedule->id,
                            "instructorId" => $schedule->instructor_fk,
                            "instructorInfo" => $instructorInfo,
                            "disciplineId" => $schedule->discipline_fk,
                            "disciplineName" => $schedule->disciplineFk->name,
                            "turn" => $schedule->turn
                        ];
                    }
                }
            } else {
                $response["valid"] = NULL;
            }
        } else {
            $response = ["valid" => FALSE, "error" => "calendar"];
        }
        echo json_encode($response);
    }

    public function actionGenerateTimesheet()
    {
        /**
         * @var $classroom Classroom
         * @var $instructorDisciplines InstructorDisciplines
         */
        $classroomId = $_POST["classroom"];
        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $classroomId]);

        $criteria = new CDbCriteria();
        $criteria->alias = "cf";
        $criteria->join = "join schedule on schedule.id = cf.schedule_fk";
        $criteria->condition = "schedule.classroom_fk = :classroom_fk";
        $criteria->params = ["classroom_fk" => $classroomId];
        $hasFrequency = ClassFaults::model()->exists($criteria);
        if (!$hasFrequency) {
            $curricularMatrix = TimesheetCurricularMatrix::model()->findAll("stage_fk = :stage and school_fk = :school", [
                ":stage" => $classroom->edcenso_stage_vs_modality_fk, ":school" => Yii::app()->user->school
            ]);
            if ($curricularMatrix != null) {
                Schedule::model()->deleteAll("classroom_fk = :classroom", [":classroom" => $classroomId]);

                $schedulesQuantity = 10;
                $turn = 0;

                if ($classroom->initial_hour < 12) {
                    $turn = 0;
                }
                if ($classroom->initial_hour >= 12 && $classroom->initial_hour < 19) {
                    $turn = 1;
                }
                if ($classroom->initial_hour >= 19) {
                    $turn = 2;
                }


                $weekDays = [];
                if ($classroom->week_days_sunday) {
                    array_push($weekDays, 0);
                }
                if ($classroom->week_days_monday) {
                    array_push($weekDays, 1);
                }
                if ($classroom->week_days_tuesday) {
                    array_push($weekDays, 2);
                }
                if ($classroom->week_days_wednesday) {
                    array_push($weekDays, 3);
                }
                if ($classroom->week_days_thursday) {
                    array_push($weekDays, 4);
                }
                if ($classroom->week_days_friday) {
                    array_push($weekDays, 5);
                }
                if ($classroom->week_days_saturday) {
                    array_push($weekDays, 6);
                }

//            $instructorDisciplines = InstructorDisciplines::model()->findAll("stage_vs_modality_fk = :svm", [":svm" => $classroom->edcenso_stage_vs_modality_fk]);
//            $instructors = [];
//            foreach ($instructorDisciplines as $instructorDiscipline) {
//                if (!isset($instructors[$instructorDiscipline->instructor_fk])) {
//                    $instructors[$instructorDiscipline->instructor_fk] = $instructorDiscipline->instructorFk;
//                }
//            }
//
//            $instructorsUnavailabilities = [];
//            /** @var $instructor TimesheetInstructor */
//            $i = 0;
//            foreach ($instructors as $id => $instructor) {
//                $unavailabilities = $instructor->getInstructorUnavailabilities($turn);
//                if ($instructorsUnavailabilities == NULL) {
//                    $instructorsUnavailabilities = [];
//                }
//                $instructorsUnavailabilities[$i++] = ['id' => $id, 'unavailabilities' => $unavailabilities];
//            }
//
//            function compare($a, $b)
//            {
//                if ($a['unavailabilities']['count'] == $b['unavailabilities']['count']) {
//                    return 0;
//                }
//                if ($a['unavailabilities']['count'] > $b['unavailabilities']['count']) {
//                    return -1;
//                }
//
//                return 1;
//            }
//
//            usort($instructorsUnavailabilities, 'compare');

                $disciplines = [];
                $i = 0;
                /** @var CurricularMatrix $cm */
                foreach ($curricularMatrix as $cm) {
                    $disciplines[$i] = [
                        "discipline" => $cm->discipline_fk, "instructor" => NULL, "credits" => $cm->credits
                    ];
//                $needed = $cm->credits;
//                if (count($instructors) > 0) {
//                    $indexArray = array();
//	                for ($idx = 0; $idx < count($instructors); $idx++) {
//		                array_push($indexArray, $idx);
//	                }
//	                shuffle($indexArray);
//		            for ($idx = 0; $idx < count($indexArray); $idx++) {
//			            if ($instructorsUnavailabilities[$indexArray[$idx]]['unavailabilities']['count'] + $needed < $schedulesQuantity * 7) {
//				            $disciplines[$i]["instructor"] = $instructorsUnavailabilities[$indexArray[$idx]]['id'];
//				            $instructorsUnavailabilities[$indexArray[$idx]]['unavailabilities']['count'] += $needed;
//				            break;
//			            }
//		            }
//                }
                    $i++;
                }

                $schedules = [];

                for ($i = 1; $i <= $schedulesQuantity; $i++) {
                    foreach ($weekDays as $wk) {
                        $schedule = new Schedule();
                        $schedule->week_day = $wk;
                        $schedule->schedule = $i;
                        array_push($schedules, $schedule);
                    }
                }

                $batchInsert = [];
                foreach ($schedules as $schedule) {
                    shuffle($disciplines);
                    $rand = 0;
//                foreach ($disciplines as $index => $d) {
//                    if ($d["instructor"] == NULL) {
//                        $rand = $index;
//                        break;
//                    }
//                    $una = NULL;
//                    foreach ($instructorsUnavailabilities as $iu) {
//                        if ($iu == $d["instructor"]) {
//                            $una = $iu['unavailabilities'];
//                            break;
//                        }
//                    }
//                    if ($una == NULL) {
//                        $rand = $index;
//                        break;
//                    }
//                    $wk = $schedule->week_day;
//                    $sc = $schedule->schedule;
//                    if (!in_array($sc, $una[$wk])) {
//                        $rand = $index;
//                        break;
//                    }
//                }
//                $instructor = $disciplines[$rand]['instructor'];
                    $discipline = $disciplines[$rand]['discipline'];
                    $disciplines[$rand]['credits']--;
                    if ($disciplines[$rand]['credits'] <= 0) {
                        unset($disciplines[$rand]);
                    }

                    if ($discipline !== null) {
                        $unavailableEvents = Yii::app()->db->createCommand("select ce.start_date, ce.end_date, ce.calendar_event_type_fk from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and (ce.calendar_event_type_fk = 101 or ce.calendar_event_type_fk = 102) and YEAR(c.start_date) = " . Yii::app()->user->year . ";")->queryAll();
                        $hardUnavailableDaysArray = [];
                        $softUnavailableDaysArray = [];
                        foreach ($unavailableEvents as $unavailableEvent) {
                            $startDate = new DateTime($unavailableEvent["start_date"]);
                            $endDate = new DateTime($unavailableEvent["end_date"]);
                            $endDate->modify('+1 day');
                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($startDate, $interval, $endDate);
                            foreach ($period as $date) {
                                if ($unavailableEvent["calendar_event_type_fk"] == 102) {
                                    if (!in_array($date, $hardUnavailableDaysArray)) {
                                        array_push($hardUnavailableDaysArray, $date);
                                    }
                                } else {
                                    if (!in_array($date, $softUnavailableDaysArray)) {
                                        array_push($softUnavailableDaysArray, $date);
                                    }
                                }
                            }
                        }

                        $firstDay = Yii::app()->db->createCommand("select ce.start_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . " and calendar_event_type_fk = 1000;")->queryRow();
                        $lastDay = Yii::app()->db->createCommand("select ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) join calendar_stages as cs on cs.calendar_fk = c.id join classroom cr on cr.edcenso_stage_vs_modality_fk = cs.stage_fk where cr.id = " . $classroomId . " and YEAR(c.start_date) = " . Yii::app()->user->year . " and calendar_event_type_fk  = 1001;")->queryRow();
                        $firstDay = new DateTime($firstDay["start_date"]);
                        $lastDay = new DateTime($lastDay["end_date"]);
                        $lastDay->modify('+1 day');
                        $interval = DateInterval::createFromDateString('1 day');
                        $period = new DatePeriod($firstDay, $interval, $lastDay);
                        foreach ($period as $date) {
                            if ($schedule->week_day == date("w", strtotime($date->format("Y-m-d"))) && !in_array($date, $hardUnavailableDaysArray)) {
                                $sc = new Schedule();
                                $sc->discipline_fk = $discipline;
                                $sc->classroom_fk = $classroomId;
                                $sc->day = $date->format("j");
                                $sc->month = $date->format("n");
                                $sc->week = $date->format("W");
                                $sc->week_day = $schedule->week_day;
                                $sc->schedule = $schedule->schedule;
                                $sc->unavailable = in_array($date, $softUnavailableDaysArray) ? 1 : 0;
                                $sc->turn = $turn;
                                array_push($batchInsert, $sc->getAttributes());
                            }
                        }
                    }
                }
                Yii::app()->db->getCommandBuilder()->createMultipleInsertCommand('schedule', $batchInsert)->execute();
                Log::model()->saveAction("timesheet", $classroom->id, "U", $classroom->name);
            }
            $this->actionGetTimesheet($classroomId);
        } else {
            echo json_encode(["valid" => false, "error" => "frequencyFilled"]);
        }
    }

    public function actionChangeSchedules()
    {
        $changes = [];
        $weekOfTheChange = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["firstSchedule"]["day"], 'month' => $_POST["firstSchedule"]["month"], 'schedule' => $_POST["firstSchedule"]["schedule"]));
        if ($weekOfTheChange == null) {
            $weekOfTheChange = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["secondSchedule"]["day"], 'month' => $_POST["secondSchedule"]["month"], 'schedule' => $_POST["secondSchedule"]["schedule"]));
        }
        $weekLimit = $_POST["replicate"] ? 53 : $weekOfTheChange["week"];
        $schedulesToCheckHardUnavailability = [];
        $softUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "soft");
        for ($week = $weekOfTheChange["week"]; $week <= $weekLimit; $week++) {
            $firstSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'week' => $week, 'week_day' => $_POST["firstSchedule"]["week_day"], 'schedule' => $_POST["firstSchedule"]["schedule"]));
            $secondSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'week' => $week, 'week_day' => $_POST["secondSchedule"]["week_day"], 'schedule' => $_POST["secondSchedule"]["schedule"]));
            if ($firstSchedule != null && $secondSchedule != null) {
                array_push($changes, [
                    "firstSchedule" => ["day" => $firstSchedule->day, "month" => $firstSchedule->month, "schedule" => $firstSchedule->schedule],
                    "secondSchedule" => ["day" => $secondSchedule->day, "month" => $secondSchedule->month, "schedule" => $secondSchedule->schedule]
                ]);

                $tmpDay = $secondSchedule->day;
                $secondSchedule->day = $firstSchedule->day;
                $firstSchedule->day = $tmpDay;

                $tmpMonth = $secondSchedule->month;
                $secondSchedule->month = $firstSchedule->month;
                $firstSchedule->month = $tmpMonth;

                $tmpWeekDay = $secondSchedule->week_day;
                $secondSchedule->week_day = $firstSchedule->week_day;
                $firstSchedule->week_day = $tmpWeekDay;

                $tmpSchedule = $secondSchedule->schedule;
                $secondSchedule->schedule = $firstSchedule->schedule;
                $firstSchedule->schedule = $tmpSchedule;

                $firstSchedule->unavailable = in_array(Yii::app()->user->year . "-" . str_pad($firstSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($firstSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;
                $secondSchedule->unavailable = in_array(Yii::app()->user->year . "-" . str_pad($secondSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($secondSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;

                $firstSchedule->save();
                $secondSchedule->save();

                array_push($schedulesToCheckHardUnavailability, $firstSchedule);
                array_push($schedulesToCheckHardUnavailability, $secondSchedule);
            } else if ($firstSchedule != null || $secondSchedule != null) {
                $firstScheduleDate = new Datetime(Yii::app()->user->year . "-" . str_pad($_POST["firstSchedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["firstSchedule"]["day"]);
                $secondScheduleDate = new Datetime(Yii::app()->user->year . "-" . str_pad($_POST["secondSchedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["secondSchedule"]["day"]);
                if ($secondSchedule != null) {
                    $daysDiff = $secondScheduleDate->diff($firstScheduleDate)->format('%r%a');
                    $date = new Datetime(Yii::app()->user->year . "-" . str_pad($secondSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . $secondSchedule->day);
                    $date->modify($daysDiff . " day");

                    array_push($changes, [
                        "firstSchedule" => ["day" => $date->format('j'), "month" => $date->format('n'), "schedule" => $_POST["firstSchedule"]["schedule"]],
                        "secondSchedule" => ["day" => $secondSchedule->day, "month" => $secondSchedule->month, "schedule" => $secondSchedule->schedule]
                    ]);

                    $secondSchedule->day = $date->format('j');
                    $secondSchedule->month = $date->format('m');
                    $secondSchedule->week_day = $_POST["firstSchedule"]["week_day"];
                    $secondSchedule->schedule = $_POST["firstSchedule"]["schedule"];
                    $secondSchedule->unavailable = in_array(Yii::app()->user->year . "-" . str_pad($secondSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($secondSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;
                    $secondSchedule->save();

                    array_push($schedulesToCheckHardUnavailability, $secondSchedule);
                } else {
                    $daysDiff = $firstScheduleDate->diff($secondScheduleDate)->format('%r%a');
                    $date = new Datetime(Yii::app()->user->year . "-" . str_pad($firstSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . $firstSchedule->day);
                    $date->modify($daysDiff . " day");

                    array_push($changes, [
                        "firstSchedule" => ["day" => $firstSchedule->day, "month" => $firstSchedule->month, "schedule" => $firstSchedule->schedule],
                        "secondSchedule" => ["day" => $date->format('j'), "month" => $date->format('n'), "schedule" => $_POST["secondSchedule"]["schedule"]]
                    ]);

                    $firstSchedule->day = $date->format('j');
                    $firstSchedule->month = $date->format('n');
                    $firstSchedule->week_day = $_POST["secondSchedule"]["week_day"];
                    $firstSchedule->schedule = $_POST["secondSchedule"]["schedule"];
                    $firstSchedule->unavailable = in_array(Yii::app()->user->year . "-" . str_pad($firstSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($firstSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;
                    $firstSchedule->save();

                    array_push($schedulesToCheckHardUnavailability, $firstSchedule);
                }
            }
        }
        $hardUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "hard");
        foreach ($schedulesToCheckHardUnavailability as $scheduleToCheckUnavailability) {
            $dateStr = Yii::app()->user->year . "-" . str_pad($scheduleToCheckUnavailability->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($scheduleToCheckUnavailability->day, 2, "0", STR_PAD_LEFT);
            if (in_array($dateStr, $hardUnavailableDays)) {
                $scheduleToCheckUnavailability->delete();
            }
        }

        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $_POST["classroomId"]]);
        $workloads = Yii::app()->db->createCommand(
            " select " .
            " edcenso_discipline.id as disciplineId, " .
            " edcenso_discipline.name as disciplineName, " .
            " (select count(schedule.id) from schedule where classroom_fk = " . $_POST["classroomId"] . " and schedule.unavailable = 0 and schedule.discipline_fk = disciplineId) as workloadUsed, " .
            " curricular_matrix.workload as workloadTotal " .
            " from curricular_matrix " .
            " join edcenso_discipline on edcenso_discipline.id = curricular_matrix.discipline_fk " .
            " where curricular_matrix.stage_fk = " . $classroom->edcenso_stage_vs_modality_fk . " and curricular_matrix.school_fk = " . Yii::app()->user->school)->queryAll();
        echo json_encode(["valid" => true, "changes" => $changes, "disciplines" => $workloads]);
    }

    public function actionRemoveSchedule()
    {
        $removes = [];
        $disciplines = [];

        $weekOfTheChange = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["schedule"]["day"], 'month' => $_POST["schedule"]["month"], 'schedule' => $_POST["schedule"]["schedule"]));
        $weekLimit = $_POST["replicate"] ? 53 : $weekOfTheChange["week"];
        for ($week = $weekOfTheChange["week"]; $week <= $weekLimit; $week++) {
            $schedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'week' => $week, 'week_day' => $_POST["schedule"]["week_day"], 'schedule' => $_POST["schedule"]["schedule"]));
            if ($schedule != null) {
                array_push($removes, ["day" => $schedule->day, "month" => $schedule->month, "schedule" => $schedule->schedule]);
                if (!$schedule->unavailable) {
                    $key = array_search($schedule->discipline_fk, array_column($disciplines, 'disciplineId'));
                    if ($key === false) {
                        array_push($disciplines, ["disciplineId" => $schedule->discipline_fk, "workloadUsed" => -1]);
                    } else {
                        $disciplines[$key]["workloadUsed"]--;
                    }
                }
                $schedule->delete();
            }
        }
        echo json_encode(["valid" => true, "removes" => $removes, "disciplines" => $disciplines]);
    }

    public function actionAddSchedule()
    {
        $adds = [];
        $disciplines = [];

        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $_POST["classroomId"]]);
        $turn = $classroom->initial_hour < 12 ? 0 : ($classroom->initial_hour >= 12 && $classroom->initial_hour < 19 ? 1 : 2);

        $date = new Datetime(Yii::app()->user->year . "-" . str_pad($_POST["schedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["schedule"]["day"]);
        $softUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "soft");
        $hardUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "hard");

        $weekOfTheChange = $date->format("W");
        $weekLimit = $_POST["replicate"] ? 53 : $weekOfTheChange;
        for ($week = $weekOfTheChange; $week <= $weekLimit; $week++) {
            if (!in_array($date->format("Y-m-d"), $hardUnavailableDays)) {
                $schedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'week' => $week, 'week_day' => $_POST["schedule"]["week_day"], 'schedule' => $_POST["schedule"]["schedule"]));
                if ($schedule == null) {
                    $schedule = new Schedule();
                    $schedule->discipline_fk = $_POST["disciplineId"];
                    $schedule->classroom_fk = $_POST["classroomId"];
                    $schedule->day = $date->format("j");
                    $schedule->month = $date->format("n");
                    $schedule->week = $week;
                    $schedule->week_day = $_POST["schedule"]["week_day"];
                    $schedule->schedule = $_POST["schedule"]["schedule"];
                    if (in_array($date->format("Y-m-d"), $softUnavailableDays)) {
                        $schedule->unavailable = 1;
                    } else {
                        $schedule->unavailable = 0;
                        $key = array_search($_POST["disciplineId"], array_column($disciplines, 'disciplineId'));
                        if ($key === false) {
                            array_push($disciplines, ["disciplineId" => $_POST["disciplineId"], "workloadUsed" => 1]);
                        } else {
                            $disciplines[$key]["workloadUsed"]++;
                        }
                    }
                    $schedule->turn = $turn;
                    $schedule->save();
                    array_push($adds, [
                        "id" => $schedule->id,
                        "day" => $schedule->day,
                        "month" => $schedule->month,
                        "schedule" => $schedule->schedule,
                        "disciplineId" => $schedule->discipline_fk,
                        "disciplineName" => $schedule->disciplineFk->name,
                    ]);
                }
            }
            $date->modify("+7 days");
            if ($date->format("Y") != Yii::app()->user->year) {
                break;
            }
        }
        echo json_encode(["valid" => true, "adds" => $adds, "disciplines" => $disciplines]);
    }

    public function actionGetInstructorDisciplines($id)
    {
        /** @var $istructorDisciplines InstructorDisciplines[]
         * @var $idisc InstructorDisciplines
         */
        $response = [];
        $instructorDisciplines = InstructorDisciplines::model()->findAllByAttributes(["instructor_fk" => $id]);
        foreach ($instructorDisciplines as $idisc) {
            array_push($response, [
                "instructor" => $id, "discipline" => $idisc->discipline_fk,
                "discipline_name" => $idisc->disciplineFk->name, "stage" => $idisc->stage_vs_modality_fk,
                "stage_name" => $idisc->stageVsModalityFk->name,
            ]);
        }
        echo json_encode($response);
    }

    public function actionAddInstructors()
    {
        $ids = $_POST["add-instructors-ids"];
        $school = Yii::App()->user->school;
        foreach ($ids as $id) {
            $instructor = InstructorSchool::model()->findAllByAttributes([
                "instructor_fk" => $id, "school_fk" => $school
            ]);
            if (count($instructor) == 0) {
                $instructor = new InstructorSchool();
                $instructor->school_fk = $school;
                $instructor->instructor_fk = $id;
                if ($instructor->validate()) {
                    $instructor->save();
                }
            }
        }
        $this->render('instructors');
    }


    public function actionAddInstructorsUnavailability()
    {
        $instructorsIds = $_POST["add-instructors-unavailability-ids"];
        $turns = $_POST["add-instructors-unavailability-turn"];
        $schedules = $_POST["add-instructors-unavailability-schedule"];
        $weekDays = $_POST["add-instructors-unavailability-week-day"];

        foreach ($instructorsIds as $instructorId) {
            foreach ($turns as $key => $turn) {
                $schedule = $schedules[$key];
                $weekDay = $weekDays[$key];
                $unavailability = new Unavailability();
                $unavailability->instructor_school_fk = $instructorId;
                $unavailability->week_day = $weekDay;
                $unavailability->turn = $turn;
                $unavailability->schedule = $schedule - 1;
                $unavailability->save();
            }
        }
        $this->render('instructors');
    }

    public function actionAddInstructorsDisciplines()
    {
        if (isset($_POST["add-instructors-disciplines-discipline"]) && isset($_POST["add-instructors-disciplines-stage"]) && isset($_POST["add-instructors-disciplines-ids"])) {
            $instructors = $_POST["add-instructors-disciplines-ids"];
            $stagesDisciplines = $_POST["add-instructors-disciplines-stage"];
            $disciplines = $_POST["add-instructors-disciplines-discipline"];
            foreach ($instructors as $instructor) {
                foreach ($stagesDisciplines as $i => $stages) {
                    foreach ($stages as $stage) {
                        foreach ($disciplines[$i] as $discipline) {
                            $instructorDiscipline = InstructorDisciplines::model()->findAll("stage_vs_modality_fk = :stage and discipline_fk = :discipline and instructor_fk = :instructor", [
                                ":stage" => $stage, ":discipline" => $discipline, ":instructor" => $instructor
                            ]);
                            if ($instructorDiscipline == NULL) {
                                /**
                                 * @var $instructorDiscipline InstructorDisciplines
                                 */
                                $instructorDiscipline = new InstructorDisciplines();
                                $instructorDiscipline->stage_vs_modality_fk = $stage;
                                $instructorDiscipline->discipline_fk = $discipline;
                                $instructorDiscipline->instructor_fk = $instructor;
                                $instructorDiscipline->save();
                            }
                        }
                    }
                }
            }
        }
        $this->render('index');
    }

    public function actionLoadUnavailability()
    {
        /** @var  $iu Unavailability */
        $instructorId = $_POST["id"];
        $instructorUnavailability = Unavailability::model()->findAll("instructor_school_fk = :instructorSchool", [":instructorSchool" => $instructorId]);
        $response = [];
        foreach ($instructorUnavailability as $iu) {
            if (!isset($response[$iu->week_day])) {
                $response[$iu->week_day] = ["0" => [], "1" => [], "2" => []];
            }
            array_push($response[$iu->week_day][$iu->turn], $iu->schedule);
        }
        echo json_encode($response);
    }

    public function actionGetInstructors()
    {
        if (isset($_POST['discipline'])) {
            $id = $_POST['discipline'];
            $list = CHtml::listData(InstructorDisciplines::model()->findAllByAttributes(["discipline_fk" => $id]), "instructorFk.id", "instructorFk.name");
            echo CHtml::tag('option', ["value" => "null"], "Sem Instrutor");
            foreach ($list as $id => $name) {
                echo CHtml::tag('option', ["value" => $id], $name);
            }
        }
    }

    public function actionChangeInstructor()
    {
        if (isset($_POST['schedule'], $_POST['instructor'])) {
            $scheduleId = $_POST['schedule'];
            $instructorId = $_POST['instructor'];
            if ($instructorId == 'null') {
                $instructorId = null;
            }
            /** @var Schedule $schedule */
            $schedule = Schedule::model()->findByPk($scheduleId);
            $schedule->instructor_fk = $instructorId;
            $schedule->save();

            $this->actionGetTimesheet($schedule->classroom_fk);
        }
    }
}