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
        $this->render('index', array(
            "daysPerMonth" => $daysPerMonth
        ));
    }

    public function actionInstructors()
    {
        $this->render('instructors');
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

    public function actionGetTimesheet($classroomId = NULL)
    {

        if ($classroomId == NULL) {
            $classroomId = $_POST["cid"];
        }

        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $classroomId]);
        $curricularMatrix = TimesheetCurricularMatrix::model()->findAll("stage_fk = :stage and school_fk = :school", [
            ":stage" => $classroom->edcenso_stage_vs_modality_fk, ":school" => Yii::app()->user->school
        ]);
        $hasMatrix = $curricularMatrix != null;

        if ($classroomId != "") {
            /** @var Schedule[] $schedules */
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom", [":classroom" => $classroomId]);
            $response = [];
            if (count($schedules) == 0) {
                if ($hasMatrix) {
                    $response = ["valid" => FALSE];
                } else {
                    $response = ["valid" => FALSE, "error" => "curricularMatrix"];
                }
            } else {
                $response = [
                    "valid" => TRUE, "schedules" => [],
                ];
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

                    $response["schedules"][$schedule->month][$schedule->day][$schedule->schedule] = [
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
            $response = ["valid" => NULL];
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
                    $firstDay = Yii::app()->db->createCommand("select ce.start_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and calendar_event_type_fk = 1000;")->queryRow();
                    $lastDay = Yii::app()->db->createCommand("select ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and calendar_event_type_fk  = 1001;")->queryRow();
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod(new Datetime($firstDay["start_date"]), $interval, new Datetime($lastDay["end_date"]));
                    foreach ($period as $date) {
                        if ($schedule->week_day == date("w", strtotime($date->format("Y-m-d")))) {
                            $sc = new Schedule();
                            $sc->discipline_fk = $discipline;
                            $sc->classroom_fk = $classroomId;
                            $sc->day = $date->format("d");
                            $sc->month = $date->format("m");
                            $sc->week = $date->format("W");
                            $sc->week_day = $schedule->week_day;
                            $sc->schedule = $schedule->schedule;
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
    }

    public function actionChangeSchedules()
    {
        if ($_POST["replicate"]) {
            $weekOfTheChange = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["firstSchedule"]["day"], 'month' => $_POST["firstSchedule"]["month"], 'schedule' => $_POST["firstSchedule"]["schedule"]));
            if ($weekOfTheChange == null) {
                $weekOfTheChange = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["secondSchedule"]["day"], 'month' => $_POST["secondSchedule"]["month"], 'schedule' => $_POST["secondSchedule"]["schedule"]));
            }
            for ($week = $weekOfTheChange["week"]; $week <= 53; $week++) {
                $firstSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'week' => $week, 'week_day' => $_POST["firstSchedule"]["week_day"], 'schedule' => $_POST["firstSchedule"]["schedule"]));
                $secondSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'week' => $week, 'week_day' => $_POST["secondSchedule"]["week_day"], 'schedule' => $_POST["secondSchedule"]["schedule"]));
                if ($firstSchedule != null && $secondSchedule != null) {
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

                    $firstSchedule->save();
                    $secondSchedule->save();
                } else if ($firstSchedule == null && $secondSchedule != null) {
                    $secondSchedule->day = (new DateTime())->setISODate(Yii::app()->user->year, $week, $_POST["firstSchedule"]["week_day"])->format('j');
                    $secondSchedule->month = (new DateTime())->setISODate(Yii::app()->user->year, $week)->format('m');
                    $secondSchedule->week_day = $_POST["firstSchedule"]["week_day"];
                    $secondSchedule->schedule = $_POST["firstSchedule"]["schedule"];
                    $secondSchedule->save();
                } else if ($firstSchedule != null && $secondSchedule == null) {
                    $firstSchedule->day = (new DateTime())->setISODate(Yii::app()->user->year, $week, $_POST["secondSchedule"]["week_day"])->format('j');
                    $firstSchedule->month = (new DateTime())->setISODate(Yii::app()->user->year, $week)->format('m');
                    $firstSchedule->week_day = $_POST["secondSchedule"]["week_day"];
                    $firstSchedule->schedule = $_POST["secondSchedule"]["schedule"];
                    $firstSchedule->save();
                } else {
                    break;
                }
            }
        } else {
            $firstSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["firstSchedule"]["day"], 'month' => $_POST["firstSchedule"]["month"], 'schedule' => $_POST["firstSchedule"]["schedule"]));
            $secondSchedule = Schedule::model()->findByAttributes(array('classroom_fk' => $_POST["classroomId"], 'day' => $_POST["secondSchedule"]["day"], 'month' => $_POST["secondSchedule"]["month"], 'schedule' => $_POST["secondSchedule"]["schedule"]));
            if ($firstSchedule != null && $secondSchedule != null) {
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

                $firstSchedule->save();
                $secondSchedule->save();
            } else if ($firstSchedule == null && $secondSchedule != null) {
                $secondSchedule->day = $_POST["firstSchedule"]["day"];
                $secondSchedule->month = $_POST["firstSchedule"]["month"];
                $secondSchedule->week_day = $_POST["firstSchedule"]["week_day"];
                $secondSchedule->schedule = $_POST["firstSchedule"]["schedule"];
                $secondSchedule->save();
            } else if ($firstSchedule != null && $secondSchedule == null) {
                $firstSchedule->day = $_POST["secondSchedule"]["day"];
                $firstSchedule->month = $_POST["secondSchedule"]["month"];
                $firstSchedule->week_day = $_POST["secondSchedule"]["week_day"];
                $firstSchedule->schedule = $_POST["secondSchedule"]["schedule"];
                $firstSchedule->save();
            }
        }
        $this->actionGetTimesheet($_POST["classroomId"]);
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