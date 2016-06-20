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
        $this->render('instructors');
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
                    if (!isset($response["schedules"][$schedule->week_day])) {
                        $response["schedules"][$schedule->week_day] = [];
                    }
                    $instructorInfo = [];
                    if ($schedule->instructor_fk != NULL) {
                        /** @var TimesheetInstructor $instructor */
                        $instructor = TimesheetInstructor::model()->find("id = :id", [":id" => $schedule->instructor_fk]);
                        $unavailable = $instructor->isUnavailable($schedule->week_day, $schedule->turn, $schedule->schedule);
                        $countConflicts = $instructor->countConflicts($schedule->week_day, $schedule->turn, $schedule->schedule);
                        $instructorInfo = [
	                        "id" => $schedule->instructorFk->id,
                            "name" => $schedule->instructorFk->name,
                            "unavailable" => $unavailable,
                            "countConflicts" => $countConflicts
                        ];
                    } else {
                        $instructorInfo = [
	                        "id" => null,
                            "name" => "Sem Instrutor",
                            "unavailable" => false,
                            "countConflicts" => 0
                        ];
                    }

                    $response["schedules"][$schedule->week_day][$schedule->schedule] = [
                        "id" => $schedule->id, "instructorId" => $schedule->instructor_fk,
                        "instructorInfo" => $instructorInfo,
                        "disciplineId" => $schedule->discipline_fk,
                        "disciplineName" => $schedule->disciplineFk->name, "turn" => $schedule->turn
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

            $instructorDisciplines = InstructorDisciplines::model()->findAll("stage_vs_modality_fk = :svm", [":svm" => $classroom->edcenso_stage_vs_modality_fk]);
            $instructors = [];
            foreach ($instructorDisciplines as $istructorDiscipline) {
                if (!isset($instructors[$istructorDiscipline->instructor_fk])) {
                    $instructors[$istructorDiscipline->instructor_fk] = $istructorDiscipline->instructorFk;
                }
            }

            $instructorsUnavailabilities = [];
            /** @var $instructor TimesheetInstructor */
            $i = 0;
            foreach ($instructors as $id => $instructor) {
                $unavailabilities = $instructor->getInstructorUnavailabilities($turn);
                if ($instructorsUnavailabilities == NULL) {
                    $instructorsUnavailabilities = [];
                }
                $instructorsUnavailabilities[$i++] = ['id' => $id, 'unavailabilities' => $unavailabilities];
            }

            function compare($a, $b)
            {
                if ($a['unavailabilities']['count'] == $b['unavailabilities']['count']) {
                    return 0;
                }
                if ($a['unavailabilities']['count'] > $b['unavailabilities']['count']) {
                    return -1;
                }

                return 1;
            }

            usort($instructorsUnavailabilities, 'compare');

            $disciplines = [];
            $i = 0;
            /** @var CurricularMatrix $cm */
            foreach ($curricularMatrix as $cm) {
                $needed = $cm->credits;
                $disciplines[$i] = [
                    "discipline" => $cm->discipline_fk, "instructor" => NULL, "credits" => $cm->credits
                ];
                $j = 0;
                while ($j < count($instructors)) {
                    if ($instructorsUnavailabilities[$j]['unavailabilities']['count'] + $needed < $schedulesQuantity * 7) {
                        $disciplines[$i]["instructor"] = $instructorsUnavailabilities[$j]['id'];
                        $instructorsUnavailabilities[$j]['unavailabilities']['count'] += $needed;
                        break;
                    }
                    $j++;
                }
                $i++;
            }

            $schedules = [];

            for ($i = 0; $i < $schedulesQuantity; $i++) {
                foreach ($weekDays as $wk) {
                    $schedule = new Schedule();
                    $schedule->classroom_fk = $classroomId;
                    $schedule->week_day = $wk;
                    $schedule->schedule = $i;
                    array_push($schedules, $schedule);
                }
            }

            /** @var Schedule $schedule */
            foreach ($schedules as $schedule) {
                shuffle($disciplines);
                $rand = 0;
                foreach ($disciplines as $index => $d) {
                    if ($d["instructor"] == NULL) {
                        $rand = $index;
                        break;
                    }
                    $una = NULL;
                    foreach ($instructorsUnavailabilities as $iu) {
                        if ($iu == $d["instructor"]) {
                            $una = $iu['unavailabilities'];
                            break;
                        }
                    }
                    if ($una == NULL) {
                        $rand = $index;
                        break;
                    }
                    $wk = $schedule->week_day;
                    $sc = $schedule->schedule;
                    if (!in_array($sc, $una[$wk])) {
                        $rand = $index;
                        break;
                    }


                }
                $discipline = $disciplines[$rand]['discipline'];
                $instructor = $disciplines[$rand]['instructor'];
                $disciplines[$rand]['credits']--;
                if ($disciplines[$rand]['credits'] <= 0) {
                    unset($disciplines[$rand]);
                }

                $schedule->discipline_fk = $discipline;
                $schedule->instructor_fk = $instructor;
                $schedule->turn = $turn;
                $schedule->save();
            }
            Log::model()->saveAction("timesheet", $classroom->id, "U", $classroom->name);
        }

        $this->actionGetTimesheet($classroomId);
    }

    public function actionChangeSchedules(){
		if(isset($_POST["firstSchedule"], $_POST["secondSchedule"])){
			$first = $_POST["firstSchedule"]; //{id, week_day, schedule}
			$second = $_POST["secondSchedule"]; //{id, week_day, schedule}
			$firstSchedule = null;
			$secondSchedule = null;
			if($first['id'] != null){
				$firstSchedule = Schedule::model()->findByPk($first['id']);
			}
			if($second['id'] != null){
				$secondSchedule = Schedule::model()->findByPk($second['id']);
			}

			/** @var $firstSchedule Schedule
			  * @var $secondSchedule Schedule
			 */
			$classroomID = null;
			if($firstSchedule != null && $secondSchedule != null){
				$tmpWK = $secondSchedule->week_day;
				$secondSchedule->week_day = $firstSchedule->week_day;
				$firstSchedule->week_day = $tmpWK;

				$tmpSC = $secondSchedule->schedule;
				$secondSchedule->schedule = $firstSchedule->schedule;
				$firstSchedule->schedule = $tmpSC;

				$firstSchedule->save();
				$secondSchedule->save();
				$classroomID = $firstSchedule->classroom_fk;
			}else if($firstSchedule == null && $secondSchedule != null){
				$secondSchedule->week_day = $first['week_day'];
				$secondSchedule->schedule = $first['schedule'];
				$secondSchedule->save();
				$classroomID = $secondSchedule->classroom_fk;
			}else if($firstSchedule != null && $secondSchedule == null){
				$firstSchedule->week_day = $second['week_day'];
				$firstSchedule->schedule = $second['schedule'];
				$firstSchedule->save();
				$classroomID = $firstSchedule->classroom_fk;
			}

			if($classroomID != null){
				$this->actionGetTimesheet($classroomID);
			}
		}

    }

	public function actionGetInstructors(){
		if(isset($_POST['discipline'])){
			$id = $_POST['discipline'];
			$list = CHtml::listData(InstructorDisciplines::model()->findAllByAttributes(["discipline_fk" => $id]),"instructorFk.id","instructorFk.name");
			echo  CHtml::tag('option', ["value"=>"null"], "Sem Instrutor");
			foreach($list as $id => $name){
				echo  CHtml::tag('option', ["value"=>$id], $name);
			}
		}
	}

	public function actionChangeInstructor(){
		if(isset($_POST['schedule'], $_POST['instructor'])){
			$scheduleId = $_POST['schedule'];
			$instructorId = $_POST['instructor'];
			if($instructorId == 'null'){
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