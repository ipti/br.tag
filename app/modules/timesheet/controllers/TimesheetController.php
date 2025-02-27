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
                    'getTimesheet', 'generateTimesheet', "addinstructorsdisciplines", "changeSchedules", "ChangeInstructor", "changeUnavailableSchedule",
                    "addSubstituteInstructorDay", "saveSubstituteInstructorDay", "deleteSubstituteInstructorDay",
                    "getDisciplines", "getFrequency", "fixBuggedUnavailableDaysFor2024"

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
        $calendarTypes = CalendarEventType::model()->findAll();
        $this->render('index', array(
            "calendarTypes" => $calendarTypes,
        ));
    }

    public function actionInstructors()
    {
        $this->render('instructors');
    }

    public function actionGetTimesheet($classroomId = NULL)
    {
        if ($classroomId == NULL) {
            $classroomId = $_POST["cid"];
        }
        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $classroomId]);

        if ($classroom->calendar_fk !== null) {
            $calendarEvents = Yii::app()->db->createCommand("select ce.*, cet.* from calendar_event as ce inner join calendar_event_type as cet on cet.id = ce.calendar_event_type_fk where ce.calendar_fk = :calendar_fk")->bindParam(":calendar_fk", $classroom->calendar_fk)->queryAll();
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
                        "year" => $date->format("Y"),
                        "name" => yii::t('timesheetModule.timesheet', $calendarEvent["name"]),
                        "icon" => $calendarEvent["icon"],
                        "color" => $calendarEvent["color"]
                    ]);
                }
            }
            $response["calendarEvents"] = $calendarEventsArray;

            $curricularMatrixes = TimesheetCurricularMatrix::model()->findAll("stage_fk = :stage and school_year = :year", [
                ":stage" => $classroom->edcenso_stage_vs_modality_fk, ":year" => Yii::app()->user->year
            ]);
            if (count($curricularMatrixes) !== 0) {
                $response["disciplines"] = [];
                foreach ($curricularMatrixes as $cm) {
                    $instructorName = Yii::app()->db->createCommand("
                    select ii.name from teaching_matrixes tm
                    join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                    join instructor_identification ii on itd.instructor_fk = ii.id
                    where itd.classroom_id_fk = :cid and tm.curricular_matrix_fk = :cmid")->bindParam(":cmid", $cm->id)->bindParam(":cid", $classroomId)->queryRow();
                    array_push($response["disciplines"], ["disciplineId" => $cm->discipline_fk, "disciplineName" => $cm->disciplineFk->name, "workloadUsed" => 0, "workloadTotal" => $cm->workload, "instructorName" => $instructorName["name"]]);
                }

                if ($classroomId != "") {
                    /** @var Schedule[] $schedules */
                    $schedules = Schedule::model()->findAll("classroom_fk = :classroom", [":classroom" => $classroomId]);
                    if (count($schedules) == 0) {
                        $response["hardUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "hard");
                        $response["softUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "soft");
                        $response["schedules"] = [];
                    } else {
                        $response["hardUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "hard");
                        $response["softUnavailableDays"] = $this->getUnavailableDays($classroomId, false, "soft");

                        $response["schedules"] = [];
                        $vha = (object)\InstanceConfig::model()->findByAttributes(array('parameter_key' => 'VHA'));
                        $hours = $vha["value"] / 60;
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
                                $response["disciplines"][$cmKey]["workloadUsed"] += $hours;
                            }

                            $response["schedules"][$schedule->year][$schedule->month][$schedule->schedule][$schedule->day] = [
                                "id" => $schedule->id,
                                "instructorId" => $schedule->instructor_fk,
                                "instructorInfo" => $instructorInfo,
                                "disciplineId" => $schedule->discipline_fk,
                                "disciplineName" => $schedule->disciplineFk->name,
                                "unavailable" => $schedule->unavailable,
                            ];
                        }
                    }

                    $calendar = $classroom->calendarFk;

                    $begin = new Datetime($calendar->start_date);
                    $begin->modify("first day of this month");
                    $end = new Datetime($calendar->end_date);
                    $end->modify("first day of next month");
                    $interval = DateInterval::createFromDateString('1 month');
                    $period = new DatePeriod($begin, $interval, $end);
                    $daysPerMonth = [];
                    foreach ($period as $dt) {
                        $daysPerMonth[$dt->format("Y")][$dt->format("n")]["daysCount"] = $dt->format("t");
                        $daysPerMonth[$dt->format("Y")][$dt->format("n")]["monthName"] = $dt->format("F");
                        $daysPerMonth[$dt->format("Y")][$dt->format("n")]["weekDayOfTheFirstDay"] = $dt->format("w");
                    }
                    $response["daysPerMonth"] = $daysPerMonth;
                    $response["turn"] = $classroom->turn;
                    $response["valid"] = TRUE;
                } else {
                    $response["valid"] = NULL;
                }
            } else {
                $response = ["valid" => FALSE, "error" => "curricularMatrix"];
            }
        } else {
            $response = ["valid" => FALSE, "error" => "calendar"];
        }
        echo json_encode($response);
    }

    private function getUnavailableDays($classroomId, $fullDate, $level)
    {
        $unavailableDays = [];
        $classroom = Classroom::model()->findByPk($classroomId);
        $calendar = $classroom->calendarFk;
        if ($level == "hard") {
            $firstDay = new Datetime($calendar->start_date);
            $lastDay = new Datetime($calendar->end_date);
            $unavailableEvents = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce where ce.calendar_fk = :calendar_fk and ce.calendar_event_type_fk = 102")->bindParam(":calendar_fk", $calendar->id)->queryAll();
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
            $begin = new Datetime($firstDay->format("Y-m") . "-01");
            $end = new Datetime($lastDay->format("Y-m-d"));
            $end->modify('last day of this month');
            $end->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $date) {
                if ($date->format("Ymd") < $firstDay->format("Ymd") || $date->format("Ymd") > $lastDay->format("Ymd") || in_array($date->format("Y-m-d"), $unavailableEventsArray)) {
                    if ($fullDate) {
                        if (!in_array($date->format("Y-m-d"), $unavailableDays)) {
                            array_push($unavailableDays, $date->format("Y-m-d"));
                        }
                    } else {
                        if (!isset($unavailableDays[$date->format("Y")][$date->format("n")])) {
                            $unavailableDays[$date->format("Y")][$date->format("n")] = [];
                        }
                        if (!in_array($date->format("j"), $unavailableDays[$date->format("Y")][$date->format("n")])) {
                            array_push($unavailableDays[$date->format("Y")][$date->format("n")], $date->format("j"));
                        }
                    }
                }
            }
        } else if ($level == "soft") {
            $unavailableEvents = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce where ce.calendar_fk = :calendar_fk and (ce.calendar_event_type_fk = 101 or ce.calendar_event_type_fk = 104)")->bindParam(":calendar_fk", $calendar->id)->queryAll();
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
                        if (!isset($unavailableDays[$date->format("Y")][$date->format("n")])) {
                            $unavailableDays[$date->format("Y")][$date->format("n")] = [];
                        }
                        if (!in_array($date->format("j"), $unavailableDays[$date->format("Y")][$date->format("n")])) {
                            array_push($unavailableDays[$date->format("Y")][$date->format("n")], $date->format("j"));
                        }
                    }
                }
            }
        }
        return $unavailableDays;
    }

    public function actionGenerateTimesheet()
    {
        /**
         * @var $classroom Classroom
         * @var $instructorDisciplines InstructorDisciplines
         */
        $classroomId = $_POST["classroom"];
        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $classroomId]);
        $calendar = $classroom->calendarFk;

        $criteria = new CDbCriteria();
        $criteria->alias = "cf";
        $criteria->join = "join schedule on schedule.id = cf.schedule_fk";
        $criteria->condition = "schedule.classroom_fk = :classroom_fk";
        $criteria->params = ["classroom_fk" => $classroomId];
        $hasFrequency = ClassFaults::model()->exists($criteria);

        $criteria = new CDbCriteria();
        $criteria->alias = "cc";
        $criteria->join = "join schedule on schedule.id = cc.schedule_fk";
        $criteria->condition = "schedule.classroom_fk = :classroom_fk";
        $criteria->params = ["classroom_fk" => $classroomId];
        $hasClassContent = ClassContents::model()->exists($criteria);

        if (!$hasFrequency && !$hasClassContent) {
            $curricularMatrix = TimesheetCurricularMatrix::model()->findAll("stage_fk = :stage and school_year = :year", [
                ":stage" => $classroom->edcenso_stage_vs_modality_fk, ":year" => Yii::app()->user->year
            ]);
            if ($curricularMatrix != null) {
                Schedule::model()->deleteAll("classroom_fk = :classroom", [":classroom" => $classroomId]);

                $schedulesQuantity = 10;

//                $turn = 0;
//                if ($classroom->initial_hour < 12) {
//                    $turn = 0;
//                }
//                if ($classroom->initial_hour >= 12 && $classroom->initial_hour < 19) {
//                    $turn = 1;
//                }
//                if ($classroom->initial_hour >= 19) {
//                    $turn = 2;
//                }


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
                        $unavailableEvents = Yii::app()->db->createCommand("select ce.start_date, ce.end_date, ce.calendar_event_type_fk from calendar_event as ce where ce.calendar_fk = :calendar_fk and (ce.calendar_event_type_fk = 101 or ce.calendar_event_type_fk = 102 or ce.calendar_event_type_fk = 104)")->bindParam(":calendar_fk", $calendar->id)->queryAll();
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

                        $firstDay = new DateTime($calendar->start_date);
                        $lastDay = new DateTime($calendar->end_date);
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
                                $sc->year = $date->format("Y");
                                $sc->week = $date->format("W");
                                $sc->week_day = $schedule->week_day;
                                $sc->schedule = $schedule->schedule;
                                $sc->unavailable = in_array($date, $softUnavailableDaysArray) ? 1 : 0;
                                $sc->turn = $classroom->turn;
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
            echo json_encode(["valid" => false, "error" => "frequencyOrClassContentFilled"]);
        }
    }

    public function actionChangeUnavailableSchedule()
    {
        $schedule = Schedule::model()->findByPk($_POST["schedule"]);
        $schedule->unavailable = $schedule->unavailable ? 0 : 1;
        $schedule->save();

        $disciplines = [];
        $vha = (object)\InstanceConfig::model()->findByAttributes(array('parameter_key' => 'VHA'));
        $hours = $vha["value"] / 60;
        array_push($disciplines, ["disciplineId" => $schedule->discipline_fk, "workloadUsed" => $schedule->unavailable ? -$hours : $hours]);
        echo json_encode(["unavailable" => $schedule->unavailable, "disciplines" => $disciplines]);
    }

    public function actionChangeSchedules()
    {
        $changes = [];

        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $_POST["classroomId"]]);
        $calendar = $classroom->calendarFk;
        $finalDate = new DateTime($calendar->end_date);

        $firstScheduleDate = new Datetime($_POST["firstSchedule"]["year"] . "-" . str_pad($_POST["firstSchedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["firstSchedule"]["day"]);
        $secondScheduleDate = new Datetime($_POST["secondSchedule"]["year"] . "-" . str_pad($_POST["secondSchedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["secondSchedule"]["day"]);
        if (!$_POST["replicate"]) {
            $finalDate = new Datetime($_POST["firstSchedule"]["year"] . "-" . str_pad($_POST["firstSchedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["firstSchedule"]["day"]);
        }

        $lastClassFaultDay = Yii::app()->db->createCommand("select s.* from class_faults as cf join schedule as s on cf.schedule_fk = s.id where s.classroom_fk = :id order by month DESC, day DESC limit 1")->bindParam(":id", $_POST["classroomId"])->queryRow();
        $lastClassContentDay = Yii::app()->db->createCommand("select s.* from class_contents as cc join schedule as s on cc.schedule_fk = s.id where s.classroom_fk = :id order by month DESC, day DESC limit 1")->bindParam(":id", $_POST["classroomId"])->queryRow();
        $lastClassFaultDate = new Datetime($lastClassFaultDay["year"] . "-" . str_pad($lastClassFaultDay["month"], 2, "0", STR_PAD_LEFT) . "-" . str_pad($lastClassFaultDay["day"], 2, "0", STR_PAD_LEFT));
        $lastClassContentDate = new Datetime($lastClassContentDay["year"] . "-" . str_pad($lastClassContentDay["month"], 2, "0", STR_PAD_LEFT) . "-" . str_pad($lastClassContentDay["day"], 2, "0", STR_PAD_LEFT));

        if (($lastClassFaultDay == null || ($firstScheduleDate > $lastClassFaultDate && $secondScheduleDate > $lastClassFaultDate)) &&
            ($lastClassContentDay == null || ($firstScheduleDate > $lastClassContentDate && $secondScheduleDate > $lastClassContentDate))) {
            $schedulesToCheckHardUnavailability = [];
            $softUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "soft");
            while ($firstScheduleDate <= $finalDate || $secondScheduleDate <= $finalDate) {
                $firstSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'year' => $firstScheduleDate->format("Y"), 'month' => $firstScheduleDate->format("n"), 'day' => $firstScheduleDate->format("j"), 'schedule' => $_POST["firstSchedule"]["schedule"]));
                $secondSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'year' => $secondScheduleDate->format("Y"), 'month' => $secondScheduleDate->format("n"), 'day' => $secondScheduleDate->format("j"), 'schedule' => $_POST["secondSchedule"]["schedule"]));
                if ($firstSchedule != null && $secondSchedule != null) {
                    array_push($changes, [
                        "firstSchedule" => ["day" => $firstSchedule->day, "month" => $firstSchedule->month, "year" => $firstSchedule->year, "schedule" => $firstSchedule->schedule],
                        "secondSchedule" => ["day" => $secondSchedule->day, "month" => $secondSchedule->month, "year" => $secondSchedule->year, "schedule" => $secondSchedule->schedule]
                    ]);

                    $tmpDay = $secondSchedule->day;
                    $secondSchedule->day = $firstSchedule->day;
                    $firstSchedule->day = $tmpDay;

                    $tmpMonth = $secondSchedule->month;
                    $secondSchedule->month = $firstSchedule->month;
                    $firstSchedule->month = $tmpMonth;

                    $tmpYear = $secondSchedule->year;
                    $secondSchedule->year = $firstSchedule->year;
                    $firstSchedule->year = $tmpYear;

                    $tmpWeekDay = $secondSchedule->week_day;
                    $secondSchedule->week_day = $firstSchedule->week_day;
                    $firstSchedule->week_day = $tmpWeekDay;

                    $tmpSchedule = $secondSchedule->schedule;
                    $secondSchedule->schedule = $firstSchedule->schedule;
                    $firstSchedule->schedule = $tmpSchedule;

                    $firstSchedule->unavailable = in_array($firstSchedule->year . "-" . str_pad($firstSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($firstSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;
                    $secondSchedule->unavailable = in_array($secondSchedule->year . "-" . str_pad($secondSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($secondSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;

                    $firstSchedule->save();
                    $secondSchedule->save();

                    array_push($schedulesToCheckHardUnavailability, $firstSchedule);
                    array_push($schedulesToCheckHardUnavailability, $secondSchedule);
                } else if ($firstSchedule != null || $secondSchedule != null) {
                    if ($secondSchedule != null) {
                        array_push($changes, [
                            "firstSchedule" => ["day" => $firstScheduleDate->format('j'), "month" => $firstScheduleDate->format('n'), "year" => $firstScheduleDate->format("Y"), "schedule" => $_POST["firstSchedule"]["schedule"]],
                            "secondSchedule" => ["day" => $secondSchedule->day, "month" => $secondSchedule->month, "year" => $secondSchedule->year, "schedule" => $secondSchedule->schedule]
                        ]);

                        $secondSchedule->day = $firstScheduleDate->format('j');
                        $secondSchedule->month = $firstScheduleDate->format('m');
                        $secondSchedule->year = $firstScheduleDate->format('Y');
                        $secondSchedule->week_day = $firstScheduleDate->format('w');
                        $secondSchedule->schedule = $_POST["firstSchedule"]["schedule"];
                        $secondSchedule->unavailable = in_array($secondSchedule->year . "-" . str_pad($secondSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($secondSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;
                        $secondSchedule->save();

                        array_push($schedulesToCheckHardUnavailability, $secondSchedule);
                    } else {
                        array_push($changes, [
                            "firstSchedule" => ["day" => $firstSchedule->day, "month" => $firstSchedule->month, "year" => $firstSchedule->year, "schedule" => $firstSchedule->schedule],
                            "secondSchedule" => ["day" => $secondScheduleDate->format('j'), "month" => $secondScheduleDate->format('n'), "year" => $secondScheduleDate->format("Y"), "schedule" => $_POST["secondSchedule"]["schedule"]]
                        ]);

                        $firstSchedule->day = $secondScheduleDate->format('j');
                        $firstSchedule->month = $secondScheduleDate->format('n');
                        $firstSchedule->year = $secondScheduleDate->format('Y');
                        $firstSchedule->week_day = $secondScheduleDate->format('w');
                        $firstSchedule->schedule = $_POST["secondSchedule"]["schedule"];
                        $firstSchedule->unavailable = in_array($firstSchedule->year . "-" . str_pad($firstSchedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($firstSchedule->day, 2, "0", STR_PAD_LEFT), $softUnavailableDays) ? 1 : 0;
                        $firstSchedule->save();

                        array_push($schedulesToCheckHardUnavailability, $firstSchedule);
                    }
                }
                $firstScheduleDate->modify("+7 days");
                $secondScheduleDate->modify("+7 days");
            }

            $hardUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "hard");
            foreach ($schedulesToCheckHardUnavailability as $scheduleToCheckUnavailability) {
                $dateStr = $scheduleToCheckUnavailability->year . "-" . str_pad($scheduleToCheckUnavailability->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($scheduleToCheckUnavailability->day, 2, "0", STR_PAD_LEFT);
                if (in_array($dateStr, $hardUnavailableDays)) {
                    $scheduleToCheckUnavailability->delete();
                }
            }

            $workloads = Yii::app()->db->createCommand(
                " select " .
                " edcenso_discipline.id as disciplineId, " .
                " edcenso_discipline.name as disciplineName, " .
                " (select (count(schedule.id) * (select value from instance_config where parameter_key = 'VHA') / 60) from schedule where classroom_fk = " . $_POST["classroomId"] . " and schedule.unavailable = 0 and schedule.discipline_fk = disciplineId) as workloadUsed, " .
                " curricular_matrix.workload as workloadTotal, " .
                " (select ii.name from teaching_matrixes tm join instructor_teaching_data itd on itd.id = tm.teaching_data_fk join instructor_identification ii on itd.instructor_fk = ii.id where itd.classroom_id_fk = " . $_POST["classroomId"] . " and tm.curricular_matrix_fk = curricular_matrix.id) as instructorName " .
                " from curricular_matrix " .
                " join edcenso_discipline on edcenso_discipline.id = curricular_matrix.discipline_fk " .
                " where curricular_matrix.stage_fk = :stage and curricular_matrix.school_year = :year")
                ->bindParam(":stage", $classroom->edcenso_stage_vs_modality_fk)->bindParam(":year", Yii::app()->user->year)->queryAll();
            echo json_encode(["valid" => true, "changes" => $changes, "disciplines" => $workloads]);
        } else {
            echo json_encode([
                "valid" => false,
                "lastClassFaultDate" => $lastClassFaultDay != null ? str_pad($lastClassFaultDay["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($lastClassFaultDay["month"], 2, "0", STR_PAD_LEFT) . "/" . $lastClassFaultDay["year"] : "",
                "lastClassContentDate" => $lastClassContentDay != null ? str_pad($lastClassContentDay["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($lastClassContentDay["month"], 2, "0", STR_PAD_LEFT) . "/" . $lastClassContentDay["year"] : ""
            ]);
        }
    }

    public function actionRemoveSchedule()
    {
        $removes = [];
        $disciplines = [];

        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $_POST["classroomId"]]);
        $calendar = $classroom->calendarFk;
        $finalDate = new DateTime($calendar->end_date);

        $selectedDate = new Datetime($_POST["schedule"]["year"] . "-" . str_pad($_POST["schedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["schedule"]["day"]);
        if ($_POST["schedule"]["hardUnavailableDaySelected"] || !$_POST["replicate"]) {
            $finalDate = new Datetime($_POST["schedule"]["year"] . "-" . str_pad($_POST["schedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["schedule"]["day"]);
        }
        $vha = (object)\InstanceConfig::model()->findByAttributes(array('parameter_key' => 'VHA'));
        $hours = $vha["value"] / 60;
        for ($date = $selectedDate; $date <= $finalDate; $date->modify("+7 days")) {
            $schedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'year' => $date->format("Y"), 'month' => $date->format("n"), 'day' => $date->format("j"), 'schedule' => $_POST["schedule"]["schedule"]));
            if ($schedule != null) {
                array_push($removes, ["day" => $schedule->day, "month" => $schedule->month, "year" => $schedule->year, "schedule" => $schedule->schedule]);
                if (!$schedule->unavailable) {
                    $key = array_search($schedule->discipline_fk, array_column($disciplines, 'disciplineId'));
                    if ($key === false) {
                        array_push($disciplines, ["disciplineId" => $schedule->discipline_fk, "workloadUsed" => -$hours]);
                    } else {
                        $disciplines[$key]["workloadUsed"] -= $hours;
                    }
                }

                if (!empty($schedule->classContents)) {
                    //Verifica se o schedule a ser removido possui aula ministrada. Se possuir, vincula a aula ministrada ao próximo schedule
                    //OBS1: Lembrando: a aula ministrada fica armazenada apenas no primeiro schedule do dia (ou da disciplina no fundamental maior)
                    //OBS2: Para frequência não precisa, uma vez que o registro de frequência fica vinculado a todos os schedules do dia.
                    //OBS3: No fundamental maior, a regra é a mesma, mas com filtro de disciplina
                    if (TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk)) {
                        $secondScheduleInTheDay = Schedule::model()->find("classroom_fk = :classroom_fk and year = :year and month = :month and day = :day and schedule != :schedule order by schedule",
                            [
                                "classroom_fk" => $_POST["classroomId"],
                                "year" => $date->format("Y"),
                                "month" => $date->format("n"),
                                "day" => $date->format("j"),
                                "schedule" => $_POST["schedule"]["schedule"]
                            ]
                        );
                    } else {
                        $secondScheduleInTheDay = Schedule::model()->find("classroom_fk = :classroom_fk and year = :year and month = :month and day = :day and schedule != :schedule and discipline_fk = :discipline_fk order by schedule",
                            [
                                "classroom_fk" => $_POST["classroomId"],
                                "year" => $date->format("Y"),
                                "month" => $date->format("n"),
                                "day" => $date->format("j"),
                                "schedule" => $_POST["schedule"]["schedule"],
                                "discipline_fk" => $schedule->discipline_fk
                            ]
                        );
                    }
                    foreach ($schedule->classContents as $cc) {
                        $classContent = new ClassContents();
                        $classContent->schedule_fk = $secondScheduleInTheDay->id;
                        $classContent->course_class_fk = $cc->course_class_fk;
                        $classContent->save();
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

        $softUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "soft");
        $hardUnavailableDays = $this->getUnavailableDays($_POST["classroomId"], true, "hard");

        $calendar = $classroom->calendarFk;
        $finalDate = new DateTime($calendar->end_date);


        $selectedDate = new Datetime($_POST["schedule"]["year"] . "-" . str_pad($_POST["schedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["schedule"]["day"]);
        if ($_POST["hardUnavailableDaySelected"] || !$_POST["replicate"]) {
            $finalDate = new Datetime($_POST["schedule"]["year"] . "-" . str_pad($_POST["schedule"]["month"], 2, "0", STR_PAD_LEFT) . "-" . $_POST["schedule"]["day"]);
        }
        $vha = (object)\InstanceConfig::model()->findByAttributes(array('parameter_key' => 'VHA'));
        $hours = $vha["value"] / 60;
        for ($date = $selectedDate; $date <= $finalDate; $date->modify("+7 days")) {
            if (!in_array($date->format("Y-m-d"), $hardUnavailableDays) || $_POST["hardUnavailableDaySelected"]) {
                $schedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'year' => $date->format("Y"), 'month' => $date->format("n"), 'day' => $date->format("j"), 'schedule' => $_POST["schedule"]["schedule"]));
                if ($schedule == null) {
                    $schedule = new Schedule();
                    $schedule->discipline_fk = $_POST["disciplineId"];
                    $schedule->classroom_fk = $_POST["classroomId"];
                    $schedule->day = $date->format("j");
                    $schedule->month = $date->format("n");
                    $schedule->year = $date->format("Y");
                    $schedule->week = $date->format("W");
                    $schedule->week_day = $date->format("w");
                    $schedule->schedule = $_POST["schedule"]["schedule"];

                    if (in_array($date->format("Y-m-d"), $softUnavailableDays)) {
                        $schedule->unavailable = 1;
                    } else {
                        $schedule->unavailable = 0;
                        $key = array_search($_POST["disciplineId"], array_column($disciplines, 'disciplineId'));
                        if ($key === false) {
                            array_push($disciplines, ["disciplineId" => $_POST["disciplineId"], "workloadUsed" => $hours]);
                        } else {
                            $disciplines[$key]["workloadUsed"] += $hours;
                        }
                    }
                    $schedule->turn = $classroom->turn;
                    $schedule->save();

                    if (TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk)) {
                        //Verifica quais alunos de fundamental menor tomou falta esse DIA. Nesse caso, para quem tomou, criar a falta também para esse schedule.
                        //OBS: Para aulas ministradas não precisa, uma vez que o registro de aula ministrada fica vinculado apenas na primeira schedule do dia.
                        $anyOtherScheduleInTheDay = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'year' => $date->format("Y"), 'month' => $date->format("n"), 'day' => $date->format("j")), 'schedule != :schedule', [":schedule" => $_POST["schedule"]["schedule"]]);
                        if ($anyOtherScheduleInTheDay != null && !empty($anyOtherScheduleInTheDay->classFaults)) {
                            foreach ($anyOtherScheduleInTheDay->classFaults as $cf) {
                                $classFault = new ClassFaults();
                                $classFault->student_fk = $cf->student_fk;
                                $classFault->schedule_fk = $schedule->id;
                                $classFault->save();
                            }
                        }
                    }

                    array_push($adds, [
                        "id" => $schedule->id,
                        "day" => $schedule->day,
                        "month" => $schedule->month,
                        "year" => $schedule->year,
                        "schedule" => $schedule->schedule,
                        "disciplineId" => $schedule->discipline_fk,
                        "disciplineName" => $schedule->disciplineFk->name,
                        "unavailable" => $schedule->unavailable
                    ]);
                }
            }
        }
        echo json_encode(["valid" => true, "adds" => $adds, "disciplines" => $disciplines]);
    }

    public function actionAddSubstituteInstructorDay()
    {
        $scheduleId = Yii::app()->request->getPost('schedule');
        $instructorId = Yii::app()->request->getPost('instructorId');

        $transaction = Yii::app()->db->beginTransaction();

        if(isset($scheduleId) && isset($instructorId)){
            try{
                self::actionSaveSubstituteInstructorDay($instructorId, $scheduleId);
                $transaction->commit();
                header('HTTP/1.1 200 OK');
                echo json_encode(["valid" => true]);
                Yii::app()->end();
            } catch (Exception $e) {
                $transaction->rollback();
                TLog::error("Erro durante a transação de AddSubstituteInstructorDay", array(
                    "ExceptionMessage" => $e->getMessage()
                ));
                throw new Exception($e->getMessage(), 500, $e);
            }
        }
        header('HTTP/1.1 400 Request invalid');
        echo json_encode(["valid" => false]);
    }

    public function actionSubstituteInstructor()
    {
        $instructorsIdentification = InstructorIdentification::model()->findAll();

        $this->render
        (
            'substituteInstructor',
        );
    }

    public function actionSaveSubstituteInstructorDay($instructorId, $scheduleId)
    {
        $schedule = Schedule::model()->findByPk($scheduleId);
        $instructor = InstructorIdentification::model()->findByPk($instructorId);

        $teachingData = InstructorTeachingData::model()->findByAttributes(
            array(
                "classroom_id_fk" => $schedule->classroom_fk,
                "instructor_fk" => $instructor->id
            )
        );

        // Verifica se já existe um registro de professor substituto
        $substituteInstructor = SubstituteInstructor::model()->findByAttributes(
            array(
                "instructor_fk" => $instructor->id,
                "teaching_data_fk" => $teachingData->id
            )
        );

        // Se não existir, cria um novo
        if($substituteInstructor == null){
            $substituteInstructor = new SubstituteInstructor();
            $substituteInstructor->teaching_data_fk = $teachingData->id;
            $substituteInstructor->instructor_fk = $instructor->id;
        }

        $classroom = Classroom::model()->findByPk($teachingData->classroom_id_fk);
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : ClassesController::checkIsStageMinorEducation($classroom);

        if($substituteInstructor->save()){
            if($isMinor == false){
                $schedule->substitute_instructor_fk = $substituteInstructor->id;
                if($schedule->save()){
                    TLog::info("SubstituteInstructor atribuído a Schedule com sucesso", array(
                        "Schedule" => $schedule->id
                    ));
                    return;
                }
            }

            if($isMinor == true){
                $schedulesAllDay = Schedule::model()->findAllByAttributes(
                    array(
                        "classroom_fk" => $classroom->id,
                        "day" => $schedule->day,
                        "month" => $schedule->month,
                        "year" => $schedule->year
                    )
                );

                foreach($schedulesAllDay as $scheduleUnique){
                    $scheduleUnique->substitute_instructor_fk = $substituteInstructor->id;
                    if($scheduleUnique->save()){
                        TLog::info("SubstituteInstructor atribuído a Schedule com sucesso", array(
                            "Schedule" => $schedule->id
                        ));
                    }
                }
                return;
            }

            TLog::error("Erro: Falha na Atualização do schedule.", array(
                "Schedule" => $schedule->id,
                "ErrorMessage" => $schedule->getErrors()
            ));
        }
        TLog::error("Erro: Falha na atualização de substituteInstructor", array(
            "SubstituteInstructor" => $substituteInstructor->id,
            "ErrorMessage" => $substituteInstructor->getErrors()
        ));
    }

    public function actionDeleteSubstituteInstructorDay(){
        $instructorId = Yii::app()->request->getPost('instructorId');
        $scheduleId =  Yii::app()->request->getPost('schedule');

        $schedule = Schedule::model()->findByPk($scheduleId);
        $instructor = InstructorIdentification::model()->findByPk($instructorId);
        $teachingData = InstructorTeachingData::model()->findByAttributes(
            array(
                "instructor_fk" => $instructor->id,
                "classroom_id_fk" => $schedule->classroom_fk
            ));

        $substituteInstructor = SubstituteInstructor::model()->findByAttributes(
            array(
                "instructor_fk" => $instructor->id,
                "teaching_data_fk" => $teachingData->id
            ));

        $classroom = Classroom::model()->findByPk($teachingData->classroom_id_fk);
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : ClassesController::checkIsStageMinorEducation($classroom);

        $transaction = Yii::app()->db->beginTransaction();

        try{
            // Valida se os schedules foram salvos adequadamente
            $scheduleSaved = true;

            // Para turmas do fundamental menor
            if($isMinor == true){
                $schedulesAllDay = Schedule::model()->findAllByAttributes(
                    array(
                        "classroom_fk" => $classroom->id,
                        "day" => $schedule->day,
                        "month" => $schedule->month,
                        "year" => $schedule->year
                    )
                );

                foreach($schedulesAllDay as $scheduleUnique) {
                    $scheduleUnique->substitute_instructor_fk = null;
                    if(!$scheduleUnique->save()){
                        $scheduleSaved = false;
                        break;
                    }
                }
            }

            // Para turmas do fundamental maior
            if($isMinor == false){
                $schedule->substitute_instructor_fk = null;
                if(!$schedule->save()){
                    $scheduleSaved = false;
                }
            }

            // Verificar se ainda existe algum schedule com a chave do registro de professor substituto para aquela turma
            $allSchedules = Schedule::model()->findAllByAttributes(
                array(
                    "substitute_instructor_fk" => $substituteInstructor->id
                )
            );

            if($allSchedules == null){
                $substituteInstructor->delete();
            }

            if($scheduleSaved){
                $transaction->commit();
                header('HTTP/1.1 200 OK');
                echo json_encode(["valid"=>true]);
                Yii::app()->end();
            }
        }catch(Exception $e){
            $transaction->rollback();
            TLog::error("Erro durante a transação de DeleteSubstituteInstructorDay", array(
                "ExceptionMessage" => $e->getMessage()
            ));
            throw new Exception($e->getMessage(), 500, $e);
        }

        header('HTTP/1.1 400 Request invalid');
        echo json_encode(["valid" => false]);
    }

    public function actionGetFrequency()
    {
        $classroomId = Yii::app()->request->getPost("classroom");
        $instructorId = Yii::app()->request->getPost("instructor");

        $classroom = Classroom::model()->findByPk($classroomId);

        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : ClassesController::checkIsStageMinorEducation($classroom);

        if ($isMinor == false) {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom and year = :year and discipline_fk = :discipline_fk and month = :month and unavailable = 0 order by day, schedule",
                array(
                    "classroom" => $classroom->id,
                    "year" => Yii::app()->request->getPost("year"),
                    "month" => Yii::app()->request->getPost("month"),
                    "discipline_fk" => Yii::app()->request->getPost("discipline")
                )
            );
        }

        if ($isMinor != false) {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and year = :year and month = :month and unavailable = 0 group by day order by day, schedule",
                [
                    "classroom_fk" => Yii::app()->request->getPost("classroom"),
                    "year" => Yii::app()->request->getPost("year"),
                    "month" => Yii::app()->request->getPost("month")
                ]
            );
        }

        $dayName = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
        $instructorModel = InstructorIdentification::model()->findByPk($instructorId);

        $response = [];
        $response["instructorId"] = $instructorModel->id;
        $response["instructorName"] = $instructorModel->name;
        $response["schedules"] = [];
        $response["isMinor"] = $isMinor;

        foreach($schedules as $schedule){
            $date = $this->generateDate($schedule->day, $schedule->month, $schedule->year, 0);
            $classDay = $schedule->substitute_instructor_fk != null;
            array_push($response["schedules"], array(
                "day" => $schedule->day,
                "week_day" => $dayName[$schedule->week_day],
                "schedule" => $schedule->schedule,
                "idSchedule" => $schedule->id,
                "class_day" => $classDay,
                "date" => $date,
            ));
        }

        echo json_encode(["valid" => true, "response" => $response]);
        Yii::app()->end();
    }

    public function actionGetDisciplines(){

        $classroom = Classroom::model()->findByPk(Yii::app()->request->getPost("classroom"));

        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : ClassesController::checkIsStageMinorEducation($classroom);

        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();

        $htmlOptions = CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione o componente  curricular/eixo'), true);

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name"
            )->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $classroom->id)->queryAll();
            foreach ($disciplines as $discipline) {
                $htmlOptions .= htmlspecialchars(
                    CHtml::tag('option', array(
                        'option', array(
                            'value' => $discipline['id'])),
                            CHtml::encode($disciplinesLabels[$discipline['id']]), true
                        ));
                    }
        }

        if (!Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)){
            $classr = Yii::app()->db->createCommand(
                "select curricular_matrix.discipline_fk
                from curricular_matrix
                join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk
                where stage_fk = :stage_fk and school_year = :year order by ed.name"
            )
                ->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)
                ->bindParam(":year", Yii::app()->user->year)
            ->queryAll();
            foreach($classr as $discipline){
                if (isset($discipline['discipline_fk'])) {
                    $htmlOptions .= htmlspecialchars(
                        CHtml::tag(
                                'option',
                                array('value' => $discipline['discipline_fk']),
                                CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]),
                                true
                                )
                        );
                }
            }
        }

        $response = array(
            "isMinor" => $isMinor,
            "disciplines" => $htmlOptions
        );

        echo json_encode($response);
        Yii::app()->end();
    }

    public function actionGetInstructors() {
        $classroom = Classroom::model()->findByPk(Yii::app()->request->getPost("classroom"));

        $teachingData = InstructorTeachingData::model()->findAllByAttributes(array("classroom_id_fk" => $classroom->id));

        $htmlOptions = CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione o professor'), true);

        foreach ($teachingData as $td) {
            $instructor = InstructorIdentification::model()->findByPk($td->instructor_fk);
            $htmlOptions .= htmlspecialchars(
                CHtml::tag(
                    'option',
                    array('value' => $instructor->id),
                    CHtml::encode($instructor->name), true
                    ));
        }

        echo json_encode($htmlOptions);
        Yii::app()->end();
    }
  
    private function generateDate($day, $month, $year, $usecase){
        switch($usecase){
            case 0:
                $day = ($day < 10) ? '0' . $day : $day;
                $month = ($month < 10) ? '0' . $month : $month;
                return $day . "/" . $month . "/" . $year;
            case 1:
                $day = ($day < 10) ? '0' . $day : $day;
                $month = ($month < 10) ? '0' . $month : $month;
                return $day . "-" . $month . "-" . $year;
            default:
                break;
        }
      
    public function actionFixBuggedUnavailableDaysFor2024()
    {
        //Nessa função, precisa-se passar em cada schedule de 2024 e verificar se o dia está indisponível (coluna unavailable = 1) quando, na verdade, ele está disponível
        //O erro surgiu quando o usuário chamava o método chanceEvent no calendário e trocava um evento de feriado/férias por um evento útil. Naquele algoritmo, ele não trocava a flag de unavailable de 1 para 0.
        //Isso foi resolvido a nível de código, mas os problemas que já existiam no banco continuaram.
        //Esse algoritmo, em resumo, considerará o dia disponível quando estiver:
        //(I) entre a data de início e fim do calendário; e
        //(II) fora de um intervalo marcado como feriado, férias ou ponto facultativo.
        //OBS: ESSE ERRO FOI DESCOBERTO PORQUE TINHA DIAS DE AULA NO QUADRO DE HORÁRIO QUE NÃO CONSTAVAM EM AULAS MINISTRADAS. VERIFICOU-SE QUE AS AULAS DAQUELE DIA, APESAR DE ÚTIL, ESTAVAM MARCADAS COMO UNAVAILABLE
        $classrooms = Classroom::model()->findAll("school_year = 2024");
        foreach ($classrooms as $classroom) {
            $calendar = $classroom->calendarFk;
            $calendarStartDate = $calendar->start_date;
            $calendarEndDate = $calendar->end_date;
            foreach ($classroom->schedules as $schedule) {
                if ($schedule->unavailable) {
                    $scheduleDate = $schedule->year . "-" . str_pad($schedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($schedule->day, 2, "0", STR_PAD_LEFT);
                    if ($scheduleDate >= $calendarStartDate && $scheduleDate <= $calendarEndDate) {
                        $mustRemoveUnavailable = false;
                        foreach ($calendar->calendarEvents as $calendarEvent) {
                            if ($calendarEvent->calendar_event_type_fk == 101 || $calendarEvent->calendar_event_type_fk == 102 || $calendarEvent->calendar_event_type_fk == 104) {
                                if (str_replace(" 00:00:00", "", $calendarEvent->start_date) >= $scheduleDate && str_replace(" 00:00:00", "", $calendarEvent->end_date) <= $scheduleDate) {
                                    //Ao entrar aqui, a schedule está dentro do calendário letivo e não está em nenhum dia de feriado, férias ou ponto facultativo. Deve-se mudar a flag de unavailable de 1 para 0.
                                    $mustRemoveUnavailable = true;
                                    break;
                                }
                            }
                        }
                        if ($mustRemoveUnavailable) {
                            Schedule::model()->updateAll(["unavailable" => 0], "id = :id", [":id" => $schedule->id]);
                        }
                    }
                }
            };
        }
        var_dump("Fim de código");
    }

//    public function actionGetInstructorDisciplines($id)
//    {
//        /** @var $istructorDisciplines InstructorDisciplines[]
//         * @var $idisc InstructorDisciplines
//         */
//        $response = [];
//        $instructorDisciplines = InstructorDisciplines::model()->findAllByAttributes(["instructor_fk" => $id]);
//        foreach ($instructorDisciplines as $idisc) {
//            array_push($response, [
//                "instructor" => $id, "discipline" => $idisc->discipline_fk,
//                "discipline_name" => $idisc->disciplineFk->name, "stage" => $idisc->stage_vs_modality_fk,
//                "stage_name" => $idisc->stageVsModalityFk->name,
//            ]);
//        }
//        echo json_encode($response);
//    }
//
//    public function actionAddInstructors()
//    {
//        $ids = $_POST["add-instructors-ids"];
//        $school = Yii::App()->user->school;
//        foreach ($ids as $id) {
//            $instructor = InstructorSchool::model()->findAllByAttributes([
//                "instructor_fk" => $id, "school_fk" => $school
//            ]);
//            if (count($instructor) == 0) {
//                $instructor = new InstructorSchool();
//                $instructor->school_fk = $school;
//                $instructor->instructor_fk = $id;
//                if ($instructor->validate()) {
//                    $instructor->save();
//                }
//            }
//        }
//        $this->render('instructors');
//    }
//
//
//    public function actionAddInstructorsUnavailability()
//    {
//        $instructorsIds = $_POST["add-instructors-unavailability-ids"];
//        $turns = $_POST["add-instructors-unavailability-turn"];
//        $schedules = $_POST["add-instructors-unavailability-schedule"];
//        $weekDays = $_POST["add-instructors-unavailability-week-day"];
//
//        foreach ($instructorsIds as $instructorId) {
//            foreach ($turns as $key => $turn) {
//                $schedule = $schedules[$key];
//                $weekDay = $weekDays[$key];
//                $unavailability = new Unavailability();
//                $unavailability->instructor_school_fk = $instructorId;
//                $unavailability->week_day = $weekDay;
//                $unavailability->turn = $turn;
//                $unavailability->schedule = $schedule - 1;
//                $unavailability->save();
//            }
//        }
//        $this->render('instructors');
//    }
//
//    public function actionAddInstructorsDisciplines()
//    {
//        if (isset($_POST["add-instructors-disciplines-discipline"]) && isset($_POST["add-instructors-disciplines-stage"]) && isset($_POST["add-instructors-disciplines-ids"])) {
//            $instructors = $_POST["add-instructors-disciplines-ids"];
//            $stagesDisciplines = $_POST["add-instructors-disciplines-stage"];
//            $disciplines = $_POST["add-instructors-disciplines-discipline"];
//            foreach ($instructors as $instructor) {
//                foreach ($stagesDisciplines as $i => $stages) {
//                    foreach ($stages as $stage) {
//                        foreach ($disciplines[$i] as $discipline) {
//                            $instructorDiscipline = InstructorDisciplines::model()->findAll("stage_vs_modality_fk = :stage and discipline_fk = :discipline and instructor_fk = :instructor", [
//                                ":stage" => $stage, ":discipline" => $discipline, ":instructor" => $instructor
//                            ]);
//                            if ($instructorDiscipline == NULL) {
//                                /**
//                                 * @var $instructorDiscipline InstructorDisciplines
//                                 */
//                                $instructorDiscipline = new InstructorDisciplines();
//                                $instructorDiscipline->stage_vs_modality_fk = $stage;
//                                $instructorDiscipline->discipline_fk = $discipline;
//                                $instructorDiscipline->instructor_fk = $instructor;
//                                $instructorDiscipline->save();
//                            }
//                        }
//                    }
//                }
//            }
//        }
//        $this->render('index');
//    }
//
//    public function actionLoadUnavailability()
//    {
//        /** @var  $iu Unavailability */
//        $instructorId = $_POST["id"];
//        $instructorUnavailability = Unavailability::model()->findAll("instructor_school_fk = :instructorSchool", [":instructorSchool" => $instructorId]);
//        $response = [];
//        foreach ($instructorUnavailability as $iu) {
//            if (!isset($response[$iu->week_day])) {
//                $response[$iu->week_day] = ["0" => [], "1" => [], "2" => []];
//            }
//            array_push($response[$iu->week_day][$iu->turn], $iu->schedule);
//        }
//        echo json_encode($response);
//    }
//
//    public function actionGetInstructors()
//    {
//        if (isset($_POST['discipline'])) {
//            $id = $_POST['discipline'];
//            $list = CHtml::listData(InstructorDisciplines::model()->findAllByAttributes(["discipline_fk" => $id]), "instructorFk.id", "instructorFk.name");
//            echo htmlspecialchars(CHtml::tag('option', ["value" => "null"], "Sem Instrutor"));
//            foreach ($list as $id => $name) {
//                echo htmlspecialchars(CHtml::tag('option', ["value" => $id], $name));
//            }
//        }
//    }
//
//    public function actionChangeInstructor()
//    {
//        if (isset($_POST['schedule'], $_POST['instructor'])) {
//            $scheduleId = $_POST['schedule'];
//            $instructorId = $_POST['instructor'];
//            if ($instructorId == 'null') {
//                $instructorId = null;
//            }
//            /** @var Schedule $schedule */
//            $schedule = Schedule::model()->findByPk($scheduleId);
//            $schedule->instructor_fk = $instructorId;
//            $schedule->save();
//
//            $this->actionGetTimesheet($schedule->classroom_fk);
//        }
//    }
}
