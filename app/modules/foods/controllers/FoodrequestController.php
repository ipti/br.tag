<?php

class FoodrequestController extends Controller
{

    public function actionSaveRequest()
    {
        $foodRequests = Yii::app()->request->getPost('foodRequests');

        if (!empty($foodRequests)) {
            foreach ($foodRequests as $request) {
                $FoodRequest = new FoodRequest;

                $FoodRequest->food_fk = $request['id'];
                $FoodRequest->amount = $request['amount'];
                $FoodRequest->measurementUnit = $request['measurementUnit'];
                $FoodRequest->description = $request['description'];
                $FoodRequest->school_fk = Yii::app()->user->school;

                if (!$FoodRequest->save()) {
                    Yii::app()->request->sendStatusCode(400);
                    $errors = $FoodRequest->getErrors();
                    echo json_encode(['error' => 'Ocorreu um erro ao salvar: ' . reset($errors)[0]]);
                }
            }
        }
    }

    public function actionGetFoodRequest()
    {
        $schoolFk = Yii::app()->user->school;

        $criteria = new CDbCriteria();
        $criteria->with = array('foodFk');
        $criteria->compare('school_fk', $schoolFk);

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
                'status' => $request->status,
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new FoodRequest;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FoodRequest'])) {
            $model->attributes = $_POST['FoodRequest'];
            if ($model->save())  {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FoodRequest'])) {
            $model->attributes = $_POST['FoodRequest'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
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
        if (!isset($_GET['ajax'])) {
            $url = Yii::app()->createUrl(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            $this->redirect($url);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new FoodRequest;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FoodRequest'])) {
            $model->attributes = $_POST['FoodRequest'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new FoodRequest('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['FoodRequest'])) {
            $model->attributes = $_GET['FoodRequest'];
        }

        $this->render('admin', array(
            'model' => $model,
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
        $model = FoodRequest::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param FoodRequest $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'food-request-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
