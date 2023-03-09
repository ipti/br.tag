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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','create','update', 'delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

		$modelProfessional = new Professional;
		$modelAttendance = new Attendance;

		if(isset($_POST['Professional']))
		{
			$school = SchoolIdentification::model()->findByAttributes(array('inep_id' => Yii::app()->user->school));
			$city = EdCensoCity::model()->findByAttributes(array('id' => $school->edcenso_city_fk));
			$modelProfessional->attributes=$_POST['Professional'];
			$modelProfessional->inep_id_fk = Yii::app()->user->school;
			$modelAttendance->date = date("Y-m-d");
			$modelAttendance->local = $city->name;
			if($modelProfessional->validate()) {
				if($modelProfessional->save()) {
					$modelAttendance->professional_fk = $modelProfessional->id;
					if($modelAttendance->save()) {
						Yii::app()->user->setFlash('success', Yii::t('default', 'Profissional cadastrado com sucesso!'));
						$this->redirect(array('index','id'=>$modelProfessional->id));
					}
				}
			}
		}

		$this->render('create',array(
			'modelProfessional' => $modelProfessional,
			'modelAttendance' => $modelAttendance
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "professional_fk = ".$id;
		$modelProfessional = Professional::model()->findByPk($id);
		$modelAttendance = new Attendance;
		$modelAttendances = Attendance::model()->findAll($criteria);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Professional']) && isset($_POST['Attendance']))
		{
			$city = EdCensoCity::model()->findByAttributes(array('id' => $_POST['Attendance']['local']));
			$modelProfessional->attributes = $_POST['Professional'];
			$modelAttendance->attributes = $_POST['Attendance'];
			$modelAttendance->local = $city->name;
			if($modelProfessional->save() && $modelAttendance->save())
				Yii::app()->user->setFlash('success', Yii::t('default', 'Profissional atualizado com sucesso!'));
				$this->redirect(array('index','id'=>$modelProfessional->id));
		}

		$this->render('update',array(
			'modelProfessional' => $modelProfessional,
			'modelAttendances' => $modelAttendances,
			'modelAttendance' => $modelAttendance
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$professional = Professional::model()->findByPk($id);
		$attendance = Attendance::model()->findByAttributes(array('professional_fk'=>$id));;

		if($professional->delete() && $attendance->delete()) {
			Yii::app()->user->setFlash('success', Yii::t('default', 'Profissional excluído com sucesso!'));
            $this->redirect(array('index'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$query = Professional::model()->findAll();
		$dataProvider = new CActiveDataProvider('Professional', [
            'criteria' => [
                'order' => 'name ASC',
				'condition' => "inep_id_fk = ".Yii::app()->user->school,
            ], 'pagination' => [
                'pageSize' => count($query),
            ]
        ]);
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
}
