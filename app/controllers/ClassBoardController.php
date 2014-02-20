<?php

class ClassBoardController extends Controller {

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
                'actions' => array('index', 'view', 'getClassBoard', 'create', 'update', 'addLesson', 'updateLesson', 'removeDraggedLesson', 'deleteLesson'),
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
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionDeleteLesson($lesson = null) {
        $lesson = ($lesson == null) ? $_POST['lesson'] : $lesson;

        $days = isset($_POST['days']) ? $_POST['days'] : 0;
        $minutes = isset($_POST['minutes']) ? $_POST['minutes'] : 0;

        $classboard = ClassBoard::model()->findByPk($lesson['db']);

        $initial_timestamp = strtotime($lesson['start']);
        $final_timestamp = empty($lesson['end']) ? -1 : strtotime($lesson['end']);

        $day = date('w', $initial_timestamp);
        $day -= $days;
        $schedule_initial = date('G', $initial_timestamp);
        $schedule_initial -= $minutes / 60;
        $schedule_final = ($final_timestamp == -1) ? ($schedule_initial + 1) : date('G', $final_timestamp);

        //Pega a semana em forma de matriz
        $week = $this->getSchedule($classboard);

        $schedule = $week[$day];
        //Percorre o intervalo dos horários selecionados
        //removendo os que não existem mais
        for ($i = $schedule_initial; $i < $schedule_final; $i++) {
            foreach ($schedule as $key => $value) {
                if ($value == $i) {
                    unset($schedule[$key]);
                }
            }
        }
        
        //Coloca o horário de volta em forma de string
        //para então colocar de volta no objeto
        $schedule = implode(';', $schedule);
        switch ($day) {
            case 0: $classboard->week_day_sunday = $schedule;
                break;
            case 1: $classboard->week_day_monday = $schedule;
                break;
            case 2: $classboard->week_day_tuesday = $schedule;
                break;
            case 3: $classboard->week_day_wednesday = $schedule;
                break;
            case 4: $classboard->week_day_thursday = $schedule;
                break;
            case 5: $classboard->week_day_friday = $schedule;
                break;
            case 6: $classboard->week_day_saturday = $schedule;
                break;
        }

        return ($classboard->validate() && $classboard->save());
    }

    public function actionUpdateLesson() {
        $lesson = $_POST['lesson'];
        return $this->actionDeleteLesson($lesson) && $this->actionAddLesson($lesson);
    }

    public function actionAddLesson($lesson = null) {
        $lesson = ($lesson == null) ? $_POST['lesson'] : $lesson;
        $classroom = $lesson['classroom'];
        $discipline = $lesson['discipline'];
        $classboard = ClassBoard::model()->find("classroom_fk = $classroom and discipline_fk =$discipline");

        $initial_timestamp = strtotime($lesson['start']);
        $final_timestamp = empty($lesson['end']) ? -1 : strtotime($lesson['end']);

        $schedule_initial = date('G', $initial_timestamp);
        $schedule_final = ($final_timestamp == -1) ? ($schedule_initial + 1) : date('G', $final_timestamp);

        $week_day = date('w', $initial_timestamp);

        if ($classboard == null) {
            $classboard = new ClassBoard;
            $classboard->classroom_fk = $classroom;
            $classboard->discipline_fk = $discipline;

            $schedule = array();
        } else {
            switch ($week_day) {
                case 0: $schedule = $classboard->week_day_sunday;
                    break;
                case 1: $schedule = $classboard->week_day_monday;
                    break;
                case 2: $schedule = $classboard->week_day_tuesday;
                    break;
                case 3: $schedule = $classboard->week_day_wednesday;
                    break;
                case 4: $schedule = $classboard->week_day_thursday;
                    break;
                case 5: $schedule = $classboard->week_day_friday;
                    break;
                case 6: $schedule = $classboard->week_day_saturday;
                    break;
            }
            $schedule = $schedule == '0' ? array() : explode(';', $schedule);
        }

        for ($i = $schedule_initial; $i < $schedule_final; $i++) {
            array_push($schedule, $i);
        }
        $schedule = array_unique($schedule);
        $schedule = implode(';', $schedule);

        switch ($week_day) {
            case 0: $classboard->week_day_sunday = $schedule;
                break;
            case 1: $classboard->week_day_monday = $schedule;
                break;
            case 2: $classboard->week_day_tuesday = $schedule;
                break;
            case 3: $classboard->week_day_wednesday = $schedule;
                break;
            case 4: $classboard->week_day_thursday = $schedule;
                break;
            case 5: $classboard->week_day_friday = $schedule;
                break;
            case 6: $classboard->week_day_saturday = $schedule;
                break;
        }

        if ($classboard->validate() && $classboard->save()) {
            $lesson['title'] = $classboard->disciplineFk->name;
            $event = array(
                'id' => $lesson['id'],
                'db' => $classboard->id,
                'title' => strlen($lesson['title']) > 40 ? substr($lesson['title'], 0, 37) . "..." : $lesson['title'],
                'discipline' => $lesson['discipline'],
                'classroom' => $lesson['classroom'],
                'start' => $lesson['start'],
                'end' => $lesson['end'],
            );
            echo json_encode($event);
            return true;
        } else {
            return false;
        }
    }

    private function getSchedule($classboard) {
        $schedule = array(
            explode(';', $classboard->week_day_sunday),
            explode(';', $classboard->week_day_monday),
            explode(';', $classboard->week_day_tuesday),
            explode(';', $classboard->week_day_wednesday),
            explode(';', $classboard->week_day_thursday),
            explode(';', $classboard->week_day_friday),
            explode(';', $classboard->week_day_saturday),
        );
        return $schedule;
    }

    public function actionGetClassBoard() {
        $year = 1996;
        $month = 1;

        //@done s2 - remover erro ao classroom vir vazio
        if (!isset($_POST['ClassBoard']['classroom_fk']) || empty($_POST['ClassBoard']['classroom_fk']))
            return null;

        $classroom = $_POST['ClassBoard']['classroom_fk'];
        $classboard = ClassBoard::model()->findAll("classroom_fk = $classroom");

        $lessons = 0;

        $events = array();
        foreach ($classboard as $cb) {
            $discipline = $cb->disciplineFk;
            $week = $this->getSchedule($cb);
            foreach ($week as $day => $d) {
                foreach ($d as $schedule) {
                    if ($schedule != 0) {
                        $event = array(
                            'id' => ++$lessons,
                            'db' => $cb->id,
                            'title' => strlen($discipline->name) > 40 ? substr($discipline->name, 0, 37) . "..." : $discipline->name,
                            'discipline' => $discipline->id,
                            'classroom' => $classroom,
                            'start' => date(DateTime::ISO8601, mktime($schedule, 0, 0, $month, $day, $year))
                        );
                        array_push($events, $event);
                    }
                }
            }
        }
        echo json_encode($events);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ClassBoard;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ClassBoard'])) {
            $model->attributes = $_POST['ClassBoard'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ClassBoard'])) {
            $model->attributes = $_POST['ClassBoard'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
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
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('ClassBoard');
        $model = new ClassBoard;

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ClassBoard('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ClassBoard']))
            $model->attributes = $_GET['ClassBoard'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ClassBoard the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ClassBoard::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ClassBoard $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'class-board-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
