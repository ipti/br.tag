<?php

class DefaultController extends Controller {
	/**
	 * @return array action filters
	 */
	public function filters(){
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
	public function accessRules(){
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex(){
        /** @var $school CalendarSchool */
        $school = CalendarSchool::model()->findByPk(Yii::app()->user->school);
        $calendar = $school->getActualCalendar();


		$this->render('index', ["modelCalendar" =>$calendar] );
	}

    public function actionCreate(){
        $calendar = new Calendar();
        $attributes = isset($_POST['Calendar']) ? $_POST['Calendar'] : null;
        if($attributes != null){
            $calendar->attributes = $attributes;
            $calendar->school_fk = Yii::app()->user->school;
            if($calendar->validate()){
                $calendar->save();
                if($calendar->actual == 1){
                    $calendars = $calendar->schoolFk->calendars;
                    foreach($calendars as $c){
                        $c->actual = 0;
                        $c->save();
                    }
                    $calendar->actual = 1;
                    $calendar->save();
                }
                Yii::app()->user->setFlash('success', Yii::t('calendarModule.index', 'School Calendar created successfully!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('calendarModule.index', 'Something went wrong!'));
            }
        }
		$this->render('index', ["modelCalendar" =>$calendar] );
    }


    public function loadModel($id){
        $model=Calendar::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}