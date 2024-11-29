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
                    'validateClassContents',
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

    public function actionValidateClassContents()
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
            'validateClassContents',
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
        $classroomId = Yii::app()->request->getPost('classroom');
        $classroom = Classroom::model()->findByPk($classroomId);
        $month = Yii::app()->request->getPost('month');
        $year = Yii::app()->request->getPost('year');
        $disciplineId = Yii::app()->request->getPost('discipline');

        $students = $this->getStudentsByClassroom($classroomId);
        $isMinorEducation = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($classroom);
        $totalClasses = $this->getTotalClassesByMonth($classroomId, $month, $year, $disciplineId);
        $totalClassContents = $this->getTotalClassContentsByMonth($classroomId, $month, $year, $disciplineId);

        if (!$isMinorEducation) {
            $schedules = $this->getSchedulesFromMajorStage($classroomId, $month, $year, $disciplineId);

        } else {
            $schedules = $this->getSchedulesFromMinorStage($classroomId, $month, $year);
        }

        if (!empty($schedules)) {
            $classContents = $this->buildClassContents($schedules, $students);

            if (TagUtils::isInstructor()) {
                if (!$isMinorEducation) {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content, cp.id as cpid from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.discipline_fk = :discipline_fk and cp.users_fk = :users_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":discipline_fk", $disciplineId)
                        ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                        ->queryAll();
                } else {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content, cp.id as cpid from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                        ->queryAll();

                    $additionalClasses = Yii::app()->db->createCommand(
                        "select distinct cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content, cp.id as cpid
                        from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join course_plan_discipline_vs_abilities dvsa on dvsa.course_class_fk = cc.id
                        join edcenso_discipline ed on ed.id = dvsa.discipline_fk
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                        ->queryAll();


                    $courseClasses = array_merge($courseClasses, $additionalClasses);
                }
            } else {
                if (!$isMinorEducation) {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content, cp.id as cpid from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.discipline_fk = :discipline_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->bindParam(":discipline_fk", $disciplineId)
                        ->queryAll();
                } else {
                    $courseClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content, cp.id as cpid from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        join edcenso_discipline ed on cp.discipline_fk = ed.id
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk
                        order by ed.name, cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->queryAll();

                    $additionalClasses = Yii::app()->db->createCommand(
                        "select cc.id, cp.name as cpname, cp.discipline_fk ,cc.order, cc.content, cp.id as cpid
                        from course_class cc
                        join course_plan cp on cp.id = cc.course_plan_fk
                        where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.discipline_fk IS NULL
                        order by cp.name"
                    )
                        ->bindParam(":school_inep_fk", Yii::app()->user->school)
                        ->bindParam(":modality_fk", $schedules[0]->classroomFk->edcenso_stage_vs_modality_fk)
                        ->queryAll();

                    $courseClasses = array_merge($courseClasses, $additionalClasses);
                }
            }

            echo json_encode([
                "valid" => true,
                "classContents" => $classContents,
                "courseClasses" => $courseClasses,
                "isMinorEducation" => $isMinorEducation,
                "totalClasses" => $totalClasses,
                "totalClassContents" => $totalClassContents
            ]);
        } else {
            echo json_encode(["valid" => false, "error" => "Mês/Ano " . ($isMinorEducation == false ? "e Disciplina" : "") . " sem aula no Quadro de Horário."]);
        }
    }

    private function getTotalClassesByMonth($classroomId, $month, $year, $disciplineId)
    {
        if (!$disciplineId) {
            return Yii::app()->db->createCommand(
                "select count(*) from schedule sc
                where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
                and sc.unavailable = 0"
            )
                ->bindParam(":classroom", $classroomId)
                ->bindParam(":month", $month)
                ->bindParam(":year", $year)
                ->queryScalar();
        }
        return Yii::app()->db->createCommand(
            "select count(*) from schedule sc
            where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0"
        )
            ->bindParam(":classroom", $classroomId)
            ->bindParam(":month", $month)
            ->bindParam(":year", $year)
            ->bindParam(":discipline", $disciplineId)
            ->queryScalar();
    }

    private function getTotalClassContentsByMonth($classroomId, $month, $year, $disciplineId)
    {
        if (!$disciplineId) {
            return Yii::app()->db->createCommand(
                "select count(*) from class_contents cc
                join schedule sc on sc.id = cc.schedule_fk
                where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
                and sc.unavailable = 0"
            )
                ->bindParam(":classroom", $classroomId)
                ->bindParam(":month", $month)
                ->bindParam(":year", $year)
                ->queryScalar();
        }
        return Yii::app()->db->createCommand(
            "select count(*) from class_contents cc
            join schedule sc on sc.id = cc.schedule_fk
            where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0"
        )
            ->bindParam(":classroom", $classroomId)
            ->bindParam(":month", $month)
            ->bindParam(":year", $year)
            ->bindParam(":discipline", $disciplineId)
            ->queryScalar();
    }

    private function getSchedulesFromMajorStage($classroomId, $month, $year, $disciplineId)
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
        $classContentsResult = [];
        foreach ($schedules as $schedule) {
            $scheduleDate = date("Y-m-d", mktime(0, 0, 0, $schedule->month, $schedule->day, $schedule->year));
            $dayKey = $schedule->day;

            // Inicializa os valores principais do dia
            $classContentsResult[$dayKey] = [
                "available" => date("Y-m-d") >= $scheduleDate,
                "diary" => $schedule->diary !== null ? $schedule->diary : "",
                "students" => [],
            ];

            // Atualiza as anotações dos alunos
            $studentArray = $this->updateStudentAnottations($schedule, $students);
            $classContentsResult[$dayKey]["students"][] = $studentArray;

            // Consulta para os conteúdos da classe
            $classContents = ClassContents::model()->findAll(
                "year = :year AND month = :month AND day = :day AND discipline_fk = :discipline_fk AND classroom_fk = :classroom_fk",
                [
                    ":year" => $schedule->year,
                    ":month" => $schedule->month,
                    ":day" => $schedule->day,
                    ":discipline_fk" => $schedule->discipline_fk,
                    ":classroom_fk" => $schedule->classroom_fk,
                ]
            );

            // Adiciona os conteúdos da classe ao array de resultados
            foreach ($classContents as $classContent) {
                $classContentsResult[$dayKey]["contents"][] = $classContent->courseClassFk->id;
            }
        }

        return $classContentsResult;
    }

    private function updateStudentAnottations($schedule, $students)
    {
        $studentArray = [];
        foreach ($students as $student) {

            $studentData = [
                "id" => $student["id"],
                "name" => $student["name"],
                "diary" => ""
            ];

            foreach ($schedule->classDiaries as $classDiary) {
                if ($classDiary->student_fk == $student["id"]) {
                    $studentData["diary"] = $classDiary->diary;
                }
            }
            $studentArray[] = $studentData;
        }

        return $studentArray;
    }

    /**
     * Save the contents for each class.
     */

    public function actionSaveClassContents()
    {

        $classContents = $_POST["classContents"];
        $classroom = $_POST["classroom"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $discipline = $_POST["discipline"];

        $modelClassroom = Classroom::model()->findByPk($classroom);
        $isMinor = $modelClassroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($modelClassroom);
        $isMajorStage = !$isMinor;

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

        $contentsToExclude = array_column(ClassContents::model()->with("courseClassFk.coursePlanFk")->findAll(
            'schedule_fk = :schedule_fk and coursePlanFk.users_fk = :user_fk',
            [
                'schedule_fk' => $schedule->id,
                'user_fk' => Yii::app()->user->loginInfos->id
            ]
        ), 'id');

        if (!empty($contentsToExclude)) {
            ClassContents::model()->deleteAll("id IN (" . implode(", ", $contentsToExclude) . ")");
        }


        foreach ($classContent["contents"] as $content) {
            $existingContent = ClassContents::model()->findAll(
                'schedule_fk = :schedule_fk and course_class_fk = :course_class_fk',
                [
                    'schedule_fk' => $schedule->id,
                    'course_class_fk' => $content
                ]
            );
            if (empty($existingContent)) {
                $this->saveClassContents($content, $schedule);
            }
        }
    }

    private function saveClassContents($content, $schedule)
    {
        $classHasContent = new ClassContents();
        $classHasContent->schedule_fk = $schedule->id;
        $classHasContent->course_class_fk = $content;
        $classHasContent->day = $schedule->day;
        $classHasContent->month = $schedule->month;
        $classHasContent->year = $schedule->year;
        $classHasContent->classroom_fk = $schedule->classroom_fk;
        $classHasContent->discipline_fk = $schedule->discipline_fk;
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
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($classroom);
        if ($isMinor == false) {
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
            $schedulePerDays = $this->getSchedulePerDays($schedules);

            if ($enrollments != null) {
                $students = [];
                $dayName = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
                foreach ($enrollments as $enrollment) {
                    $array["studentId"] = $enrollment->student_fk;
                    $array["studentName"] = $enrollment->studentFk->name;
                    $array["schedules"] = [];
                    $array["status"] = $enrollment->status;
                    $array["statusLabel"] = $enrollment->getCurrentStatus();
                    foreach ($schedules as $schedule) {
                        $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $enrollment->student_fk]);
                        $available = date("Y-m-d") >= $schedule->year . "-" . str_pad($schedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($schedule->day, 2, "0", STR_PAD_LEFT);
                        $date = $this->gerateDate($schedule->day, $schedule->month, $schedule->year, 0);

                        $valid = $this->verifyStatusEnrollment($enrollment, $schedule);

                        array_push($array["schedules"], [
                            "available" => $available,
                            "day" => $schedule->day,
                            "week_day" => $dayName[$schedule->week_day],
                            "schedule" => $schedule->schedule,
                            "fault" => $classFault != null,
                            "justification" => $classFault->justification,
                            "date" => $date,
                            "valid" => $valid
                        ]);
                    }
                    array_push($students, $array);
                }
                echo json_encode(["valid" => true, "students" => $students, "scheduleDays" => $scheduleDays, "schedulePerDays" => $schedulePerDays, "isMinor" => $isMinor]);

            } else {
                echo json_encode(["valid" => false, "error" => "Matricule alunos nesta turma para trazer o Quadro de Frequência."]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Mês/Ano " . ($isMinor == false ? "e Disciplina" : "") . " sem aula no Quadro de Horário."]);
        }
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
    private function gerateDate($day, $month, $year, $usecase)
    {
        switch ($usecase) {
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
    }
    private function getSchedulePerDays($schedules)
    {
        $result = [];
        foreach ($schedules as $schedule) {
            $date = $this->gerateDate($schedule->day, $schedule->month, $schedule->year, 0);
            $index = array_search($date, array_column($result, 'date'));
            if ($index === false) {
                array_push($result, [
                    "schedulePerDays" => [$schedule->schedule],
                    "date" => $date
                ]);
            } else {
                array_push($result[$index]["schedulePerDays"], $schedule->schedule);
            }
        }
        return $result;
    }

    /**
     * Save the frequency for each student and class.
     */

    public function actionSaveFrequency()
    {
        $classroom = Classroom::model()->findByPk($_POST["classroomId"]);
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($classroom);
        if ($isMinor == false) {
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
                    $valid = $this->verifyStatusEnrollment($enrollment, $schedule);
                    if ($classFault == null && $valid) {
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

    // A função abaixo deve verificar se o status de matrícula do aluno é válido para preenchimento do quadro de frequência
    // Retorna True para o caso positivo, e False para o caso negativo
    // A função abaixo deve verificar se o status de matrícula do aluno é válido para preenchimento do quadro de frequência
    // Retorna True para o caso positivo, e False para o caso negativo
    public function verifyStatusEnrollment($enrollment, $schedule)
    {
        $dateFormat = 'd/m/Y';
        $dateFormat2 = 'Y-m-d';

        $date = $this->gerateDate($schedule->day, $schedule->month, $schedule->year, 0);

        $startDate = DateTime::createFromFormat($dateFormat, $enrollment->school_readmission_date);
        $returnDate = DateTime::createFromFormat($dateFormat, $enrollment->class_transfer_date);

        $scheduleDate = date_create_from_format($dateFormat, $date);
        $transferDate = isset($enrollment->transfer_date) ? DateTime::createFromFormat($dateFormat2, $enrollment->transfer_date) : null;
        $enrollmentDate = isset($enrollment->enrollment_date) ? DateTime::createFromFormat($dateFormat2, $enrollment->enrollment_date) : null;

        switch ($enrollment->status) {
            case '1': // MATRICULADO
                $result = !(isset($enrollmentDate) && $scheduleDate <= $enrollmentDate);
                break;
            case '2': // TRANSFERIDO
                $result = isset($transferDate) && $scheduleDate <= $transferDate;
                break;
            case '13': // Aluno saiu e retornou
                $result = ($scheduleDate < $startDate && $scheduleDate > $returnDate);
                break;
            case '11': // DEATH
                $result = false;
                break;
            default: // Qualquer outro status
                $result = true;
                break;
        }

        return $result;
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
        $classroom = Classroom::model()->findByPk($_POST["classroomId"]);
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($classroom);
        if ($isMinor == false) {
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
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($classroom);
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
            if ($isMinor == false) {
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
        $result["isMinor"] = $isMinor;
        echo json_encode($result);
    }
    private function checkIsStageMinorEducation($classroom)
    {
        $isMinor = TagUtils::isStageMinorEducation($classroom->edcensoStageVsModalityFk->edcenso_associated_stage_id);

        if (!$isMinor && TagUtils::isMultiStage($classroom->edcensoStageVsModalityFk->edcenso_associated_stage_id)) {
            $enrollments = StudentEnrollment::model()->findAllByAttributes(["classroom_fk" => $classroom->id]);

            foreach ($enrollments as $enrollment) {
                if (
                    !$enrollment->edcensoStageVsModalityFk->edcenso_associated_stage_id ||
                    !TagUtils::isStageMinorEducation($enrollment->edcensoStageVsModalityFk->edcenso_associated_stage_id)
                ) {
                    return false;
                }
            }

            $isMinor = true;
        }

        return $isMinor;
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
