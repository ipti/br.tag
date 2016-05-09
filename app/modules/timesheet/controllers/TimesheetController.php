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
                'actions' => ['index', 'instructors', 'GetInstructorDisciplines', 'addInstructors', 'loadUnavailability', 'getTimesheet', 'generateTimesheet'],
                'users' => ['@'],
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
        $this->render('index');
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
                $unavailability->schedule = $schedule;
                $unavailability->save();
            }
        }
        $this->render('instructors');
    }

    public function actionLoadUnavailability()
    {
        /** @var  $iu Unavailability */
        $instructorId = $_POST["id"];
        $instructorUnavailability = Unavailability::model()->findAll("instructor_school_fk = :instructorSchool", [":instructorSchool" => $instructorId]);
        $response = [];
        foreach ($instructorUnavailability as $iu) {
            if(!isset($response[$iu->week_day])) $response[$iu->week_day]=["0"=>[],"1"=>[],"2"=>[]];
            array_push($response[$iu->week_day][$iu->turn], $iu->schedule);
        }
        echo json_encode($response);
    }

    public function actionGetTimesheet()
    {
        if ($_POST["cid"] != "") {
            $classroomId = $_POST["cid"];
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom", [":classroom" => $classroomId]);
            $response = [];
            if (count($schedules) == 0) {
                $response = ["valid" => false];
            } else {
                /**
                 * @var $schedule Schedule
                 * @var $db CDbConnection
                 */
                $sql = "select MIN(initial_hour) AS lessHour, MAX(final_hour) AS mostHour from schedule where classroom_fk = $classroomId";
                $db = yii::app()->db;
                $row = $db->createCommand($sql)->queryRow();
                $response = ["valid" => true, "schedules" => [], "lessHour" => $row["lessHour"], "mostHour" => $row["mostHour"]];
                foreach ($schedules as $schedule) {
                    if (!isset($response["schedules"][$schedule->week_day])) {
                        $response["schedules"][$schedule->week_day] = [];
                    }
                    $response["schedules"][$schedule->week_day][$schedule->id] = [
                        "instructorId" => $schedule->instructor_fk,
                        "instructorName" => $schedule->instructorFk->name,
                        "disciplineId" => $schedule->discipline_fk,
                        "disciplineName" => $schedule->disciplineFk->name,
                        "weekDay" => $schedule->week_day,
                        "initial" => $schedule->initial_hour,
                        "final" => $schedule->final_hour
                    ];

                }
            }
        } else {
            $response = ["valid" => null];
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
        $schedulesQuantity = $_POST["schedules"];
        $classroom = Classroom::model()->find("id = :classroomId", [":classroomId" => $classroomId]);
        $initialMinute = ($classroom->initial_hour * 60) + $classroom->initial_minute;
        $finalMinute = ($classroom->final_hour * 60) + $classroom->final_minute;
        $scheduleMinutes = round(($finalMinute - $initialMinute) / $schedulesQuantity);
        $weekDays = [];
        if ($classroom->week_days_sunday) array_push($weekDays, 0);
        if ($classroom->week_days_monday) array_push($weekDays, 1);
        if ($classroom->week_days_tuesday) array_push($weekDays, 2);
        if ($classroom->week_days_wednesday) array_push($weekDays, 3);
        if ($classroom->week_days_thursday) array_push($weekDays, 4);
        if ($classroom->week_days_friday) array_push($weekDays, 5);
        if ($classroom->week_days_saturday) array_push($weekDays, 6);
        $schedules = [];

        foreach($weekDays as $wk) {
            $actualInitialMinutes = $initialMinute;
            for ($i = 0; $i < $schedulesQuantity; $i++) {
                $schedule = new Schedule();
                $schedule->classroom_fk = $classroomId;
                $schedule->week_day = $wk;
                $schedule->initial_hour = $i;
//                $hour = floor($actualInitialMinutes/60);
//                $minute = $actualInitialMinutes % 60;
//                $schedule->initial_hour = $hour.":".$minute;
//                $actualInitialMinutes += $scheduleMinutes;
//                $hour = floor($actualInitialMinutes / 60);
//                $minute = $actualInitialMinutes % 60;
//                $schedule->final_hour = $hour.":".$minute;
                array_push($schedules, $schedule);
            }
        }
        $curricularMatrix = CurricularMatrix::model()->findAll("stage_fk = :stage and school_fk = :school", [":stage" => $classroom->edcenso_stage_vs_modality_fk, ":school" => Yii::app()->user->school]);
        $instructorDisciplines = InstructorDisciplines::model()->findAll("stage_vs_modality_fk = :svm", [":svm" => $classroom->edcenso_stage_vs_modality_fk]);

        $disciplines = [];
        $i = 0;
        foreach($curricularMatrix as $cm) {
            $disciplines[$i] = [
                "discipline" => $cm->discipline_fk,
                "instructor" => "asds"
            ];
            $i++;
        }
    }
}