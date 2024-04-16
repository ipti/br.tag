<?php

set_time_limit(0);
Yii::import('application.modules.timesheet.models.TimesheetCurricularMatrix', true);
Yii::import('application.modules.timesheet.models.TimesheetInstructor', true);
Yii::import('application.modules.timesheet.models.InstructorSchool', true);
Yii::import('application.modules.timesheet.models.Unavailability', true);

class ClassesController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'index',
                    'frequency',
                    'saveFrequency',
                    'SaveFrequencies',
                    'classContents',
                    'getClassContents',
                    'saveClassContents',
                    'getmonthsanddisciplines',
                    'getdisciplines',
                    'getfrequency',
                    'saveJustification',
                    'saveJustifications'
                ),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays the Class Contents
     */
    public function actionClassContents()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(
                ':school_year' => Yii::app()->user->year,
                ':school_inep_fk' => Yii::app()->user->school,
                ':users_fk' => Yii::app()->user->loginInfos->id
            );

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll(
                'school_year = :school_year and school_inep_fk = :school_inep_fk order by name',
                [
                    'school_year' => Yii::app()->user->year,
                    'school_inep_fk' => Yii::app()->user->school
                ]
            );
        }
        $this->render(
            'classContents',
            array(
                'classrooms' => $classrooms
            )
        );
    }

    /**
     * Get all classes by classroom, discipline and month
     */
    public function actionGetClassContents()
    {
        $classroomId = $_POST["classroom"];
        $isMinorEducation = TagUtils::isStageMinorEducation(Classroom::model()->findByPk($classroomId)->edcenso_stage_vs_modality_fk);
        $month = $_POST["month"];
        $year = $_POST["year"];
        $disciplineId = $_POST["discipline"];

        $students = $this->getStudentsByClassroom($classroomId);

        if (!$isMinorEducation) {
            $schedules = $this->getSchedulesFromMajorStage($classroomId, $month, $disciplineId, $year);

        } else {
            $schedules = $this->getSchedulesFromMinorStage($classroomId, $month, $year);
        }

        if (!empty($schedules)) {
            $classContents = $this->buildClassContents($schedules, $students);

            if (TagUtils::isInstructor()) {
                if (!$isMinorEducation) {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.discipline_fk = :discipline_fk and cp.users_fk = :users_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":discipline_fk", $_POST["discipline"])
                        ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                        ->queryAll();
                } else {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                        ->queryAll();
                }
            } else {
                if (!$isMinorEducation) {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.discipline_fk = :discipline_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":discipline_fk", $_POST["discipline"])
                        ->queryAll();
                } else {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->queryAll();
                }
            }

            echo json_encode([
                "valid" => true,
                "classContents" => $classContents,
                "courseClasses" => $courseClasses,
            ]);
        } else {
            echo json_encode(["valid" => false, "error" => "Mês/Ano " . ($_POST["fundamentalMaior"] == "1" ? "e Disciplina" : "") . " sem aula no Quadro de Horário."]);
        }
    }


    /**
     * Summary of getSchedulesFromMajorStage
     * @param integer $classroomId
     * @param integer $month
     * @param integer $disciplineId
     * @return Schedule[]
     */
    private function getSchedulesFromMajorStage($classroomId, $month, $disciplineId, $year)
    {
        return Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month and year = :year and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule",
            [
                "classroom_fk" => $classroomId,
                "month" => $month,
                "year" => $year,
                "discipline_fk" => $disciplineId
            ]
        );
    }

    /**
     * Summary of getSchedulesFromMinorStage
     * @param integer $classroomId
     * @param integer $month
     * @return Schedule[]
     */
    private function getSchedulesFromMinorStage($classroomId, $month, $year)
    {
        return Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month and year = :year and unavailable = 0 group by day order by day, schedule",
            [
                "classroom_fk" => $classroomId,
                "month" => $month,
                "year" => $year
            ]
        );
    }

    /**
     * Summary of getStudentsByClassroom
     * @param mixed $classroomId
     * @return mixed
     */
    private function getStudentsByClassroom($classroomId)
    {
        return Yii::app()->db->createCommand(
            "select
                    si.id,
                    si.name
                from student_enrollment se
                    join student_identification si on si.id = se.student_fk
                where classroom_fk = :classroom_fk
                order by si.name"
        )
            ->bindParam(":classroom_fk", $classroomId)
            ->queryAll();
    }

    /**
     * Summary of buildClassContents
     * @param Schedule[] $schedules
     * @param mixed $students
     * @return array
     */
    private function buildClassContents($schedules, $students)
    {
        $classContents = [];
        foreach ($schedules as $schedule) {
            $scheduleDate = date("Y-m-d", mktime(0, 0, 0, $schedule->month, $schedule->day, $schedule->year));
            $classContents[$schedule->day]["available"] = date("Y-m-d") >= $scheduleDate;
            $classContents[$schedule->day]["diary"] = $schedule->diary !== null ? $schedule->diary : "";
            $classContents[$schedule->day]["students"] = [];

            $studentArray = $this->updateStudentAnottations($schedule, $students);
            array_push($classContents[$schedule->day]["students"], $studentArray);

            foreach ($schedule->classContents as $classContent) {
                if (!isset($classContents[$schedule->day]["contents"])) {
                    $classContents[$schedule->day]["contents"] = [];
                }
                array_push($classContents[$schedule->day]["contents"], $classContent->courseClassFk->id);
            }
        }

        return $classContents;
    }

    private function updateStudentAnottations($schedule, $students)
    {
        $studentArray = [];
        foreach ($students as $student) {
            $studentArray["id"] = $student["id"];
            $studentArray["name"] = $student["name"];
            $studentArray["diary"] = "";

            foreach ($schedule->classDiaries as $classDiary) {
                if ($classDiary->student_fk == $student["id"]) {
                    $studentArray["diary"] = $classDiary->diary;
                }
            }
        }

        return $studentArray;
    }

    /**
     * Save the contents for each class.
     */

    public function actionSaveClassContents()
    {
        $isMajorStage = $_POST["fundamentalMaior"];
        $classContents = $_POST["classContents"];
        $classroom = $_POST["classroom"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $discipline = $_POST["discipline"];

        $schedules = $this->loadSchedulesByStage($isMajorStage, $classroom, $month, $year, $discipline);

        foreach ($classContents as $classContent) {
            $scheduleKey = array_search($classContent["day"], array_column($schedules, 'day'));
            if ($scheduleKey !== false) {
                $this->saveSchedule($schedules[$scheduleKey], $classContent);
            }
        }
    }

    private function loadSchedulesByStage($isMajorStage, $classroom, $month, $year, $discipline)
    {
        if ($isMajorStage) {
            return Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and month = :month and year = :year and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule",
                [
                    "classroom_fk" => $classroom,
                    "month" => $month,
                    "year" => $year,
                    "discipline_fk" => $discipline
                ]
            );
        }

        return Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month and year = :year and unavailable = 0 group by day order by day, schedule",
            [
                "classroom_fk" => $classroom,
                "month" => $month,
                "year" => $year,
            ]
        );
    }

    /**
     * Summary of saveSchedule
     * @param Schedule $schedule
     * @param mixed $classContent
     * @return void
     */
    private function saveSchedule($schedule, $classContent)
    {

        $schedule->diary = $classContent["diary"] === "" ? null : $classContent["diary"];
        $schedule->save();

        foreach ($classContent["students"] as $student) {
            $this->saveClassDiary($student, $schedule);
        }

        ClassContents::model()->deleteAll("schedule_fk = :schedule_fk", ["schedule_fk" => $schedule->id]);

        foreach ($classContent["contents"] as $content) {
            $this->saveClassContents($content, $schedule);
        }
    }

    private function saveClassContents($content, $schedule)
    {
        $classHasContent = new ClassContents();
        $classHasContent->schedule_fk = $schedule->id;
        $classHasContent->course_class_fk = $content;
        $classHasContent->save();
    }

    private function saveClassDiary($student, $schedule)
    {
        if ($student["diary"] != "") {
            $classDiary = ClassDiaries::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", [":schedule_fk" => $schedule->id, ":student_fk" => $student["id"]]);
            if ($classDiary == null) {
                $classDiary = new ClassDiaries();
                $classDiary->schedule_fk = $schedule->id;
                $classDiary->student_fk = $student["id"];
            }
            $classDiary->diary = $student["diary"] === "" ? null : $student["diary"];
            $classDiary->save();
        } else {
            ClassDiaries::model()->deleteAll("schedule_fk = :schedule_fk and student_fk = :student_fk", [":schedule_fk" => $schedule->id, ":student_fk" => $student["id"]]);
        }
    }

    ////////////
    //FREQUÊNCIA
    ////////////

    /**
     * Open the Frequency View.
     */

    public function actionFrequency()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id);
            $classrooms = Classroom::model()->findAll($criteria);
            $this->render(
                'frequencyInstructor',
                array(
                    'classrooms' => $classrooms
                )
            );
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
            $this->render(
                'frequency',
                array(
                    'classrooms' => $classrooms
                )
            );
        }
        // $this->render('frequency', array(
        //     'classrooms' => $classrooms
        // ));
    }

    /**
     * Get all frequency by classroom, discipline and month
     */

    public function actionGetFrequency()
    {
        if ($_POST["fundamentalMaior"] == "1") {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and year = :year and month = :month and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule",
                [
                    "classroom_fk" => $_POST["classroom"],
                    "year" => $_POST["year"],
                    "month" => $_POST["month"],
                    "discipline_fk" => $_POST["discipline"]
                ]
            );
        } else {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and year = :year and month = :month and unavailable = 0 group by day order by day, schedule",
                [
                    "classroom_fk" => $_POST["classroom"],
                    "year" => $_POST["year"],
                    "month" => $_POST["month"]
                ]
            );
        }

        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $_POST["classroom"]), $criteria);
        if ($schedules != null) {
            $scheduleDays = $this->getScheduleDays($schedules);
            if ($enrollments != null) {
                $students = [];
                $dayName = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
                foreach ($enrollments as $enrollment) {
                    $array["studentId"] = $enrollment->student_fk;
                    $array["studentName"] = $enrollment->studentFk->name;
                    $array["schedules"] = [];
                    foreach ($schedules as $schedule) {
                        $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $enrollment->student_fk]);
                        $available = date("Y-m-d") >= $schedule->year . "-" . str_pad($schedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($schedule->day, 2, "0", STR_PAD_LEFT);
                        $date = $this->gerateDate($schedule->day, $schedule->month, $schedule->year);
                        array_push($array["schedules"], [
                            "available" => $available,
                            "day" => $schedule->day,
                            "week_day" => $dayName[$schedule->week_day],
                            "schedule" => $schedule->schedule,
                            "fault" => $classFault != null,
                            "justification" => $classFault->justification,
                            "date" => $date
                        ]);
                    }
                    array_push($students, $array);
                }
                echo json_encode(["valid" => true, "students" => $students, "scheduleDays" => $scheduleDays]);
            } else {
                echo json_encode(["valid" => false, "error" => "Matricule alunos nesta turma para trazer o Quadro de Frequência."]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Mês/Ano " . ($_POST["fundamentalMaior"] == "1" ? "e Disciplina" : "") . " sem aula no Quadro de Horário."]);
        }
    }
    private function gerateDate($day, $month, $year)
    {
        $day = ($day < 10) ? '0' . $day : $day;
        $month = ($month < 10) ? '0' . $month : $month;
        return $day . "/" . $month . "/" . $year;
    }
    private function getScheduleDays($schedules)
    {
        $result = [];
        foreach ($schedules as $schedule) {
            $day = ($schedule->day < 10) ? '0' . $schedule->day : $schedule->day;
            $month = ($schedule->month < 10) ? '0' . $schedule->month : $schedule->month;
            $date = $day . "/" . $month . "/" . $schedule->year;
            $index = array_search($date, array_column($result, 'date'));
            if ($index === false) {
                array_push($result, [
                    "day" => $schedule->day,
                    "date" => $date
                ]);
            }
        }
        return $result;
    }

    public function actionSaveFrequencies()
    {
        $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and year = :year and month = :month", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "year" => $_POST["year"]]);
        foreach ($schedules as $schedule) {
            $this->saveFrequency($schedule);
        }
    }

    /**
     * Save the frequency for each student and class.
     */

    public function actionSaveFrequency()
    {
        if ($_POST["fundamentalMaior"] == "1") {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and year = :year and month = :month and schedule = :schedule", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "year" => $_POST["year"], "schedule" => $_POST["schedule"]]);
            $this->saveFrequency($schedule);
        } else {
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and year = :year and month = :month", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "year" => $_POST["year"]]);
            foreach ($schedules as $schedule) {
                $this->saveFrequency($schedule);
            }
        }
    }

    private function saveFrequency($schedule)
    {
        if ($_POST["studentId"] != null) {
            if ($_POST["fault"] == "1") {
                $classFault = new ClassFaults();
                $classFault->student_fk = $_POST["studentId"];
                $classFault->schedule_fk = $schedule->id;
                $classFault->save();
            } else {
                ClassFaults::model()->deleteAll("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]);
            }
        } else {
            if ($_POST["fault"] == "1") {
                $enrollments = StudentEnrollment::model()->findAll("classroom_fk = :classroom_fk", ["classroom_fk" => $_POST["classroomId"]]);

                foreach ($enrollments as $enrollment) {
                    $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $enrollment->student_fk]);
                    if ($classFault == null) {
                        $classFault = new ClassFaults();
                        $classFault->student_fk = $enrollment->student_fk;
                        $classFault->schedule_fk = $schedule->id;
                        $classFault->save();
                    }
                }
            } else {
                ClassFaults::model()->deleteAll("schedule_fk = :schedule_fk", ["schedule_fk" => $schedule->id]);
            }
        }
    }

    public function actionSaveJustifications()
    {
        $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month and year = :year ", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "year" => $_POST["year"]]);
        foreach ($schedules as $schedule) {
            $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]);
            $classFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
            $classFault->save();
        }
    }
    public function actionSaveJustification()
    {

        if ($_POST["fundamentalMaior"] == "1") {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and year = :year and schedule = :schedule", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "year" => $_POST["year"], "schedule" => $_POST["schedule"]]);
            $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]);
            $classFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
            $classFault->save();
        } else {
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month and year = :year ", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "year" => $_POST["year"]]);
            foreach ($schedules as $schedule) {
                $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]);
                $classFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
                $classFault->save();
            }
        }
    }

    /**
     * Get all months and disciplines by classroom
     */
    public function actionGetMonthsAndDisciplines()
    {
        $result = [];
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        if ($classroom->calendar_fk != null) {

            $result["months"] = [];
            $calendar = $classroom->calendarFk;
            $begin = new Datetime($calendar->start_date);
            $begin->modify("first day of this month");
            $end = new Datetime($calendar->end_date);
            $end->modify("first day of next month");
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($begin, $interval, $end);
            $meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
            foreach ($period as $date) {
                array_push($result["months"], ["id" => $date->format("Y") . "-" . $date->format("n"), "name" => $meses[$date->format("n") - 1] . "/" . $date->format("Y")]);
            }

            $result["disciplines"] = [];
            if ($_POST["fundamentalMaior"] == "1") {
                if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
                    $disciplines = Yii::app()->db->createCommand(
                        "select ed.id, ed.name from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name"
                    )->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $classroom->id)->queryAll();
                } else {
                    $disciplines = Yii::app()->db->createCommand("select ed.id, ed.name from curricular_matrix join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk where stage_fk = :stage_fk and school_year = :year order by ed.name")->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)->bindParam(":year", Yii::app()->user->year)->queryAll();
                }
                foreach ($disciplines as $discipline) {
                    array_push($result["disciplines"], ["id" => $discipline["id"], "name" => $discipline["name"]]);
                }
            }

            $result["valid"] = true;
        } else {
            $result = ["valid" => false, "error" => "A Turma está sem Calendário Escolar vinculado."];
        }
        echo json_encode($result);
    }

    /**
     * Get all disciplines by classroom
     */
    public function actionGetDisciplines()
    {
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name"
            )
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $classroom->id)->queryAll();
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione o componente curricular/eixo'), true);
            foreach ($disciplines as $discipline) {
                echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione o componente curricular/eixo'), true);
            $classr = Yii::app()->db->createCommand("select curricular_matrix.discipline_fk from curricular_matrix join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk where stage_fk = :stage_fk and school_year = :year order by ed.name")->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }
}
