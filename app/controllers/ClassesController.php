<?php

set_time_limit(0);

class ClassesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters() {
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
    public function accessRules() {
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
    public function actionClassContents() {
        $model = new Classes;
        $dataProvider = new CActiveDataProvider('Classes');
        $this->render('classContents', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
        ));
    }

    /**
     *   Save a new Content  
     */
    public function actionSaveContent() {
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
    public function actionSaveClassContents() {
        if (isset($_POST['classroom'], $_POST['month'], $_POST['day'])) {
            $classroom = $_POST['classroom'];
            $discipline = $_POST['disciplines'];
            $month = $_POST['month'];
            $days = $_POST['day'];
            $discipline = ($discipline == "Todas as disciplinas") ? null : $discipline;
            $allDisciplines = ($discipline == null);
            $allSaved = true;

            $classes = Classes::model()->findAllByAttributes(array(
                'classroom_fk' => $classroom,
                'discipline_fk' => $discipline,
                'month' => $month));

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
                if ($discipline == null) {
                    $disciplineName = "Todas as Disciplinas";
                } else {
                    $disciplineName = EdcensoDiscipline::model()->findByPk($discipline)->name;
                }
                Log::model()->saveAction("class", $classroom . "|" . $discipline . "|" . $month, "U", $classroomName . "|" . $disciplineName . "|" . Yii::t("default", date('F', mktime(0, 0, 0, $month, 10))));
                Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de Aula Atualizado com Sucesso!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Houve um erro inesperado!'));
            }
        }

        $this->redirect(array('classContents'));
    }

    /**
     * Save the frequency for each student and class.
     */
    public function actionSaveFrequency($classroom = null, $discipline = null, $month = null) {
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

        $given_classes = Array();
        $is_first_to_thrid_year = Yii::app()->db->createCommand("select count(id) as status from classroom where id = $classroom and  (name like '1%' or name like '2%' or name like '3%');")->queryAll();


//        if($is_first_to_thrid_year[0]['status'] == '1'){
            foreach ($instructor_days as $day => $value){
                $class = Classes::model()->findByAttributes(array(
                    'classroom_fk' => $classroom,
                    'discipline_fk' => $discipline,
                    'month' => $month,
                    'day' => $day));

                if(isset($class)){
                    if(isset($instructor_faults)){
                        $class->given_class = array_key_exists($day, $instructor_faults) ?  ($instructor_faults[$day] == '1' ? 0 :  ($instructor_faults[$day] == '2' ? 0 : 1)) : 1 ;
                    } else {
                        $class->given_class = 1;
                    }
                    if ($class->given_class == 1){
                        $given_classes[$day] = $class->id;
                    }
                    if($class->update()){
                    }
                } else {
                    $class = new Classes();
                    $class->classroom_fk = $classroom;
                    $class->discipline_fk = $discipline;
                    $class->day = $day;
                    $class->month = $month;
                    $class->schedule = 1;
                    $class->classtype = 'N';
                    if(isset($instructor_faults)) {
                        $class->given_class = array_key_exists($day, $instructor_faults) ? ($instructor_faults[$day] == '1' ? 0 : ($instructor_faults[$day] == '2' ? 0 : 1)) : 1;
                    } else {
                        $class->given_class = 1;
                    }
                    if($class->save()){

                        $given_classes[$day] = $class->id;
                    }
                }

            }
//        }
        $discipline_condition = ($discipline == null)? " c.discipline_fk is null" : " c.discipline_fk	= $discipline ";
        Yii::app()->db->createCommand("CREATE TEMPORARY TABLE IF NOT EXISTS table2 AS (select cf.id from class as c inner join class_faults as cf on (c.id = cf.class_fk) where c.classroom_fk = '$classroom'  and $discipline_condition and c.month = '$month');
                                                delete from class_faults  where  id in (select id from table2)")->query();

        if(isset($student_faults)){

            foreach ($student_faults as $sid => $days) {
                foreach ($given_classes as $day => $id){


                    if  (array_key_exists($day, $days)) {
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
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Open the Frequency View.
     */
    public function actionFrequency() {
        $model = new Classes;
        $dataProvider = new CActiveDataProvider('Classes');
        $this->render('frequency', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
        ));
    }

    /**
     * Get all disciplines by classroom
     */
    public function actionGetDisciplines() {
        echo CHtml::tag('option', array('value' => null), CHtml::encode('Todas as disciplinas'), true);

        if (empty($_POST['classroom']))
            return true;
        $crid = $_POST['classroom'];
        $classr = Yii::app()->db->createCommand("select distinct discipline_fk from schedule where classroom_fk = $crid;")->queryAll();
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        foreach ($classr as $i => $discipline) {
            if (isset($discipline['discipline_fk'])) {
                echo CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true);
            }
        }
    }

    /**
     * Get all contents
     */
    public function actionGetContents() {
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
    public function actionGetClasses($classroom, $month, $disciplines) {
        $discipline = ($disciplines == "Todas as disciplinas") ? null : $disciplines;
        $classes = Classes::model()->findAllByAttributes(array(
            'classroom_fk' => $classroom,
            'discipline_fk' => $discipline,
            'month' => $month));
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
    public function actionGetClassesForFrequency($classroom = null, $discipline = null, $month = null) {
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
        }
        else {
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
        $criteria->addInCondition('calenda', array('1','2'), 'OR');
        $curyear =  Yii::app()->user->year;
        $special_days = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_year = $curyear and calendar_event_type_fk  like '1%';")->queryAll();
        $saturday_school = Yii::app()->db->createCommand("select ce.start_date, ce.end_date from calendar_event as ce inner join calendar as c on (ce.calendar_fk = c.id) where c.school_year = $curyear and calendar_event_type_fk  = 301;")->queryAll();
        $is_first_to_thrid_year = Yii::app()->db->createCommand("select count(id) as status from classroom where id = $classroom and  (name like '1%' or name like '2%' or name like '3%');")->queryAll();

        $return = array('days' => array(), 'no_school' => array(), 'students' => array(), 'weekly_schedule' => array(), 'special_days' => array(), 'saturday_school' => array(), 'is_first_to_third_year' => $is_first_to_thrid_year[0]['status']);



        $classes_days = array();

       foreach ($schedules as $key => $schedule){

           $classes_days[$schedule->week_day][$schedule->turn][$schedule->schedule] = $schedule->id;

       }

        $return['weekly_schedule'] = $classes_days;
        $return['special_days'] = $special_days;
        $return['saturday_school'] = $saturday_school;

       /*
        if ($classes == null) {

            $cr = Classroom::model()->findByPk($classroom);

            //@done s2 - Trocar o ano atual pelo da turma
            $year = $cr->school_year; //$year = date('Y');
            $time = mktime(0, 0, 0, $month, 1, $year);

            $monthDays = date('t', $time);
            for ($day = 1; $day <= $monthDays; $day++) {
                $time = mktime(0, 0, 0, $month, $day, $year);
                $weekDay = date('w', $time);
                $days = $classDays[$weekDay];
                sort($days);
                $classDays[$weekDay] = $days;
            }
        } else {
            foreach ($classes as $c) {
                $schedule = $allDisciplines ? 1 : $c->schedule;

                if ($c->given_class == 1) {
                    $return['faults'][$c->day][$schedule] = isset($return['faults'][$c->day][$schedule]) ? $return['faults'][$c->day][$schedule] : array();

                    $faults = ClassFaults::model()->findAllByAttributes(array('class_fk' => $c->id, 'schedule' => $schedule));

                    foreach ($faults as $f) {
                        $return['faults'][$c->day][$schedule] = isset($return['faults'][$c->day][$schedule]) ? $return['faults'][$c->day][$schedule] : array();
                        $return['faults'][$c->day][$schedule] = array_merge($return['faults'][$c->day][$schedule], array($f->student_fk));
                    }
                } else {
                    $return['instructorFaults'][$c->day] = isset($return['instructorFaults'][$c->day]) ? $return['instructorFaults'][$c->day] : array();
                    $return['instructorFaults'][$c->day] = array_merge($return['instructorFaults'][$c->day], array($schedule));
                }
            }
        }*/

        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom), $criteria);
        $return['students'] = array();

        $discipline_condition = ($discipline == null)? " c.discipline_fk is null" : " c.discipline_fk	= $discipline ";
        $no_school = Yii::app()->db->createCommand("select c.day, c.classtype, c.schedule from class c  where $discipline_condition and	 
            c.classroom_fk = $classroom  and c.month = $month and c.given_class != 1;")->queryAll();
        $return['no_school'] = $no_school;

        foreach ($enrollments as $key => $e) {

            $faults = Yii::app()->db->createCommand("select c.day , c.classtype, c.schedule from class c inner join class_faults cf on (c.id = cf.class_fk) where $discipline_condition and	 
            c.classroom_fk = $classroom and cf.student_fk = $e->student_fk and c.month = $month and c.given_class = 1;")->queryAll();
            if(isset($e->student_fk)){
                $return['students'][$key]['id'] = $e->student_fk;
                $return['students'][$key]['name'] = $e->studentFk->name;
                $return['students'][$key]['faults'] = $faults;
            }


/*            $return['students']['id'] = isset($return['students']['id']) ? $return['students']['id'] : array();
            $return['students']['id'] = array_merge($return['students']['id'], array($e->student_fk));


            $return['students']['name'] = isset($return['students']['name']) ? $return['students']['name'] : array();
            $return['students']['name'] = array_merge($return['students']['name'], array($e->studentFk->name));*/

        }
        echo json_encode($return);

        return array();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Classes the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Classes::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Classes $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'classes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
