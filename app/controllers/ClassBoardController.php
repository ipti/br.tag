<?php

class ClassBoardController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='fullmenu';

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
				'actions'=>array('index','view','getClassBoard','create','update', 'addLesson'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        
        public function actionAddLesson(){
            $lesson = $_POST['lesson'];
            $classroom = $lesson->classroom;
            $discipline = $lesson->discipline;
            $classboard = ClassBoard::model()->find("classroom_fk = $classroom and discipline_fk =$discipline");
            if ($classboard == null) {
                $classboard = new ClassBoard;
                $classboard->classroom_fk = $classroom;
                $classboard->discipline_fk = $discipline;
                //new
            }else{
                //update
            }
            
        }


        
        public function actionGetClassBoard(){
            $year = 1996;
            $month = 1;
            
            $classroom = $_POST['ClassBoard']['classroom_fk'];
            $classboard = ClassBoard::model()->findAll("classroom_fk = $classroom");
            
            $events = array();
            foreach($classboard as $cb){
                $discipline = $cb->disciplineFk;
                $classes = Classes::model()->findAll("classroom_fk = $classroom and discipline_fk = ".$discipline->id);
                foreach($classes as $class) {
                    $day = $class->day;
                    $schedule = $class->schedule;
                    
                    $event = array(
                        'id' => $cb->id,
                        'title' => $discipline->name,
                        'start' =>  date(DateTime::ISO8601, mktime($schedule, 0, 0, $month, $day, $year))
                    );
                    array_push($events, $event);
                }
            }
            
            echo json_encode($events);

//            echo json_encode(array(
//                array(
//                    'id' => 111,
//                    'title' => "Event1",
//                    'start' => date(DateTime::ISO8601, mktime(4, 0, 0, $month, 1, $year)),
//                ),
//                array(
//                    'id' => 222,
//                    'title' => "Event2",
//                    'start' => date(DateTime::ISO8601, mktime(4, 0, 0, $month, 2, $year)),
//                )
//            ));
        }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ClassBoard;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ClassBoard']))
		{
			$model->attributes=$_POST['ClassBoard'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ClassBoard']))
		{
			$model->attributes=$_POST['ClassBoard'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ClassBoard');
                $model = new ClassBoard;
                
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ClassBoard('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ClassBoard']))
			$model->attributes=$_GET['ClassBoard'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ClassBoard the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ClassBoard::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ClassBoard $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='class-board-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
