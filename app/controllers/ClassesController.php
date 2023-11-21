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
                    'classContents',
                    'getClassContents',
                    'saveClassContents',
                    'getdisciplines',
                    'getfrequency',
                    'saveJustification'
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
                . " join instructor_teaching_data on
                 instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on
                 instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and
             c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
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
        $month = $_POST["month"];
        $disciplineId = $_POST["discipline"];

        $students = $this->getStudentsByClassroom($classroomId);

        if ($_POST["fundamentalMaior"] == "1") {
            $schedules = $this->getSchedulesFromMajorStage($classroomId, $month, $disciplineId);
        } else {
            $schedules = $this->getSchedulesFromMinorStage($classroomId, $month);
        }

        if (!empty($schedules)) {
            $classContents = $this->buildClassContents($schedules, $students);

            if (TagUtils::isInstructor()) {
                if ($_POST["fundamentalMaior"] == "1") {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and
                        cp.modality_fk = :modality_fk and
                        cp.discipline_fk = :discipline_fk and cp.users_fk = :users_fk
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
                        where cp.school_inep_fk = :school_inep_fk and
                        cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                        ->queryAll();
                }
            } else {
                if ($_POST["fundamentalMaior"] == "1") {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk
                        = :modality_fk and cp.discipline_fk = :discipline_fk
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
            echo json_encode([
                "valid" => false,
                "error" => "Não existe quadro de horário com dias letivos para o mês selecionado."
            ]);
        }
    }


    /**
     * Summary of getSchedulesFromMajorStage
     * @param integer $classroomId
     * @param integer $month
     * @param integer $disciplineId
     * @return Schedule[]
     */
    private function getSchedulesFromMajorStage($classroomId, $month, $disciplineId)
    {
        return Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule",
            [
                "classroom_fk" => $classroomId,
                "month" => $month,
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
    private function getSchedulesFromMinorStage($classroomId, $month)
    {
        return Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month and unavailable = 0 group by day order by day, schedule",
            [
                "classroom_fk" => $classroomId,
                "month" => $month
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
            $scheduleDate = date("Y-m-d", mktime(0, 0, 0, $schedule->month, $schedule->day, Yii::app()->user->year));
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
        $isMinorStage = $_POST["fundamentalMaior"];
        $classContents = $_POST["classContents"];
        $classroom = $_POST["classroom"];
        $month = $_POST["month"];
        $discipline = $_POST["discipline"];

        $schedules = $this->loadScheduleByStage($isMinorStage, $classroom, $month, $discipline);

        foreach ($classContents as $classContent) {
            $scheduleKey = array_search($classContent["day"], array_column($schedules, 'day'));
            if ($scheduleKey !== false) {
                CVarDumper::dump($scheduleKey);
                $this->saveSchedule($schedules[$scheduleKey], $classContent);
            }
        }
    }

    private function loadScheduleByStage($isMinorStage, $classroom, $month, $discipline)
    {
        if ($isMinorStage != "1") {
            return Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and month = :month and discipline_fk = :discipline_fk group by day order by day, schedule",
                [
                    "classroom_fk" => $classroom,
                    "month" => $month,
                    "discipline_fk" => $discipline
                ]
            );
        }

        return Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month group by day order by day, schedule",
            [
                "classroom_fk" => $classroom,
                "month" => $month
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
                . " join instructor_teaching_data on
                instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on
                instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and
            c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(
                ':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school,
                ':users_fk' => Yii::app()->user->loginInfos->id
            );
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
                "classroom_fk = :classroom_fk and month = :month and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule",
                [
                    "classroom_fk" => $_POST["classroom"],
                    "month" => $_POST["month"],
                    "discipline_fk" => $_POST["discipline"]
                ]
            );
        } else {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and month = :month and unavailable = 0 group by day order by day, schedule",
                [
                    "classroom_fk" => $_POST["classroom"],
                    "month" => $_POST["month"]
                ]
            );
        }

        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array(
            'classroom_fk' => $_POST["classroom"]
        ), $criteria);
        if ($schedules != null) {
            if ($enrollments != null) {
                $students = [];
                $dayName = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
                foreach ($enrollments as $enrollment) {
                    $array["studentId"] = $enrollment->student_fk;
                    $array["studentName"] = $enrollment->studentFk->name;
                    $array["schedules"] = [];
                    foreach ($schedules as $schedule) {
                        $classFault = ClassFaults::model()->find(
                            "schedule_fk = :schedule_fk and student_fk = :student_fk",
                            [
                                "schedule_fk" => $schedule->id, "student_fk" => $enrollment->student_fk
                            ]
                        );
                        $available = date("Y-m-d") >= Yii::app()->user->year . "-" . str_pad(
                            $schedule->month,
                            2,
                            "0",
                            STR_PAD_LEFT
                        ) . "-" . str_pad($schedule->day, 2, "0", STR_PAD_LEFT);
                        array_push($array["schedules"], [
                            "available" => $available,
                            "day" => $schedule->day,
                            "week_day" => $dayName[$schedule->week_day],
                            "schedule" => $schedule->schedule,
                            "fault" => $classFault != null,
                            "justification" => $classFault->justification
                        ]);
                    }
                    array_push($students, $array);
                }
                echo json_encode(["valid" => true, "students" => $students]);
            } else {
                echo json_encode([
                    "valid" => false, "error" => "Matricule alunos nesta turma para trazer o quadro de frequência."
                ]);
            }
        } else {
            echo json_encode([
                "valid" => false,
                "error" => "No quadro de horário da turma, não existe dia letivo no mês selecionado para este componente curricular/eixo."
            ]);
        }
    }

    /**
     * Save the frequency for each student and class.
     */

    public function actionSaveFrequency()
    {
        if ($_POST["fundamentalMaior"] == "1") {
            $schedule = Schedule::model()->find(
                "classroom_fk = :classroom_fk and
                day = :day and month = :month and schedule = :schedule",
                [
                    "classroom_fk" => $_POST["classroomId"],
                    "day" => $_POST["day"],
                    "month" => $_POST["month"], "schedule" => $_POST["schedule"]
                ]
            );
            $this->saveFrequency($schedule);
        } else {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and day = :day and month = :month",
                [
                    "classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"]
                ]
            );
            foreach ($schedules as $schedule) {
                $this->saveFrequency($schedule);
            }
        }
    }

    private
        function saveFrequency(
        $schedule
    ) {


        if ($_POST["studentId"] != null) {
            if ($_POST["fault"] == "1") {
                $classFault = new ClassFaults();
                $classFault->student_fk = $_POST["studentId"];
                $classFault->schedule_fk = $schedule->id;
                $classFault->save();
            } else {
                ClassFaults::model()->deleteAll(
                    "schedule_fk = :schedule_fk and student_fk = :student_fk",
                    ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]
                );
            }

        } else {
            if ($_POST["fault"] == "1") {
                $enrollments = StudentEnrollment::model()->findAll("classroom_fk = :classroom_fk", ["classroom_fk" => $_POST["classroomId"]]);

                foreach ($enrollments as $enrollment) {
                    $classFault = ClassFaults::model()->find(
                        "schedule_fk = :schedule_fk and student_fk = :student_fk",
                        ["schedule_fk" => $schedule->id, "student_fk" => $enrollment->student_fk]
                    );
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

    public function actionSaveJustification()
    {

        if ($_POST["fundamentalMaior"] == "1") {
            $schedule = Schedule::model()->find(
                "classroom_fk = :classroom_fk and day = :day and
                month = :month and schedule = :schedule",
                [
                    "classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"],
                    "month" => $_POST["month"], "schedule" => $_POST["schedule"]
                ]
            );
            $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and
            student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]);
            $classFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
            $classFault->save();


        } else {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and day = :day and month = :month",
                ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"]]
            );
            foreach ($schedules as $schedule) {
                $classFault = ClassFaults::model()->find(
                    "schedule_fk = :schedule_fk and student_fk = :student_fk",
                    ["schedule_fk" => $schedule->id, "student_fk" => $_POST["studentId"]]
                );
                $classFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
                $classFault->save();
            }
        }
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
                echo htmlspecialchars(CHtml::tag('option', array(
                    'value' => $discipline['id']
                ), CHtml::encode(
                    $disciplinesLabels[$discipline['id']]
                ), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode(
                'Selecione o componente curricular/eixo'
            ), true);
            $classr = Yii::app()->db->createCommand(
                "select curricular_matrix.discipline_fk from curricular_matrix join edcenso_discipline ed on
                 ed.id = curricular_matrix.discipline_fk where
                  stage_fk = :stage_fk and school_year = :year order by ed.name"
            )->bindParam(
                ":stage_fk",
                $classroom->edcenso_stage_vs_modality_fk
            )->bindParam(
                ":year",
                Yii::app()->user->year
            )->queryAll();
            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array(
                        'value' => $discipline['discipline_fk']
                    ), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }
}
