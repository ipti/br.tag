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
        $criteria->select = 'id, description, measurementUnit, category';
        $criteria->condition = 'alias_id = t.id';

        $foodsDescription = Food::model()->findAll($criteria);

        $values = [];
        foreach ($foodsDescription as $food) {
            $values[$food->id] = (object) [
                'description' => $food->description,
                'measurementUnit' => $food->measurementUnit,
                'category' => $food->category
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

    private function saveRequestSchools($requestSchools, $foodRequest) {
        foreach($requestSchools as $school) {
            $requestSchool = new FoodRequestVsSchoolIdentification();

            $requestSchool->school_fk = $school["id"];
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

            $requestItem->food_fk = $item['food_id'];
            $requestItem->amount = $item['amount'];
            $requestItem->measurementUnit = $item['measurementUnit'];
            $requestItem->food_request_fk = $foodRequest->id;

            if(!$requestItem->save()) {
                return false;
            }
        }
        return true;
    }

    public function actionUpdateRequestStatus() {
        $requestId = Yii::app()->request->getParam('requestId');
        $token = Yii::app()->request->getParam('token');

        if($token == "123") {
            $criteria = new CDbCriteria();
            $criteria->condition = 't.id = :id';
            $criteria->params = array(':id' => $requestId);

            $request = FoodRequest::model()->find($criteria);
            $request->status = "Finalizado";
            if($request->save()) {
                return json_encode(['success' => 'Status da solicitação modificado com sucesso']);
            } else {
                return json_encode(['error' => 'Não foi possível modificar o status da solicitação']);
            }
        } else {
            return json_encode(['error' => 'Token inválido']);
        }
    }

    public function actionGetFoodRequest()
    {
        $foodRequestData = FoodRequest::model()->with(array(
            'noticeFk' => array(
                'select' => 'name'
            )
        ))->findAll();

        $requestsList = array();
        foreach ($foodRequestData as $request) {
            $requestData = array(
                "requestInfo" => array(
                    'id' => $request->id,
                    'status' => $request->status,
                    'date' => date('d/m/Y', strtotime($request->date)),
                    'notice' => $request->noticeFk->name,
                ),
                "items" => array(),
                "farmers" => array(),
                "schools" => array()

            );

            $this->getFoodRequestItems($requestData, $request->id);
            $this->getFoodRequestFarmers($requestData, $request->id);
            $this->getFoodRequestSchools($requestData, $request->id);

            $requestsList[] = $requestData;
        }

        echo json_encode($requestsList);
    }

    private function getFoodRequestItems(&$requestDataArray, $requestId) {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = array(':requestId' => $requestId);

        $requestItems = FoodRequestItem::model()->with(array(
            'foodFk' => array(
                'select' => 'description'
            )
        ))->findAll($criteria);

        foreach ($requestItems as $item) {
            $requestDataArray["items"][] = array(
                'id' => $item->id,
                'foodId' => $item->food_fk,
                'foodName' => $item->foodFk->description,
                'amount' => $item->amount,
                'measurementUnit' => $item->measurementUnit
            );
        }
    }

    private function getFoodRequestFarmers(&$requestDataArray, $requestId) {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = array(':requestId' => $requestId);
        $requestFarmers = FoodRequestVsFarmerRegister::model()->with(array(
            'farmerFk' => array(
                'select' => 'name'
            )
        ))->findAll($criteria);

        foreach ($requestFarmers as $farmer) {
            $requestDataArray["farmers"][] = array(
                "id" => $farmer->farmer_fk,
                "name" => $farmer->farmerFk->name,
            );
        }
    }

    public function getFoodRequestSchools(&$requestDataArray, $requestId) {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = array(':requestId' => $requestId);
        $requestSchools = FoodRequestVsSchoolIdentification::model()->with(array(
            'schoolFk' => array(
                'select' => 'name'
            )
        ))->findAll($criteria);

        foreach ($requestSchools as $school) {
            $requestDataArray["schools"][] = array(
                "id" => $school->school_fk,
                "name" => $school->schoolFk->name,
            );
        }
    }

    public function actionCreate()
    {
        if(Yii::app()->request->isAjaxRequest) {
            $noticeId = Yii::app()->request->getPost('noticeId');
            $requestSchools = Yii::app()->request->getPost('requestSchools');
            $requestFarmers = Yii::app()->request->getPost('requestFarmers');
            $requestItems = Yii::app()->request->getPost('requestItems');
            $requestTitle = Yii::app()->request->getPost('requestTitle');

            $foodRequest = new FoodRequest;
            $foodRequest->notice_fk = $noticeId;

            if($foodRequest->save() && $this->saveRequestSchools($requestSchools, $foodRequest) &&
            $this->saveRequestFarmers($requestFarmers, $foodRequest) && $this->saveRequestItems($requestItems, $foodRequest)) {
                $requestSchoolNames = array_map(function($school) {
                    return $school["name"];
                }, $requestSchools);

                $criteria = new CDbCriteria();
                $criteria->addInCondition('id', $requestFarmers);
                $farmers = FarmerRegister::model()->findAll($criteria);

                $farmersReferenceId = [];

                foreach ($farmers as $farmer) {
                    $farmersReferenceId[] = $farmer->reference_id;
                }

                $createFoodRequest = new CreateFoodRequest();
                $requestReferenceId = $createFoodRequest->exec($requestTitle, $requestSchoolNames, $farmersReferenceId, $requestItems);

                $foodRequest->reference_id = $requestReferenceId;
                $foodRequest->save();

                Yii::app()->user->setFlash('success', Yii::t('default', 'Solicitação foi gerada com sucesso!'));
            }
        }
        $model = new FoodRequest;
        $requestFarmerModel = new FoodRequestVsFarmerRegister;
        $requestSchoolModel = new FoodRequestVsSchoolIdentification;
        $requestItemModel = new FoodRequestItem;

        $this->render('create', array(
            'model' => $model,
            'requestFarmerModel' => $requestFarmerModel,
            'requestSchoolModel' => $requestSchoolModel,
            'requestItemModel' => $requestItemModel,
        ));
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
