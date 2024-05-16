<?php

class FoodrequestController extends Controller
{
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

    public function actionGetFoodAlias()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description, measurementUnit';
        $criteria->condition = 'alias_id = t.id';

        $foodsDescription = Food::model()->findAll($criteria);

        $values = [];
        foreach ($foodsDescription as $food) {
            $values[$food->id] = (object) [
                'description' => $food->description,
                'measurementUnit' => $food->measurementUnit
            ];
        }

        echo json_encode($values);
    }

    public function actionGetFoodNotice()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name';

        $foodNotices =  FoodNotice::model()->findAll($criteria);

        $values = [];
        foreach ($foodNotices as $notice) {
            $values[$notice->id] = (object) [
                'name' => $notice->name
            ];
        }

        echo json_encode($values);
    }

    public function actionGetFarmerRegister() {
        $farmerRegisters = FarmerRegister::model()->findAll();

        $values = [];
        foreach ($farmerRegisters as $farmer) {
            $values[$farmer->id] = (object) [
                'name' => $farmer->name,
            ];
        }

        echo json_encode($values);
    }

    public function actionSaveRequest()
    {
        $noticeId = Yii::app()->request->getPost('noticeId');
        $requestSchools = Yii::app()->request->getPost('requestSchools');
        $requestFarmers = Yii::app()->request->getPost('requestFarmers');
        $requestItems = Yii::app()->request->getPost('requestItems');

        $foodRequest = new FoodRequest();
        $foodRequest->notice_fk = $noticeId;
        if($foodRequest->save() && $this->saveRequestSchools($requestSchools, $foodRequest) &&
        $this->saveRequestFarmers($requestFarmers, $foodRequest) && $this->saveRequestItems($requestItems, $foodRequest)) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Solicitação foi gerada com sucesso!'));
        }
    }

    private function saveRequestSchools($requestSchools, $foodRequest) {
        foreach($requestSchools as $school) {
            $requestSchool = new FoodRequestVsSchoolIdentification();

            $requestSchool->school_fk = $school;
            $requestSchool->food_request_fk = $foodRequest->id;

            if(!$requestSchool->save()) {
                return false;
            }
        }
        return true;
    }

    private function saveRequestFarmers($requestFarmers, $foodRequest) {
        foreach($requestFarmers as $farmer) {
            $requestFarmer = new FoodRequestVsFarmerRegister();

            $requestFarmer->farmer_fk = $farmer;
            $requestFarmer->food_request_fk = $foodRequest->id;

            if(!$requestFarmer->save()) {
                return false;
            }
        }
        return true;
    }

    private function saveRequestItems($requestItems, $foodRequest) {
        foreach($requestItems as $item) {
            $requestItem = new FoodRequestItem();

            $requestItem->food_fk = $item['id'];
            $requestItem->amount = $item['amount'];
            $requestItem->measurementUnit = $item['measurementUnit'];
            $requestItem->food_request_fk = $foodRequest->id;

            if(!$requestItem->save()) {
                return false;
            }
        }
        return true;
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

    public function actionCreate()
    {
        $model = new FoodRequest;
        $requestFarmerModel = new FoodRequestVsFarmerRegister;
        $requestSchoolModel = new FoodRequestVsSchoolIdentification;
        $requestItemModel = new FoodRequestItem;

        $noticeId = Yii::app()->request->getPost('noticeId');
        $requestSchools = Yii::app()->request->getPost('requestSchools');
        $requestFarmers = Yii::app()->request->getPost('requestFarmers');
        $requestItems = Yii::app()->request->getPost('requestItems');

        if ($noticeId == null && $requestSchools == null && $requestFarmers == null && $requestItems == null) {
            $this->render('create', array(
                'model' => $model,
                'requestFarmerModel' => $requestFarmerModel,
                'requestSchoolModel' => $requestSchoolModel,
                'requestItemModel' => $requestItemModel,
            ));
        }
        $foodRequest = new FoodRequest();
        $foodRequest->notice_fk = $noticeId;

        if($foodRequest->save() && $this->saveRequestSchools($requestSchools, $foodRequest) &&
        $this->saveRequestFarmers($requestFarmers, $foodRequest) && $this->saveRequestItems($requestItems, $foodRequest)) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Solicitação foi gerada com sucesso!'));
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

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

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax'])) {
            $url = Yii::app()->createUrl(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            $this->redirect($url);
        }
    }

    public function actionIndex()
    {
        $model = new FoodRequest;

        if (isset($_POST['FoodRequest'])) {
            $model->attributes = $_POST['FoodRequest'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $dataProvider = new CActiveDataProvider('FoodRequest');
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdmin()
    {
        $model = new FoodRequest('search');
        $model->unsetAttributes();
        if (isset($_GET['FoodRequest'])) {
            $model->attributes = $_GET['FoodRequest'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = FoodRequest::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'food-request-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
