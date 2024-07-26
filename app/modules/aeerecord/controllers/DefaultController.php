<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
                    'getInstructorClassrooms',
                    'getClassroomStudents'
                ),
                'users' => array('@'),
            ),
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

    public function actionGetInstructorClassrooms() {
        $sql = "SELECT c.id, c.name
                from classroom c
                join instructor_teaching_data itd on itd.classroom_id_fk = c.id
                join instructor_identification ii on itd.instructor_fk = ii.id
                WHERE ii.users_fk = :users_fk and c.school_year = :user_year
                ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
        ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);

        $classrooms = $command->queryAll();

        echo json_encode($classrooms);
    }

    public function actionGetClassroomStudents() {
        $classroomId = Yii::app()->request->getPost('classroomId');

        $sql = "SELECT std.id, std.name
                FROM student_enrollment se
                JOIN classroom c ON c.id = se.classroom_fk
                JOIN student_identification std ON std.id = se.student_fk
                WHERE c.id = :classroom_id";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':classroom_id', $classroomId, PDO::PARAM_INT);

        $students = $command->queryAll();

        echo json_encode($students);
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new StudentAeeRecord;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentAeeRecord']))
		{
			$model->attributes=$_POST['StudentAeeRecord'];
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

		if(isset($_POST['StudentAeeRecord']))
		{
			$model->attributes=$_POST['StudentAeeRecord'];
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
		$dataProvider=new CActiveDataProvider('StudentAeeRecord');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StudentAeeRecord('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StudentAeeRecord']))
			$model->attributes=$_GET['StudentAeeRecord'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StudentAeeRecord the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=StudentAeeRecord::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StudentAeeRecord $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='student-aee-record-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
