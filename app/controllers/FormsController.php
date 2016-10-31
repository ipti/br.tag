<?php

class FormsController extends Controller {

    public $layout = 'fullmenu';
    public $year = 0;

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'StudentsFileReport', 'StudentsFileInformation'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action){
        $this->year = Yii::app()->user->year;
        return true;
    }

    public function actionStudentsFileForm($enrollment_id) {
        $this->layout = "reports";
        $this->render('StudentsFileForm', array('enrollment_id'=>$enrollment_id));
    }
    public function actionGetStudentsFileInformation($enrollment_id){
        $sql = "SELECT * FROM studentsfile WHERE enrollment_id = ".$enrollment_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        echo json_encode($result);
    }

    public function actionIndex() {
        $this->render('index');
    }

}