<?php

class DefaultController extends CController{
	public $headerDescription = "";
    public $showPrintButton = false;
    public $studentName = null;
    public $whichSectionIs = null;
    public $eid = null;

	public function actionIndex(){
        if(Yii::app()->user->isGuest)
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
        else
            $this->render('select');
	}
    public function actionError(){
        $this->layout = "login";
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', ['error'=>$error]);
        }
    }

	public function actionGrades($eid){
        if(Yii::app()->user->isGuest)
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
        else {
            /* @var $enrollment StudentEnrollment
             * @var $classroom Classroom
             * @var $classboards ClassBoard[]
             * @var $cb ClassBoard
             */
            $this->showPrintButton = true;
            $this->whichSectionIs = "Notas";
            $this->eid = $eid;
            $enrollment = StudentEnrollment::model()->findByPk($this->eid);
            $this->studentName = $enrollment->studentFk->name;
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
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
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
        if(Yii::app()->user->isGuest) {
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
        }else{
            $this->redirect(yii::app()->createUrl("schoolreport/default/select"));
        }
    }
    public function actionLogout(){
        if(Yii::app()->user->isGuest)
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
        else {
            Yii::app()->user->logout(false);
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
        }
    }

    public function actionSelect(){
        if(Yii::app()->user->isGuest)
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
        else {
            $this->render('select');
        }
    }

    public function actionFrequency($eid){
        if(Yii::app()->user->isGuest)
            $this->redirect(yii::app()->createUrl("schoolreport/default/login"));
        else {
            /* @var $enrollment StudentEnrollment
             * @var $classroom Classroom
             * @var $classboards ClassBoard[]
             * @var $cb ClassBoard
             */
            $this->showPrintButton = true;
            $this->whichSectionIs = "Frequ&ecirc;ncia";
            $this->eid = $eid;
            $enrollment = StudentEnrollment::model()->findByPk($this->eid);
            $this->studentName = $enrollment->studentFk->name;
            $classroom = $enrollment->classroomFk;
            $frequency = [];
            $classesList = Yii::app()->db->createCommand(
                "select month, discipline_fk, ed.name discipline_name, classroom_fk, count(schedule) classes from class
                  LEFT JOIN edcenso_discipline ed ON ed.id = class.discipline_fk
                  WHERE given_class = 1 AND classroom_fk = :cid
                  GROUP BY classroom_fk, discipline_fk,  month;"
            )->queryAll(true, [":cid"=>$classroom->id]);

            foreach ($classesList as $c) {
                $did = $c['discipline_fk'] == null ? -1 : $c['discipline_fk'];
                $dName = $c['discipline_fk'] == null ? "Todas as Disciplinas" :  $c['discipline_name'];
                $classes = $c['classes'];
                $month = $c['month'];
                if(!isset($frequency[$did])) {
                    $frequency[$did] = [];
                    $frequency[$did]["name"] = $dName;
                    $frequency[$did]["months"] = [];
                    for($i = 1; $i <=12; $i++){
                        $frequency[$did]["months"][$i] = [];
                    }
                }
				$did_query = $did == -1 ? 'IS NULL' : "= $did";
                $f = Yii::app()->db->createCommand(
                    "select  month, discipline_fk, se.classroom_fk cid, se.id eid, count(cf.schedule) faults from class_faults cf
                      join class c on c.id = cf.class_fk
                      join student_enrollment se ON se.student_fk = cf.student_fk AND c.classroom_fk = se.classroom_fk
                      where se.id = :eid
                      and discipline_fk $did_query
                      and month = :month
                    GROUP BY eid, discipline_fk,  month;"
                )->queryRow(true,[":eid"=>$eid, ":month"=>$month]);
                $faults = $f['faults'] == null ? 0 : $f['faults'];
                $frequency[$did]["months"][$month] = ['faults'=>intval($faults), 'classes'=>intval($classes)];
            }
            asort($frequency, SORT_STRING);
            $this->render('frequency', ['eid'=>$eid, 'frequency'=>$frequency]);
        }
    }
}