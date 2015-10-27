<?php

class DefaultController extends CController{
	public $headerDescription = "";
    public $eid = null;

	public function actionIndex(){
        if(Yii::app()->user->isGuest)
    		$this->render('login');
        else
            $this->render('select');
	}

	public function actionGrades($eid){
        if(Yii::app()->user->isGuest)
            $this->render('login');
        else {
            /* @var $enrollment StudentEnrollment
             * @var $classroom Classroom
             * @var $classboards ClassBoard[]
             * @var $cb ClassBoard
             */
            $this->eid = $eid;
            $enrollment = StudentEnrollment::model()->findByPk($this->eid);
            $classroom = $enrollment->classroomFk;
            $classboards = $classroom->classBoards;
            $disciplines = [];
            foreach ($classboards as $cb) {
                $discipline = $cb->disciplineFk;
                $disciplines[$discipline->id] = $discipline->name;
            }
            asort($disciplines, SORT_STRING);
            $this->render('grades', ['eid' => $eid, 'disciplines' => $disciplines]);
        }
	}

	public function actionGetGrades($eid){
        if(Yii::app()->user->isGuest)
            $this->render('login');
        else {
            /* @var $grade Grade */
            $grades = Grade::model()->findAll("enrollment_fk = :eid", [":eid" => $eid]);

            $result = [];
            foreach ($grades as $grade) {
                $result[$grade->disciplineFk->name] = $grade->attributes;
            }
            echo json_encode($result);
        }
    }

    public function actionLogin(){
        $this->layout = "login";
        // collect user input data
        $model = new LoginForm();
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $this->redirect($this->createUrl("default/select"));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }
    public function actionLogout(){
        if(Yii::app()->user->isGuest)
            $this->render('login');
        else {
            Yii::app()->user->logout(false);
            $this->redirect(Yii::app()->getModule('schoolreport')->user->loginUrl);
        }
    }

    public function actionSelect(){
        if(Yii::app()->user->isGuest)
            $this->render('login');
        else {
            $this->render('select');
        }
    }

    public function actionFrequency($eid){
        if(Yii::app()->user->isGuest)
            $this->render('login');
        else {
            /* @var $enrollment StudentEnrollment
             * @var $classroom Classroom
             * @var $classboards ClassBoard[]
             * @var $cb ClassBoard
             */
            $this->eid = $eid;
            $enrollment = StudentEnrollment::model()->findByPk($this->eid);
            $classroom = $enrollment->classroomFk;
            $classboards = $classroom->classBoards;
            $disciplines = [];
            foreach ($classboards as $cb) {
                $discipline = $cb->disciplineFk;
                $disciplines[$discipline->id] = $discipline->name;
            }
            asort($disciplines, SORT_STRING);
            $this->render('frequency', ['eid'=>$eid, 'disciplines' => $disciplines]);
        }
    }
}