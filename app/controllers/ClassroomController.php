<?php

class ClassroomController extends Controller
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
				'actions'=>array('index','view','create','update','getassistancetype'),
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
        
        public function actionGetAssistanceType(){
            $classroom = new Classroom();
            $classroom->attributes = $_POST['Classroom'];

            $schoolStructure = SchoolStructure::model()->findByPk($classroom->school_inep_fk);
            $data = array('null' => '(Select Assistance Type)');
            if($schoolStructure->complementary_activities == 1 || $schoolStructure->complementary_activities == 2 ){
                $data = array_combine($data, array(4=>'Atividade Complementar'));
            }else if($schoolStructure->aee == 1 || $schoolStructure->aee == 2 ){
                $data = array_combine($data, array(5=>'Atendimento Educacional Especializado (AEE)'));
            }else {
                $data = array_combine($data, array(0=>'Não se Aplica', 
                    1=>'Classe Hospitalar', 
                    2=>'Unidade de Internação Socioeducativa', 
                    3=>'Unidade Prisional'));  
            }  
            var_dump($data); exit;
            $data = CHtml::listData($data, 'id', 'name');
            foreach ($data as $value => $name) {
                 echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);

            }
        
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
		$model=new Classroom;
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Classroom']))
		{
			$model->attributes=$_POST['Classroom'];
                        if($model->attributes->week_days_sunday 
                                || $model->attributes->week_days_monday 
                                || $model->attributes->week_days_tuesday 
                                || $model->attributes->week_days_wednesday 
                                || $model->attributes->week_days_thursday 
                                || $model->attributes->week_days_friday 
                                || $model->attributes->week_days_saturday ){
                            if($model->save()){
                                    Yii::app()->user->setFlash('success', Yii::t('default', 'Classroom Created Successful:'));
                                    $this->redirect(array('index'));
                                   }
                         }
		}

		$this->render('create',array(
			'model'=>$model
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
                
//                $baseUrl = Yii::app()->theme->baseUrl; 
//                $cs = Yii::app()->getClientScript();
//                $cs->registerScriptFile($baseUrl.'/js/yourscript.js');
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Classroom']))
		{
			$model->attributes=$_POST['Classroom'];
                        if($model->attributes->week_days_sunday 
                                || $model->attributes->week_days_monday 
                                || $model->attributes->week_days_tuesday 
                                || $model->attributes->week_days_wednesday 
                                || $model->attributes->week_days_thursday 
                                || $model->attributes->week_days_friday 
                                || $model->attributes->week_days_saturday ){
                            if($model->save())
                                    $this->redirect(array('view','id'=>$model->id));
                        }
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Classroom',
                array('pagination' => array(
                        'pageSize' => 12,
                        )));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Classroom('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Classroom']))
			$model->attributes=$_GET['Classroom'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Classroom::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='classroom-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
