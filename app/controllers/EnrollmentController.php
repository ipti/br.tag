<?php

class EnrollmentController extends Controller
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
				'actions'=>array('index','view','create','update',"updatedependencies"),
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

        
        public function actionUpdateDependencies(){
            $enrollment = new StudentEnrollment;
            $enrollment->attributes = $_POST["StudentEnrollment"];

            $students = StudentIdentification::model()->findAll('school_inep_id_fk=:id', array(':id' => $enrollment->school_inep_id_fk));
            $students = CHtml::listData($students, 'id', 'name');
            
            $classrooms = Classroom::model()->findAll('school_inep_fk=:id', array(':id' => $enrollment->school_inep_id_fk));
            $classrooms = CHtml::listData($classrooms, 'id', 'name');

            $result['Students'] = CHtml::tag('option', array('value' => null), 'Selecione um Aluno', true);
            foreach ($students as $value => $name) {
                $result['Students'] .=  CHtml::tag('option', array('value' => $value, ($enrollment->student_fk == $value?"selected":"deselected") => ($enrollment->student_fk == $value?"selected":"deselected")), CHtml::encode($name), true);
            }
            
            $result['Classrooms'] = CHtml::tag('option', array('value' => null), 'Selecione uma Turma', true);
            foreach ($classrooms as $value => $name) {
                $result['Classrooms'] .=  CHtml::tag('option', array('value' => $value,($enrollment->classroom_fk == $value?"selected":"deselected") => ($enrollment->classroom_fk == $value?"selected":"deselected")), CHtml::encode($name), true);
            }
            
            echo json_encode($result);
        }
        
        
        
        
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new StudentEnrollment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentEnrollment']))
		{
                    if($model->validate()){
			$model->attributes=$_POST['StudentEnrollment'];
                        $model->classroom_inep_id = Classroom::model()->findByPk($model->classroom_fk)->inep_id;
                        $model->student_inep_id = StudentIdentification::model()->findByPk($model->student_fk)->inep_id;
                        
			if($model->save()){
                                Yii::app()->user->setFlash('success', Yii::t('default', 'StudentEnrollment Created Successful:'));
				$this->redirect(array('index'));
                               }
                    }
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
                
                if ($model->student_fk == NULL && $model->classroom_fk == NULL){
                    $model->student_fk = StudentIdentification::model()->find('inep_id="'.$model->student_inep_id.'"')->id;
                    $model->classroom_fk = Classroom::model()->find('inep_id="'.$model->classroom_inep_id.'"')->id;
                }
                
		if(isset($_POST['StudentEnrollment']))
		{
                    if($model->validate()){
			$model->attributes=$_POST['StudentEnrollment'];
			if($model->save()){
                                Yii::app()->user->setFlash('success', Yii::t('default', 'StudentEnrollment Edited Successful:'));
				$this->redirect(array('index'));
                               }
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
            
            
            if($this->loadModel($id)->delete()){
                Yii::app()->user->setFlash('success', Yii::t('default', 'Matrícula excluída com sucesso:'));
                $this->redirect(array('index'));
            }else{
                throw new CHttpException(404,'The requested page does not exist.');
            }
            
//		if(Yii::app()->request->isPostRequest)
//		{
//                    
//			// we only allow deletion via POST request
//			$this->loadModel($id)->delete();
//
//			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//			if(!isset($_GET['ajax']))
//				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//		}
//		else
//			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
            $school = Yii::app()->user->school;
            $dataProvider = new CActiveDataProvider('StudentEnrollment',
                            array(
                                'criteria' => array(
                                    'condition' => 'school_inep_id_fk=' . $school,
                                ),
                                'pagination' => array(
                                    'pageSize' => 12,
                                ),

                                ));
            
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StudentEnrollment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StudentEnrollment']))
			$model->attributes=$_GET['StudentEnrollment'];

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
		$model=StudentEnrollment::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='student-enrollment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
