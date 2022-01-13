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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index',
                    'frequency', 'saveFrequency',
                    'classContents', 'saveClassContents', 'saveContent',
                    'getdisciplines', 'getclasses', 'getclassesforfrequency', 'getcontents'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
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
            $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id);

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
        }
        $this->render('classContents', array(
            'model' => new Classes,
            'classrooms' => $classrooms
        ));
    }

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
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
        }
        $this->render('frequency', array(
            'model' => new Classes(),
            'classrooms' => $classrooms
        ));
    }

    /**
     *   Save a new Content
     */
    public function actionSaveContent()
    {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $name = strtoupper($_POST['name']);
            $description = isset($_POST['description']) ? strtoupper($_POST['description']) : "";
            $type = isset($_POST['type']) ? $_POST['type'] : 1;
            $exist = ClassResources::model()->exists('name = :n', ['n' => $name]);
            if (!$exist) {
                $newContent = new ClassResources();
                $newContent->name = $name;
                $newContent->description = $description;
                $newContent->type = $type;
                $newContent->save();

                $return = ['id' => $newContent->id, 'name' => $newContent->name];
                echo json_encode($return);
            }
        }
    }

    /**
     * Save the contents for each class.
     */
    public function actionSaveClassContents()
    {
        if (isset($_POST['classroom'], $_POST['month'], $_POST['day'])) {
            $classroom = $_POST['classroom'];
            $disciplines = $_POST['disciplines'];
            $month = $_POST['month'];
            $days = $_POST['day'];
            $allSaved = true;

            $classes = Classes::model()->findAll("classroom_fk = :classroom_fk and month = :month" . ($disciplines != "" && $disciplines != "-1" ? " and discipline_fk = " . $disciplines : ""), [
                ":classroom_fk" => $classroom, ":month" => $month
            ]);

            foreach ($classes as $class) {
                $classContents = $class->classContents;
                foreach ($classContents as $classContent) {
                    $classContent->delete();
                }
            }
            foreach ($classes as $class) {
                if (isset($days[$class->day])) {
                    $contents = $days[$class->day];
                    foreach ($contents as $content) {
                        $newClassContent = new ClassHasContent();
                        $newClassContent->class_fk = $class->id;
                        $newClassContent->content_fk = $content;
                        $allSaved = $allSaved && $newClassContent->save();
                    }
                }
            }

            if ($allSaved) {
                $classroomName = Classroom::model()->findByPk($classroom)->name;
                if ($disciplines == "") {
                    $disciplineName = "Todas as Disciplinas";
                } else {
                    $disciplineName = EdcensoDiscipline::model()->findByPk($disciplines)->name;
                }
                Log::model()->saveAction("class", $classroom . "|" . $disciplines . "|" . $month, "U", $classroomName . "|" . $disciplineName . "|" . Yii::t("default", date('F', mktime(0, 0, 0, $month, 10))));
                Yii::app()->user->setFlash('success', Yii::t('default', 'Aulas Ministradas Atualizadas com Sucesso!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Houve um erro inesperado!'));
            }
        }

        $this->redirect(array('classContents'));
    }

    /**
     * Save the frequency for each student and class.
     */
    public function actionSaveFrequency($classroom = null, $discipline = null, $month = null)
    {
        set_time_limit(0);
        ignore_user_abort();


        $everyThingIsOkay = true;

        $classroom = $classroom == null ? $_POST['classroom'] : $classroom;
        $discipline = $discipline == null ? $_POST['disciplines'] : $discipline;
        $discipline = ($discipline == "Todas as disciplinas") ? null : $discipline;
        $month = $month == null ? $_POST['month'] : $month;
        @$instructor_faults = $_POST['instructor_faults'];
        $instructor_days = $_POST['instructor_days'];
        $student_faults = $_POST['student_faults'];

        if (!isset($_POST['schedule'])) {
            $given_classes = Array();
            $is_first_to_thrid_year = Yii::app()->db->createCommand("select count(id) as status from classroom where id = $classroom and  (name like '1%' or name like '2%' or name like '3%');")->queryAll();


            //        if($is_first_to_thrid_year[0]['status'] == '1'){
            foreach ($instructor_days as $day => $value) {
                $class = Classes::model()->findByAttributes(array(
                    'classroom_fk' => $classroom,
                    'discipline_fk' => $discipline,
                    'month' => $month,
                    'day' => $day));

                if (isset($class)) {
                    if (isset($instructor_faults)) {
                        $class->given_class = array_key_exists($day, $instructor_faults) ? ($instructor_faults[$day] == '1' ? 0 : ($instructor_faults[$day] == '2' ? 0 : 1)) : 1;
                    } else {
                        $class->given_class = 1;
                    }
                    if ($class->given_class == 1) {
                        $given_classes[$day] = $class->id;
                    }
                    if ($class->update()) {
                    }
                } else {
                    $class = new Classes();
                    $class->classroom_fk = $classroom;
                    $class->discipline_fk = $discipline;
                    $class->day = $day;
                    $class->month = $month;
                    $class->schedule = 1;
                    $class->classtype = 'N';
                    if (isset($instructor_faults)) {
                        $class->given_class = array_key_exists($day, $instructor_faults) ? ($instructor_faults[$day] == '1' ? 0 : ($instructor_faults[$day] == '2' ? 0 : 1)) : 1;
                    } else {
                        $class->given_class = 1;
                    }
                    if ($class->save()) {

                        $given_classes[$day] = $class->id;
                    }
                }

            }
            //        }
            $discipline_condition = ($discipline == null) ? " c.discipline_fk is null" : " c.discipline_fk	= $discipline ";
            Yii::app()->db->createCommand("CREATE TEMPORARY TABLE IF NOT EXISTS table2 AS (select cf.id from class as c inner join class_faults as cf on (c.id = cf.class_fk) where c.classroom_fk = '$classroom'  and $discipline_condition and c.month = '$month');
                                                    delete from class_faults  where  id in (select id from table2)")->query();

            if (isset($student_faults)) {

                foreach ($student_faults as $sid => $days) {
                    foreach ($given_classes as $day => $id) {


                        if (array_key_exists($day, $days)) {
                            $classFaults = new ClassFaults();
                            $classFaults->class_fk = $id;
                            $classFaults->student_fk = $sid;
                            $classFaults->schedule = 1;
                            if ($classFaults->save()) {

                            }
                        }
                    }
                }
            }

            set_time_limit(30);
            $this->redirect(array('frequency'));

        } else {
            $discipline = $_POST['discipline'];
            $student = $_POST['student'];
            $schedule = $_POST['schedule'];
            $day = $_POST['day'];

            $class = Classes::model()->findByAttributes(array(
                'classroom_fk' => $classroom,
                'discipline_fk' => $discipline,
                'month' => $month,
                'day' => $day,
                'schedule' => $schedule,
            ));

            $id = $class->id;
            if (!isset($class)) {
                $class = new Classes();
                $class->classroom_fk = $classroom;
                $class->discipline_fk = $discipline;
                $class->day = $day;
                $class->month = $month;
                $class->schedule = $schedule;
                $class->classtype = 'N';
                $class->given_class = 1;

                if ($class->save()) {
                    $id = $class->id;
                }
            }

            if (isset($student)) {

                $fault = ClassFaults::model()->findByAttributes(array(
                    'class_fk' => $id,
                    'student_fk' => $student,
                    'schedule' => $schedule));

                if ($fault) {
                    if ($fault->delete()) {
                        echo json_encode(1);
                    }
                } else {
                    $classFaults = new ClassFaults();
                    $classFaults->class_fk = $id;
                    $classFaults->student_fk = $student;
                    $classFaults->schedule = $schedule;

                    if ($classFaults->save()) {
                        echo json_encode(1);
                    }
                }
            }

            return [];
        }


    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Get all disciplines by classroom
     */
    public function actionGetDisciplines()
    {
        $crid = $_POST['classroom'];
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select d.id from `edcenso_discipline` as `d`
                        JOIN `instructor_teaching_data` `t` ON 
                                (`t`.`discipline_1_fk` = `d`.`id` 
                                || `t`.`discipline_2_fk` = `d`.`id` 
                                || `t`.`discipline_3_fk` = `d`.`id`
                                || `t`.`discipline_4_fk` = `d`.`id`
                                || `t`.`discipline_5_fk` = `d`.`id`
                                || `t`.`discipline_6_fk` = `d`.`id`
                                || `t`.`discipline_7_fk` = `d`.`id`
                                || `t`.`discipline_8_fk` = `d`.`id`
                                || `t`.`discipline_9_fk` = `d`.`id`
                                || `t`.`discipline_10_fk` = `d`.`id`
                                || `t`.`discipline_11_fk` = `d`.`id`
                                || `t`.`discipline_12_fk` = `d`.`id`
                                || `t`.`discipline_13_fk` = `d`.`id`)
                        join `classroom` as `c` on (c.id = t.classroom_id_fk)
                        left join instructor_identification ii on t.instructor_fk = ii.id 
                        where ii.users_fk = " . Yii::app()->user->loginInfos->id . " and t.classroom_id_fk = " . $crid)->queryAll();
            foreach ($disciplines as $discipline) {
                echo CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Todas as disciplinas'), true);
            $classr = Yii::app()->db->createCommand("select distinct discipline_fk from schedule where classroom_fk = $crid;")->queryAll();
            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true);
                }
            }
        }
    }

    /**
     * Get all contents
     */
    public function actionGetContents()
    {
        $contents = ClassResources::model()->findAllByAttributes(['type' => ClassResources::CONTENT]);
        $return = [];
        foreach ($contents as $content) {
            $return[$content->id] = $content->name;
        }
        echo json_encode($return);
    }

    /**
     * Get all classes by classroom, disciplene and month
     */
    public function actionGetClasses($classroom = null, $month = null, $disciplines = null)
    {
        $classes = Classes::model()->findAll("classroom_fk = :classroom_fk and month = :month" . ($disciplines != "" && $disciplines != "-1" ? " and discipline_fk = " . $disciplines : ""), [
            ":classroom_fk" => $classroom, ":month" => $month
        ]);
        $return = [];
        foreach ($classes as $class) {
            $day = $class->day;
            if ($class->given_class == 1) {
                $return[$day] = [];

                $classContents = $class->classContents;
                foreach ($classContents as $classContent) {
                    $id = $classContent->contentFk->id;
                    $description = $classContent->contentFk->description;

                    $return[$day][$id] = $description;
                }
            }
        }

        if ($return === []) {
            echo json_decode(null);
        } else {
            echo json_encode($return);
        }
    }

    /**
     * Get all classes by classroom, disciplene and month
     */
    public function actionGetClassesForFrequency($classroom = null, $discipline = null, $month = null)
    {
        $firstDay = Yii::app()->db->createCommand("select ce.start_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and calendar_event_type_fk = 1000;")->queryRow();
        $lastDay = Yii::app()->db->createCommand("select ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and calendar_event_type_fk  = 1001;")->queryRow();
        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom), $criteria);
        if ($firstDay !== null && $lastDay !== null && $enrollments) {
            $classroom = $classroom == null ? $_GET['classroom'] : $classroom;
            $discipline = $discipline == null ? $_GET['disciplines'] : $discipline;
            $month = $month == null ? $_GET['month'] : $month;

            $discipline = ($discipline == "Todas as disciplinas") ? null : $discipline;
            $allDisciplines = ($discipline == null);

            $classes = null;

            $classes = Classes::model()->findAllByAttributes(array(
                'classroom_fk' => $classroom,
                'discipline_fk' => $discipline,
                'month' => $month));

            if ($allDisciplines) {
                $schedules = Schedule::model()->findAllByAttributes(array(
                    'classroom_fk' => $classroom));
            } else {
                $schedules = Schedule::model()->findAllByAttributes(array(
                    'classroom_fk' => $classroom,
                    'discipline_fk' => $discipline));
            }
            /*        $calendars = Calendar::model()->findAllByAttributes(
                       array(
                           'school_year' => $_POST['year']
                       )
                    );
                    $match =
                    $match = addcslashes($match, '%_');*/

            $criteria = new CDbCriteria();
            $criteria->addInCondition('calenda', array('1', '2'), 'OR');
            $curyear = Yii::app()->user->year;
            $special_days = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and year(c.start_date) = $curyear and year(c.end_date) = $curyear and calendar_event_type_fk  like '1%';")->queryAll();
            $saturday_school = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and year(c.start_date) = $curyear and year(c.end_date) = $curyear and calendar_event_type_fk  = 301;")->queryAll();
            $is_first_to_thrid_year = Yii::app()->db->createCommand("select count(id) as status from classroom where id = $classroom;")->queryAll();

            $firstDay = Yii::app()->db->createCommand("select ce.start_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and calendar_event_type_fk = 1000;")->queryRow();
            $lastDay = Yii::app()->db->createCommand("select ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_fk = " . Yii::app()->user->school . " and c.actual = 1 and calendar_event_type_fk  = 1001;")->queryRow();

            $return = array('days' => array(), 'no_school' => array(), 'students' => array(), 'weekly_schedule' => array(), 'special_days' => array(), 'saturday_school' => array(), 'is_first_to_third_year' => $is_first_to_thrid_year[0]['status']);

            $classes_days = array();

            foreach ($schedules as $key => $schedule) {

                $classes_days[$schedule->week_day][$schedule->turn][$schedule->schedule] = $schedule->id;

            }

            $return['weekly_schedule'] = $classes_days;
            $return['special_days'] = $special_days;
            $return['saturday_school'] = $saturday_school;

            $return['students'] = array();

            $discipline_condition = ($discipline == null) ? " 1=1" : " c.discipline_fk	= $discipline ";
            $no_school = Yii::app()->db->createCommand("select c.day, c.classtype, c.schedule from class c  where $discipline_condition and	 
            c.classroom_fk = $classroom  and c.month = $month and c.given_class != 1;")->queryAll();
            $return['no_school'] = $no_school;

            foreach ($enrollments as $key => $e) {
                if (isset($e->student_fk)) {
                    $return['students'][$key]['id'] = $e->student_fk;
                    $return['students'][$key]['name'] = $e->studentFk->name;
                    $return['students'][$key]['faults'] = Yii::app()->db->createCommand("select c.day , c.classtype, c.schedule, c.discipline_fk from class c inner join class_faults cf on (c.id = cf.class_fk) where $discipline_condition and	 
                c.classroom_fk = $classroom and cf.student_fk = $e->student_fk and c.month = $month and c.given_class = 1;")->queryAll();
                }
            }

            $result = $this->actionGetTimesheet($classroom);

            $report = [];

            $date = Yii::app()->user->year . "-" . $month . "-01";
            $qdtDaysMonth = date("t", strtotime($date));

            foreach ($return['students'] as $student) {
                $report[$student['name']]['id'] = $student['id'];
                $report[$student['name']]['month'] = $month;
                $report[$student['name']]['faults'] = $student['faults'];

                for ($i = 1; $i <= $qdtDaysMonth; $i++) {
                    $day = Yii::app()->user->year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . '-' . str_pad($i, 2, "0", STR_PAD_LEFT) . " 00:00:00";
                    if ($day >= $firstDay["start_date"] && $day <= $lastDay["end_date"]) {
                        $dayWeek = date('N', strtotime($day));
                        $report[$student['name']]['shedules'][$i] = $result['schedules'][$dayWeek];
                    }
                }
            }

            echo json_encode(['valid' => true, 'report' => $report, 'qdtDaysMonth' => $qdtDaysMonth]);
            return [];
        } else {
            echo json_encode(["valid" => false]);
            return [];
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Classes the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Classes::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Classes $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'classes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
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

        return $response;
    }

}
