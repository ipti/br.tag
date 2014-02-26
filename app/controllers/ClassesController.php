<?php

class ClassesController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','getdisciplines','getclasses'),
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Classes;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Classes']))
		{
			$model->attributes=$_POST['Classes'];
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

		if(isset($_POST['Classes']))
		{
			$model->attributes=$_POST['Classes'];
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
            $dataProvider = new CActiveDataProvider('Classes');
            $model = new Classes;
		$dataProvider=new CActiveDataProvider('Classes');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
                        'model' => $model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionGetDisciplines() {
            $classroom = Classroom::model()->findByPk($_POST['classroom']);
            $disciplines = array();
            $disciplinesLabels = array();
            $disciplines = ClassroomController::classroomDiscipline2array($classroom);
            $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
            echo CHtml::tag('option', array('value' => null), CHtml::encode('Selecione a disciplina'), true);

            foreach ($disciplines as $i => $discipline) {
                if ($discipline != 0) {
                    echo CHtml::tag('option', array('value' => $i), CHtml::encode($disciplinesLabels[$i]), true);
                }
            }
        }
        
        public function actionGetClasses(){
            $classroom = $_POST['classroom'];
            $discipline = $_POST['disciplines'];
            $month = $_POST['month'];
            
            $classes = null;
            $classes = Classes::model()->findAllByAttributes(array(
                'classroom_fk'=>$classroom,
                'discipline_fk'=>$discipline,
                'month'=>$month));
            
            $classboards = ClassBoard::model()->findAllByAttributes(array(
                'classroom_fk'=>$classroom,
                'discipline_fk'=>$discipline,));
            
            var_dump($classboards);
            if($classes == null){
                $classDays = array();
                for($i=0; $i<=6;$i++){
                    $classDays[$i] = array();
                }
                foreach($classboards as $cb){
                    //$cb = new ClassBoard;
                    
                    $str = array();
                    
                    $str[0] = $cb->week_day_sunday;
                    $str[1] = $cb->week_day_monday;
                    $str[2] = $cb->week_day_tuesday;
                    $str[3] = $cb->week_day_wednesday;
                    $str[4] = $cb->week_day_thursday;
                    $str[5] = $cb->week_day_friday;
                    $str[6] = $cb->week_day_saturday;
                    
                    for($i=0; $i<=6;$i++){
                        $temp = explode(';', $str[$i]);
                        $classDays[$i] = array_merge($classDays[$i], $temp);
                        $classDays[$i] = array_unique($classDays[$i]);
                    }
                    var_dump($classDays);exit;
                }
                $year = date('Y');
                $time = mktime(0,0,0,$month,1,$year);
                
                $monthDays = date('t', $time);
                echo $monthDays;
                for($day=1; $day<= $monthDays; $day++){
                    $time = mktime(0,0,0,$month,$day,$year);
                    $weekDay = date('w', $time);
                    if($weekDay == 1)
                        echo 'blah';
                    
                }
                
                //gerar
            }
            
            //mostrar
            
        }

    /**
     * Manages all models.
     */
	public function actionAdmin()
	{
		$model=new Classes('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Classes']))
			$model->attributes=$_GET['Classes'];

		$this->render('admin',array(
			'model'=>$model,
		));
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
		$model=Classes::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Classes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='classes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
