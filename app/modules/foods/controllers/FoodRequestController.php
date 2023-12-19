<?php

class FoodRequestController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(
					'index',
					'view',
					'getFoodAlias',
					'saveRequest',
					'getFoodRequest',
				),
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

	public function actionSaveRequest() {
		$foodRequests = Yii::app()->request->getPost('foodRequests');

		if (!empty($foodRequests)) {
			foreach ($foodRequests as $request) {
				$FoodRequest = new FoodRequest;

				$FoodRequest->food_fk = $request['id'];
				$FoodRequest->amount = $request['amount'];
				$FoodRequest->measurementUnit = $request['measurementUnit'];
				$FoodRequest->description = $request['description'];

				if (!$FoodRequest->save()) {
					Yii::app()->request->sendStatusCode(400);
					$errors = $FoodRequest->getErrors();
					echo json_encode(['error' => 'Ocorreu um erro ao salvar: ' . reset($errors)[0]]);
				}
			}
		}
	}

	public function actionGetFoodRequest() {
		$criteria = new CDbCriteria();
		$criteria->with = array('foodFk');

		$foodRequestData = FoodRequest::model()->findAll($criteria);

		$values = [];
		foreach ($foodRequestData as $request) {
			$values[] = array(
				'id' => $request->id,
				'foodId' => $request->food_fk,
                'foodName' => $request->foodFk->description,
                'amount' => $request->amount,
				'measurementUnit' => $request->measurementUnit,
                'description' => $request->description,
                'delivered' => $request->delivered == 0 ? false : true,
				'date' => date('d/m/Y', strtotime($request->date)),
            );
		}

        echo json_encode($values);
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
		$model=new FoodRequest;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FoodRequest']))
		{
			$model->attributes=$_POST['FoodRequest'];
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

		if(isset($_POST['FoodRequest']))
		{
			$model->attributes=$_POST['FoodRequest'];
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
		$dataProvider=new CActiveDataProvider('FoodRequest');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FoodRequest('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FoodRequest']))
			$model->attributes=$_GET['FoodRequest'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FoodRequest the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FoodRequest::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FoodRequest $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='food-request-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
